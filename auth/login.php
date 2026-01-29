<?php
session_start();
include("../config/db.php");

$error = "";

// Marka user-ku login sameeyo
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Raadi username
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' LIMIT 1");

    if (mysqli_num_rows($query) == 1) {
        $user = mysqli_fetch_assoc($query);

        // Hubi password si secure ah
        if (password_verify($password, $user['password'])) {

            // Session set garee
            $_SESSION['user'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect ku saleysan role
            if ($user['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");
            } elseif ($user['role'] == 'staff') {
                header("Location: ../staff/dashboard.php");
            } else {
                header("Location: ../user/index.php");
            }
            exit();
        } else {
            $error = "Password-ka waa qalad!";
        }
    } else {
        $error = "Username ma jiro!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Kaama Liito</title>
    
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
                        <i class="fas fa-utensils text-warning fs-1 mb-2"></i>
                        <h2 class="auth-title">Welcome Back</h2>
                        <p class="text-muted">Sign in to continue your dining experience</p>
                    </div>

                    <?php if ($error != "") { ?>
                        <div class="alert alert-danger border-0 shadow-sm" style="border-radius:10px;">
                            <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
                        </div>
                    <?php } ?>

                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small">USERNAME</label>
                            <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small">PASSWORD</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" name="login" class="btn btn-auth">
                                Sign In <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>

                        <p class="text-center mb-0 text-muted">
                            Don’t have an account? <a href="register.php" class="text-danger fw-bold text-decoration-none">Register Now</a>
                        </p>
                        
                        <div class="text-center mt-3">
                            <a href="../index.php" class="text-muted small text-decoration-none">
                                <i class="fas fa-home me-1"></i> Back to Home
                            </a>
                        </div>
                    </form>

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
