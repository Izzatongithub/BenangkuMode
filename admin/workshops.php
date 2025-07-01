<?php
session_start();
require_once '../config/database.php';

// Hanya admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$conn = getDbConnection();

// Handle hapus
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM workshops WHERE id = $id");
    header('Location: workshops.php');
    exit();
}

// Ambil data workshop
$sql = "SELECT w.*, c.name as category_name FROM workshops w LEFT JOIN workshop_categories c ON w.category_id = c.id ORDER BY w.start_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Workshops</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Workshop</h2>
        <a href="add_workshop.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Workshop</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Gambar</th>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Kategori</th>
                    <th>Instruktur</th>
                    <th>Tgl Mulai</th>
                    <th>Tgl Akhir</th>
                    <th>Durasi</th>
                    <th>Lokasi</th>
                    <th>Harga</th>
                    <th>Slot</th>
                    <th>Aktif</th>
                    <th>Event</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php if ($row['image']): ?>
                            <img src="../assets/images/workshops/<?= htmlspecialchars($row['image']) ?>" alt="Gambar" style="max-width:80px;max-height:60px;">
                        <?php else: ?>
                            <span class="text-muted">Tidak ada</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= htmlspecialchars($row['category_name']) ?></td>
                    <td><?= htmlspecialchars($row['instructor']) ?></td>
                    <td><?= $row['start_date'] ?></td>
                    <td><?= $row['end_date'] ?></td>
                    <td><?= htmlspecialchars($row['duration']) ?></td>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    <td>Rp <?= number_format($row['price'],0,',','.') ?></td>
                    <td><?= $row['current_participants'] ?>/<?= $row['max_participants'] ?></td>
                    <td><?= $row['is_active'] ? 'Ya' : 'Tidak' ?></td>
                    <td><?= $row['is_past_event'] ? 'Lampau' : 'Mendatang' ?></td>
                    <td>
                        <a href="edit_workshop.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus workshop ini?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="14" class="text-center">Belum ada workshop.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <a href="dashboard.php" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
</div>
</body>
</html> 