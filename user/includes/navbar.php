<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top shadow-sm" style="background: blue;">
    <div class="container">
        <a class="navbar-brand fw-bold text-dark" href="index.php">
            <i class="fas fa-utensils text-danger me-2"></i> 
            Kaama<span class="text-secondary">Liito</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="userNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link text-dark fw-bold me-3" href="index.php">
                        <i class="fas fa-book-open me-1"></i> MENU
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark fw-bold me-3" href="orders.php">
                        <i class="fas fa-receipt me-1"></i> MY ORDERS
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link text-dark fw-bold me-3 position-relative" href="view_cart.php">
                        <i class="fas fa-shopping-cart me-1"></i> CART
                        <?php 
                        $cart_count = 0;
                        if(isset($_SESSION['cart'])){
                            foreach($_SESSION['cart'] as $item){
                                $cart_count += $item['qty'];
                            }
                        }
                        if($cart_count > 0){
                        ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $cart_count; ?>
                        </span>
                        <?php } ?>
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle btn btn-light rounded-pill px-3" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i> <?= $_SESSION['user']; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-circle me-2"></i> My Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
