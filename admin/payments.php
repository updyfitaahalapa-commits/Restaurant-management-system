<?php
include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");

$q = mysqli_query($conn, "SELECT p.*, o.customer, o.total as order_total 
                          FROM payments p 
                          JOIN orders o ON p.order_id = o.id 
                          ORDER BY p.transaction_date DESC");
?>

<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Payment Records</h4>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row=mysqli_fetch_assoc($q)){ ?>
                        <tr>
                            <td class="text-muted fw-bold">#<?= $row['id']; ?></td>
                            <td class="fw-bold">
                                <a href="orders.php?id=<?= $row['order_id']; ?>" class="text-decoration-none">
                                    #<?= $row['order_id']; ?>
                                </a>
                            </td>
                            <td><?= $row['customer']; ?></td>
                            <td class="fw-bold text-success">$<?= number_format($row['amount'], 2); ?></td>
                            <td><?= $row['payment_method']; ?></td>
                            <td>
                                <?php if($row['status']=="Paid"){ ?>
                                    <span class="badge bg-light-success text-success">Paid</span>
                                <?php } else { ?>
                                    <span class="badge bg-light-warning text-warning">Pending</span>
                                <?php } ?>
                            </td>
                            <td class="text-muted small"><?= date('M d, Y H:i', strtotime($row['transaction_date'])); ?></td>
                            <td>
                                <?php if($row['status']!="Paid"){ ?>
                                    <form method="POST" action="update_payment.php" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                        <button class="btn btn-sm btn-success" name="mark_paid" title="Mark as Paid">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                <?php } ?>
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
