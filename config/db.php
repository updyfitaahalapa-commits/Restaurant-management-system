<?php
$conn = mysqli_connect("localhost","root","","restaurant_system");
if(!$conn){
    die("Database connection failed: ".mysqli_connect_error());
}
?>