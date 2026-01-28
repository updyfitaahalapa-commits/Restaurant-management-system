<?php
session_start();
include("../config/db.php");

// Security Check
if (!isset($_SESSION['user']) || $_SESSION['role'] != "staff") {
    header("location:../auth/login.php");
    exit();
}

// Complete Order Logic
if(isset($_GET['complete'])){
    $id = $_GET['complete'];
    mysqli_query($conn,"UPDATE orders SET status='Completed' WHERE id='$id'");
    header("location:dashboard.php");
    exit();
}

// Stats
$pending_q = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE status='Pending'");
$pending_count = mysqli_fetch_assoc($pending_q)['count'];

$completed_q = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE status='Completed'");
$completed_count = mysqli_fetch_assoc($completed_q)['count'];

// Recent Orders
$orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY order_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard | Gourmet Haven</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="../admin/css/admin.css" rel="stylesheet"> <!-- Re-using admin styles -->
    
    <style>
        .sidebar { background: #2c3e50; } 
    </style>
</head>
<body>

<div class="d-flex" id="wrapper">
    <!-- SIDEBAR -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h4 class="fw-bold mb-0">GOURMET<span style="color:#f39c12">STAFF</span></h4>
            <small class="text-muted" style="font-size:0.7rem; letter-spacing:1px;">STAFF PANEL</small>
        </div>

        <ul class="list-unstyled components">
            <li class="active">
                <a href="dashboard.php"><i class="fas fa-th-large"></i> Dashboard</a>
            </li>
            <li>
                <a href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a>
            </li>
        </ul>

        <div class="mt-auto p-3">
            <a href="../auth/logout.php" class="btn btn-warning w-100 text-dark fw-bold">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </nav>

    <!-- CONTENT -->
    <div id="content" class="w-100">
        
        <!-- TOPBAR -->
        <nav class="navbar navbar-expand-lg topbar mb-4">
            <div class="container-fluid">
                <span class="navbar-brand ms-auto fw-bold text-dark">
                    <i class="fas fa-user-circle me-2"></i> Staff: <?= $_SESSION['user']; ?>
                </span>
            </div>
        </nav>

        <div class="container-fluid px-4">
            
            <!-- STATS -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase fw-bold mb-1">Pending Orders</h6>
                                <h3 class="text-warning"><?= $pending_count; ?></h3>
                            </div>
                            <div class="stat-icon text-warning"><i class="fas fa-hourglass-half"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase fw-bold mb-1">Completed Orders</h6>
                                <h3 class="text-success"><?= $completed_count; ?></h3>
                            </div>
                            <div class="stat-icon text-success"><i class="fas fa-check-circle"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ORDERS TABLE -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Incoming Orders</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">#ID</th>
                                    <th>Customer</th>
                                    <th>Item</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($r = mysqli_fetch_assoc($orders)){ ?>
                                <tr>
                                    <td class="ps-4 fw-bold">#<?= $r['id']; ?></td>
                                    <td><?= $r['customer']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-shopping-bag text-muted me-2"></i>
                                            <?= $r['item']; ?> <span class="badge bg-light text-dark ms-2">x<?= $r['quantity']; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($r['status'] == 'Pending'){ ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php } else { ?>
                                            <span class="badge bg-success">Completed</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if($r['status'] == 'Pending'){ ?>
                                            <a href="?complete=<?= $r['id']; ?>" class="btn btn-sm btn-success rounded-pill px-3">
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
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
