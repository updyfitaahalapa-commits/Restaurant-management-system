<?php
include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");

$msg="";
if(isset($_POST['save'])){
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Upload image
    $img = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp,"../assets/images/".$img);

    mysqli_query($conn,"INSERT INTO menu (name,price,image) VALUES ('$name','$price','assets/images/$img')");
    $msg = "Menu item added successfully!";
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card stat-card border-0">
            <div class="text-center mb-4">
                <i class="fas fa-utensils text-primary fs-1 mb-2"></i>
                <h4 class="fw-bold">Add New Item</h4>
                <p class="text-muted">Create a new dish for your menu</p>
            </div>

            <?php if($msg){ ?>
                <div class="alert alert-success border-0 shadow-sm rounded-3">
                    <i class="fas fa-check-circle me-2"></i> <?=$msg;?>
                </div>
            <?php } ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">DISH NAME</label>
                    <input type="text" name="name" class="form-control form-control-lg fs-6" placeholder="e.g. Grilled Salmon" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">PRICE ($)</label>
                    <input type="number" step="0.01" name="price" class="form-control form-control-lg fs-6" placeholder="e.g. 15.99" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">DISH IMAGE</label>
                    <input type="file" name="image" class="form-control" required>
                    <div class="form-text">Recommended size: 500x500px</div>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-primary btn-lg" name="save">
                        <i class="fas fa-save me-2"></i> Save to Menu
                    </button>
                    <a href="view_menu.php" class="btn btn-light btn-lg text-muted">Cancel</a>
                </div>
            </form>

        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>