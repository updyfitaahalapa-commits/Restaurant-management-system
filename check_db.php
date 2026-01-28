<?php
include("config/db.php");
$t = mysqli_query($conn, "DESCRIBE inventory");
if($t) {
    echo "Inventory table exists.\n";
    while($row = mysqli_fetch_assoc($t)){
        echo $row['Field'] . " - " . $row['Type'] ."\n";
    }
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
