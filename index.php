<?php
session_start();
include("config/db.php");

// Menu preview
$menu = mysqli_query($conn,"SELECT * FROM menu LIMIT 6");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaama Liito | Premium Dining Experience</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-utensils me-2 text-warning"></i> 
                Kaama<span>Liito</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <?php if(isset($_SESSION['user'])){ ?>
                        <li class="nav-item"><a class="nav-link" href="user/index.php">Browse Menu</a></li>
                        <li class="nav-item"><a class="nav-link" href="auth/logout.php">Sign Out</a></li>
                    <?php } else { ?>
                        <li class="nav-item"><a class="nav-link" href="auth/login.php">Sign In</a></li>
                        <li class="nav-item">
                            <a class="btn btn-main ms-3" href="auth/register.php">Register</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <h1>Hooyga <span class="text-warning">Dhadhanka</span></h1>
            <p>
                Kaama Liito waxa uu ku siinayaa cuntooyin aad u macaan oo laga sameeyay maqaayada. 
                Waxaan ku siinaynaa cuntooyin aad u macaan oo laga sameeyay maqaayada.
            </p>
            <div class="mt-4">
                <a href="auth/login.php" class="btn btn-main me-3">Order Now</a>
                <a href="#about" class="btn btn-outline-light">Our Story</a>
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section class="section bg-white">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Why Dine With Us?</h2>
            </div>
            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <i class="fas fa-mobile-alt feature-icon"></i>
                        <h5>Seamless Ordering</h5>
                        <p>Explore our visual menu and place orders in seconds from any device.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <i class="fas fa-shipping-fast feature-icon"></i>
                        <h5>Express Delivery</h5>
                        <p>Hot and fresh food delivered to your doorstep with real-time tracking.</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <i class="fas fa-shield-alt feature-icon"></i>
                        <h5>Quality Promise</h5>
                        <p>We use only the finest ingredients to ensure every dish is a masterpiece.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MENU PREVIEW (Placeholder / Concept) -->
    <!-- Ideally fetch from DB here, but static for now for structure -->
    <section class="section" style="background:#f8f9fa;">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Signature Dishes</h2>
            </div>
            <div class="row g-4">
                <?php 
                // Hardcoded example if DB is empty or simple
                // In real app, loop likely via PHP
                ?>
                <div class="col-md-4" data-aos="zoom-in">
                    <div class="card border-0 shadow-sm h-100 overflow-hidden" style="border-radius:15px;">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=2080&auto=format&fit=crop" class="card-img-top" alt="Food" style="height:250px; object-fit:cover;">
                        <div class="card-body text-center">
                            <h5 class="fw-bold">Gourmet Salad</h5>
                            <p class="text-muted small">Fresh organic greens with special dressing.</p>
                            <span class="text-warning fw-bold fs-5">$12.99</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100 overflow-hidden" style="border-radius:15px;">
                        <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?q=80&w=1981&auto=format&fit=crop" class="card-img-top" alt="Food" style="height:250px; object-fit:cover;">
                        <div class="card-body text-center">
                            <h5 class="fw-bold">Artisan Pizza</h5>
                            <p class="text-muted small">Wood-fired oven pizza with premium toppings.</p>
                            <span class="text-warning fw-bold fs-5">$18.50</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
                    <div class="card border-0 shadow-sm h-100 overflow-hidden" style="border-radius:15px;">
                        <img src="https://images.unsplash.com/photo-1482049016688-2d3e1b311543?q=80&w=2010&auto=format&fit=crop" class="card-img-top" alt="Food" style="height:250px; object-fit:cover;">
                        <div class="card-body text-center">
                            <h5 class="fw-bold">Morning Delight</h5>
                            <p class="text-muted small">Perfectly toasted bread with avocado and egg.</p>
                            <span class="text-warning fw-bold fs-5">$10.00</span>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="text-center mt-5">
                <a href="auth/login.php" class="btn btn-outline-dark rounded-pill px-5 py-3">View Full Menu</a>
            </div>
        </div>
    </section>

    <!-- ABOUT SECTION -->
    <section class="section about" id="about">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="section-title mb-5" data-aos="fade-up">
                        <h2>The Chef's Talk</h2>
                    </div>
                    <div class="about-box" data-aos="fade-up">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center mb-4 mb-md-0">
                                <div style="width:150px; height:150px; background:#ddd; border-radius:50%; margin:auto; overflow:hidden; border:4px solid var(--accent-color);">
                                    <img src="assets/images/alaba.jpg" alt="Profile" style="width:95%; height:95%; object-fit:cover;">
                                </div>
                                <h5 class="mt-3 text-warning">Abdifitaah</h5>
                                <small class="text-white-50">Lead Developer & Chef</small>
                            </div>
                            <div class="col-md-8">
                                <p class="fst-italic opacity-75 mb-4">
                                    "We built this platform to bridge the gap between delicious food and technology. 
                                    Our mission is simple: serve happiness on a plate, with a side of digital excellence."
                                </p>
                                <div class="contact-info">
                                    <div class="contact-item">
                                        <i class="fas fa-envelope"></i> cabdifitaaxmaxamuud@gmail.com
                                    </div>
                                    <div class="contact-item">
                                        <i class="fab fa-whatsapp"></i> +252 613 177 377
                                    </div>
                                    <div class="contact-item">
                                        <i class="fas fa-map-marker-alt"></i> Wadajir, Mogadishu
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div class="mb-3">
                <a href="#" class="text-white mx-2"><i class="fab fa-facebook fa-lg"></i></a>
                <a href="#" class="text-white mx-2"><i class="fab fa-instagram fa-lg"></i></a>
                <a href="#" class="text-white mx-2"><i class="fab fa-twitter fa-lg"></i></a>
            </div>
            <p class="mb-0">© <?=date('Y');?> Kaama liito Restaurant. All Rights Reserved.</p>
            <small class="text-muted">Developed with ❤️ by Abdifitaah</small>
        </div>
    </footer>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            offset: 100
        });
        
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('.navbar').style.padding = '10px 0';
                document.querySelector('.navbar').style.boxShadow = '0 5px 20px rgba(0,0,0,0.1)';
            } else {
                document.querySelector('.navbar').style.padding = '15px 0';
                document.querySelector('.navbar').style.boxShadow = 'none';
            }
        });
    </script>
</body>
</html>
