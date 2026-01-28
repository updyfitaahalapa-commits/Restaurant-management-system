<?php
include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");

// Update Stock Logic
if(isset($_POST['update_stock'])){
    $id = $_POST['id'];
    $qty = $_POST['quantity'];
    
    // Check if entry exists
    $check = mysqli_query($conn, "SELECT * FROM inventory WHERE menu_id='$id'");
    if(mysqli_num_rows($check) > 0){
        mysqli_query($conn, "UPDATE inventory SET quantity='$qty' WHERE menu_id='$id'");
    } else {
        mysqli_query($conn, "INSERT INTO inventory (menu_id, quantity) VALUES ('$id', '$qty')");
    }
    echo "<script>window.location.href='inventory.php';</script>";
}

// Get menu items with inventory
$q = mysqli_query($conn, "SELECT m.*, i.quantity FROM menu m LEFT JOIN inventory i ON m.id = i.menu_id");
?>

<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Inventory Management</h4>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Item Name</th>
                            <th>Current Stock</th>
                            <th>Update Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row=mysqli_fetch_assoc($q)){ 
                            $stock = $row['quantity'] ? $row['quantity'] : 0;
                        ?>
                        <tr>
                            <td>
                                <img src="../<?= $row['image']; ?>" 
                                     style="width:50px; height:50px; object-fit:cover; border-radius:10px;" 
                                     alt="Food">
                            </td>
                            <td><span class="fw-bold"><?= $row['name']; ?></span></td>
                            <td>
                                <?php if($stock < 5) { ?>
                                    <span class="badge bg-light-danger text-danger">Low: <?= $stock; ?></span>
                                <?php } else { ?>
                                    <span class="badge bg-light-success text-success"><?= $stock; ?></span>
                                <?php } ?>
                            </td>
                            <td>
                                <form method="POST" class="d-flex align-items-center" style="max-width:200px;">
                                    <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                    <input type="number" name="quantity" class="form-control form-control-sm me-2" value="<?= $stock; ?>" min="0" required>
                                    <button class="btn btn-sm btn-primary" name="update_stock">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
