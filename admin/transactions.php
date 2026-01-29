<?php
include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");

// Enable Error Reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Fetch Transactions
$q_sql = "SELECT t.*, o.customer FROM transactions t LEFT JOIN orders o ON t.order_id = o.id ORDER BY t.created_at DESC";
$q = mysqli_query($conn, $q_sql);

if(!$q){
    echo "<div class='alert alert-danger'>Error fetching transactions: " . mysqli_error($conn) . "</div>";
}
?>

<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Transaction History</h4>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Trans ID</th>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Card Mask</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(mysqli_num_rows($q) > 0){
                        while($row=mysqli_fetch_assoc($q)){ ?>
                        <tr>
                            <td class="text-muted small"><?= $row['id']; ?></td>
                            <td class="fw-bold text-dark"><?= $row['transaction_id']; ?></td>
                            <td>
                                <a href="orders.php?id=<?= $row['order_id']; ?>" class="text-primary text-decoration-none">
                                    #<?= $row['order_id']; ?>
                                </a>
                            </td>
                            <td><?= $row['customer']; ?></td>
                            <td class="fw-bold text-success">$<?= number_format($row['amount'], 2); ?></td>
                            <td><span class="badge bg-light text-dark"><i class="fas fa-credit-card me-1"></i> <?= $row['payment_method']; ?></span></td>
                            <td class="text-muted small"><?= $row['card_mask']; ?></td>
                            <td>
                                <?php if($row['status'] == 'Success'){ ?>
                                    <span class="badge bg-light-success text-success">Success</span>
                                <?php } else { ?>
                                    <span class="badge bg-light-danger text-danger">Failed</span>
                                <?php } ?>
                            </td>
                            <td class="text-muted small"><?= date('M d, Y H:i', strtotime($row['created_at'])); ?></td>
                        </tr>
                        <?php } 
                        } else {
                            echo "<tr><td colspan='9' class='text-center py-4 text-muted'>No transactions found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
