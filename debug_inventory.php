<?php
include("config/db.php");

echo "=== MENU ITEMS ===\n";
$q = mysqli_query($conn, "SELECT id, name, inventory_id, quantity_required FROM menu");
while($row = mysqli_fetch_assoc($q)){
    echo "ID: " . $row['id'] . " | Name: " . $row['name'] . " | Linked Inv ID: " . ($row['inventory_id'] ? $row['inventory_id'] : "NULL") . " | QtyReq: " . $row['quantity_required'] . "\n";
}

echo "\n=== INVENTORY ITEMS ===\n";
$q2 = mysqli_query($conn, "SELECT id, item_name, quantity FROM inventory");
while($row = mysqli_fetch_assoc($q2)){
    echo "ID: " . $row['id'] . " | Name: " . $row['item_name'] . " | Stock: " . $row['quantity'] . "\n";
}
?>
