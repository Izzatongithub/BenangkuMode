<?php
session_start();
require_once 'config/database.php';

// Ambil ID produk dari URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$product_id) {
    header('Location: products.php');
    exit;
}

// Ambil detail produk
global $conn;
$sql = "SELECT p.*, pc.name as category_name FROM products p LEFT JOIN product_categories pc ON p.category_id = pc.id WHERE p.id = $product_id AND p.is_active = 1";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);
if (!$product) {
    echo '<h2>Produk tidak ditemukan.</h2>';
    exit;
}

// Ambil review produk
$reviews = [];
$sql_reviews = "SELECT r.*, u.full_name FROM product_reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = $product_id ORDER BY r.created_at DESC";
$res_reviews = mysqli_query($conn, $sql_reviews);
while ($row = mysqli_fetch_assoc($res_reviews)) {
    $reviews[] = $row;
}

// Cek apakah user sudah login dan pernah membeli produk
$can_review = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql_order = "SELECT oi.id FROM order_items oi JOIN orders o ON oi.order_id = o.id WHERE oi.product_id = $product_id AND o.customer_email = (SELECT email FROM users WHERE id = $user_id) LIMIT 1";
    $res_order = mysqli_query($conn, $sql_order);
    if (mysqli_num_rows($res_order) > 0) {
        // Cek apakah user sudah pernah review produk ini
        $sql_has_review = "SELECT id FROM product_reviews WHERE product_id = $product_id AND user_id = $user_id";
        $res_has_review = mysqli_query($conn, $sql_has_review);
        if (mysqli_num_rows($res_has_review) == 0) {
            $can_review = true;
        }
    }
}

// Handle submit review
$review_message = '';
if ($can_review && isset($_POST['rating'], $_POST['review_text'])) {
    $rating = (int)$_POST['rating'];
    $review_text = mysqli_real_escape_string($conn, $_POST['review_text']);
    if ($rating >= 1 && $rating <= 5) {
        $sql_insert = "INSERT INTO product_reviews (product_id, user_id, rating, review_text) VALUES ($product_id, $user_id, $rating, '$review_text')";
        if (mysqli_query($conn, $sql_insert)) {
            $review_message = 'Review berhasil dikirim!';
            header('Location: detail_produk.php?id=' . $product_id); // Refresh untuk tampilkan review
            exit;
        } else {
            $review_message = 'Gagal mengirim review.';
        }
    } else {
        $review_message = 'Rating harus antara 1-5.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - <?= htmlspecialchars($product['name']) ?></title>
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
        .icon-spacing {
            margin-right: 8px;
        }
    </style>
</head>
<body>
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
            </div>
        </nav>
    </header>

    <div class="container" style="margin-top: 120px; margin-bottom: 40px;">
        <div class="product-detail-card" style="background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 32px; max-width: 900px; margin: 0 auto;">
            <div style="display: flex; flex-wrap: wrap; gap: 32px;">
                <div class="product-image" style="flex: 1 1 340px; min-width: 280px; max-width: 370px; min-height: 340px; background: #f8f9fa; border-radius: 12px; overflow: hidden; display: flex; align-items: center; justify-content: center; padding: 0;">
                    <img src="assets/images/products/<?= htmlspecialchars($product['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width: 100%; height: 360px; object-fit: cover; border-radius: 12px; display: block; margin: 0; padding: 0; aspect-ratio: 1/1;">
                </div>
                <div style="flex: 2 1 350px; min-width: 280px;">
                    <h1 style="font-size: 2rem; margin-bottom: 0.5rem; color: #2c3e50;"> <?= htmlspecialchars($product['name']) ?> </h1>
                    <div style="margin-bottom: 1rem; color: #888;">Kategori: <?= htmlspecialchars($product['category_name']) ?></div>
                    <div style="font-size: 1.3rem; font-weight: 600; margin-bottom: 1rem;">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>
                    <div style="margin-bottom: 1.5rem; color: #555;"> <?= nl2br(htmlspecialchars($product['description'])) ?> </div>
                    <div style="margin-bottom: 1.5rem;">
                        <span>Stok: <?= (int)$product['stock_quantity'] ?></span>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <a href="add_to_cart.php?product_id=<?= $product_id ?>" class="btn btn-primary" style="margin-bottom:10px;">
                            <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                        </a>
                    </div>
                    <div>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- <a href="checkout.php?product_id=<?= $product_id ?>" class="btn btn-primary">Beli Sekarang</a> -->
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary">Login untuk membeli</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <hr style="margin: 2.5rem 0;">
            <div class="product-reviews">
                <h2 style="margin-bottom: 1rem;">Review Produk</h2>
                <?php if (count($reviews) === 0): ?>
                    <p>Belum ada review untuk produk ini.</p>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div style="border-bottom: 1px solid #eee; padding: 1rem 0;">
                            <div style="font-weight: 600; color: #2c3e50;"> <?= htmlspecialchars($review['full_name']) ?> </div>
                            <div style="color: #f1c40f;">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star<?= $i <= $review['rating'] ? '' : '-o' ?>"></i>
                                <?php endfor; ?>
                                <span style="color: #888; font-size: 0.95em; margin-left: 8px;"> <?= date('d M Y', strtotime($review['created_at'])) ?> </span>
                            </div>
                            <div style="margin-top: 0.5rem; color: #444;"> <?= nl2br(htmlspecialchars($review['review_text'])) ?> </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html> 