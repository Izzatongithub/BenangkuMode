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
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 0;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar p-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white">Admin Panel</h4>
                        <small class="text-white-50">BenangkuMode</small>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="users.php">
                            <i class="fas fa-users me-2"></i>Users
                        </a>
                        <a class="nav-link" href="products.php">
                            <i class="fas fa-box me-2"></i>Products
                        </a>
                        <a class="nav-link" href="comingsoon.php">
                            <i class="fas fa-clock-rotate-left me-2"></i>Add Coming Soon
                        </a>
                        <a class="nav-link active" href="workshops.php">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Workshops
                        </a>
                        <a class="nav-link" href="verifikasi_pembayaran.php">
                            <i class="fas fa-user-check me-2"></i>Pendaftar Workshop
                        </a>
                        <a class="nav-link" href="peserta_workshop.php">
                            <i class="fas fa-users me-2"></i>Peserta per Workshop
                        </a>
                        <a class="nav-link" href="destinations.php">
                            <i class="fas fa-map-marker-alt me-2"></i>Destinations
                        </a>
                        <a class="nav-link" href="orders.php">
                            <i class="fas fa-shopping-cart me-2"></i>Orders
                        </a>
                        <a class="nav-link" href="settings.php">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                        <hr class="text-white-50">
                        <a class="nav-link" href="../index.php">
                            <i class="fas fa-home me-2"></i>Back to Site
                        </a>
                        <a class="nav-link" href="../logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Daftar Workshop</h2>
                        <a href="add_workshop.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Workshop
                        </a>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">All Workshops</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
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
                                                <div class="btn-group btn-group-sm">
                                                        <a href="edit_workshop.php?id=<?php echo $row['id']; ?>" 
                                                            class="btn btn-outline-primary">
                                                                <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="POST" style="display: inline;" 
                                                            onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                                <input type="hidden" name="action" value="delete">
                                                                <button type="submit" class="btn btn-outline-danger">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                        </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="14" class="text-center">Belum ada workshop.</td></tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 