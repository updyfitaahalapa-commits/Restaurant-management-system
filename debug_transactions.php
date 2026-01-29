<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("config/db.php");

echo "<h3>Transaction Debugger</h3>";

// 1. Check Table
$check = mysqli_query($conn, "SHOW TABLES LIKE 'transactions'");
if(mysqli_num_rows($check) > 0){
    echo "<p style='color:green'>[OK] Table 'transactions' exists.</p>";
} else {
    echo "<p style='color:red'>[ERROR] Table 'transactions' DOES NOT exist.</p>";
    exit();
}

// 2. Check Orders to link to
$ord = mysqli_query($conn, "SELECT id, customer FROM orders LIMIT 1");
if(mysqli_num_rows($ord) > 0){
    $order = mysqli_fetch_assoc($ord);
    $order_id = $order['id'];
    echo "<p style='color:green'>[OK] Found Order ID: $order_id (Customer: {$order['customer']}) to link.</p>";

    // 3. Try Insert
    $trans_id = "TEST-" . rand(1000,9999);
    $sql = "INSERT INTO transactions (order_id, transaction_id, amount, payment_method, card_mask, status) 
            VALUES ('$order_id', '$trans_id', '10.00', 'Debug', '**** 0000', 'Success')";
    
    if(mysqli_query($conn, $sql)){
        echo "<p style='color:green'>[OK] Dummy Transaction Inserted successfully.</p>";
    } else {
        echo "<p style='color:red'>[ERROR] Insert Failed: " . mysqli_error($conn) . "</p>";
    }

} else {
    echo "<p style='color:orange'>[WARNING] No orders found in database. Cannot test foreign key insert.</p>";
}

// 4. Test Fetch Query (Admin Page Query)
$q_sql = "SELECT t.*, o.customer FROM transactions t JOIN orders o ON t.order_id = o.id ORDER BY t.created_at DESC LIMIT 5";
$q = mysqli_query($conn, $q_sql);

if($q){
    echo "<p style='color:green'>[OK] Fetch Query Successful. Found " . mysqli_num_rows($q) . " records.</p>";
    echo "<table border='1'><tr><th>ID</th><th>TransID</th><th>Customer</th></tr>";
    while($row = mysqli_fetch_assoc($q)){
        echo "<tr><td>{$row['id']}</td><td>{$row['transaction_id']}</td><td>{$row['customer']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red'>[ERROR] Fetch Query Failed: " . mysqli_error($conn) . "</p>";
}
?>
