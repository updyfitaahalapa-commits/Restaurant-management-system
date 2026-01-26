<?php
include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");

// Delete Logic
if(isset($_GET['del'])){
    $id = $_GET['del'];
    mysqli_query($conn,"DELETE FROM menu WHERE id='$id'");
    echo "<script>window.location.href='view_menu.php';</script>";
}

// Edit form submit
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    if(!empty($_FILES['image']['name'])){
        $img = $_FILES['image']['name'];
        $tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($tmp,"../assets/images/".$img);
        mysqli_query($conn,"UPDATE menu SET name='$name', price='$price', image='assets/images/$img' WHERE id='$id'");
    } else {
        mysqli_query($conn,"UPDATE menu SET name='$name', price='$price' WHERE id='$id'");
    }
    echo "<script>window.location.href='view_menu.php';</script>";
}

// Get menu items
$q = mysqli_query($conn,"SELECT * FROM menu");

// Get single item for edit
$edit_item = null;
if(isset($_GET['edit'])){
    $eid = $_GET['edit'];
    $edit_q = mysqli_query($conn,"SELECT * FROM menu WHERE id='$eid'");
    $edit_item = mysqli_fetch_assoc($edit_q);
}
?>

<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Menu Management</h4>
                <a href="add_menu.php" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-plus me-2"></i> Add New Item
                </a>
            </div>

            <!-- Edit Section -->
            <?php if($edit_item){ ?>
            <div class="card border-0 shadow-sm mb-4" style="background:#fff3cd;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fas fa-edit me-2"></i> Edit Menu Item</h5>
                    <form method="POST" enctype="multipart/form-data" class="row g-3">
                        <input type="hidden" name="id" value="<?= $edit_item['id']; ?>">
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">ITEM NAME</label>
                            <input type="text" name="name" value="<?= $edit_item['name']; ?>" class="form-control" required>
                        </div>
                        
                        <div class="col-md-3">
                             <label class="form-label fw-bold small text-muted">PRICE ($)</label>
                            <input type="number" step="0.01" name="price" value="<?= $edit_item['price']; ?>" class="form-control" required>
                        </div>
                        
                        <div class="col-md-3">
                             <label class="form-label fw-bold small text-muted">IMAGE</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-success w-100" name="update">Update Item</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php } ?>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Item Name</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; while($row=mysqli_fetch_assoc($q)){ ?>
                        <tr>
                            <td class="text-muted fw-bold"><?= $i++; ?></td>
                            <td>
                                <img src="../<?= $row['image']; ?>" 
                                     style="width:60px; height:60px; object-fit:cover; border-radius:10px; box-shadow:0 2px 5px rgba(0,0,0,0.1);" 
                                     alt="Food">
                            </td>
                            <td><span class="fw-bold"><?= $row['name']; ?></span></td>
                            <td class="text-success fw-bold fs-6">$<?= $row['price']; ?></td>
                            <td>
                                <a href="view_menu.php?edit=<?= $row['id']; ?>" class="btn btn-sm btn-icon btn-light text-warning me-2" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="view_menu.php?del=<?= $row['id']; ?>" class="btn btn-sm btn-icon btn-light text-danger" onclick="return confirm('Are you sure you want to delete this item?')" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
