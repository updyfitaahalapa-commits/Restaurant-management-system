<?php
include("config/db.php");

// Create Transactions Table
$sql = "CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `transaction_id` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `card_mask` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Success',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (mysqli_query($conn, $sql)) {
    echo "Table 'transactions' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}
?>
