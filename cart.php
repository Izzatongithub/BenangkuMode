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
    header('Location: cart.php');
    exit;
}
if (isset($_POST['remove'], $_POST['product_id'])) {
    $pid = (int)$_POST['product_id'];
    unset($_SESSION['cart'][$pid]);
    header('Location: cart.php');
    exit;
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['qty'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container" style="margin-top:120px; margin-bottom:40px; max-width:900px;">
    <h1>Keranjang Belanja</h1>
    <?php if (empty($cart)): ?>
        <p>Keranjang Anda kosong.</p>
        <a href="products.php" class="btn btn-primary">Belanja Produk</a>
    <?php else: ?>
        <form method="post">
        <table style="width:100%; border-collapse:collapse; margin-bottom:2rem;">
            <thead>
                <tr style="background:#f8f9fa;">
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cart as $item): ?>
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:10px 0;">
                        <img src="assets/images/products/<?= htmlspecialchars($item['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width:60px; height:60px; object-fit:contain; vertical-align:middle; border-radius:8px; margin-right:10px;">
                        <?= htmlspecialchars($item['name']) ?>
                    </td>
                    <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <input type="number" name="update_qty" value="<?= $item['qty'] ?>" min="1" style="width:50px;">
                            <button type="submit" class="btn btn-secondary" style="padding:2px 10px;">Update</button>
                        </form>
                    </td>
                    <td>Rp <?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <button type="submit" name="remove" class="btn btn-danger" style="padding:2px 10px;">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </form>
        <div style="text-align:right; font-size:1.2rem; font-weight:600; margin-bottom:2rem;">Total: Rp <?= number_format($total, 0, ',', '.') ?></div>
        <a href="checkout.php" class="btn btn-primary">Checkout</a>
    <?php endif; ?>
</div>
</body>
</html> 