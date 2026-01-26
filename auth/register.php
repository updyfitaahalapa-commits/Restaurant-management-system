<?php
session_start();
include("../config/db.php");

$msg = "";

if(isset($_POST['register'])){
    $fullname = mysqli_real_escape_string($conn,$_POST['fullname']);
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if($password != $cpassword){
        $msg = "Passwords do not match!";
    } else {
        $check = mysqli_query($conn,"SELECT * FROM users WHERE username='$username'");
        if(mysqli_num_rows($check)>0){
            $msg = "Username already exists!";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($conn,"INSERT INTO users (fullname,username,password,role) 
                                VALUES ('$fullname','$username','$hash','customer')");
            $_SESSION['success'] = "Account created successfully! Login now.";
            header("location:login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Gourmet Haven</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="../assets/style.css" rel="stylesheet">
</head>
<body class="auth-body">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="auth-card" data-aos="zoom-in" data-aos-duration="1000">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-plus text-warning fs-1 mb-2"></i>
                        <h2 class="auth-title">Join Us</h2>
                        <p class="text-muted">Create an account to start ordering</p>
                    </div>

                    <?php if($msg){ ?>
                        <div class="alert alert-danger border-0 shadow-sm" style="border-radius:10px;">
                            <i class="fas fa-exclamation-circle me-2"></i> <?= $msg ?>
                        </div>
                    <?php } ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">FULL NAME</label>
                            <input type="text" name="fullname" class="form-control" placeholder="Enter full name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">USERNAME</label>
                            <input type="text" name="username" class="form-control" placeholder="Choose a username" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">PASSWORD</label>
                            <input type="password" name="password" class="form-control" placeholder="Create a password" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small">CONFIRM PASSWORD</label>
                            <input type="password" name="cpassword" class="form-control" placeholder="Confirm password" required>
                        </div>

                        <div class="d-grid mb-4">
                            <button class="btn btn-auth" name="register">
                                Create Account <i class="fas fa-check-circle ms-2"></i>
                            </button>
                        </div>
                    </form>

                    <p class="text-center mb-0 text-muted">
                        Already have an account? <a href="login.php" class="text-danger fw-bold text-decoration-none">Sign In</a>
                    </p>
                    
                    <div class="text-center mt-3">
                        <a href="../index.php" class="text-muted small text-decoration-none">
                            <i class="fas fa-home me-1"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
