<?php
include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");

// Total Sales
$sales_q = mysqli_query($conn,"SELECT SUM(total) AS total_sales FROM orders WHERE status='Completed'");
$sales = mysqli_fetch_assoc($sales_q)['total_sales'] ?? 0;

// Chart Data
$chart_q = mysqli_query($conn,"SELECT DATE(order_date) as order_day,SUM(total) AS total FROM orders WHERE status='Completed' GROUP BY order_day ORDER BY order_day ASC");
$chart_labels=[];$chart_data=[];
while($row=mysqli_fetch_assoc($chart_q)){
    $chart_labels []= date('M d', strtotime($row['order_day']));
    $chart_data []= $row['total'];
}
?>

<div class="row">
    <!-- Sales Card -->
    <div class="col-md-4 mb-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #1e1e2d 0%, #2c2c40 100%); color:white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-white-50 text-uppercase fw-bold mb-1">Total Earnings</h6>
                    <h3 class="text-white mb-0">$<?= number_format($sales, 2); ?></h3>
                    <small class="text-success"><i class="fas fa-arrow-up"></i> All time revenue</small>
                </div>
                <div class="stat-icon" style="opacity:0.2;">
                    <i class="fas fa-wallet text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body p-4 text-center">
                <i class="fas fa-file-download text-primary fs-1 mb-3"></i>
                <h5 class="fw-bold">Export Data</h5>
                <p class="text-muted small">Download sales reports as CSV or PDF.</p>
                <button class="btn btn-outline-primary btn-sm rounded-pill px-4">Download CSV</button>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="col-md-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">Sales Analytics</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" style="max-height: 400px;"></canvas>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($chart_labels); ?>,
        datasets: [{
            label: 'Daily Sales ($)',
            data: <?= json_encode($chart_data); ?>,
            borderColor: '#D32F2F',
            backgroundColor: 'rgba(211, 47, 47, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#D32F2F',
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    borderDash: [5, 5]
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>