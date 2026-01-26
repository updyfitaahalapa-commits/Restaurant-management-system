<?php
include("includes/header.php");
include("includes/navbar.php");

$user = $_SESSION['user'];

// Handle Add to cart / place order
if(isset($_POST['order'])){
    $item = $_POST['item'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $total = $price * $qty;

    // Save in DB
    mysqli_query($conn,"INSERT INTO orders (customer,item,quantity,total,status) VALUES ('$user','$item','$qty','$total','Pending')");
    $_SESSION['msg'] = "Order placed successfully!";
    echo "<script>window.location.href='index.php';</script>";
}

// Fetch all menu items
$menu_q = mysqli_query($conn,"SELECT * FROM menu");
?>

<!-- USER HERO -->
<section style="background: url('https://images.pexels.com/photos/5086619/pexels-photo-5086619.jpeg') no-repeat center center/cover; padding: 100px 0; color: white;">
    <div class="container text-center">
        <h1 class="display-4 fw-bold" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">Our Delicious Menu</h1>
        <p class="lead" style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);">Browse our selection and order your favorites instantly</p>
    </div>
</section>

<div class="container mt-5">

    <?php if(isset($_SESSION['msg'])){ ?>
        <div class="alert alert-success shadow-sm rounded-3 border-0 mb-4">
            <i class="fas fa-check-circle me-2"></i> <?=$_SESSION['msg'];?>
            <?php unset($_SESSION['msg']); ?>
        </div>
    <?php } ?>
    
    <div class="row g-4 mb-5">
        <?php while($row = mysqli_fetch_assoc($menu_q)){ ?>
        <div class="col-md-6 col-lg-3">
            <div class="card food-card h-100 shadow-sm">
                <div class="food-img-wrapper">
                    <img src="../<?= $row['image']; ?>" class="card-img-top" alt="<?= $row['name']; ?>">
                </div>
                <div class="card-body d-flex flex-column text-center">
                    <h5 class="card-title fw-bold mb-1"><?= $row['name']; ?></h5>
                    <p class="card-text text-danger fw-bold fs-5 mb-3">$<?= $row['price']; ?></p>
                    
                    <form method="POST" class="mt-auto">
                        <input type="hidden" name="item" value="<?= $row['name']; ?>">
                        <input type="hidden" name="price" value="<?= $row['price']; ?>">
                        
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-white border-end-0">Qty</span>
                            <input type="number" name="qty" value="1" min="1" max="10" class="form-control text-center border-start-0 ps-0">
                        </div>
                        
                        <button class="btn btn-warning w-100 fw-bold text-dark rounded-pill" name="order">
                            <i class="fas fa-cart-plus me-2"></i> Order Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

</div>

<?php include("includes/footer.php"); ?>
