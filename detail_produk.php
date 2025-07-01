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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div class="container" style="margin-top: 120px; margin-bottom: 40px;">
        <div class="product-detail-card" style="background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 32px; max-width: 900px; margin: 0 auto;">
            <div style="display: flex; flex-wrap: wrap; gap: 32px;">
                <div class="product-image" style="flex: 1 1 320px; min-width: 280px; max-width: 350px;">
                    <img src="assets/images/products/<?= htmlspecialchars($product['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width: 100%; height: 320px; object-fit: contain; background: #f8f9fa; border-radius: 12px;">
                </div>
                <div style="flex: 2 1 350px; min-width: 280px;">
                    <h1 style="font-size: 2rem; margin-bottom: 0.5rem; color: #2c3e50;"> <?= htmlspecialchars($product['name']) ?> </h1>
                    <div style="margin-bottom: 1rem; color: #888;">Kategori: <?= htmlspecialchars($product['category_name']) ?></div>
                    <div style="font-size: 1.3rem; color: #e74c3c; font-weight: 600; margin-bottom: 1rem;">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>
                    <div style="margin-bottom: 1.5rem; color: #555;"> <?= nl2br(htmlspecialchars($product['description'])) ?> </div>
                    <div style="margin-bottom: 1.5rem;">
                        <span>Stok: <?= (int)$product['stock_quantity'] ?></span>
                    </div>
                    <div>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="checkout.php?product_id=<?= $product_id ?>" class="btn btn-primary">Beli Sekarang</a>
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
            <?php if ($can_review): ?>
                <hr style="margin: 2.5rem 0;">
                <div class="review-form">
                    <h3>Tulis Review Anda</h3>
                    <?php if ($review_message): ?>
                        <div style="color: #e74c3c; margin-bottom: 1rem;"> <?= htmlspecialchars($review_message) ?> </div>
                    <?php endif; ?>
                    <form method="post">
                        <label for="rating">Rating:</label>
                        <select name="rating" id="rating" required style="margin-bottom: 1rem;">
                            <option value="">Pilih rating</option>
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?= $i ?>"> <?= $i ?> </option>
                            <?php endfor; ?>
                        </select>
                        <br>
                        <label for="review_text">Review:</label><br>
                        <textarea name="review_text" id="review_text" rows="4" style="width: 100%; max-width: 500px; margin-bottom: 1rem;" required></textarea><br>
                        <button type="submit" class="btn btn-primary">Kirim Review</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php include 'includes/navbar.php'; ?>
</body>
</html> 