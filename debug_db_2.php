<?php
include("config/db.php");
echo "<h3>Columns in 'order_items':</h3>";
$cols = mysqli_query($conn, "DESCRIBE order_items");
if ($cols) {
    while ($col = mysqli_fetch_assoc($cols)) {
        echo $col['Field'] . " - " . $col['Type'] . "<br>";
    }
} else {
    echo "Error describing order_items: " . mysqli_error($conn);
}

echo "<h3>Columns in 'orders':</h3>";
$cols = mysqli_query($conn, "DESCRIBE orders");
if ($cols) {
    while ($col = mysqli_fetch_assoc($cols)) {
        echo $col['Field'] . " - " . $col['Type'] . "<br>";
    }
}
?>
