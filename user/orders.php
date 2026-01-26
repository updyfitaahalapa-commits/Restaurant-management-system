<?php
include("includes/header.php");
include("includes/navbar.php");

$customer = $_SESSION['user'];
$q = mysqli_query($conn,"SELECT o.*, m.image FROM orders o LEFT JOIN menu m ON o.item = m.name WHERE o.customer='$customer' ORDER BY o.order_date DESC");
?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="fas fa-receipt text-secondary me-2"></i> MY ORDER HISTORY</h3>
        <a href="index.php" class="btn btn-outline-dark rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i> Back to Menu
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 ps-4">ITEM DETAILS</th>
                            <th class="py-3">QUANTITY</th>
                            <th class="py-3">TOTAL</th>
                            <th class="py-3">STATUS</th>
                            <th class="py-3">DATE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($q)==0){ ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">You haven't placed any orders yet.</td></tr>
                        <?php } else {
                        while($row = mysqli_fetch_assoc($q)){ ?>
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <img src="../<?= $row['image'] ?? 'assets/images/no-image.png'; ?>" 
                                         style="width:60px; height:60px; object-fit:cover; border-radius:10px; margin-right:15px;" 
                                         alt="Food">
                                    <span class="fw-bold text-dark"><?= $row['item']; ?></span>
                                </div>
                            </td>
                            <td class="fw-bold text-muted ps-4"><?= $row['quantity']; ?></td>
                            <td class="fw-bold text-primary">$<?= number_format($row['total'], 2); ?></td>
                            <td>
                                <?php if($row['status']=="Pending"){ ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-3">Pending</span>
                                <?php } else { ?>
                                    <span class="badge bg-success rounded-pill px-3">Completed</span>
                                <?php } ?>
                            </td>
                            <td class="text-muted small"><?= date('M d, Y', strtotime($row['order_date'])); ?></td>
                        </tr>
                        <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>