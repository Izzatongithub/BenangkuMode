<?php
// $conn = mysqli_connect("localhost", "root", "", "benangkumode_db");
// if (!$conn) {
//     die("Koneksi gagal: " . mysqli_connect_error());
// }

require_once 'config/database.php';

// Ambil kategori galeri
$categories = [];
$result = mysqli_query($conn, "SELECT * FROM gallery_categories WHERE is_active = 1 ORDER BY name");
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}

// Ambil filter kategori jika ada
$category_id = isset($_GET['kategori']) ? intval($_GET['kategori']) : 0;
if ($category_id) {
    $result = mysqli_query($conn, "SELECT * FROM gallery_images WHERE is_active = 1 AND category_id = $category_id ORDER BY created_at DESC");
} else {
    $result = mysqli_query($conn, "SELECT * FROM gallery_images WHERE is_active = 1 ORDER BY created_at DESC");
}
$images = [];
while ($row = mysqli_fetch_assoc($result)) {
    $images[] = $row;
}

function getCategoryName($id, $categories) {
    foreach ($categories as $cat) {
        if ($cat['id'] == $id) return $cat['name'];
    }
    return '-';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri - BenangkuMode</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
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
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <div class="nav-logo">
                        <h2>BenangkuMode</h2>
                    </div>
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
                        <a href="comingsoon.php" class="nav-link">Coming Soon</a>
                    </li>
                    <li class="nav-item">
                        <a href="gallery.php" class="nav-link active">Galeri</a>
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
            <h1 class="hero-title">Galeri Kerajinan</h1>
            <p class="hero-subtitle">Temukan kerajinan terbaik di Pulau Lombok yang memukau</p>
        </div>
    </section>
        <form class="gallery-filter" method="get">
            <select name="kategori" onchange="this.form.submit()">
                <option value="0">Semua Kategori</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $category_id==$cat['id']?'selected':'' ?>><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <div class="gallery-grid">
            <?php if(count($images)): foreach($images as $img): ?>
                <div class="gallery-card" onclick="openModal('<?= htmlspecialchars($img['image_path']) ?>', '<?= htmlspecialchars($img['title']) ?>', '<?= getCategoryName($img['category_id'],$categories) ?>')">
                    <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="<?= htmlspecialchars($img['title']) ?>" class="gallery-img">
                    <div class="gallery-info">
                        <div class="gallery-title"><?= htmlspecialchars($img['title']) ?></div>
                        <div class="gallery-category">Kategori: <?= getCategoryName($img['category_id'],$categories) ?></div>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="gallery-empty">Belum ada gambar di galeri.</div>
            <?php endif; ?>
        </div>
    </div>
    <div class="modal" id="galleryModal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <img src="" alt="" class="modal-img" id="modalImg">
            <div id="modalTitle" class="modal-title"></div>
            <div id="modalCat" class="modal-cat"></div>
        </div>
    </div>
    <script>
    function openModal(src, title, cat) {
        document.getElementById('galleryModal').classList.add('active');
        document.getElementById('modalImg').src = src;
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('modalCat').innerText = 'Kategori: ' + cat;
    }
    function closeModal() {
        document.getElementById('galleryModal').classList.remove('active');
    }
    window.onclick = function(event) {
        var modal = document.getElementById('galleryModal');
        if (event.target == modal) closeModal();
    }
    window.isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    </script>
    <script src="assets/js/products.js"></script>
</body>
</html> 