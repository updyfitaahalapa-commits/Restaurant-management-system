<?php
session_start();
include("../config/db.php");
if(!isset($_SESSION['user'])) header("location=../auth/login.php");

if(isset($_POST['order'])){
    $item = $_POST['item'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $total = $price * $qty;

    // Save to session cart
    if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $_SESSION['cart'][] = [
        'item' => $item,
        'qty' => $qty,
        'total' => $total
    ];

    // Optional: Save directly to DB
    $customer = $_SESSION['user'];
    mysqli_query($conn,"INSERT INTO orders (customer,item,quantity,total,status) VALUES ('$customer','$item','$qty','$total','Pending')");
    
    header("location:index.php"); // back to menu page
    exit();
}
?>