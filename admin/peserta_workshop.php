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
    </style>
</head>
<body>
    <div class="container">
        <h2>Peserta per Workshop</h2>
        <form class="filter-form" method="get">
            <label for="workshop_id">Pilih Workshop: </label>
            <select name="workshop_id" id="workshop_id" onchange="this.form.submit()">
                <option value="0">-- Pilih Workshop --</option>
                <?php foreach ($workshops as $w): ?>
                    <option value="<?= $w['id'] ?>" <?= $selectedWorkshop==$w['id']?'selected':''; ?>><?= htmlspecialchars($w['title']) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
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
</body>
</html> 