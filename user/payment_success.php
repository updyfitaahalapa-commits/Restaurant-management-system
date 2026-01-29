<?php
session_start();
include("includes/header.php");
include("includes/navbar.php");

if(!isset($_GET['tid'])){
    header("Location: index.php");
    exit();
}

$tid = $_GET['tid'];
?>

<div class="container mt-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 p-5">
                <div class="mb-4">
                    <div style="width:80px; height:80px; background:#e8f5e9; border-radius:50%; display:inline-flex; align-items:center; justify-content:center;">
                        <i class="fas fa-check text-success display-4"></i>
                    </div>
                </div>
                
                <h2 class="fw-bold text-success mb-2">Payment Successful!</h2>
                <p class="text-muted">Thank you for your purchase.</p>

                <div class="bg-light p-4 rounded-3 mt-4 text-start">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small fw-bold">TRANSACTION ID</span>
                        <span class="fw-bold text-dark"><?= $tid; ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small fw-bold">DATE</span>
                        <span class="fw-bold text-dark"><?= date('M d, Y H:i'); ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small fw-bold">STATUS</span>
                        <span class="badge bg-success">APPROVED</span>
                    </div>
                </div>

                <div class="mt-5">
                    <a href="orders.php" class="btn btn-dark rounded-pill px-5 fw-bold">View My Orders</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
