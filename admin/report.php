<?php
include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");

// 1. Sales Data
$today = date('Y-m-d');
$sales_today_q = mysqli_query($conn, "SELECT SUM(total) as val FROM orders WHERE status='Completed' AND DATE(order_date)='$today'");
$sales_today = mysqli_fetch_assoc($sales_today_q)['val'] ?? 0;

$sales_month_q = mysqli_query($conn, "SELECT SUM(total) as val FROM orders WHERE status='Completed' AND MONTH(order_date)=MONTH(CURRENT_DATE()) AND YEAR(order_date)=YEAR(CURRENT_DATE())");
$sales_month = mysqli_fetch_assoc($sales_month_q)['val'] ?? 0;

$sales_all_q = mysqli_query($conn, "SELECT SUM(total) as val FROM orders WHERE status='Completed'");
$sales_all = mysqli_fetch_assoc($sales_all_q)['val'] ?? 0;

// 2. Inventory Usage (Top Selling)
$top_q = mysqli_query($conn, "SELECT item_name, SUM(quantity) as sold FROM order_items GROUP BY item_name ORDER BY sold DESC LIMIT 5");

// 3. Low Stock Items
$low_stock_q = mysqli_query($conn, "SELECT m.name, i.quantity FROM menu m JOIN inventory i ON m.id = i.menu_id WHERE i.quantity < 5");

?>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card bg-primary text-white">
            <h6 class="text-white-50">Sales Today</h6>
            <h3>$<?= number_format($sales_today, 2); ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-success text-white">
            <h6 class="text-white-50">Sales This Month</h6>
            <h3>$<?= number_format($sales_month, 2); ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-dark text-white">
            <h6 class="text-white-50">Total Sales</h6>
            <h3>$<?= number_format($sales_all, 2); ?></h3>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top Selling Items -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Inventory Usage (Top Selling)</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Qty Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($top_q)) { ?>
                        <tr>
                            <td><?= $row['item_name']; ?></td>
                            <td class="fw-bold text-primary"><?= $row['sold']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Low Stock -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0 text-danger">Low Stock Alerts</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Stock Left</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($low_stock_q)) { ?>
                        <tr>
                            <td><?= $row['name']; ?></td>
                            <td class="fw-bold text-danger"><?= $row['quantity']; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if(mysqli_num_rows($low_stock_q)==0){ echo "<tr><td colspan='2'>No low stock items.</td></tr>"; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
             <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Monthly Sales Breakdown</h5>
            </div>
            <div class="card-body">
                 <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Orders Count</th>
                            <th>Total Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $month_breakdown = mysqli_query($conn, "SELECT DATE(order_date) as d, COUNT(*) as c, SUM(total) as t FROM orders WHERE status='Completed' AND MONTH(order_date)=MONTH(CURRENT_DATE()) GROUP BY d ORDER BY d DESC");
                        while($row=mysqli_fetch_assoc($month_breakdown)){
                        ?>
                        <tr>
                            <td><?= $row['d']; ?></td>
                            <td><?= $row['c']; ?></td>
                            <td>$<?= number_format($row['t'], 2); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                 </table>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>