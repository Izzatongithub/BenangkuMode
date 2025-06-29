<?php
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon - BenangkuMode</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .auth-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .auth-buttons .btn {
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .auth-buttons .btn-login {
            background: transparent;
            color: #333;
            border: 1px solid #333;
        }
        .auth-buttons .btn-login:hover {
            background: #333;
            color: white;
        }
        .auth-buttons .btn-register {
            background: #667eea;
            color: white;
            border: 1px solid #667eea;
        }
        .auth-buttons .btn-register:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <h2>BenangkuMode</h2>
                </div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a href="about.php" class="nav-link">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a href="products.php" class="nav-link">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a href="workshop.php" class="nav-link">Workshop</a>
                    </li>
                    <li class="nav-item">
                        <a href="comingsoon.php" class="nav-link active">Coming Soon</a>
                    </li>
                    <li class="nav-item">
                        <a href="gallery.php" class="nav-link">Galeri</a>
                    </li>
                    <li class="nav-item">
                        <a href="wisata.php" class="nav-link">Wisata Lombok</a>
                    </li>
                </ul>
                <!-- Auth Section -->
                <div class="auth-section">
                    <?php if (isLoggedIn()): ?>
                        <div class="user-menu">
                            <div class="user-avatar">
                                <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                            </div>
                            <div class="dropdown-menu">
                                <a href="profile.php">
                                    <i class="fas fa-user me-2"></i>Profil
                                </a>
                                <a href="orders.php">
                                    <i class="fas fa-shopping-bag me-2"></i>Pesanan
                                </a>
                                <?php if (isAdmin()): ?>
                                    <div class="divider"></div>
                                    <a href="admin/dashboard.php">
                                        <i class="fas fa-cog me-2"></i>Admin Panel
                                    </a>
                                <?php endif; ?>
                                <div class="divider"></div>
                                <a href="logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="auth-buttons">
                            <a href="login.php" class="btn btn-login">Login</a>
                            <a href="register.php" class="btn btn-register">Daftar</a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="page-hero">
        <div class="container">
            <h1>Produk Coming Soon</h1>
            <p>Nantikan produk-produk terbaru dari BenangkuMode!</p>
        </div>
    </section>

    <!-- Coming Soon Content -->
    <section class="comingsoon-content">
        <div class="container">
            <div class="comingsoon-grid">
                <!-- Daftar produk coming soon bisa diisi di sini -->
            </div>
        </div>
    </section>

    <!-- Coming Soon Info -->
    <section class="coming-soon-info">
        <div class="container">
            <div class="info-content">
                <h2>Produk Baru Akan Hadir</h2>
                <p>Kami sedang mengembangkan produk-produk baru yang inovatif dan trendy. Berikan vote Anda untuk produk yang paling Anda minati, dan kami akan memprioritaskan produksinya!</p>
                <div class="info-features">
                    <div class="feature">
                        <i class="fas fa-lightbulb"></i>
                        <span>Ide Inovatif</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-star"></i>
                        <span>Kualitas Premium</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-heart"></i>
                        <span>Desain Unik</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Coming Soon Products -->
    <section class="coming-soon-products">
        <div class="container">
            <h2 class="section-title">Produk Coming Soon</h2>
            <div class="products-grid" id="comingSoonGrid">
                <!-- Product cards will be generated by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Vote Results -->
    <section class="vote-results">
        <div class="container">
            <h2 class="section-title">Hasil Voting</h2>
            <div class="results-grid" id="resultsGrid">
                <!-- Results will be generated by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Newsletter Signup -->
    <section class="newsletter-signup">
        <div class="container">
            <div class="newsletter-content">
                <h2>Dapatkan Update Terbaru</h2>
                <p>Berlangganan newsletter kami untuk mendapatkan informasi terbaru tentang produk coming soon dan penawaran eksklusif.</p>
                <form id="newsletterForm" class="newsletter-form">
                    <div class="form-group">
                        <input type="email" id="newsletterEmail" placeholder="Masukkan email Anda" required>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Berlangganan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>BenangkuMode</h3>
                    <p>Pengrajin tangan merajut terbaik di Lombok, menghadirkan produk berkualitas dengan sentuhan tradisional dan desain modern.</p>
                </div>
                <div class="footer-section">
                    <h4>Kontak</h4>
                    <p><i class="fas fa-phone"></i> +62 812-3456-7890</p>
                    <p><i class="fas fa-envelope"></i> info@benangkumode.com</p>
                    <p><i class="fas fa-map-marker-alt"></i> Lombok, Nusa Tenggara Barat</p>
                </div>
                <div class="footer-section">
                    <h4>Ikuti Kami</h4>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 BenangkuMode. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
    <script src="assets/js/comingsoon.js"></script>
</body>
</html> 