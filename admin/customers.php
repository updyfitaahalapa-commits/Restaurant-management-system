<?php
include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");

// Delete Customer
if(isset($_GET['del'])){
    $id = $_GET['del'];
    // Optional: Check if customer has orders before deleting? 
    // For now, simple delete. (Cascading delete in DB handles orders usually, or strictly forbid)
    // To be safe, just delete user.
    mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
    echo "<script>window.location.href='customers.php';</script>";
}

// Fetch Customers
$q = mysqli_query($conn, "SELECT * FROM users WHERE role='customer' ORDER BY created_at DESC");
?>

<div class="row">
    <!-- Customer List -->
    <div class="col-12">
        <div class="table-card">
            <h4 class="fw-bold mb-4">Customer Management</h4>
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
                            echo "<tr><td colspan='6' class='text-center text-muted py-4'>No customers found.</td></tr>";
                        } else {
                        while($row = mysqli_fetch_assoc($q)){ ?>
                        <tr>
                            <td class="text-muted fw-bold"><?= $i++; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div style="width:35px; height:35px; background:#f0f2f5; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:10px;">
                                        <i class="fas fa-user text-secondary"></i>
                                    </div>
                                    <?= $row['fullname']; ?>
                                </div>
                            </td>
                            <td><?= $row['username']; ?></td>
                            <td><span class="badge bg-light-info text-info text-uppercase"><?= $row['role']; ?></span></td>
                            <td class="text-muted small">
                                <?= isset($row['created_at']) ? date('M d, Y', strtotime($row['created_at'])) : '-'; ?>
                            </td>
                            <td>
                                <a href="customers.php?del=<?= $row['id']; ?>" class="btn btn-sm btn-icon btn-light text-danger" onclick="return confirm('Are you sure you want to delete this customer? This might affect order history!')" title="Remove">
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
</div>

<?php include("includes/footer.php"); ?>
