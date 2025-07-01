<?php
session_start();
require_once 'config/database.php';

if (!isset($_GET['product_id'])) {
    header('Location: products.php');
    exit;
}
$product_id = (int)$_GET['product_id'];
if ($product_id <= 0) {
    header('Location: products.php');
    exit;
}

// Ambil data produk
$sql = "SELECT * FROM products WHERE id = $product_id AND is_active = 1";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);
if (!$product) {
    header('Location: products.php');
    exit;
}

// Tambah ke session cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['qty'] += 1;
} else {
    $_SESSION['cart'][$product_id] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'image' => $product['image'],
        'qty' => 1
    ];
}
header('Location: cart.php');
exit; 