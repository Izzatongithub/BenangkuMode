<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$conn = getDbConnection();
$user_email = mysqli_real_escape_string($conn, $_SESSION['user_email']);

// Get user's orders
$sql = "SELECT 
        o.*, 
        oi.id as order_item_id,
        oi.product_id,
        oi.product_name,
        oi.quantity,
        oi.price,
        oi.subtotal,
        -- oi.status as item_status,
        o.created_at as order_date,
        p.image as product_image
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN products p ON oi.product_id = p.id
    WHERE o.customer_email = '$user_email'
    ORDER BY o.created_at DESC";

$result = mysqli_query($conn, $sql);
if (!$result) {
    die('Query Error: ' . mysqli_error($conn));
}

$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $order_id = $row['id'];
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
            'order_id' => $order_id,
            'order_number' => $row['order_number'],
            'order_date' => $row['order_date'],
            'total_amount' => $row['total_amount'],
            'status' => $row['status'],
            'payment_status' => $row['payment_status'],
            'items' => []
        ];
    }
    
    if ($row['order_item_id']) {
        $orders[$order_id]['items'][] = [
            'id' => $row['order_item_id'],
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'quantity' => $row['quantity'],
            'price' => $row['price'],
            'subtotal' => $row['subtotal'],
            'product_image' => $row['product_image']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - BenangkuMode</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .order-card {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .order-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
        }
        .order-item {
            background: #fff;
            border-radius: 10px;
            margin-bottom: 12px;
            padding: 16px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
            border-bottom: 1px solid #f0f0f0;
            transition: box-shadow 0.2s;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .order-item .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 18px;
            background: #f8f9fa;
        }
        .order-item .item-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .order-item .item-info strong {
            font-size: 1.05rem;
            color: #2c3e50;
        }
        .order-item .item-info .qty {
            color: #888;
            font-size: 0.95rem;
        }
        .order-item .item-price,
        .order-item .item-subtotal {
            min-width: 90px;
            text-align: right;
            font-weight: 500;
            color: #e74c3c;
            font-size: 1rem;
            margin-left: 18px;
        }
        @media (max-width: 600px) {
            .order-item {
                flex-direction: column;
                align-items: flex-start;
                padding: 14px 8px;
            }
            .order-item .item-price,
            .order-item .item-subtotal {
                margin-left: 0;
                text-align: left;
            }
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #d1ecf1; color: #0c5460; }
        .status-shipped { background: #d1ecf1; color: #0c5460; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .status-paid { background: #d4edda; color: #155724; }
        .status-failed { background: #f8d7da; color: #721c24; }
        .status-refunded { background: #f8d7da; color: #721c24; }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        .empty-state i {
            font-size: 64px;
            color: #ddd;
            margin-bottom: 20px;
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

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h3">Riwayat Pesanan</h1>
                        <a href="products.php" class="btn btn-primary">
                            <i class="fas fa-shopping-cart me-2"></i>Belanja Lagi
                        </a>
                    </div>

                    <?php if (empty($orders)): ?>
                        <div class="empty-state">
                            <i class="fas fa-shopping-bag"></i>
                            <h4>Belum Ada Pesanan</h4>
                            <p>Anda belum memiliki pesanan. Mulai belanja sekarang!</p>
                            <a href="products.php" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Lihat Produk
                            </a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-0">Pesanan #<?php echo $order['order_number']; ?></h5>
                                            <small><?php echo date('d F Y H:i', strtotime($order['order_date'])); ?></small>
                                        </div>
                                        <div class="text-end">
                                            <h6 class="mb-0">Total: <?php echo formatCurrency($order['total_amount']); ?></h6>
                                            <div class="mt-1">
                                                <span class="status-badge status-<?php echo $order['status']; ?>">
                                                    <?php 
                                                    switch($order['status']) {
                                                        case 'pending': echo 'Menunggu'; break;
                                                        case 'processing': echo 'Diproses'; break;
                                                        case 'shipped': echo 'Dikirim'; break;
                                                        case 'delivered': echo 'Terkirim'; break;
                                                        case 'cancelled': echo 'Dibatalkan'; break;
                                                        default: echo ucfirst($order['status']);
                                                    }
                                                    ?>
                                                </span>
                                                <span class="status-badge status-<?php echo $order['payment_status']; ?> ms-1">
                                                    <?php 
                                                    switch($order['payment_status']) {
                                                        case 'pending': echo 'Belum Bayar'; break;
                                                        case 'paid': echo 'Lunas'; break;
                                                        case 'failed': echo 'Gagal'; break;
                                                        case 'refunded': echo 'Dikembalikan'; break;
                                                        default: echo ucfirst($order['payment_status']);
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="order-item">
                                        <?php if ($item['product_image']): ?>
                                            <img src="assets/images/products/<?php echo htmlspecialchars($item['product_image']); ?>" class="product-image" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                        <?php else: ?>
                                            <div class="product-image" style="display:flex;align-items:center;justify-content:center;"><i class="fas fa-image text-muted"></i></div>
                                        <?php endif; ?>
                                        <div class="item-info">
                                            <strong><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                            <span class="qty">Qty: <?php echo $item['quantity']; ?></span>
                                        </div>
                                        <div class="item-price">Rp <?php echo number_format($item['price'],0,',','.'); ?></div>
                                        <div class="item-subtotal">Rp <?php echo number_format($item['subtotal'],0,',','.'); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html> 