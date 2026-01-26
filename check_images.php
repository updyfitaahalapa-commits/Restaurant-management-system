<?php
include("config/db.php");

echo "<h2>Orders (Items vs Menu Images)</h2>";
$orders = mysqli_query($conn, "SELECT o.id, o.item, m.name as menu_name, m.image FROM orders o LEFT JOIN menu m ON o.item = m.name");

echo "<table border='1'><tr><th>Order ID</th><th>Item Name</th><th>Menu Match?</th><th>Image Path</th></tr>";
while($row = mysqli_fetch_assoc($orders)){
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['item']}</td>";
    echo "<td>" . ($row['menu_name'] ? 'Yes' : 'NO MATCH') . "</td>";
    echo "<td>{$row['image']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>All Menu Items</h2>";
$menu = mysqli_query($conn, "SELECT * FROM menu");
echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Image</th></tr>";
while($row = mysqli_fetch_assoc($menu)){
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['name']}</td>";
    echo "<td>{$row['image']}</td>";
    echo "</tr>";
}
echo "</table>";
?>
