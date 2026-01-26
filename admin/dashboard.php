<?php
include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");

// Stats Queries
$menu_count = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM menu"));
$order_count = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM orders"));
$sales_q = mysqli_query($conn,"SELECT SUM(total) AS sales FROM orders WHERE status='Completed'");
$sales = mysqli_fetch_assoc($sales_q)['sales'];
if(!$sales) $sales = 0;
?>

<div class="row g-4 mb-4">
    <!-- Stat Card 1 -->
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted text-uppercase fw-bold mb-1">Total Sales</h6>
                    <h3 class="text-success">$<?= number_format($sales, 2); ?></h3>
                    <span class="badge bg-light-success text-success">
                        <i class="fas fa-arrow-up me-1"></i> +12.5%
                    </span>
                    <small class="text-muted ms-2">vs last month</small>
                </div>
                <div class="stat-icon text-success">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted text-uppercase fw-bold mb-1">Total Orders</h6>
                    <h3 class="text-primary"><?= $order_count; ?></h3>
                    <span class="badge bg-light-warning text-warning">
                        <i class="fas fa-exclamation-circle me-1"></i> New
                    </span>
                    <small class="text-muted ms-2">orders pending</small>
                </div>
                <div class="stat-icon text-primary">
                    <i class="fas fa-shopping-bag"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted text-uppercase fw-bold mb-1">Menu Items</h6>
                    <h3 class="text-info"><?= $menu_count; ?></h3>
                    <span class="text-muted small">Active dishes</span>
                </div>
                <div class="stat-icon text-info">
                    <i class="fas fa-utensils"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- RECENT ORDERS -->
<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Recent Orders</h5>
                <a href="orders.php" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Item</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recent = mysqli_query($conn,"SELECT * FROM orders ORDER BY order_date DESC LIMIT 5");
                        while($r = mysqli_fetch_assoc($recent)){ 
                        ?>
                        <tr>
                            <td><span class="fw-bold">#<?= $r['id']; ?></span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div style="width:35px; height:35px; background:#f0f2f5; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:10px; font-weight:bold; color:#5e6278;">
                                        <?= strtoupper(substr($r['customer'],0,1)); ?>
                                    </div>
                                    <?= $r['customer']; ?>
                                </div>
                            </td>
                            <td><?= $r['item']; ?> <small class="text-muted">x<?= $r['quantity']; ?></small></td>
                            <td class="fw-bold">$<?= number_format($r['total'], 2); ?></td>
                            <td>
                                <?php if($r['status']=="Pending"){ ?>
                                    <span class="badge bg-light-warning">Pending</span>
                                <?php } else { ?>
                                    <span class="badge bg-light-success">Completed</span>
                                <?php } ?>
                            </td>
                            <td class="text-muted small"><?= date('M d, Y', strtotime($r['order_date'])); ?></td>
                            <td>
                                <a href="orders.php?id=<?= $r['id']; ?>" class="btn btn-icon btn-sm btn-light text-primary" title="Details">
                                    <i class="fas fa-eye"></i>
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
