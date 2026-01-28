<?php
include("config/db.php");

$sqlFile = 'updates_v2.sql';
if (!file_exists($sqlFile)) {
    die("Error: updates_v2.sql file not found.");
}

$sql = file_get_contents($sqlFile);
$queries = explode(";", $sql);

foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        if (mysqli_query($conn, $query)) {
            echo "Success: Query executed.\n";
        } else {
            echo "Error executing query: " . mysqli_error($conn) . "\nQuery: $query\n";
        }
    }
}
echo "Database v2 update process completed.\n";
?>
