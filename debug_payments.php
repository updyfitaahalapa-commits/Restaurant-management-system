<?php
include("config/db.php");
echo "<h3>Columns in 'payments':</h3>";
$cols = mysqli_query($conn, "DESCRIBE payments");
if ($cols) {
    while ($col = mysqli_fetch_assoc($cols)) {
        echo $col['Field'] . " - " . $col['Type'] . "<br>";
    }
} else {
    echo "Error describing payments: " . mysqli_error($conn);
}
?>
