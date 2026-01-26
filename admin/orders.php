<?php
include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");

// Complete order logic
if(isset($_GET['id'])){
    $id = $_GET['id'];
    mysqli_query($conn,"UPDATE orders SET status='Completed' WHERE id='$id'");
    echo "<script>window.location.href='orders.php';</script>";
}

$q = mysqli_query($conn,"SELECT o.*, m.image FROM orders o LEFT JOIN menu m ON o.item = m.name ORDER BY order_date DESC");
?>

<div class="row">
    <div class="col-12">
        <div class="table-card">
            <h4 class="fw-bold mb-4">Order Management</h4>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Item Details</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i=1; 
                        while($row=mysqli_fetch_assoc($q)){ 
                        ?>
                        <tr>
                            <td class="text-muted fw-bold"><?= $i++; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div style="width:40px; height:40px; background:#f0f2f5; border-radius:8px; overflow:hidden; margin-right:15px; display:flex; align-items:center; justify-content:center;">
                                        <i class="fas fa-user text-secondary"></i>
                                    </div>
                                    <span class="fw-bold"><?= $row['customer']; ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="../<?= $row['image'] ?? 'assets/images/no-image.png'; ?>" 
                                         style="width:50px; height:50px; object-fit:cover; border-radius:8px; margin-right:15px;" 
                                         alt="Food">
                                    <div>
                                        <h6 class="mb-0 fw-bold"><?= $row['item']; ?></h6>
                                        <small class="text-muted">Qty: <?= $row['quantity']; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-bold fs-6 text-primary">$<?= number_format($row['total'], 2); ?></td>
                            <td>
                                <?php if($row['status']=="Pending"){ ?>
                                    <span class="badge bg-light-warning">Pending</span>
                                <?php } else { ?>
                                    <span class="badge bg-light-success">Completed</span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if($row['status']=="Pending"){ ?>
                                    <a href="?id=<?= $row['id']; ?>" class="btn btn-sm btn-success rounded-pill px-3">
                                        <i class="fas fa-check me-1"></i> Complete
                                    </a>
                                <?php } else { ?>
                                    <button class="btn btn-sm btn-light text-muted rounded-pill px-3" disabled>
                                        <i class="fas fa-check-double me-1"></i> Done
                                    </button>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                <a href="dashboard.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                </a>
            </div>

        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>