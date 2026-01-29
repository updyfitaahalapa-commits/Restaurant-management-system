<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_POST['place_order'])) {
    
    $username = $_SESSION['user'];
    // Get User ID
    $user_q = mysqli_query($conn, "SELECT id, role FROM users WHERE username='$username'");
    $user_row = mysqli_fetch_assoc($user_q);
    $user_id = $user_row['id'];
    $role = $user_row['role']; 

    $payment_method = $_POST['payment_method'];
    $payment_phone = isset($_POST['payment_phone']) ? $_POST['payment_phone'] : null;

    // Server-side Validation for Payment Phone
    if(in_array($payment_method, ['EVC Plus', 'eDahab', 'Somnet'])){
        if(strlen($payment_phone) != 9 || !is_numeric($payment_phone)){
            $_SESSION['msg'] = "Invalid phone number length.";
            $_SESSION['msg_type'] = "error";
            header("Location: checkout.php");
            exit();
        }

        $prefix = substr($payment_phone, 0, 2);
        $valid = false;

        if ($payment_method == 'EVC Plus' && in_array($prefix, ['61', '68', '77'])) $valid = true;
        elseif ($payment_method == 'eDahab' && in_array($prefix, ['65', '62', '60'])) $valid = true;
        elseif ($payment_method == 'Somnet' && in_array($prefix, ['66'])) $valid = true;

        if(!$valid){
            $_SESSION['msg'] = "Invalid number for selected payment method.";
            $_SESSION['msg_type'] = "error";
            header("Location: checkout.php");
            exit();
        }
    }
    
    // Determine Status
    $payment_status = 'Pending';
    if(in_array($payment_method, ['EVC Plus', 'eDahab', 'Somnet'])){
        $payment_status = 'Paid'; 
    } elseif ($payment_method == 'Cash') { // Cash is now 'Pending' until admin confirms, or 'Unpaid'? Requirement says: "Update order status after payment."
        // For Cash on Delivery, status is Pending.
        $payment_status = 'Unpaid'; 
    }
    
    // If Manual Payment (Cash), payment_status is Unpaid initially.
    if($payment_method == 'Cash On Delivery') {
         $payment_status = 'Unpaid';
    }

    if (empty($_SESSION['cart'])) {
        header("Location: index.php");
        exit();
    }

    // INVENTORY CHECK (AGGREGATED)
    $inventory_usage = [];
    $item_map = []; // To store menu details to avoid re-querying

    foreach ($_SESSION['cart'] as $key => $item) {
        $name = mysqli_real_escape_string($conn, $item['item_name']);
        $cart_qty = $item['qty'];

        $menu_q = mysqli_query($conn, "SELECT id, inventory_id, quantity_required FROM menu WHERE name='$name'");
        if(mysqli_num_rows($menu_q) > 0){
            $menu_row = mysqli_fetch_assoc($menu_q);
            $inv_id = $menu_row['inventory_id'];
            $qty_req = $menu_row['quantity_required'] ? $menu_row['quantity_required'] : 1;
            
            // Store for later use
            $item_map[$name] = ['inv_id' => $inv_id, 'qty_req' => $qty_req];

            if($inv_id){
                if(!isset($inventory_usage[$inv_id])) {
                    $inventory_usage[$inv_id] = 0;
                }
                $inventory_usage[$inv_id] += ($cart_qty * $qty_req);
            }
        }
    }

    // Verify Stock Availability
    foreach ($inventory_usage as $inv_id => $total_needed) {
        $inv_q = mysqli_query($conn, "SELECT quantity, item_name FROM inventory WHERE id='$inv_id'");
        if(mysqli_num_rows($inv_q) > 0){
            $inv_row = mysqli_fetch_assoc($inv_q);
            $stock = $inv_row['quantity'];

            if($stock < $total_needed){
                $_SESSION['msg'] = "Insufficient stock for " . $inv_row['item_name'] . ". Required: $total_needed, Available: $stock";
                $_SESSION['msg_type'] = "error";
                header("Location: checkout.php");
                exit();
            }
        } else {
             $_SESSION['msg'] = "Linked inventory item (ID: $inv_id) not found.";
             $_SESSION['msg_type'] = "error";
             header("Location: checkout.php");
             exit();
        }
    }

    // Calculate Totals form Session (Secure way)
    $grand_total = 0;
    $total_qty = 0;
    $items_summary = [];

    foreach ($_SESSION['cart'] as $item) {
        $grand_total += ($item['price'] * $item['qty']);
        $total_qty += $item['qty'];
        $items_summary[] = $item['item_name'];
    }

    // Create Summary String
    $item_summary_str = implode(", ", array_slice($items_summary, 0, 2));
    if (count($items_summary) > 2) {
        $item_summary_str .= " + " . (count($items_summary) - 2) . " more";
    }

    // START TRANSACTION
    mysqli_begin_transaction($conn);
    $transaction_error = false;

    // Insert into Orders
    $sql = "INSERT INTO orders (customer, user_id, item, quantity, total, status, payment_method, payment_status, payment_phone, order_date) 
            VALUES ('$username', '$user_id', '$item_summary_str', '$total_qty', '$grand_total', 'Pending', '$payment_method', '$payment_status', '$payment_phone', NOW())";

    if (mysqli_query($conn, $sql)) {
        $order_id = mysqli_insert_id($conn);

        // Insert Order Items and Update Inventory
        foreach ($_SESSION['cart'] as $cart_item) {
            $name = mysqli_real_escape_string($conn, $cart_item['item_name']);
            $price = $cart_item['price'];
            $qty = $cart_item['qty'];
            
            // 1. Insert Order Item
            $res_item = mysqli_query($conn, "INSERT INTO order_items (order_id, item_name, price, quantity) 
                                 VALUES ('$order_id', '$name', '$price', '$qty')");
            if(!$res_item) $transaction_error = true;
                                 
            // 2. Reduce Inventory
            if(isset($item_map[$name]) && $item_map[$name]['inv_id']){
                $inv_id = $item_map[$name]['inv_id'];
                $deduct = $qty * $item_map[$name]['qty_req'];
                
                // Atomic update ensuring no negative stock (extra safety)
                $res_inv = mysqli_query($conn, "UPDATE inventory SET quantity = quantity - $deduct WHERE id='$inv_id' AND quantity >= $deduct");
                
                // If update failed (likely mostly due to race condition if check passed), flag error
                if(mysqli_affected_rows($conn) == 0){
                    $transaction_error = true; // Rollback if stock vanished
                }
            }
        }
        
        if(!$transaction_error){
            // Insert Payment Record
            $pay_status_rec = ($payment_status == 'Paid') ? 'Paid' : 'Pending';
            $res_pay = mysqli_query($conn, "INSERT INTO payments (order_id, amount, payment_method, status, transaction_date) 
                                 VALUES ('$order_id', '$grand_total', '$payment_method', '$pay_status_rec', NOW())");
            
            if($res_pay){
                mysqli_commit($conn);
                
                // Clear Cart
                unset($_SESSION['cart']);
                $_SESSION['msg'] = "Order placed successfully! " . ($payment_status == 'Paid' ? "Payment received." : "Payment pending.");
                $_SESSION['msg_type'] = "success";
                header("Location: orders.php");
                exit();
            } else {
                 mysqli_rollback($conn);
                 $_SESSION['msg'] = "Error recording payment. Please try again.";
                 $_SESSION['msg_type'] = "error";
                 header("Location: checkout.php");
                 exit();
            }
        } else {
            mysqli_rollback($conn);
            $_SESSION['msg'] = "Stock update failed (Item became unavailable). Please try again.";
            $_SESSION['msg_type'] = "error";
            header("Location: checkout.php");
            exit();
        }

    } else {
        mysqli_rollback($conn);
        $_SESSION['msg'] = "Error placing order: " . mysqli_error($conn);
        $_SESSION['msg_type'] = "error";
        header("Location: checkout.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
