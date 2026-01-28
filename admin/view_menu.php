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
    
    // Inventory Link Updates
    $inv_id = $_POST['inventory_id'];
    $qty_req = $_POST['quantity_required'];
    $inv_val = ($inv_id) ? "'$inv_id'" : "NULL";

    if(!empty($_FILES['image']['name'])){
        $img = $_FILES['image']['name'];
        $tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($tmp,"../assets/images/".$img);
        $sql = "UPDATE menu SET name='$name', price='$price', image='assets/images/$img', inventory_id=$inv_val, quantity_required='$qty_req' WHERE id='$id'";
    } else {
        $sql = "UPDATE menu SET name='$name', price='$price', inventory_id=$inv_val, quantity_required='$qty_req' WHERE id='$id'";
    }
    
    if(mysqli_query($conn, $sql)){
        echo "<script>window.location.href='view_menu.php';</script>";
    } else {
        echo "<script>alert('Error updating: ".mysqli_error($conn)."');</script>";
    }
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
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $edit_item['id']; ?>">
                        
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold small text-muted">ITEM NAME</label>
                                <input type="text" name="name" value="<?= $edit_item['name']; ?>" class="form-control" required>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label fw-bold small text-muted">PRICE ($)</label>
                                <input type="number" step="0.01" name="price" value="<?= $edit_item['price']; ?>" class="form-control" required>
                            </div>

                            <!-- Inventory Link Edit -->
                            <div class="col-md-3">
                                <label class="form-label fw-bold small text-muted">LINK STOCK</label>
                                <select name="inventory_id" class="form-select">
                                    <option value="">-- No Link --</option>
                                    <?php 
                                    $inv = mysqli_query($conn, "SELECT * FROM inventory ORDER BY item_name ASC");
                                    while($i = mysqli_fetch_assoc($inv)){
                                        $selected = ($edit_item['inventory_id'] == $i['id']) ? "selected" : "";
                                        echo "<option value='".$i['id']."' $selected>".$i['item_name']." (Avail: ".$i['quantity'].")</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold small text-muted">QTY REQ</label>
                                <input type="number" name="quantity_required" value="<?= $edit_item['quantity_required'] ? $edit_item['quantity_required'] : 1; ?>" class="form-control" min="1">
                            </div>
                            
                            <div class="col-md-2 mt-auto">
                                <label class="form-label fw-bold small text-muted">&nbsp;</label>
                                <input type="file" name="image" class="form-control mb-2" style="font-size:0.8rem;">
                                <button class="btn btn-success w-100" name="update">Update</button>
                            </div>
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
                            <th>Stock Link</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; while($row=mysqli_fetch_assoc($q)){ 
                             // Get inventory name if linked
                             $inv_name = "<span class='text-muted small'>-</span>";
                             if($row['inventory_id']){
                                 $inv_res = mysqli_query($conn, "SELECT item_name, quantity FROM inventory WHERE id='".$row['inventory_id']."'");
                                 if($inv_data = mysqli_fetch_assoc($inv_res)){
                                     $inv_name = "<span class='badge bg-light-info text-info'>".$inv_data['item_name']." (".$inv_data['quantity'].") link</span>";
                                 }
                             }
                        ?>
                        <tr>
                            <td class="text-muted fw-bold"><?= $i++; ?></td>
                            <td>
                                <img src="../<?= $row['image']; ?>" 
                                     style="width:50px; height:50px; object-fit:cover; border-radius:10px;" 
                                     alt="Food">
                            </td>
                            <td><span class="fw-bold"><?= $row['name']; ?></span></td>
                            <td class="text-success fw-bold">$<?= $row['price']; ?></td>
                            <td><?= $inv_name; ?></td>
                            <td>
                                <a href="view_menu.php?edit=<?= $row['id']; ?>" class="btn btn-sm btn-icon btn-light text-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="view_menu.php?del=<?= $row['id']; ?>" class="btn btn-sm btn-icon btn-light text-danger" onclick="return confirm('Delete?')" title="Delete">
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
