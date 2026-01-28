<?php
include("config/db.php");

echo "<h2>Tables in Database:</h2>";
$result = mysqli_query($conn, "SHOW TABLES");
while ($row = mysqli_fetch_array($result)) {
    echo $row[0] . "<br>";
    if ($row[0] == 'inventory') {
        echo "<h3>Columns in 'inventory':</h3>";
        $cols = mysqli_query($conn, "DESCRIBE inventory");
        while ($col = mysqli_fetch_assoc($cols)) {
            echo $col['Field'] . " - " . $col['Type'] . "<br>";
        }
    }
}
?>
