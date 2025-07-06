<?php
session_start();
require_once 'config/database.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$popup = null;

// Handle upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registration_id'])) {
    $registration_id = intval($_POST['registration_id']);
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    $max_size = 2 * 1024 * 1024; // 2MB

    if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['payment_proof'];
        if (!in_array($file['type'], $allowed_types)) {
            $popup = ['type' => 'error', 'msg' => 'Format file harus JPG/PNG.'];
        } elseif ($file['size'] > $max_size) {
            $popup = ['type' => 'error', 'msg' => 'Ukuran file maksimal 2MB.'];
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_name = 'proof_' . $registration_id . '_' . time() . '.' . $ext;
            $target_dir = 'assets/images/payment_proof/';
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $target_file = $target_dir . $new_name;
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                // Update database (procedural mysqli)
                $stmt = mysqli_prepare($conn, "UPDATE workshop_registrations SET payment_proof=?, payment_status='pending' WHERE id=? AND user_id=?");
                if (!$stmt) {
                    die("Prepare failed: " . mysqli_error($conn));
                }
                mysqli_stmt_bind_param($stmt, 'sii', $target_file, $registration_id, $user_id);
                if (mysqli_stmt_execute($stmt)) {
                    $popup = ['type' => 'success', 'msg' => 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.'];
                } else {
                    $popup = ['type' => 'error', 'msg' => 'Gagal update database.'];
                }
                mysqli_stmt_close($stmt);
            } else {
                $popup = ['type' => 'error', 'msg' => 'Gagal upload file.'];
            }
        }
    } else {
        $popup = ['type' => 'error', 'msg' => 'Pilih file bukti pembayaran.'];
    }
}

// Ambil daftar pendaftaran workshop user yang statusnya pending (procedural mysqli)
$sql = "SELECT wr.id, w.title, wr.payment_status, wr.payment_proof, wr.registration_date
        FROM workshop_registrations wr
        JOIN workshops w ON wr.workshop_id = w.id
        WHERE wr.user_id=? AND wr.payment_status='pending'";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$registrations = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $registrations[] = $row;
    }
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload Bukti Pembayaran Workshop</title>
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
        .container {
            margin-top: 140px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(102,126,234,0.07);
        }
        th, td {
            padding: 12px 14px;
            border-bottom: 1px solid #f0f0f0;
            text-align: left;
        }
        th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
            font-size: 1rem;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:hover {
            background: #f3f6fd;
        }
        .upload-form {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .btn-upload {
            background: #667eea;
            color: #fff;
            border: none;
            padding: 7px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.2s, transform 0.2s;
        }
        .btn-upload:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }
        .proof-img {
            max-width: 80px;
            max-height: 80px;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(102,126,234,0.08);
        }
        .no-data {
            text-align: center;
            color: #888;
            padding: 24px 0;
        }
        .alert.success { background: #eafbe7; color: #27ae60; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
        .alert.error { background: #fdeaea; color: #e74c3c; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
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
    <div class="container">
        <h2>Upload Bukti Pembayaran Workshop</h2>
        <?php if ($popup): ?>
            <div class="alert <?= $popup['type']; ?>">
                <?= htmlspecialchars($popup['msg']); ?>
            </div>
        <?php endif; ?>
        <?php if (count($registrations) === 0): ?>
            <div class="no-data">Tidak ada pendaftaran workshop yang menunggu pembayaran.</div>
        <?php else: ?>
        <table>
            <tr>
                <th>Workshop</th>
                <th>Tanggal Daftar</th>
                <th>Bukti Pembayaran</th>
                <th>Status</th>
                <th>Upload</th>
            </tr>
            <?php foreach ($registrations as $reg): ?>
            <tr>
                <td><?= htmlspecialchars($reg['title']) ?></td>
                <td><?= date('d M Y H:i', strtotime($reg['registration_date'])) ?></td>
                <td>
                    <?php if ($reg['payment_proof']): ?>
                        <img src="<?= htmlspecialchars($reg['payment_proof']) ?>" class="proof-img" alt="Bukti Pembayaran">
                    <?php else: ?>
                        <span class="text-muted">Belum ada</span>
                    <?php endif; ?>
                </td>
                <td class="status-pending">Menunggu Pembayaran</td>
                <td>
                    <form class="upload-form" method="post" enctype="multipart/form-data" onsubmit="return confirm('Upload bukti pembayaran untuk workshop ini?')">
                        <input type="hidden" name="registration_id" value="<?= $reg['id'] ?>">
                        <input type="file" name="payment_proof" accept="image/*" required>
                        <button type="submit" class="btn-upload">Upload</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</body>
</html> 