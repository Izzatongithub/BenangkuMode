<?php
session_start();
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - BenangkuMode</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .user-menu {
            position: relative;
            display: inline-block;
        }
        .user-menu .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 8px;
            padding: 8px 0;
        }
        .user-menu:hover .dropdown-menu {
            display: block;
        }
        .user-menu .dropdown-menu a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }
        .user-menu .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }
        .user-menu .dropdown-menu .divider {
            border-top: 1px solid #ddd;
            margin: 8px 0;
        }
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
        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .user-avatar:hover {
            transform: scale(1.1);
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
                        <a href="about.php" class="nav-link active">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a href="products.php" class="nav-link">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a href="workshop.php" class="nav-link">Workshop</a>
                    </li>
                    <li class="nav-item">
                        <a href="comingsoon.php" class="nav-link">Coming Soon</a>
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
            <h1>Tentang BenangkuMode</h1>
            <p>Mengenal lebih dekat dengan pengrajin tangan merajut terbaik di Lombok</p>
        </div>
    </section>

    <!-- About Content -->
    <section class="about-content">
        <div class="container">
            <div class="about-grid">
                <div class="about-text">
                    <h2>Cerita Kami</h2>
                    <p>BenangkuMode didirikan dengan passion untuk melestarikan dan mengembangkan seni merajut tradisional Lombok. Berawal dari hobi seorang pengrajin lokal yang memiliki keahlian merajut turun-temurun, kini telah berkembang menjadi komunitas pengrajin yang menghasilkan produk berkualitas tinggi.</p>
                    
                    <p>Kami percaya bahwa setiap benang yang dianyam memiliki cerita tersendiri. Setiap produk yang kami buat tidak hanya sekedar barang, tetapi merupakan hasil dari dedikasi, kreativitas, dan cinta terhadap seni merajut yang telah diwariskan dari generasi ke generasi.</p>
                    
                    <p>Dengan menggabungkan teknik tradisional dengan desain modern, kami berhasil menciptakan produk yang tidak hanya indah tetapi juga fungsional dan nyaman digunakan.</p>
                </div>
                <div class="about-image">
                    <div class="about-placeholder">
                        <i class="fas fa-scarf"></i>
                        <p>Gambar Workshop</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Vision -->
    <section class="mission-vision">
        <div class="container">
            <div class="mv-grid">
                <div class="mv-card">
                    <div class="mv-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Misi Kami</h3>
                    <p>Menghasilkan produk kerajinan tangan merajut berkualitas tinggi yang menggabungkan nilai tradisional dengan inovasi modern, sambil memberdayakan pengrajin lokal dan melestarikan warisan budaya Lombok.</p>
                </div>
                <div class="mv-card">
                    <div class="mv-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Visi Kami</h3>
                    <p>Menjadi pelopor dalam industri kerajinan tangan merajut di Indonesia, dikenal sebagai brand yang menghadirkan produk berkualitas dengan sentuhan budaya lokal yang autentik.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="values">
        <div class="container">
            <h2 class="section-title">Nilai-Nilai Kami</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Passion</h3>
                    <p>Kami bekerja dengan penuh cinta dan dedikasi terhadap setiap produk yang dibuat.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3>Kualitas</h3>
                    <p>Kami tidak pernah berkompromi dengan kualitas, setiap detail diperhatikan dengan seksama.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Ramah Lingkungan</h3>
                    <p>Kami menggunakan bahan alami dan proses yang ramah terhadap lingkungan.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Komunitas</h3>
                    <p>Kami membangun komunitas yang saling mendukung dan berbagi pengetahuan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team -->
    <!-- <section class="team">
        <div class="container">
            <h2 class="section-title">Tim Kami</h2>
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-image">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3>Sarah Amalia</h3>
                    <p class="member-role">Founder & Master Crafter</p>
                    <p class="member-desc">Pengrajin dengan pengalaman 15 tahun dalam seni merajut tradisional Lombok.</p>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3>Budi Santoso</h3>
                    <p class="member-role">Design Director</p>
                    <p class="member-desc">Bertanggung jawab atas desain dan inovasi produk yang modern dan trendy.</p>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3>Dewi Sartika</h3>
                    <p class="member-role">Workshop Coordinator</p>
                    <p class="member-desc">Mengelola workshop dan pelatihan untuk komunitas merajut.</p>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Stats -->
    <!-- <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Produk Terjual</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Workshop Diadakan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">200+</div>
                    <div class="stat-label">Pelanggan Puas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">5+</div>
                    <div class="stat-label">Tahun Pengalaman</div>
                </div>
            </div>
        </div>
    </section> -->

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
</body>
</html> 