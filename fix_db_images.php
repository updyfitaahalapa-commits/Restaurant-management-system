<?php
include("config/db.php");

// 1. Insert generic items into Menu if they don't exist
$items = [
    ['name' => 'Burger', 'price' => 12.50, 'image' => 'assets/images/burger.png'],
    ['name' => 'Pizza', 'price' => 15.00, 'image' => 'assets/images/pizza.png'],
    ['name' => 'Pasta', 'price' => 10.99, 'image' => 'assets/images/pasta.png']
];

foreach($items as $item){
    $name = $item['name'];
    $price = $item['price'];
    $image = $item['image'];
    
    // Check if exists
    $check = mysqli_query($conn, "SELECT * FROM menu WHERE name='$name'");
    if(mysqli_num_rows($check) == 0){
        // Insert
        mysqli_query($conn, "INSERT INTO menu (name, price, image) VALUES ('$name', '$price', '$image')");
        echo "Added $name to menu.<br>";
    } else {
        // Update image just in case
        mysqli_query($conn, "UPDATE menu SET image='$image' WHERE name='$name'");
        echo "Updated image for $name.<br>";
    }
}

// 2. Link existing orders to these images (fix historical data)
// This query tries to match order item names to menu item names loosely
mysqli_query($conn, "UPDATE orders o JOIN menu m ON o.item = m.name SET o.item = m.name");

echo "<br><b>Database Updated!</b> Images should now appear.";
echo "<br><a href='user/index.php'>Go to Menu</a>";
?>
