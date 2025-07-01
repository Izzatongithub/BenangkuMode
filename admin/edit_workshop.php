<?php
session_start();
require_once '../config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$conn = getDbConnection();

// Ambil kategori
$categories = [];
$res = $conn->query("SELECT id, name FROM workshop_categories WHERE is_active = 1 ORDER BY name");
while ($row = $res->fetch_assoc()) {
    $categories[] = $row;
}

// Ambil data workshop
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$workshop = null;
if ($id) {
    $res = $conn->query("SELECT * FROM workshops WHERE id = $id");
    $workshop = $res->fetch_assoc();
    if (!$workshop) {
        header('Location: workshops.php');
        exit();
    }
} else {
    header('Location: workshops.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = (int)$_POST['category_id'];
    $instructor = mysqli_real_escape_string($conn, $_POST['instructor']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $price = (float)$_POST['price'];
    $max_participants = (int)$_POST['max_participants'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $is_past_event = isset($_POST['is_past_event']) ? 1 : 0;

    // Handle upload gambar
    $image = $workshop['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg','jpeg','png'];
        $maxSize = 2*1024*1024;
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $error = 'File gambar harus JPG, JPEG, atau PNG.';
        } elseif ($_FILES['image']['size'] > $maxSize) {
            $error = 'Ukuran gambar maksimal 2MB.';
        } else {
            $newName = uniqid('workshop_', true) . '.' . $ext;
            $target = '../assets/images/workshops/' . $newName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                // Hapus file lama jika ada
                if ($workshop['image'] && file_exists('../assets/images/workshops/' . $workshop['image'])) {
                    unlink('../assets/images/workshops/' . $workshop['image']);
                }
                $image = $newName;
            } else {
                $error = 'Gagal upload gambar.';
            }
        }
    }

    if (!$title || !$category_id || !$instructor || !$start_date || !$duration || !$location || !$price || !$max_participants) {
        $error = 'Semua field wajib diisi.';
    } elseif (!$error) {
        $sql = "UPDATE workshops SET title=?, description=?, category_id=?, instructor=?, start_date=?, end_date=?, duration=?, location=?, price=?, max_participants=?, image=?, is_active=?, is_past_event=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssisssssdisiii', $title, $description, $category_id, $instructor, $start_date, $end_date, $duration, $location, $price, $max_participants, $image, $is_active, $is_past_event, $id);
        if ($stmt->execute()) {
            header('Location: workshops.php');
            exit();
        } else {
            $error = 'Gagal mengupdate workshop.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Workshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Workshop</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"> <?= $error ?> </div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Judul Workshop *</label>
            <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($workshop['title']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($workshop['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori *</label>
            <select name="category_id" class="form-select" required>
                <option value="">- Pilih Kategori -</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $workshop['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Instruktur *</label>
            <input type="text" name="instructor" class="form-control" required value="<?= htmlspecialchars($workshop['instructor']) ?>">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Mulai *</label>
                <input type="datetime-local" name="start_date" class="form-control" required value="<?= date('Y-m-d\TH:i', strtotime($workshop['start_date'])) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="datetime-local" name="end_date" class="form-control" value="<?= $workshop['end_date'] ? date('Y-m-d\TH:i', strtotime($workshop['end_date'])) : '' ?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Durasi *</label>
            <input type="text" name="duration" class="form-control" required value="<?= htmlspecialchars($workshop['duration']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Lokasi *</label>
            <input type="text" name="location" class="form-control" required value="<?= htmlspecialchars($workshop['location']) ?>">
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Harga (Rp) *</label>
                <input type="number" name="price" class="form-control" required min="0" value="<?= $workshop['price'] ?>">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Slot Maksimal *</label>
                <input type="number" name="max_participants" class="form-control" required min="1" value="<?= $workshop['max_participants'] ?>">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Gambar (JPG/PNG, max 2MB)</label>
                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png">
                <?php if ($workshop['image']): ?>
                    <div class="mt-2">
                        <img src="../assets/images/workshops/<?= htmlspecialchars($workshop['image']) ?>" alt="Gambar Workshop" style="max-width:120px;max-height:80px;">
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" <?= $workshop['is_active'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_past_event" id="is_past_event" <?= $workshop['is_past_event'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_past_event">Event Lampau</label>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
        <a href="workshops.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html> 