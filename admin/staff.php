<?php
include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");

// Add Staff
$msg = "";
if(isset($_POST['add_staff'])){
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $role = 'staff'; // Default role

    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if(mysqli_num_rows($check) > 0){
        $msg = "Username already exists!";
        $msg_type = "danger";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($conn, "INSERT INTO users (fullname, username, password, role) VALUES ('$fullname', '$username', '$hash', '$role')");
        $msg = "Staff member added successfully!";
        $msg_type = "success";
    }
}

// Delete Staff
if(isset($_GET['del'])){
    $id = $_GET['del'];
    mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
    echo "<script>window.location.href='staff.php';</script>";
}

// Fetch Staff
$q = mysqli_query($conn, "SELECT * FROM users WHERE role='staff'");
?>

<div class="row">
    <!-- Staff List -->
    <div class="col-md-8">
        <div class="table-card">
            <h4 class="fw-bold mb-4">Staff Management</h4>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i=1;
                        if(mysqli_num_rows($q) == 0){
                            echo "<tr><td colspan='6' class='text-center text-muted py-4'>No staff members found.</td></tr>";
                        } else {
                        while($row = mysqli_fetch_assoc($q)){ ?>
                        <tr>
                            <td class="text-muted fw-bold"><?= $i++; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div style="width:35px; height:35px; background:#e9ecef; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:10px;">
                                        <i class="fas fa-user-tie text-secondary"></i>
                                    </div>
                                    <?= $row['fullname']; ?>
                                </div>
                            </td>
                            <td><?= $row['username']; ?></td>
                            <td><span class="badge bg-light-primary text-primary text-uppercase"><?= $row['role']; ?></span></td>
                            <td class="text-muted small">
                                <?= isset($row['created_at']) ? date('M d, Y', strtotime($row['created_at'])) : '-'; ?>
                            </td>
                            <td>
                                <a href="staff.php?del=<?= $row['id']; ?>" class="btn btn-sm btn-icon btn-light text-danger" onclick="return confirm('Are you sure?')" title="Remove">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Staff Form -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">Add New Staff</h5>
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
                        <input type="text" name="fullname" class="form-control" placeholder="e.g. John Doe" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">USERNAME</label>
                        <input type="text" name="username" class="form-control" placeholder="e.g. johnd" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">PASSWORD</label>
                        <input type="password" name="password" class="form-control" placeholder="Create password" required>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-dark" name="add_staff">
                            <i class="fas fa-plus-circle me-2"></i> Add Staff
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
