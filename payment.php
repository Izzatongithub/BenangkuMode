<?php
session_start();
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$method = isset($_GET['method']) ? $_GET['method'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finish'])) {
    if ($method !== 'cod' && $order_id) {
        require_once 'config/database.php';
        $conn = getDbConnection();
        mysqli_query($conn, "UPDATE orders SET payment_status='paid' WHERE id=" . intval($order_id));
    }
    unset($_SESSION['cart']);
    header('Location: orders.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Pesanan</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
        .payment-container { max-width: 420px; margin: 60px auto; background: #fff; border-radius: 16px; box-shadow: 0 2px 12px #eee; padding: 32px 28px; text-align: center; }
        .payment-title { font-size: 1.4rem; font-weight: 600; margin-bottom: 18px; color: #764ba2; }
        .payment-instr { font-size: 1.05rem; color: #333; margin-bottom: 24px; }
        .qr-sim { width: 160px; height: 160px; background: repeating-linear-gradient(45deg,#eee,#eee 10px,#ccc 10px,#ccc 20px); border-radius: 12px; margin: 0 auto 18px auto; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; color: #764ba2; }
        .btn-finish { background: #e74c3c; color: #fff; border: none; border-radius: 24px; padding: 12px 36px; font-size: 1.08rem; font-weight: 600; margin-top: 18px; cursor: pointer; }
        .btn-finish:hover { background: #c0392b; }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-title">Pembayaran Pesanan #<?= htmlspecialchars($order_id) ?></div>
        <?php if ($method === 'transfer'): ?>
            <div class="payment-instr">
                Silakan transfer total pembayaran ke rekening berikut:<br><br>
                <strong>Bank BCA</strong><br>
                No. Rekening: <strong>1234567890</strong><br>
                a.n. <strong>BenangkuMode</strong><br><br>
                Setelah transfer, klik tombol selesai di bawah.
            </div>
        <?php elseif ($method === 'ewallet'): ?>
            <div class="payment-instr">
                Silakan scan QR code berikut untuk pembayaran e-wallet:<br><br>
                <div class="qr-sim"><i class="fas fa-qrcode"></i></div>
                Setelah pembayaran, klik tombol selesai di bawah.
            </div>
        <?php else: ?>
            <div class="payment-instr">
                Silakan lakukan pembayaran online sesuai instruksi pada aplikasi.<br><br>
                Setelah pembayaran, klik tombol selesai di bawah.
            </div>
        <?php endif; ?>
        <form method="post">
            <button type="submit" name="finish" class="btn-finish">Selesai</button>
        </form>
    </div>
</body>
</html> 