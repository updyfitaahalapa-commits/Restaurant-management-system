<?php
include("config/db.php");

function addColumnIfNotExists($conn, $table, $column, $definition) {
    $check = mysqli_query($conn, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    if (mysqli_num_rows($check) == 0) {
        $sql = "ALTER TABLE `$table` ADD COLUMN `$column` $definition";
        if (mysqli_query($conn, $sql)) {
            echo "Added column $column to $table.\n";
        } else {
            echo "Error adding $column: " . mysqli_error($conn) . "\n";
        }
    } else {
        echo "Column $column already exists in $table.\n";
    }
}

addColumnIfNotExists($conn, 'orders', 'payment_method', "varchar(50) DEFAULT NULL");
addColumnIfNotExists($conn, 'orders', 'payment_status', "varchar(20) DEFAULT 'Pending'");
addColumnIfNotExists($conn, 'orders', 'payment_phone', "varchar(20) DEFAULT NULL");
addColumnIfNotExists($conn, 'orders', 'user_id', "int(11) DEFAULT NULL");

echo "Database fix completed.\n";
?>
