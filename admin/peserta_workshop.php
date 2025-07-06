<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || !isAdmin()) {
    header('Location: ../login.php');
    exit;
}

// Ambil daftar workshop
$workshops = [];
$res = mysqli_query($conn, "SELECT id, title FROM workshops ORDER BY start_date DESC");
while ($row = mysqli_fetch_assoc($res)) {
    $workshops[] = $row;
}

// Ambil workshop yang dipilih
$selectedWorkshop = isset($_GET['workshop_id']) ? intval($_GET['workshop_id']) : 0;
$participants = [];
if ($selectedWorkshop) {
    $sql = "SELECT u.username, u.email, wr.registration_date, wr.payment_status
            FROM workshop_registrations wr
            JOIN users u ON wr.user_id = u.id
            WHERE wr.workshop_id = ?
            ORDER BY wr.registration_date DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $selectedWorkshop);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $participants[] = $row;
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peserta per Workshop - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 16px #eee; padding: 32px; }
        h2 { text-align: center; margin-bottom: 24px; }
        .filter-form { margin-bottom: 18px; text-align: right; }
        .filter-form select { padding: 7px 14px; border-radius: 6px; border: 1px solid #ccc; font-size: 1rem; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #f8f9fa; }
        .status-pending { color: #e67e22; font-weight: bold; }
        .status-paid { color: #27ae60; font-weight: bold; }
        .status-cancelled { color: #e74c3c; font-weight: bold; }
        .no-data { text-align: center; color: #888; padding: 24px 0; }
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
                        <a class="nav-link" href="workshops.php">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Workshops
                        </a>
                        <a class="nav-link" href="verifikasi_pembayaran.php">
                            <i class="fas fa-user-check me-2"></i>Pendaftar Workshop
                        </a>
                        <a class="nav-link active" href="peserta_workshop.php">
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
                        <h2>Peserta per Workshop</h2>
                    </div>
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <form class="filter-form" method="get">
                                <label for="workshop_id">Pilih Workshop: </label>
                                <select name="workshop_id" id="workshop_id" onchange="this.form.submit()">
                                    <option value="0">-- Pilih Workshop --</option>
                                    <?php foreach ($workshops as $w): ?>
                                        <option value="<?= $w['id'] ?>" <?= $selectedWorkshop==$w['id']?'selected':''; ?>><?= htmlspecialchars($w['title']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>
                        <div class="card-body">
                            <?php if ($selectedWorkshop && count($participants) === 0): ?>
                                <div class="no-data">Belum ada peserta untuk workshop ini.</div>
                            <?php elseif ($selectedWorkshop): ?>
                            <table>
                                <tr>
                                    <th>Nama User</th>
                                    <th>Email</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Status</th>
                                </tr>
                                <?php foreach ($participants as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['username']) ?></td>
                                    <td><?= htmlspecialchars($p['email']) ?></td>
                                    <td><?= date('d M Y H:i', strtotime($p['registration_date'])) ?></td>
                                    <td class="status-<?= htmlspecialchars($p['payment_status']) ?>">
                                        <?= ucfirst($p['payment_status']) ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 