<?php
include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");

$id = "";
$name = "";
$qty = "";
$unit = "pcs";
$min = 5;
$edit_mode = false;

if(isset($_GET['edit'])){
    $edit_mode = true;
    $id = $_GET['edit'];
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM inventory WHERE id='$id'"));
    $name = $data['item_name'];
    $qty = $data['quantity'];
    $unit = $data['unit'];
    $min = $data['min_threshold'];
}

if(isset($_POST['save'])){
    $name = $_POST['name'];
    $qty = $_POST['quantity'];
    $unit = $_POST['unit'];
    $min = $_POST['min_threshold'];

    if($edit_mode){
        $sql = "UPDATE inventory SET item_name='$name', quantity='$qty', unit='$unit', min_threshold='$min' WHERE id='$id'";
    } else {
        $sql = "INSERT INTO inventory (item_name, quantity, unit, min_threshold) VALUES ('$name', '$qty', '$unit', '$min')";
    }

    if(mysqli_query($conn, $sql)){
        echo "<script>window.location.href='inventory.php';</script>";
    } else {
        echo "<script>alert('Error: ".mysqli_error($conn)."');</script>";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card stat-card border-0">
            <div class="text-center mb-4">
                <i class="fas fa-boxes text-primary fs-1 mb-2"></i>
                <h4 class="fw-bold"><?= $edit_mode ? "Edit Item" : "Add Inventory Item"; ?></h4>
            </div>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small">ITEM NAME</label>
                    <input type="text" name="name" class="form-control" value="<?= $name; ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">QUANTITY</label>
                        <input type="number" name="quantity" class="form-control" value="<?= $qty; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">UNIT (e.g. pcs, kg)</label>
                        <input type="text" name="unit" class="form-control" value="<?= $unit; ?>" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small">LOW STOCK THRESHOLD</label>
                    <input type="number" name="min_threshold" class="form-control" value="<?= $min; ?>" required>
                    <div class="form-text">Alert when stock falls below this number</div>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-primary" name="save">
                        <i class="fas fa-save me-2"></i> Save Item
                    </button>
                    <a href="inventory.php" class="btn btn-light text-muted">Cancel</a>
                </div>
            </form>

        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
