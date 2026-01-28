<?php
include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");

// Handle Delete
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM inventory WHERE id='$id'");
    echo "<script>window.location.href='inventory.php';</script>";
}
?>

<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Inventory Management</h4>
                <a href="manage_inventory.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Add Item
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $q = mysqli_query($conn, "SELECT * FROM inventory ORDER BY id DESC");
                        while($row=mysqli_fetch_assoc($q)){ 
                            $stock = $row['quantity'];
                            $min = $row['min_threshold'];
                        ?>
                        <tr>
                            <td>#<?= $row['id']; ?></td>
                            <td class="fw-bold"><?= $row['item_name']; ?></td>
                            <td>
                                <span class="fw-bold fs-5"><?= $stock; ?></span>
                            </td>
                            <td><?= $row['unit']; ?></td>
                            <td>
                                <?php if($stock <= $min) { ?>
                                    <span class="badge bg-light-danger text-danger">
                                        <i class="fas fa-exclamation-triangle me-1"></i> Low Stock
                                    </span>
                                <?php } else { ?>
                                    <span class="badge bg-light-success text-success">In Stock</span>
                                <?php } ?>
                            </td>
                            <td><?= date('Y-m-d H:i', strtotime($row['last_updated'])); ?></td>
                            <td>
                                <a href="manage_inventory.php?edit=<?= $row['id']; ?>" class="btn btn-sm btn-icon btn-light text-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="inventory.php?delete=<?= $row['id']; ?>" class="btn btn-sm btn-icon btn-light text-danger" onclick="return confirm('Are you sure? This might affect orders!')">
                                    <i class="fas fa-trash"></i>
                                </a>
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
