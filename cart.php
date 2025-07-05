<?php
session_start();
require_once 'config/database.php';

// Handle update/hapus
if (isset($_POST['update_qty'], $_POST['product_id'])) {
    $pid = (int)$_POST['product_id'];
    $qty = max(1, (int)$_POST['update_qty']);
    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]['qty'] = $qty;
    }
    $redirect = (isset($_GET['redirect']) && $_GET['redirect'] === 'checkout') ? 'checkout.php' : 'cart.php';
    header('Location: ' . $redirect);
    exit;
}
if (isset($_POST['remove'], $_POST['product_id'])) {
    $pid = (int)$_POST['product_id'];
    unset($_SESSION['cart'][$pid]);
    $redirect = (isset($_GET['redirect']) && $_GET['redirect'] === 'checkout') ? 'checkout.php' : 'cart.php';
    header('Location: ' . $redirect);
    exit;
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['qty'];
}

// Ambil 4 produk aktif sebagai rekomendasi
$recommended = [];

$cart_ids = array_keys($_SESSION['cart'] ?? []);
$cart_ids_str = implode(',', array_map('intval', $cart_ids));

$whereNotIn = $cart_ids ? "AND id NOT IN ($cart_ids_str)" : "";

$sql = "SELECT * FROM products WHERE is_active = 1 $whereNotIn ORDER BY RAND() LIMIT 4";
$query = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($query)) {
    $recommended[] = $row;
}

// Tambah ke keranjang jika form dikirim
if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
    $pid = (int)$_POST['product_id'];
    $res = mysqli_query($conn, "SELECT * FROM products WHERE id = $pid AND is_active = 1 LIMIT 1");
    if ($res && mysqli_num_rows($res) === 1) {
        $product = mysqli_fetch_assoc($res);
        if (!isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'qty' => 1,
                'image' => $product['image']
            ];
        } else {
            $_SESSION['cart'][$pid]['qty']++;
        }
        header("Location: cart.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
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
        .cart-section {
            padding: 100px 0 60px;
            background: #f9f9fb;
        }
        .cart-section .section-title {
            padding: 50px 0 20px;
            font-size: 2rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 2rem;
            color: #2c3e50;
        }
        .cart-section .empty-cart {
            text-align: center;
            font-size: 1rem;
            color: #777;
            margin-bottom: 2rem;
        }
        .cart-table table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .cart-table th, .cart-table td {
            padding: 1rem;
            text-align: center;
            vertical-align: middle;
            border-bottom: 1px solid #eee;
            font-size: 0.95rem;
            color: #333;
        }
        .cart-table thead {
            background: #f3f4f6;
            font-weight: 600;
        }
        .product-info {
            display: flex;
            align-items: center;
            gap: 12px;
            justify-content: center;
        }
        .product-info img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        .qty-form {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .qty-form input[type="number"] {
            width: 60px;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            text-align: center;
        }
        .qty-form button {
            display: inline-block;
        }
        .qty-form:hover button {
            display: inline-block;
        }
        .cart-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .cart-summary .total {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
        }
        .recommended-section {
            background: #f8f9fa;
            padding: 60px 0;
        }
        .recommended-section .section-title {
            font-size: 1.6rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 2rem;
            padding-left: 1rem;
        }
        .recommended-flex {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: center;
        }
        .recommend-card {
            background: #fff;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
        }
        .image-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(to right, #c084fc, #ec4899);
            height: 200px;
            overflow: hidden;
        }
        .image-wrapper img {
            height: 100%;
            object-fit: cover;
            z-index: 1;
        }
        .image-gradient {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 60px;
            background: linear-gradient(to right, #c084fc, transparent);
            left: 0;
            z-index: 0;
            }
            .image-gradient.right {
            left: auto;
            right: 0;
            background: linear-gradient(to left, #ec4899, transparent);
            }
            .content {
            padding: 1rem 1.5rem 0;
            flex-grow: 1;
            }
            .content h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.3rem;
            }
            .content .desc {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
            }
            .content .price {
            color: #d94827;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            }
            .btn-add {
            background: #d94827;
            color: white;
            border: none;
            padding: 12px;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 0 0 28px 28px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.3s;
            }
            .btn-add:hover {
            background: #b3361e;
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
        </header>

    <section class="cart-section">
        <div class="container">
            <h1 class="section-title">Keranjang Belanja</h1>
            <?php if (empty($cart)): ?>
                <p class="empty-cart">Keranjang Anda kosong.</p>
                <a href="products.php" class="btn btn-primary">Belanja Produk</a>
            <?php else: ?>
                <div class="cart-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart as $item): ?>
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <img src="assets/images/products/<?= htmlspecialchars($item['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                        <span><?= htmlspecialchars($item['name']) ?></span>
                                    </div>
                                </td>
                                <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                <td>
                                    <form method="post" class="qty-form">
                                        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                        <input type="number" name="update_qty" value="<?= $item['qty'] ?>" min="1">
                                        <button type="submit" class="btn btn-secondary">Update</button>
                                    </form>
                                </td>
                                <td>Rp <?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?></td>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                        <button type="submit" name="remove" class="btn btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="cart-summary">
                    <div class="total">Total: Rp <?= number_format($total, 0, ',', '.') ?></div>
                    <a href="checkout.php" class="btn btn-primary">Checkout</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php if (!empty($recommended)): ?>
    <section class="recommended-section">
    <div class="container">
        <h2 class="section-title">Rekomendasi untukmu</h2>
        <div class="recommended-flex">
        <?php foreach ($recommended as $rec): ?>
        <div class="recommend-card" style="max-width:220px;min-width:180px;padding:0 0 18px 0;box-shadow:0 2px 8px #eee;border-radius:18px;display:flex;flex-direction:column;align-items:stretch;">
            <div style="position:relative;">
                <a href="detail_produk.php?id=<?= $rec['id'] ?>" style="display:block;">
                    <div class="product-image" style="height:160px;background:#f8f9fa;border-radius:18px 18px 0 0;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                        <img src="assets/images/products/<?= htmlspecialchars($rec['image']) ?>" alt="<?= htmlspecialchars($rec['name']) ?>" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                </a>
                <form method="post" style="position:absolute;bottom:10px;right:10px;z-index:2;">
                    <input type="hidden" name="product_id" value="<?= $rec['id'] ?>">
                    <button type="submit" name="add_to_cart" class="btn btn-primary" style="padding:6px 10px;font-size:0.95rem;border-radius:16px;min-width:unset;">
                        <i class="fas fa-cart-plus"></i>
                    </button>
                </form>
            </div>
            <div style="padding:12px 14px 0 14px;text-align:left;">
                <span style="color:#3a4660;font-size:1.05rem;font-weight:500;line-height:1.3;margin-bottom:8px;display:block;">
                    <?= htmlspecialchars($rec['name']) ?>
                </span>
                <span class="price" style="font-weight:700; color:#222; font-size:1.15rem; display:block; line-height:1.1;">
                    Rp <?= number_format($rec['price'], 0, ',', '.') ?>
                </span>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
    </div>
    </section>
    <?php endif; ?>
</body>
</html> 