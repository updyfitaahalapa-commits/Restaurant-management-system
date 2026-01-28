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
    $role = $user_row['role']; // Optional: use for role-based logic if needed

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
        $payment_status = 'Paid'; // Simulating successful mobile payment
    } elseif ($payment_method == 'Cash') {
        $payment_status = 'Pending';
    }

    if (empty($_SESSION['cart'])) {
        header("Location: index.php");
        exit();
    }

    // INVENTORY CHECK
    // Check if enough stock exists for all items
    foreach ($_SESSION['cart'] as $item) {
        $name = mysqli_real_escape_string($conn, $item['item_name']);
        $req_qty = $item['qty'];
        
        // Find menu id
        $menu_q = mysqli_query($conn, "SELECT id FROM menu WHERE name='$name'");
        if(mysqli_num_rows($menu_q) > 0){
            $menu_row = mysqli_fetch_assoc($menu_q);
            $menu_id = $menu_row['id'];
            
            // Check inventory
            $inv_q = mysqli_query($conn, "SELECT quantity FROM inventory WHERE menu_id='$menu_id'");
            if(mysqli_num_rows($inv_q) > 0){
                $inv_row = mysqli_fetch_assoc($inv_q);
                $stock = $inv_row['quantity'];
                
                if($stock < $req_qty){
                    $_SESSION['msg'] = "Insufficient stock for " . $item['item_name'] . ". Only " . $stock . " left.";
                    $_SESSION['msg_type'] = "error";
                    header("Location: checkout.php");
                    exit();
                }
            } else {
                 // If no inventory record, assume 0 stock or unlimited? Project req says "Prevent orders if stock is insufficient", so assume 0.
                 // However, for existing items without inventory records, we might want to block order.
                 $_SESSION['msg'] = "Stock information missing for " . $item['item_name'];
                 $_SESSION['msg_type'] = "error";
                 header("Location: checkout.php");
                 exit();
            }
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
            mysqli_query($conn, "INSERT INTO order_items (order_id, item_name, price, quantity) 
                                 VALUES ('$order_id', '$name', '$price', '$qty')");
                                 
            // 2. Reduce Inventory
            $menu_q = mysqli_query($conn, "SELECT id FROM menu WHERE name='$name'");
            if(mysqli_num_rows($menu_q) > 0){
                $menu_id = mysqli_fetch_assoc($menu_q)['id'];
                mysqli_query($conn, "UPDATE inventory SET quantity = quantity - $qty WHERE menu_id='$menu_id'");
            }
        }
        
        // Insert Payment Record
        $payment_status_db = ($payment_status == 'Paid') ? 'Paid' : 'Pending';
        mysqli_query($conn, "INSERT INTO payments (order_id, amount, payment_method, status, transaction_date) 
                             VALUES ('$order_id', '$grand_total', '$payment_method', '$payment_status_db', NOW())");

        // Clear Cart
        unset($_SESSION['cart']);
        $_SESSION['msg'] = "Order placed successfully! " . ($payment_status == 'Paid' ? "Payment received." : "Payment pending.");
        $_SESSION['msg_type'] = "success";
        header("Location: orders.php");
        exit();

    } else {
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
