<?php
session_start();
require_once 'config/database.php';

$workshops = [];
$sql = "SELECT * FROM workshops ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $workshops[] = $row;
    }
}

$pastEvents = [];
$sql = "SELECT * FROM workshops WHERE is_past_event = 1 ORDER BY start_date DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pastEvents[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop & Event - BenangkuMode</title>
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
        .workshop-info {
            padding: 80px 0;
            background: #f8f9fa;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        .info-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            transition: transform 0.3s ease,  box-shadow 0.3s ease;
        }
        .info-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 36px rgba(0,0,0,0.1);
        }
        .info-icon i {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-size: 2rem;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .info-card h3 {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }
        .info-card p {
            color: #666;
            line-height: 1.6;
        }
        .workshops-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            justify-content: flex-start;
        }
        .workshop-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 12px #eee;
            padding: 0 0 24px 0;
            max-width: 340px;
            min-width: 280px;
            display: flex;
            flex-direction: column;
            margin-bottom: 24px;
        }
        .workshop-header {
            background: #f3eaff;
            border-radius: 18px 18px 0 0;
            padding: 24px 24px 12px 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .workshop-icon i {
            font-size: 2.5rem;
            color: #764ba2;
            margin-bottom: 10px;
        }
        .workshop-meta h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            text-align: center;
        }
        .meta-list {
            font-size: 0.98rem;
            color: #666;
            display: flex;
            flex-direction: column;
            gap: 2px;
            text-align: left;
        }
        .workshop-description {
            padding: 18px 24px 0 24px;
            color: #444;
            font-size: 1.02rem;
            min-height: 48px;
        }
        .workshop-footer {
            margin-top: auto;
            padding: 18px 24px 0 24px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .price {
            color: #e74c3c;
            font-weight: 700;
            font-size: 1.1rem;
        }
        .slots {
            color: #666;
            font-size: 0.98rem;
        }
        .btn.btn-primary {
            margin-top: 8px;
        }
        .event-card {
            max-width: 420px;
            margin: 0 auto 32px auto;
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
                        <a href="workshop.php" class="nav-link active">Workshop</a>
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
                                <?php if (isAdmin()): ?>
                                    <a href="admin/dashboard.php">
                                        <i class="fas fa-cog me-2"></i>Admin Panel
                                    </a>
                                <?php else: ?>
                                <a href="profile.php">
                                    <i class="fas fa-user me-2"></i>Profil
                                </a>
                                <div class="divider"></div>
                                <a href="orders.php">
                                    <i class="fas fa-shopping-bag me-2"></i>Pesanan
                                </a>
                                <a href="upload_bukti.php"><i class="fas fa-upload me-2"></i>Upload Bukti Pembayaran</a>
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
            <h1>Workshop & Event</h1>
            <p>Bergabunglah dengan workshop merajut kami dan kembangkan skill merajut Anda</p>
        </div>
    </section>

    <!-- Workshop Info -->
    <section class="workshop-info">
        <div class="container">
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Belajar dari Ahli</h3>
                    <p>Dibimbing langsung oleh pengrajin berpengalaman dengan sertifikasi merajut.</p>
                </div>
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Alat & Bahan Lengkap</h3>
                    <p>Semua alat dan bahan disediakan, Anda tinggal datang dan belajar.</p>
                </div>
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h3>Sertifikat Workshop</h3>
                    <p>Dapatkan sertifikat resmi yang dapat digunakan untuk portofolio.</p>
                </div>
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Komunitas Merajut</h3>
                    <p>Bergabung dengan komunitas merajut untuk berbagi pengalaman dan tips.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Workshops -->
    <section class="upcoming-workshops">
        <div class="container">
            <h2 class="section-title">Workshop Mendatang</h2>
            <div class="workshops-grid" id="workshopsGrid">
                <?php foreach ($workshops as $w): ?>
                <div class="workshop-card">
                    <div class="workshop-header">
                        <div class="workshop-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="workshop-meta">
                            <h3><?= htmlspecialchars($w['title']) ?></h3>
                            <div class="meta-list">
                                <span><i class="fas fa-calendar"></i> <?= date('d M Y H:i', strtotime($w['start_date'])) ?></span>
                                <span><i class="fas fa-clock"></i> <?= htmlspecialchars($w['duration']) ?></span>
                                <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($w['location']) ?></span>
                                <span><i class="fas fa-user"></i> <?= htmlspecialchars($w['instructor']) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="workshop-description">
                        <?= htmlspecialchars($w['description']) ?>
                    </div>
                    <div class="workshop-footer">
                        <span class="price">
                            <?= $w['price'] == 0 ? 'Gratis' : 'Rp ' . number_format($w['price'],0,',','.') ?>
                        </span>
                        <span class="slots <?= ($w['max_participants']-$w['current_participants']<=0)?'full':(($w['max_participants']-$w['current_participants']<=3)?'almost-full':'') ?>">
                            <?= ($w['max_participants']-$w['current_participants']<=0)?'Penuh':($w['max_participants']-$w['current_participants']).' slot tersisa' ?>
                        </span>
                        <button class="btn btn-primary" onclick="registerWorkshop(<?= $w['id'] ?>)" <?= ($w['max_participants']-$w['current_participants']<=0)?'disabled':'' ?>>
                            <?= ($w['max_participants']-$w['current_participants']<=0)?'Workshop Penuh':'Daftar Sekarang' ?>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Registration Modal -->
    <div id="registrationModal" class="registration-modal">
        <div class="registration-content">
            <div class="registration-header">
                <h3>Daftar Workshop</h3>
                <button class="close-registration" onclick="closeRegistration()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="registrationForm" class="registration-form">
                <div class="form-group">
                    <label for="workshopTitle">Workshop:</label>
                    <input type="text" id="workshopTitle" readonly>
                </div>
                <div class="form-group">
                    <label for="participantName">Nama Lengkap *</label>
                    <input type="text" id="participantName" name="name" required>
                </div>
                <div class="form-group">
                    <label for="participantEmail">Email *</label>
                    <input type="email" id="participantEmail" name="email" required>
                </div>
                <div class="form-group">
                    <label for="participantPhone">Nomor Telepon *</label>
                    <input type="tel" id="participantPhone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="participantAge">Usia</label>
                    <input type="number" id="participantAge" name="age" min="10" max="80">
                </div>
                <div class="form-group">
                    <label for="experienceLevel">Level Pengalaman</label>
                    <select id="experienceLevel" name="experience">
                        <option value="beginner">Pemula</option>
                        <option value="intermediate">Menengah</option>
                        <option value="advanced">Lanjutan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="specialNeeds">Kebutuhan Khusus</label>
                    <textarea id="specialNeeds" name="specialNeeds" rows="3" placeholder="Jika ada kebutuhan khusus atau pertanyaan..."></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeRegistration()">Batal</button>
                    <button type="submit" class="btn btn-primary">Daftar Workshop</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Past Events -->
    <section class="past-events">
        <div class="container">
            <h2 class="section-title">Event Sebelumnya</h2>
            <div class="events-grid">
                <?php foreach ($pastEvents as $event): ?>
                <div class="event-card">
                    <div class="event-image" style="background: #f3eaff; display:flex; align-items:center; justify-content:center; height:100px;">
                        <i class="fas fa-chalkboard-teacher" style="font-size:2rem;color:#764ba2;"></i>
                    </div>
                    <div class="event-content">
                        <h3><?= htmlspecialchars($event['title']) ?></h3>
                        <p class="event-date"><i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($event['start_date'])) ?></p>
                        <p class="event-location"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($event['location']) ?></p>
                        <p class="event-participants"><i class="fas fa-user"></i> <?= htmlspecialchars($event['instructor']) ?></p>
                        <p class="event-description"><?= htmlspecialchars($event['description']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
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

    <script>
    window.isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    </script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/workshop.js"></script>
</body>
</html> 