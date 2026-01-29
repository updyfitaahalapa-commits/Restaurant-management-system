<?php
include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");

$username = $_SESSION['user'];
$msg = "";
$msg_type = "";

// Handle Profile Update
if(isset($_POST['update_profile'])){
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $new_username = mysqli_real_escape_string($conn, $_POST['username']);
    
    // Check if username taken by another user
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

<div class="row justify-content-center">
    <!-- Profile Edit -->
    <div class="col-md-5">
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
    <div class="col-md-5">
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

<?php include("includes/footer.php"); ?>
