<?php include("config/db.php"); ?>
<link rel="stylesheet" href="assets/css/style.css">

<div class="container">
<h2>Online Restaurant Menu</h2>

<?php
$q=mysqli_query($conn,"SELECT * FROM menu");
while($row=mysqli_fetch_assoc($q)){
?>
<div class="card">
<form method="POST" action="order.php">
<h4><?= $row['name']; ?></h4>
<p>Price: $<?= $row['price']; ?></p>
<input type="hidden" name="item" value="<?= $row['name']; ?>">
<input type="hidden" name="price" value="<?= $row['price']; ?>">
<input type="number" name="qty" value="1" required>
<input type="text" name="customer" placeholder="Your Name" required>
<button name="order">Order Now</button>
</form>
</div>
<?php } ?>
</div>