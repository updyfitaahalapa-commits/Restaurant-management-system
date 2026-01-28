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
    $user_q = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");
    $user_row = mysqli_fetch_assoc($user_q);
    $user_id = $user_row['id'];

    $payment_method = $_POST['payment_method'];
    $payment_phone = isset($_POST['payment_phone']) ? $_POST['payment_phone'] : null;

    // Server-side Validation
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
    }

    // Calculate Totals form Session (Secure way)
    $grand_total = 0;
    $total_qty = 0;
    $items_summary = [];

    if (empty($_SESSION['cart'])) {
        header("Location: index.php");
        exit();
    }

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

        // Insert Order Items
        foreach ($_SESSION['cart'] as $cart_item) {
            $name = mysqli_real_escape_string($conn, $cart_item['item_name']);
            $price = $cart_item['price'];
            $qty = $cart_item['qty'];
            
            mysqli_query($conn, "INSERT INTO order_items (order_id, item_name, price, quantity) 
                                 VALUES ('$order_id', '$name', '$price', '$qty')");
        }

        // Clear Cart
        unset($_SESSION['cart']);
        $_SESSION['msg'] = "Order placed successfully! Paid via " . $payment_method;
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
