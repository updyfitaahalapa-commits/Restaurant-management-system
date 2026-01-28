<?php
include("includes/header.php");
include("includes/navbar.php");

$customer = $_SESSION['user'];
$q = mysqli_query($conn,"SELECT * FROM orders WHERE customer='$customer' ORDER BY order_date DESC");
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
                            <th class="py-3 ps-4">ORDER ID</th>
                            <th class="py-3">SUMMARY</th>
                            <th class="py-3">TOTAL</th>
                            <th class="py-3">STATUS</th>
                            <th class="py-3">PAYMENT</th>
                            <th class="py-3">DATE</th>
                            <th class="py-3">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($q)==0){ ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">You haven't placed any orders yet.</td></tr>
                        <?php } else {
                        while($row = mysqli_fetch_assoc($q)){ ?>
                        <tr>
                            <td class="ps-4 fw-bold">#<?= $row['id']; ?></td>
                            <td class="text-muted">
                                <span class="fw-bold text-dark"><?= $row['item']; ?></span>
                                <br><small><?= $row['quantity']; ?> Items</small>
                            </td>
                            <td class="fw-bold text-primary">$<?= number_format($row['total'], 2); ?></td>
                            <td>
                                <?php if($row['status']=="Pending"){ ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-3">Pending</span>
                                <?php } else { ?>
                                    <span class="badge bg-success rounded-pill px-3">Completed</span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php 
                                $pstatus = $row['payment_status'] ?? 'Pending';
                                if($pstatus=="Paid"){ ?>
                                    <span class="badge bg-light-success text-success fw-bold">Paid</span>
                                <?php } else { ?>
                                    <span class="badge bg-light-danger text-danger fw-bold">Unpaid</span>
                                <?php } ?>
                            </td>
                            <td class="text-muted small"><?= date('M d, Y', strtotime($row['order_date'])); ?></td>
                            <td>
                                <a href="view_order.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>