<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$conn = getDbConnection();
$user_email = $_SESSION['user_email'];
$message = '';
$error = '';

// Handle checkout process
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'checkout') {
        $cart_items = json_decode($_POST['cart_items'], true);
        
        if (empty($cart_items)) {
            $error = 'Keranjang belanja kosong!';
        } else {
            try {
                // Start transaction
                mysqli_begin_transaction($conn);
                
                // Calculate total amount
                $total_amount = 0;
                foreach ($cart_items as $item) {
                    $total_amount += $item['price'] * $item['quantity'];
                }
                
                // Generate order number
                $order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));
                
                // Get form data
                $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
                $customer_phone = mysqli_real_escape_string($conn, $_POST['customer_phone']);
                $customer_address = mysqli_real_escape_string($conn, $_POST['customer_address']);
                $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
                $shipping_method = mysqli_real_escape_string($conn, $_POST['shipping_method']);
                
                // Create order
                $order_sql = "INSERT INTO orders (order_number, customer_name, customer_email, customer_phone, customer_address, total_amount, payment_method, shipping_method, status, payment_status) 
                             VALUES ('$order_number', '$customer_name', '$user_email', '$customer_phone', '$customer_address', $total_amount, '$payment_method', '$shipping_method', 'pending', 'pending')";
                
                if (!mysqli_query($conn, $order_sql)) {
                    throw new Exception('Error creating order: ' . mysqli_error($conn));
                }
                
                $order_id = mysqli_insert_id($conn);
                
                // Create order items
                foreach ($cart_items as $item) {
                    $product_id = mysqli_real_escape_string($conn, $item['id']);
                    $product_name = mysqli_real_escape_string($conn, $item['name']);
                    $quantity = mysqli_real_escape_string($conn, $item['quantity']);
                    $price = mysqli_real_escape_string($conn, $item['price']);
                    $subtotal = $price * $quantity;
                    
                    $order_item_sql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price, subtotal, status) 
                                      VALUES ($order_id, $product_id, '$product_name', $quantity, $price, $subtotal, 'pending')";
                    
                    if (!mysqli_query($conn, $order_item_sql)) {
                        throw new Exception('Error creating order item: ' . mysqli_error($conn));
                    }
                }
                
                // Commit transaction
                mysqli_commit($conn);
                
                $message = 'Pesanan berhasil dibuat! Pesanan Anda akan diproses segera.';
                
                // Log activity
                logActivity('create_order', "Created order ID: $order_id with " . count($cart_items) . " items");
                
            } catch (Exception $e) {
                mysqli_rollback($conn);
                $error = 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage();
            }
        }
    }
}

// Get user's recent orders
$sql = "SELECT 
        o.*, 
        oi.id as order_item_id,
        oi.product_name,
        oi.quantity,
        oi.subtotal,
        oi.status as item_status,
        o.created_at as order_date
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    WHERE o.customer_email = '$user_email'
    ORDER BY o.created_at DESC
    LIMIT 5";

$result = mysqli_query($conn, $sql);
if (!$result) {
    die('Query Error: ' . mysqli_error($conn));
}

