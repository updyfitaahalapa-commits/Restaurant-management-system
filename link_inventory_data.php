<?php
include("config/db.php");

// Map Menu IDs (or Names) to Inventory IDs
// Based on current DB state:
// Menu 1 (Burger) -> Inventory 1 (Burger Patty)
// Menu 2 (Pizza) -> Inventory 2 (Pizza Dough)
// Menu 3 (Pasta) -> Inventory 3 (Pasta Pack)
// Menu 4 (Grilled Salmon) -> Inventory 4 (Salmon Fillet)
// Menu 5 (Caesar Salad) -> Inventory 5 (Lettuce Head)

$uplinks = [
    "UPDATE menu SET inventory_id=1, quantity_required=1 WHERE name LIKE '%Burger%'",
    "UPDATE menu SET inventory_id=2, quantity_required=1 WHERE name LIKE '%Pizza%'",
    "UPDATE menu SET inventory_id=3, quantity_required=1 WHERE name LIKE '%Pasta%'",
    "UPDATE menu SET inventory_id=4, quantity_required=1 WHERE name LIKE '%Salmon%'",
    "UPDATE menu SET inventory_id=5, quantity_required=1 WHERE name LIKE '%Salad%'"
];

foreach ($uplinks as $sql) {
    if(mysqli_query($conn, $sql)){
        echo "Executed: $sql - Affected: " . mysqli_affected_rows($conn) . " rows.<br>\n";
    } else {
        echo "Error: " . mysqli_error($conn) . "<br>\n";
    }
}

echo "Linking complete. Please try placing an order now.";
?>
