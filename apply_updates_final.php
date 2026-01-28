<?php
include("config/db.php");

$sql = file_get_contents("updates_final.sql");

// Split by semicolon (roughly, assuming no semicolons in strings for now, robust enough for this schema)
$statements = explode(";", $sql);

foreach ($statements as $statement) {
    $statement = trim($statement);
    if (!empty($statement)) {
        if (mysqli_query($conn, $statement)) {
            echo "Successfully executed: " . substr($statement, 0, 50) . "...<br>";
        } else {
            echo "Error executing: " . substr($statement, 0, 50) . "...<br>";
            echo "MySQL Error: " . mysqli_error($conn) . "<br>";
        }
    }
}
echo "<br>Database updates complete.";
?>