$recent_orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $recent_orders[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - BenangkuMode</title>
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
        .checkout-card {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .checkout-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
        }
        .cart-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .order-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #d1ecf1; color: #0c5460; }
        .status-completed { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
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
                        <h1 class="h3">Checkout</h1>
                        <a href="products.php" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Produk
                        </a>
                    </div>

                    <?php if ($message): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Checkout Form -->
                    <div class="checkout-card">
                        <div class="checkout-header">
                            <h4 class="mb-0">Detail Pesanan</h4>
                        </div>
                        <div class="p-4">
                            <div id="checkoutItems">
                                <!-- Cart items will be loaded here -->
                            </div>
                            
                            <div class="order-summary mt-4">
                                <h5>Ringkasan Pesanan</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span id="subtotal">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Ongkos Kirim:</span>
                                    <span>Rp 15.000</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total:</span>
                                    <span id="total">Rp 0</span>
                                </div>
                            </div>

                            <form id="checkoutForm" method="POST">
                                <input type="hidden" name="action" value="checkout">
                                <input type="hidden" name="cart_items" id="cartItemsInput">
                                
                                <div class="mt-4">
                                    <h5>Informasi Pengiriman</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Lengkap</label>
                                                <input type="text" class="form-control" name="customer_name" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Alamat Pengiriman</label>
                                        <textarea class="form-control" name="customer_address" rows="3" placeholder="Masukkan alamat lengkap pengiriman" required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nomor Telepon</label>
                                                <input type="tel" class="form-control" name="customer_phone" placeholder="08xxxxxxxxxx" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Metode Pembayaran</label>
                                                <select class="form-select" name="payment_method" required>
                                                    <option value="">Pilih metode pembayaran</option>
                                                    <option value="transfer">Transfer Bank</option>
                                                    <option value="cod">Cash on Delivery (COD)</option>
                                                    <option value="ewallet">E-Wallet</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Metode Pengiriman</label>
                                        <select class="form-select" name="shipping_method" required>
                                            <option value="">Pilih metode pengiriman</option>
                                            <option value="jne">JNE</option>
                                            <option value="jnt">J&T</option>
                                            <option value="sicepat">SiCepat</option>
                                            <option value="cod">Cash on Delivery (COD)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <a href="products.php" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Lanjut Belanja
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-check me-2"></i>Proses Pesanan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <?php if (!empty($recent_orders)): ?>
                        <div class="checkout-card">
                            <div class="checkout-header">
                                <h4 class="mb-0">Pesanan Terakhir</h4>
                            </div>
                            <div class="p-4">
                                <?php foreach ($recent_orders as $order): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6 class="mb-1"><?php echo htmlspecialchars($order['product_name']); ?></h6>
                                            <small class="text-muted">
                                                <?php echo date('d F Y H:i', strtotime($order['order_date'])); ?> • 
                                                Qty: <?php echo $order['quantity']; ?>
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold"><?php echo formatCurrency($order['subtotal']); ?></div>
                                            <span class="status-badge status-<?php echo $order['item_status']; ?>">
                                                <?php 
                                                switch($order['item_status']) {
                                                    case 'pending': echo 'Menunggu'; break;
                                                    case 'processing': echo 'Diproses'; break;
                                                    case 'completed': echo 'Selesai'; break;
                                                    case 'cancelled': echo 'Dibatalkan'; break;
                                                    default: echo ucfirst($order['item_status']);
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <div class="text-center">
                                    <a href="orders.php" class="btn btn-outline-primary btn-sm">
                                        Lihat Semua Pesanan
                                    </a>
                                </div>
                            </div>
                        </div>
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
    <script>
        // Load cart items from localStorage
        function loadCartItems() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const checkoutItems = document.getElementById('checkoutItems');
            const subtotalElement = document.getElementById('subtotal');
            const totalElement = document.getElementById('total');
            const cartItemsInput = document.getElementById('cartItemsInput');
            
            if (cart.length === 0) {
                checkoutItems.innerHTML = '<p class="text-center text-muted">Keranjang belanja kosong</p>';
                subtotalElement.textContent = 'Rp 0';
                totalElement.textContent = 'Rp 0';
                return;
            }
            
            let subtotal = 0;
            checkoutItems.innerHTML = '';
            
            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;
                
                const itemElement = document.createElement('div');
                itemElement.className = 'cart-item';
                itemElement.innerHTML = `
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-1">${item.name}</h6>
                            <small class="text-muted">Rp ${item.price.toLocaleString()} per item</small>
                        </div>
                        <div class="col-md-2 text-center">
                            <span class="badge bg-secondary">${item.quantity}</span>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="fw-bold">Rp ${itemTotal.toLocaleString()}</span>
                        </div>
                    </div>
                `;
                checkoutItems.appendChild(itemElement);
            });
            
            const shipping = 15000;
            const total = subtotal + shipping;
            
            subtotalElement.textContent = `Rp ${subtotal.toLocaleString()}`;
            totalElement.textContent = `Rp ${total.toLocaleString()}`;
            cartItemsInput.value = JSON.stringify(cart);
        }
        
        // Handle form submission
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            
            if (cart.length === 0) {
                e.preventDefault();
                alert('Keranjang belanja kosong!');
                return;
            }
            
            // Clear cart after successful submission
            localStorage.removeItem('cart');
        });
        
        // Load cart items when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadCartItems();
        });
    </script>
</body>
</html> 