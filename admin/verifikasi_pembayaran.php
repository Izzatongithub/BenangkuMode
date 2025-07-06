<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || !isAdmin()) {
    header('Location: ../login.php');
    exit;
}

$popup = null;

// Konfirmasi pembayaran
if (isset($_POST['action'], $_POST['registration_id'])) {
    $registration_id = intval($_POST['registration_id']);
    if ($_POST['action'] === 'confirm') {
        $stmt = $conn->prepare("UPDATE workshop_registration SET payment_status='paid', payment_date=NOW() WHERE id=?");
        $stmt->bind_param('i', $registration_id);
        if ($stmt->execute()) {
            $popup = ['type' => 'success', 'msg' => 'Pembayaran berhasil dikonfirmasi!'];
        } else {
            $popup = ['type' => 'error', 'msg' => 'Gagal konfirmasi pembayaran.'];
        }
        $stmt->close();
    } elseif ($_POST['action'] === 'reject') {
        $stmt = $conn->prepare("UPDATE workshop_registration SET payment_status='cancelled' WHERE id=?");
        $stmt->bind_param('i', $registration_id);
        if ($stmt->execute()) {
            $popup = ['type' => 'success', 'msg' => 'Pembayaran ditolak. Status pendaftaran dibatalkan.'];
        } else {
            $popup = ['type' => 'error', 'msg' => 'Gagal menolak pembayaran.'];
        }
        $stmt->close();
    }
}

// Ambil filter status dari GET
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Siapkan query sesuai filter
$where = '';
if ($statusFilter !== 'all') {
    $where = "WHERE wr.payment_status='" . mysqli_real_escape_string($conn, $statusFilter) . "'";
}

$sql = "SELECT wr.id, u.username, u.email as user_email, w.title as workshop_title, wr.registration_date, wr.payment_proof, wr.payment_status
        FROM workshop_registrations wr
        JOIN workshops w ON wr.workshop_id = w.id
        JOIN users u ON wr.user_id = u.id
        $where
        ORDER BY wr.registration_date DESC";
$result = $conn->query($sql);
$registrations = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Pembayaran Workshop - Admin</title>
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
        .proof-img { max-width: 80px; max-height: 80px; border-radius: 6px; }
        .btn-action { padding: 6px 16px; border-radius: 6px; border: none; cursor: pointer; font-weight: 500; }
        .btn-confirm { background: #27ae60; color: #fff; }
        .btn-confirm:hover { background: #219150; }
        .btn-reject { background: #e74c3c; color: #fff; margin-left: 6px; }
        .btn-reject:hover { background: #c0392b; }
        .status-pending { color: #e67e22; font-weight: bold; }
        .status-paid { color: #27ae60; font-weight: bold; }
        .status-cancelled { color: #e74c3c; font-weight: bold; }
        .no-data { text-align: center; color: #888; padding: 24px 0; }
        /* Popup modal */
        .modal-bg { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.3); align-items: center; justify-content: center; }
        .modal-content { background: #fff; border-radius: 10px; padding: 32px 24px; min-width: 320px; text-align: center; box-shadow: 0 4px 24px rgba(0,0,0,0.12); }
        .modal-content.success { border-left: 6px solid #27ae60; }
        .modal-content.error { border-left: 6px solid #e74c3c; }
        .modal-content .close-btn { margin-top: 18px; background: #667eea; color: #fff; border: none; border-radius: 5px; padding: 7px 18px; cursor: pointer; }
        .modal-content .close-btn:hover { background: #5a6fd8; }

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
                        <a class="nav-link active" href="verifikasi_pembayaran.php">
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
                        <h2 class="mb-4">Verifikasi Pembayaran Workshop</h2>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <form class="m-0" method="get">
                                <label for="status" class="me-2 mb-0">Filter Status: </label>
                                <select name="status" id="status" onchange="this.form.submit()" class="form-select d-inline-block w-auto">
                                    <option value="all" <?= $statusFilter==='all'?'selected':''; ?>>Semua</option>
                                    <option value="pending" <?= $statusFilter==='pending'?'selected':''; ?>>Pending</option>
                                    <option value="paid" <?= $statusFilter==='paid'?'selected':''; ?>>Paid</option>
                                    <option value="cancelled" <?= $statusFilter==='cancelled'?'selected':''; ?>>Cancelled</option>
                                </select>
                            </form>
                         </div>
                        <div class="card-body">
                           <?php if (count($registrations) === 0): ?>
                                <div class="no-data">Tidak ada pendaftar workshop.</div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table>
                                        <tr>
                                            <th>Nama User</th>
                                            <th>Email</th>
                                            <th>Workshop</th>
                                            <th>Tanggal Daftar</th>
                                            <th>Bukti Pembayaran</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    <?php foreach ($registrations as $reg): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($reg['username']) ?></td>
                                            <td><?= htmlspecialchars($reg['user_email']) ?></td>
                                            <td><?= htmlspecialchars($reg['workshop_title']) ?></td>
                                            <td><?= date('d M Y H:i', strtotime($reg['registration_date'])) ?></td>
                                            <td>
                                                <?php if ($reg['payment_proof']): ?>
                                                    <a href="../<?= htmlspecialchars($reg['payment_proof']) ?>" target="_blank">
                                                        <img src="../<?= htmlspecialchars($reg['payment_proof']) ?>" class="proof-img" alt="Bukti Pembayaran">
                                                    </a>
                                                <?php else: ?>
                                                    <span style="color:#aaa;">Belum ada</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="status-<?= htmlspecialchars($reg['payment_status']) ?>">
                                                <?= ucfirst($reg['payment_status']) ?>
                                            </td>
                                            <td>
                                                <?php if ($reg['payment_status'] === 'pending'): ?>
                                                    <form method="post" style="display:inline;" onsubmit="return confirm('Konfirmasi pembayaran ini?')">
                                                    <input type="hidden" name="registration_id" value="<?= $reg['id'] ?>">
                                                    <input type="hidden" name="action" value="confirm">
                                                        <button type="submit" class="btn-action btn-confirm">Konfirmasi</button>
                                                    </form>
                                                    <form method="post" style="display:inline;" onsubmit="return confirm('Tolak pembayaran ini?')">
                                                        <input type="hidden" name="registration_id" value="<?= $reg['id'] ?>">
                                                        <input type="hidden" name="action" value="reject">
                                                        <button type="submit" class="btn-action btn-reject">Tolak</button>
                                                    </form>
                                                <?php else: ?>
                                                    <span style="color:#aaa;">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- Popup Modal -->
                    <div class="modal-bg" id="popupModal">
                        <div class="modal-content <?php if($popup) echo $popup['type']; ?>">
                            <div id="popupMsg"><?php if($popup) echo htmlspecialchars($popup['msg']); ?></div>
                            <button class="close-btn" onclick="closeModal()">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function closeModal() {
        document.getElementById('popupModal').style.display = 'none';
    }
    <?php if ($popup): ?>
        document.getElementById('popupModal').style.display = 'flex';
    <?php endif; ?>
    </script>
</body>
</html> 