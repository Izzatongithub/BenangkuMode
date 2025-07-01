<?php
session_start();
require_once '../config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$conn = getDbConnection();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $estimated_price = $_POST['estimated_price'] !== '' ? (float)$_POST['estimated_price'] : null;
    $estimated_release_date = $_POST['estimated_release_date'] ?? null;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Handle upload gambar
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg','jpeg','png'];
        $maxSize = 2*1024*1024;
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed) && $_FILES['image']['size'] <= $maxSize) {
            $newName = uniqid('comingsoon_', true) . '.' . $ext;
            $target = '../assets/images/products/' . $newName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $image = $newName;
            }
        }
    }

    if (!$name || !$description) {
        $error = 'Nama dan deskripsi wajib diisi.';
    } else {
        $sql = "INSERT INTO coming_soon_products (name, description, estimated_price, estimated_release_date, image, is_active) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssdssi', $name, $description, $estimated_price, $estimated_release_date, $image, $is_active);
        if ($stmt->execute()) {
            header('Location: ../comingsoon.php');
            exit();
        } else {
            $error = 'Gagal menambah produk coming soon.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk Coming Soon</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Tambah Produk Coming Soon</h2>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nama Produk *</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Deskripsi *</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Harga Estimasi</label>
            <input type="number" name="estimated_price" class="form-control" min="0">
        </div>
        <div class="mb-3">
            <label>Tanggal Rilis Estimasi</label>
            <input type="date" name="estimated_release_date" class="form-control">
        </div>
        <div class="mb-3">
            <label>Gambar (opsional, JPG/PNG, max 2MB)</label>
            <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png">
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="../comingsoon.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html> 