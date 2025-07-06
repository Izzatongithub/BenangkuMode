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
    <style>
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
    </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>
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