<?php
include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");

$msg="";
if(isset($_POST['save'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $inventory_id = $_POST['inventory_id'];
    $qty_req = $_POST['quantity_required'];

    // Upload image
    $img = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    $target = "assets/images/".basename($img);
    // Note: In real app, check for errors, file types etc. 
    // Fix path: admin uses ../assets
    move_uploaded_file($tmp,"../".$target);

    $sql = "INSERT INTO menu (name, price, image, inventory_id, quantity_required) 
            VALUES ('$name', '$price', '$target', " . ($inventory_id ? "'$inventory_id'" : "NULL") . ", '$qty_req')";
    
    if(mysqli_query($conn, $sql)){
        $msg = "Menu item added successfully!";
    } else {
        $msg = "Error: " . mysqli_error($conn);
    }
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
                </div>

                <!-- NEW: Inventory Link -->
                <div class="card bg-light border-0 p-3 mb-4">
                    <h6 class="fw-bold text-muted mb-3"><i class="fas fa-boxes me-1"></i> Inventory Link (Optional)</h6>
                    <div class="mb-3">
                        <label class="form-label small">LINK TO INVENTORY ITEM</label>
                        <select name="inventory_id" class="form-select">
                            <option value="">-- No Link --</option>
                            <?php 
                            $inv = mysqli_query($conn, "SELECT * FROM inventory ORDER BY item_name ASC");
                            while($i = mysqli_fetch_assoc($inv)){
                                echo "<option value='".$i['id']."'>".$i['item_name']." (Stock: ".$i['quantity']." ".$i['unit'].")</option>";
                            }
                            ?>
                        </select>
                        <div class="form-text">If linked, ordering this item will deduct stock automatically.</div>
                    </div>
                    <div>
                        <label class="form-label small">QUANTITY TO DEDUCT</label>
                        <input type="number" name="quantity_required" class="form-control" value="1" min="1">
                    </div>
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