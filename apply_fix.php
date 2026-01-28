<?php
include("config/db.php");

$sql = file_get_contents("fix_inventory.sql");
$statements = explode(";", $sql);

foreach ($statements as $statement) {
    $stmt = trim($statement);
    if (!empty($stmt)) {
        if (mysqli_query($conn, $stmt)) {
            echo "Success: " . substr($stmt, 0, 30) . "...<br>";
        } else {
            echo "Error: " . mysqli_error($conn) . "<br>";
        }
    }
}
?>
