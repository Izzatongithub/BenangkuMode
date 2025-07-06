<?php
    session_start();
    require_once 'config/database.php';

    $comingSoonProducts = [];
    $sql = "SELECT * FROM coming_soon_products WHERE is_active = 1 ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    $totalVotes = 0;
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $resVote = mysqli_query($conn, "SELECT COUNT(*) as total FROM product_votes WHERE product_id=" . $row['id']);
            $row['vote_count'] = $resVote ? (mysqli_fetch_assoc($resVote)['total'] ?? 0) : 0;
            $totalVotes += $row['vote_count'];
            $comingSoonProducts[] = $row;
        }
    }

    if (isset($_POST['vote_product_id'])) {
        $productId = (int)$_POST['vote_product_id'];
        $voter_name = $_SESSION['user_name'] ?? 'Guest';
        $voter_email = $_SESSION['user_email'] ?? $_SESSION['guest_email'];
        // Cek sudah vote?
        $cek = mysqli_query($conn, "SELECT id FROM product_votes WHERE product_id=$productId AND voter_email='$voter_email'");
        if (mysqli_num_rows($cek) == 0) {
            mysqli_query($conn, "INSERT INTO product_votes (product_id, voter_name, voter_email) VALUES ($productId, '$voter_name', '$voter_email')");
            $vote_msg = 'Vote berhasil!';
        } else {
            $vote_msg = 'Anda sudah vote untuk produk ini.';
        }
    }

    // Pastikan guest_email tetap selama session
    if (!isset($_SESSION['guest_email'])) {
        $_SESSION['guest_email'] = 'guest_' . uniqid() . '@guest.com';
    }
    $voter_email = $_SESSION['user_email'] ?? $_SESSION['guest_email'];
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
        .alert-success {
            background: #eafbe7;
            color: #2e7d32;
            border-radius: 8px;
            padding: 12px 20px;
            margin-bottom: 20px;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }
        /* --- Penyesuaian Card Produk Coming Soon agar konsisten dengan produk utama --- */
        .coming-soon-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(120, 120, 120, 0.08);
            padding: 0 0 24px 0;
            margin-bottom: 32px;
            overflow: hidden;
            transition: box-shadow 0.2s;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        .coming-soon-card .product-image {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 240px;
            background: linear-gradient(135deg, #f3eaff 0%, #fff 100%);
            position: relative;
            overflow: hidden;
        }
        .coming-soon-card .product-image img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(120,120,120,0.08);
            background: #fff;
        }
        .coming-soon-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            background: #222;
            color: #fff;
            font-size: 0.95em;
            padding: 6px 16px;
            border-radius: 16px;
            font-weight: 600;
            z-index: 2;
            opacity: 0.92;
        }
        .coming-soon-card .product-content {
            padding: 20px 24px 0 24px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
        }
        .coming-soon-card h3 {
            font-size: 1.18rem;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .coming-soon-card .product-description {
            color: #888;
            font-size: 0.97em;
            margin-bottom: 4px;
            line-height: 1.4;
        }
        .coming-soon-card .product-meta {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 0;
            width: 100%;
            padding: 0;
        }
        .coming-soon-card .product-meta .price,
        .coming-soon-card .product-meta .release-date {
            font-size: 0.98em;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .coming-soon-card .product-meta .price i {
            color: #764ba2;
        }
        .coming-soon-card .product-meta .release-date i {
            color: #e74c3c;
        }
        .coming-soon-card .vote-section {
            margin-top: 18px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .coming-soon-card .vote-count {
            background: #eafbe7;
            color: #217a2c;
            border-radius: 20px;
            padding: 10px 24px;
            font-weight: 700;
            font-size: 1.15em;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .coming-soon-card form .btn {
            border-radius: 20px;
            font-size: 1.08em;
            padding: 10px 24px;
            font-weight: 700;
            height: auto;
            display: flex;
            align-items: center;
        }
        .icon-spacing {
            margin-right: 8px;
        }
        .vote-results {
            background: #f7f8fa;
            padding: 40px 0 60px 0;
        }
        .results-grid {
            max-width: 900px;
            margin: 0 auto;
        }
        .result-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 2px 16px rgba(120,120,120,0.08);
            padding: 32px 36px 24px 36px;
            margin-bottom: 32px;
        }
        .result-content h3 {
            font-size: 1.18rem;
            font-weight: 700;
            margin-bottom: 18px;
            color: #253858;
        }
        .vote-bar {
            width: 100%;
            height: 14px;
            background: #f1f1f1;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 16px;
        }
        .vote-progress {
            height: 100%;
            background: linear-gradient(90deg, #e74c3c 0%, #e67e22 100%);
            border-radius: 8px;
            transition: width 0.5s;
        }
        .vote-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.08em;
            color: #253858;
            font-weight: 500;
            margin-top: 2px;
        }
        .vote-info .vote-count {
            font-weight: 700;
            color: #217a2c;
        }
        .vote-info .vote-percent {
            font-weight: 700;
            color: #e74c3c;
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
                                    <a href="upload_bukti.php"><i class="fas fa-upload me-2 icon-spacing"></i>Upload Bukti Pembayaran
                                </a>
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
            <h2 class="section-title">Produk yang Akan Datang</h2>
            <div class="products-grid" id="comingSoonGrid">
                <?php if (isset($_GET['vote']) && $_GET['vote'] == 'success'): ?>
                    <div class="alert alert-success" style="margin-bottom: 20px;">
                        Vote berhasil!
                    </div>
                <?php endif; ?>
                <?php foreach ($comingSoonProducts as $p): ?>
                    <?php
                    $voteCount = 0;
                    $resVote = mysqli_query($conn, "SELECT COUNT(*) as total FROM product_votes WHERE product_id=" . $p['id']);
                    if ($resVote) {
                        $voteCount = mysqli_fetch_assoc($resVote)['total'] ?? 0;
                    }
                    $voter_email = $_SESSION['user_email'] ?? $_SESSION['guest_email'];
                    $cek = mysqli_query($conn, "SELECT id FROM product_votes WHERE product_id={$p['id']} AND voter_email='$voter_email'");
                    $sudahVote = mysqli_num_rows($cek) > 0;
                    ?>
                    <div class="coming-soon-card">
                        <div class="product-image" style="display:flex; align-items:center; justify-content:center; height:200px;">
                            <?php if (!empty($p['image'])): ?>
                                <img src="assets/images/products/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" style="max-width:100%;max-height:180px;object-fit:cover;">
                            <?php else: ?>
                                <i class="fas fa-box" style="font-size:3rem;color:#764ba2;"></i>
                            <?php endif; ?>
                            <div class="coming-soon-badge">Coming Soon</div>
                        </div>
                        <div class="product-content">
                            <h3><?= htmlspecialchars($p['name']) ?></h3>
                            <p class="product-description"><?= htmlspecialchars($p['description']) ?></p>
                            <div class="product-meta">
                                <span class=""><i class="fas fa-tag"></i> <?= $p['estimated_price'] ? 'Rp ' . number_format($p['estimated_price'],0,',','.') : '-' ?></span>
                                <span class=""><i class="fas fa-calendar"></i> <?= $p['estimated_release_date'] ? date('M Y', strtotime($p['estimated_release_date'])) : '-' ?></span>
                            </div>
                            <div class="vote-section" style="margin-top:10px;">
                                <span class="vote-count badge bg-success"><i class="fas fa-heart"></i> <?= $voteCount ?> Vote</span>
                                <?php if (!isLoggedIn()): ?>
                                    <span style="display:inline-block;margin-left:10px;color:#c0392b;font-size:0.98em;vertical-align:middle;">Silakan login untuk melakukan vote.</span>
                                <?php else: ?>
                                    <?php if ($sudahVote): ?>
                                        <form method="post" action="cancel_vote.php" style="display:inline-block;margin-left:10px;">
                                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                            <button type="submit" class="btn btn-warning btn-sm">
                                                <i class="fas fa-times icon-spacing"></i> Batalkan Vote
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form method="post" style="display:inline-block;margin-left:10px;">
                                            <input type="hidden" name="vote_product_id" value="<?= $p['id'] ?>">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-thumbs-up icon-spacing"></i> Vote
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Vote Results -->
    <section class="vote-results">
        <div class="container">
            <h2 class="section-title">Hasil Voting</h2>
            <div class="results-grid">
                <?php foreach ($comingSoonProducts as $p): 
                    $percent = $totalVotes > 0 ? round(($p['vote_count'] / $totalVotes) * 100) : 0;
                ?>
                <div class="result-card">
                    <div class="result-content">
                        <h3><?= htmlspecialchars($p['name']) ?></h3>
                        <div class="vote-bar">
                            <div class="vote-progress" style="width:<?= $percent ?>%;"></div>
                        </div>
                        <div class="vote-info">
                            <span class="vote-count"><?= $p['vote_count'] ?> Vote</span>
                            <span class="vote-percent"><?= $percent ?>%</span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
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