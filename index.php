<?php
    session_start();
    require_once 'config/database.php';

    // Ambil semua produk aktif
    $products = [];
    $result = mysqli_query($conn, "SELECT * FROM products WHERE is_active = 1");
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    // Ambil workshop mendatang
    $workshopPreviews = [];
    $res = mysqli_query($conn, "SELECT * FROM workshops WHERE is_active=1 AND is_past_event=0 ORDER BY start_date ASC LIMIT 3");
    while ($row = mysqli_fetch_assoc($res)) {
        $workshopPreviews[] = $row;
    }

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BenangkuMode - Pengrajin Tangan Merajut Lombok</title>
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
            min-width: 190px;
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
        .products-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            justify-content: center;
        }
        .product-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 12px #eee;
            padding: 0 0 24px 0;
            max-width: 340px;
            min-width: 280px;
            display: flex;
            flex-direction: column;
            margin-bottom: 24px;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .product-card:hover {
            box-shadow: 0 8px 24px rgba(102,126,234,0.13);
            transform: translateY(-4px) scale(1.02);
        }
        .product-image {
            width: 100%;
            height: 180px;
            border-radius: 12px 12px 0 0;
            overflow: hidden;
            background: #f8f8f8;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
            display: block;
        }
        .product-card h3 {
            margin-bottom: 4px;
            margin-top: 0;
            font-size: 1.08rem;
            font-weight: 500;
            letter-spacing:0.01em;
            padding: 0 18px;
        }
        .product-card .price {
            font-weight:600;
            color:#222;
            font-size:1.08rem;
            margin-bottom:2px;
            display:block;
            line-height:1.1;
            padding: 0 18px;
        }
        .icon-spacing {
            margin-right: 8px;
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
                        <a href="index.php" class="nav-link active">Beranda</a>
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
                        <a href="comingsoon.php" class="nav-link">Coming Soon</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a href="gallery.php" class="nav-link">Galeri</a>
                    </li> -->
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
                                <?php if (isAdmin()): ?>
                                    <a href="admin/dashboard.php">
                                        <i class="fas fa-cog me-2"></i>Admin Panel
                                    </a>
                                <?php else: ?>
                                <a href="profile.php">
                                    <i class="fas fa-user me-2 icon-spacing"></i>Profil
                                </a>
                                <div class="divider"></div>
                                <a href="orders.php">
                                    <i class="fas fa-shopping-bag me-2 icon-spacing"></i>Pesanan
                                </a>
                                <div class="divider"></div>
                                <a href="cart.php">
                                    <i class="fas fa-shopping-cart me-2 icon-spacing"></i>Keranjang
                                </a>
                                <div class="divider"></div>
                                <a href="upload_bukti.php"><i class="fas fa-upload me-2 icon-spacing"></i>Upload Bukti Pembayaran</a>
                                <?php endif; ?>
                                <div class="divider"></div>
                                <a href="logout.php">
                                    <i class="fas fa-sign-out-alt me-2 icon-spacing"></i>Logout
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
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title">BenangkuMode</h1>
                <p class="hero-subtitle">Pengrajin Tangan Merajut Terbaik di Lombok</p>
                <p class="hero-description">
                    Menghasilkan kerajinan tangan berkualitas tinggi dengan teknik merajut tradisional 
                    yang dipadukan dengan desain modern. Setiap produk dibuat dengan penuh cinta dan dedikasi.
                </p>
                <div class="hero-buttons">
                    <a href="products.php" class="btn btn-primary">Lihat Produk</a>
                    <a href="workshop.php" class="btn btn-secondary">Ikuti Workshop</a>
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-placeholder">
                    <i class="fas fa-scarf"></i>
                    <p>Gambar Produk Unggulan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Mengapa Memilih BenangkuMode?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-hands"></i>
                    </div>
                    <h3>Handmade</h3>
                    <p>Setiap produk dibuat dengan tangan, memastikan kualitas dan keunikan setiap item.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3>Kualitas Premium</h3>
                    <p>Menggunakan bahan berkualitas tinggi untuk hasil yang tahan lama dan nyaman.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3>Desain Unik</h3>
                    <p>Kombinasi teknik tradisional dengan desain modern yang trendy dan fashionable.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Ramah Lingkungan</h3>
                    <p>Menggunakan bahan alami dan proses yang ramah lingkungan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Preview -->
    <section class="products-preview">
        <div class="container">
            <h2 class="section-title">Produk Unggulan</h2>
            <div class="products-grid" id="productsGrid">
                <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <a href="detail_produk.php?id=<?= $product['id'] ?>" style="text-decoration:none; color:inherit; width:100%;">
                        <div class="product-image">
                            <img src="assets/images/products/<?= htmlspecialchars($product['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        </div>
                    </a>
                    <a href="detail_produk.php?id=<?= $product['id'] ?>" style="text-decoration:none; color:inherit;">
                        <h3 style="margin-bottom: 4px; margin-top: 0; font-size: 1.08rem; font-weight: 500; letter-spacing:0.01em;">
                            <?= htmlspecialchars($product['name']) ?>
                        </h3>
                    </a>
                    <span class="price" style="font-weight:600; color:#222; font-size:1.08rem; margin-bottom:2px; display:block; line-height:1.1;">
                        Rp <?= number_format($product['price'], 0, ',', '.') ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center" style="margin-top: 24px;">
                <a href="products.php" class="btn btn-primary">Lihat Semua Produk</a>
            </div>
        </div>
    </section>

    <!-- Workshop Preview -->
    <section class="workshop-preview">
        <div class="container">
            <div class="workshop-content">
                <div class="workshop-text">
                    <h2>Workshop Merajut</h2>
                    <p>Bergabunglah dengan workshop merajut kami dan pelajari teknik-teknik dasar hingga lanjutan. 
                    Cocok untuk pemula maupun yang sudah berpengalaman.</p>
                    <ul class="workshop-benefits">
                        <li><i class="fas fa-check"></i> Materi lengkap dari dasar</li>
                        <li><i class="fas fa-check"></i> Alat dan bahan disediakan</li>
                        <li><i class="fas fa-check"></i> Sertifikat workshop</li>
                        <li><i class="fas fa-check"></i> Grup komunitas merajut</li>
                    </ul>
                    <a href="workshop.php" class="btn btn-primary">Daftar Workshop</a>
                </div>
                <div class="workshop-image" style="display: flex; gap: 32px; justify-content: flex-start; align-items: stretch;">
                    <?php if ($workshopPreviews): ?>
                        <?php foreach ($workshopPreviews as $w): ?>
                        <div class="workshop-preview-card" style="background:#fff; border-radius:12px; box-shadow:0 2px 8px #eee; padding:16px; max-width:260px; min-width:220px; min-height:260px; display:flex; flex-direction:column; align-items:stretch; justify-content:flex-start; margin-bottom:0;">
                            <?php if (!empty($w['image'])): ?>
                                <img src="assets/images/workshops/<?= htmlspecialchars($w['image']) ?>" alt="Gambar Workshop" style="width:100%;max-height:110px;object-fit:cover;border-radius:8px; margin-bottom:12px;">
                            <?php else: ?>
                                <div style="height:110px;display:flex;align-items:center;justify-content:center;background:#f3f3f3;border-radius:8px; margin-bottom:12px;">
                                    <i class="fas fa-chalkboard-teacher" style="font-size:2.5rem;color:#bbb;"></i>
                                </div>
                            <?php endif; ?>
                            <div style="font-size:0.97em;color:#666; margin-bottom:8px;">
                                <i class="fas fa-calendar"></i> <?= date('d M Y H:i', strtotime($w['start_date'])) ?><br>
                                <i class="fas fa-user"></i> <?= htmlspecialchars($w['instructor']) ?>
                            </div>
                            <a href="workshop.php" class="btn btn-sm btn-outline-primary mt-2" style="margin-top:auto; color:#5a3fa0; font-weight:600; text-align:left; padding:0; background:none; border:none; box-shadow:none;">Lihat Detail</a>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="workshop-placeholder">
                            <i class="fas fa-users"></i>
                            <p>Workshop Merajut</p>
                        </div>
                    <?php endif; ?>
                </div>
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
    <script>
        const products = <?= json_encode($products, JSON_UNESCAPED_UNICODE) ?>;
    </script>
</body>
</html> 