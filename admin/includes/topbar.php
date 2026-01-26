<!-- PAGE CONTENT -->
<div id="content">

    <!-- TOPBAR -->
    <nav class="navbar navbar-expand-lg topbar mb-4">
        <button type="button" id="sidebarCollapse" class="btn btn-light shadow-sm">
            <i class="fas fa-bars"></i>
        </button>

        <div class="ms-auto d-flex align-items-center">
            
            <div class="dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                    <div style="width:35px; height:35px; background:#e0e0e0; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:10px;">
                        <i class="fas fa-user text-secondary"></i>
                    </div>
                    <span class="fw-bold text-dark"><?= $_SESSION['user'] ?? 'Admin'; ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
            </div>
            
        </div>
    </nav>

    <!-- CONTENT CONTAINER -->
    <div class="container-fluid px-4">
