<?php
session_start();
require_once 'config/database.php';

// Ambil semua produk aktif
$products = [];
$result = mysqli_query($conn, "SELECT * FROM products WHERE is_active = 1");
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - BenangkuMode</title>
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
                        <a href="about.php" class="nav-link">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a href="products.php" class="nav-link active">Produk</a>
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
            <h1>Produk Kami</h1>
            <p>Temukan kerajinan tangan merajut berkualitas tinggi dengan desain unik</p>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="container">
            <div class="filter-container">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Cari produk...">
                    <i class="fas fa-search"></i>
                </div>
                <div class="filter-options">
                    <select id="categoryFilter">
                        <option value="">Semua Kategori</option>
                        <option value="scarf">Scarf</option>
                        <option value="cardigan">Cardigan</option>
                        <option value="bag">Tas</option>
                        <option value="accessories">Aksesoris</option>
                    </select>
                    <select id="priceFilter">
                        <option value="">Semua Harga</option>
                        <option value="0-100000">Dibawah Rp 100.000</option>
                        <option value="100000-200000">Rp 100.000 - Rp 200.000</option>
                        <option value="200000-300000">Rp 200.000 - Rp 300.000</option>
                        <option value="300000+">Diatas Rp 300.000</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section">
        <div class="container">
            <div class="products-header">
                <h2>Koleksi Produk</h2>
                <div class="products-count">
                    <span id="productCount"><?= count($products) ?></span> produk ditemukan
                </div>
            </div>
            <div class="products-grid" id="productsGrid">
                <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <!-- <img src="<?= htmlspecialchars($product['image'] ?? 'assets/images/products/') ?>" alt="<?= htmlspecialchars($product['name']) ?>"> -->
                            <!-- <img src="assets/images/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>"> -->
                            <img src="assets/images/products/<?= htmlspecialchars($product['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    </div>
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <span class="price">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                    <!-- Tombol tambah ke keranjang bisa dihubungkan ke JS -->
                    <button class="btn btn-primary add-to-cart" onclick="addToCart(<?= $product['id'] ?>)">
                        <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Shopping Cart Modal -->
    <div id="cartModal" class="cart-modal">
        <div class="cart-content">
            <div class="cart-header">
                <h3>Keranjang Belanja</h3>
                <button class="close-cart" onclick="closeCart()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="cart-items" id="cartItems">
                <!-- Cart items will be displayed here -->
            </div>
            <div class="cart-footer">
                <div class="cart-total">
                    <span>Total:</span>
                    <span id="cartTotal">Rp 0</span>
                </div>
                <button class="btn btn-primary" onclick="checkout()">
                    <i class="fab fa-whatsapp"></i> Beli via WhatsApp
                </button>
            </div>
        </div>
    </div>

    <!-- Cart Button -->
    <div class="cart-button" onclick="openCart()">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-count" id="cartCount">0</span>
    </div>

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
    <script src="assets/js/products.js"></script>
</body>
</html> 