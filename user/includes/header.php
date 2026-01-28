<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../config/db.php");

// Only customers allowed
if (!isset($_SESSION['user']) || $_SESSION['role'] != "customer") {
    header("location:../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaama Liito | Menu</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Premium CSS -->
    <link href="../assets/style.css" rel="stylesheet">
    
    <style>
        body { padding-top: 80px; } /* Space for fixed navbar */
        .food-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            overflow: hidden;
            border-radius: 15px;
        }
        .food-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .food-img-wrapper {
            height: 200px;
            overflow: hidden;
            position: relative;
        }
        .food-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .food-card:hover .food-img-wrapper img {
            transform: scale(1.1);
        }
    </style>
</head>
<body>

<?php if(isset($_SESSION['msg'])){ 
    $msg = $_SESSION['msg'];
    $msg_type = isset($_SESSION['msg_type']) ? $_SESSION['msg_type'] : 'success'; // Default to success
    unset($_SESSION['msg']);
    unset($_SESSION['msg_type']);
?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            icon: '<?= $msg_type; ?>',
            title: '<?= ucfirst($msg_type); ?>!',
            text: '<?= $msg; ?>',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
    });
</script>
<?php } ?>
