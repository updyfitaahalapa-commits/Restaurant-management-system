<?php
session_start();
include("../config/db.php");

if(isset($_POST['mark_paid'])){
    $id = $_POST['id'];
    
    // Update Payments Table
    mysqli_query($conn, "UPDATE payments SET status='Paid' WHERE id='$id'");
    
    // Get Order ID
    $pq = mysqli_query($conn, "SELECT order_id FROM payments WHERE id='$id'");
    if($row = mysqli_fetch_assoc($pq)){
        $order_id = $row['order_id'];
        // Update Order Table Payment Status
        mysqli_query($conn, "UPDATE orders SET payment_status='Paid' WHERE id='$order_id'");
    }
    
    header("Location: payments.php");
}
?>
