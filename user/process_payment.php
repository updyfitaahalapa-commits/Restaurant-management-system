<?php
session_start();
include("../config/db.php");

if(isset($_POST['pay_now'])){
    $order_id = $_POST['order_id'];
    $amount = $_POST['amount'];
    $card_name = mysqli_real_escape_string($conn, $_POST['card_name']);
    $card_number = $_POST['card_number'];
    
    // Simulate Loading Time
    sleep(2); 

    // Generate Fake Transaction ID
    $trans_id = "TXN-" . strtoupper(uniqid());
    // Mask Card
    $masked = "**** **** **** " . substr(str_replace(' ', '', $card_number), -4);

    // Insert Transaction
    $sql = "INSERT INTO transactions (order_id, transaction_id, amount, payment_method, card_mask, status) 
            VALUES ('$order_id', '$trans_id', '$amount', 'Credit Card', '$masked', 'Success')";
    
    if(mysqli_query($conn, $sql)){
        // Update Order Payment Status
        mysqli_query($conn, "UPDATE orders SET payment_status='Paid' WHERE id='$order_id'");
        // Also update payments table if it exists OR create a record there too if needed to keep sync with admin/payments.php
        mysqli_query($conn, "UPDATE payments SET status='Paid' WHERE order_id='$order_id'"); 

        header("Location: payment_success.php?tid=$trans_id");
        exit();
    } else {
        $_SESSION['msg'] = "Transaction Failed: " . mysqli_error($conn);
        $_SESSION['msg_type'] = "danger";
        header("Location: payment.php?order_id=$order_id");
        exit();
    }
} else {
    header("Location: index.php");
}
?>
