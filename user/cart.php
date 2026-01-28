<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to Cart
if (isset($_POST['add_to_cart'])) {
    $item_name = $_POST['item_name'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];

    // Check if item already exists in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['item_name'] == $item_name) {
            $cart_item['qty'] += $qty;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = [
            'item_name' => $item_name,
            'price' => $price,
            'qty' => $qty
        ];
    }

    $_SESSION['msg'] = "Item added to cart!";
    $_SESSION['msg_type'] = "success";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

// Remove from Cart
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
        $_SESSION['msg'] = "Item removed from cart!";
        $_SESSION['msg_type'] = "warning";
    }
    header("Location: view_cart.php");
    exit();
}

// Clear Cart
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    $_SESSION['msg'] = "Cart cleared!";
    $_SESSION['msg_type'] = "info";
    header("Location: view_cart.php");
    exit();
}

// Update Quantity
if (isset($_POST['update_cart'])) {
    $index = $_POST['index'];
    $qty = $_POST['qty'];
    
    if($qty > 0 && isset($_SESSION['cart'][$index])) {
        $_SESSION['cart'][$index]['qty'] = $qty;
        $_SESSION['msg'] = "Cart updated!";
        $_SESSION['msg_type'] = "success";
    }
    header("Location: view_cart.php");
    exit();
}
?>
