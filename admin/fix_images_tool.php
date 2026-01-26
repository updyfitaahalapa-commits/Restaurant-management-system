<?php
include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");

// Available images in assets/images/
$available_images = [
    'assets/images/burger.png',
    'assets/images/pizza.png',
    'assets/images/pasta.png',
    'assets/images/no-image.png'
];

$msg = "";

// Handle Update
if(isset($_POST['update_images'])){
    // Update Menu Items
    if(isset($_POST['menu_img'])){
        foreach($_POST['menu_img'] as $id => $img_path){
            $img_path = mysqli_real_escape_string($conn, $img_path);
            mysqli_query($conn, "UPDATE menu SET image='$img_path' WHERE id='$id'");
        }
    }
    
    // Update Orders (by Item Name)
    // First, let's make sure we update the order `image`... oh wait, 
    // orders table doesn't have an image column, it fetches from menu via JOIN usually.
    // BUT the user might have deleted the menu item or changed the name.
    
    // The previous join query was: SELECT o.*, m.image FROM orders o LEFT JOIN menu m ON o.item = m.name
    // So we just need to make sure the MENU item exists and has an image.
    
    $msg = "Images updated successfully!";
}

// Fetch Menu Items
$menu_q = mysqli_query($conn, "SELECT * FROM menu");

// Fetch Distinct Order Items that might not be in Menu
$orders_q = mysqli_query($conn, "SELECT DISTINCT item FROM orders WHERE item NOT IN (SELECT name FROM menu)");
$orphaned_items = [];
while($r = mysqli_fetch_assoc($orders_q)){
    $orphaned_items[] = $r['item'];
}

// Handle Creating Menu Items for Orphaned Orders
if(isset($_POST['create_menu_item'])){
    $name = mysqli_real_escape_string($conn, $_POST['orphan_name']);
    $price = 10.00; // Default
    $img = mysqli_real_escape_string($conn, $_POST['orphan_img']);
    
    mysqli_query($conn, "INSERT INTO menu (name, price, image) VALUES ('$name', '$price', '$img')");
    $msg = "Created menu item for '$name'. It should now have an image.";
    echo "<script>window.location.href='fix_images_tool.php';</script>";
}

?>

<div class="row">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Use this tool to fix missing images for your menu and orders.
        </div>
        
        <?php if($msg){ ?>
            <div class="alert alert-success"><i class="fas fa-check"></i> <?=$msg;?></div>
        <?php } ?>

        <!-- 1. MANAGE MENU IMAGES -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">1. Assign Images to Menu Items</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Current Image</th>
                                    <th>Select New Image</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                mysqli_data_seek($menu_q, 0);
                                while($row = mysqli_fetch_assoc($menu_q)){ 
                                ?>
                                <tr>
                                    <td class="fw-bold"><?= $row['name']; ?></td>
                                    <td>
                                        <img src="../<?= $row['image']; ?>" width="50" height="50" style="object-fit:cover; border-radius:5px; border:1px solid #ddd;">
                                        <small class="text-muted d-block"><?= $row['image']; ?></small>
                                    </td>
                                    <td>
                                        <select name="menu_img[<?= $row['id']; ?>]" class="form-select">
                                            <?php foreach($available_images as $img){ ?>
                                                <option value="<?= $img; ?>" <?= ($row['image'] == $img) ? 'selected' : ''; ?>>
                                                    <?= basename($img); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <button class="btn btn-primary" name="update_images">
                        <i class="fas fa-save me-2"></i> Update All Menu Images
                    </button>
                </form>
            </div>
        </div>

        <!-- 2. FIX MISSING ITEMS -->
        <?php if(!empty($orphaned_items)){ ?>
        <div class="card border-0 shadow-sm bg-light-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="fw-bold mb-0">2. Fix Missing Menu Items</h5>
            </div>
            <div class="card-body">
                <p>The following items appear in "Orders" but are NOT in the "Menu". This is why they have no image.</p>
                
                <?php foreach($orphaned_items as $item_name){ ?>
                    <form method="POST" class="d-flex align-items-center mb-2">
                        <input type="hidden" name="orphan_name" value="<?= $item_name; ?>">
                        <span class="fw-bold me-3" style="min-width:150px;"><?= $item_name; ?></span>
                        <select name="orphan_img" class="form-select me-2" style="max-width:200px;">
                             <?php foreach($available_images as $img){ ?>
                                <option value="<?= $img; ?>"><?= basename($img); ?></option>
                            <?php } ?>
                        </select>
                        <button class="btn btn-sm btn-success" name="create_menu_item">Create & Fix</button>
                    </form>
                <?php } ?>
            </div>
        </div>
        <?php } ?>

    </div>
</div>

<?php include("includes/footer.php"); ?>
