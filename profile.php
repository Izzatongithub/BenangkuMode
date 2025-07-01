<?php
require_once 'config/database.php';
session_start();

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data user dari database
$sql = "SELECT * FROM users WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
} else {
    $user = null;
}

$error = '';
$success = '';

// Proses update profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');

    $sql_update = "UPDATE users SET username = '$name', email = '$email', phone = '$phone', address = '$address' WHERE id = '$user_id'";
    if (mysqli_query($conn, $sql_update)) {
        $success = 'Profil berhasil diperbarui.';
        // Ambil data terbaru
        $sql = "SELECT * FROM users WHERE id = '$user_id' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
        }
    } else {
        $error = 'Gagal memperbarui profil.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - BenangkuMode</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Tambahan Google Fonts jika belum -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Section Hero Page -->
    <section class="page-hero">
        <div class="container">
            <h1>Profil Saya</h1>
            <p>Kelola data pribadi Anda di sini.</p>
        </div>
    </section>

    <!-- Section Form Profil -->
    <section class="features" style="padding-top: 40px;">
        <div class="container" style="max-width: 600px; margin: auto;">
            
            <!-- Alert -->
            <?php if ($error): ?>
                <div class="alert alert-danger" style="background: #ffe5e5; color: #c0392b; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success" style="background: #e0f7e9; color: #27ae60; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <?php if ($user): ?>
            <form method="POST" action="" style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div class="form-group">
                    <label for="name" style="font-weight: 600; color: #2c3e50;">Nama</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required
                        style="width: 100%; padding: 12px 16px; border: 2px solid #ddd; border-radius: 8px; font-size: 1rem;">
                </div>

                <div class="form-group">
                    <label for="email" style="font-weight: 600; color: #2c3e50;">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required
                        style="width: 100%; padding: 12px 16px; border: 2px solid #ddd; border-radius: 8px; font-size: 1rem;">
                </div>

                <div class="form-group">
                    <label for="phone" style="font-weight: 600; color: #2c3e50;">No. HP</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                        style="width: 100%; padding: 12px 16px; border: 2px solid #ddd; border-radius: 8px; font-size: 1rem;">
                </div>

                <div class="form-group">
                    <label for="address" style="font-weight: 600; color: #2c3e50;">Alamat</label>
                    <textarea id="address" name="address"
                        style="width: 100%; padding: 12px 16px; border: 2px solid #ddd; border-radius: 8px; font-size: 1rem; min-height: 100px;"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; text-align: center;">Simpan Perubahan</button>
            </form>
            <?php else: ?>
                <p style="text-align: center; color: #e74c3c;">Data pengguna tidak ditemukan.</p>
            <?php endif; ?>
        </div>
        <!-- Tombol Kembali -->
        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php" style="display: inline-block; padding: 12px 30px; color: #2c3e50"> Batal </a>
        </div>
    </section>
    
    
</body>
</html>