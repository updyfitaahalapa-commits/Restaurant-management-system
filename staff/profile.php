<?php
session_start();
include("../config/db.php");

// Security Check
if (!isset($_SESSION['user']) || $_SESSION['role'] != "staff") {
    header("location:../auth/login.php");
    exit();
}

$username = $_SESSION['user'];
$msg = "";
$msg_type = "";

// Handle Profile Update
if(isset($_POST['update_profile'])){
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $new_username = mysqli_real_escape_string($conn, $_POST['username']);
    
    // Check if username taken
    $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$new_username' AND username != '$username'");
    if(mysqli_num_rows($check) > 0){
        $msg = "Username already taken!";
        $msg_type = "danger";
    } else {
        mysqli_query($conn, "UPDATE users SET fullname='$fullname', username='$new_username' WHERE username='$username'");
        $_SESSION['user'] = $new_username; // Update session
        $username = $new_username;
        $msg = "Profile updated successfully!";
        $msg_type = "success";
    }
}

// Handle Password Change
if(isset($_POST['change_password'])){
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // Get current password
    $q = mysqli_query($conn, "SELECT password FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($q);

    if(password_verify($old_pass, $user['password'])){
        if($new_pass === $confirm_pass){
            if(strlen($new_pass) >= 6){
                $hash = password_hash($new_pass, PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE users SET password='$hash' WHERE username='$username'");
                $msg = "Password changed successfully!";
                $msg_type = "success";
            } else {
                $msg = "Password must be at least 6 characters!";
                $msg_type = "danger";
            }
        } else {
            $msg = "New passwords do not match!";
            $msg_type = "danger";
        }
    } else {
        $msg = "Incorrect old password!";
        $msg_type = "danger";
    }
}

// Fetch Current Data
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE username='$username'"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Staff Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="../admin/css/admin.css" rel="stylesheet">
    
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
            <li>
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
                <div class="ms-auto dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center fw-bold text-dark" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2 fs-4"></i> Staff: <?= $_SESSION['user']; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-id-card me-2"></i> My Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid px-4">
            <h4 class="fw-bold mb-4">My Configuration</h4>

            <div class="row justify-content-center">
                <!-- Profile Edit -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">Edit Profile</h5>
                        </div>
                        <div class="card-body">
                            <?php if($msg){ ?>
                                <div class="alert alert-<?= $msg_type; ?> shadow-sm border-0 rounded-3 mb-3">
                                    <?= $msg; ?>
                                </div>
                            <?php } ?>

                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">FULL NAME</label>
                                    <input type="text" name="fullname" class="form-control" value="<?= $data['fullname']; ?>" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-muted">USERNAME</label>
                                    <input type="text" name="username" class="form-control" value="<?= $data['username']; ?>" required>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-primary" name="update_profile">
                                        <i class="fas fa-save me-2"></i> Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                         <div class="card-header bg-white border-0 py-3">
                            <h5 class="fw-bold mb-0">Change Password</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">OLD PASSWORD</label>
                                    <input type="password" name="old_password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">NEW PASSWORD</label>
                                    <input type="password" name="new_password" class="form-control" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-muted">CONFIRM PASSWORD</label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-dark" name="change_password">
                                        <i class="fas fa-key me-2"></i> Change Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
