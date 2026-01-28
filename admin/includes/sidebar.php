<?php
// Get current page name for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- SIDEBAR -->
<nav id="sidebar">
    <div class="sidebar-header">
        <h4 class="fw-bold mb-0">Kaama<span style="color:var(--admin-primary)">Liito</span></h4>
        <small class="text-muted" style="font-size:0.7rem; letter-spacing:1px;">ADMINISTRATION</small>
    </div>

    <ul class="list-unstyled components">
        <li class="<?= ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <a href="dashboard.php">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        </li>
        
        <li class="<?= ($current_page == 'orders.php') ? 'active' : ''; ?>">
            <a href="orders.php">
                <i class="fas fa-shopping-bag"></i> Orders
            </a>
        </li>

        <li class="<?= ($current_page == 'inventory.php') ? 'active' : ''; ?>">
            <a href="inventory.php">
                <i class="fas fa-boxes"></i> Inventory
            </a>
        </li>

        <li class="<?= ($current_page == 'payments.php') ? 'active' : ''; ?>">
            <a href="payments.php">
                <i class="fas fa-money-bill-wave"></i> Payments
            </a>
        </li>
        
        <li class="<?= ($current_page == 'add_menu.php' || $current_page == 'view_menu.php') ? 'active' : ''; ?>">
            <a href="#menuSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fas fa-utensils"></i> Menu Management
            </a>
            <ul class="collapse list-unstyled" id="menuSubmenu" style="background:#161622;">
                <li>
                    <a href="view_menu.php"><i class="fas fa-list ms-3"></i> View Menu</a>
                </li>
                <li>
                    <a href="add_menu.php"><i class="fas fa-plus ms-3"></i> Add Item</a>
                </li>
            </ul>
        </li>
        
        <li class="<?= ($current_page == 'report.php') ? 'active' : ''; ?>">
            <a href="report.php">
                <i class="fas fa-chart-line"></i> Reports
            </a>
        </li>

        <li class="<?= ($current_page == 'staff.php') ? 'active' : ''; ?>">
            <a href="staff.php">
                <i class="fas fa-users-cog"></i> Staff users
            </a>
        </li>

        <li class="<?= ($current_page == 'fix_images_tool.php') ? 'active' : ''; ?>">
            <a href="fix_images_tool.php">
                <i class="fas fa-images"></i> Fix Images
            </a>
        </li>

        <li>
            <a href="../index.php" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Website
            </a>
        </li>
    </ul>

    <div class="mt-auto p-3">
        <a href="../auth/logout.php" class="btn btn-danger w-100">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a>
    </div>
</nav>
