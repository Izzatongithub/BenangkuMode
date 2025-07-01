<?php
session_start();
require_once 'config/database.php';

// Ambil email user
$voter_email = $_SESSION['user_email'] ?? ('guest_' . uniqid() . '@guest.com');
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if ($product_id > 0) {
    $conn = getDbConnection();
    $stmt = mysqli_prepare($conn, "DELETE FROM product_votes WHERE product_id = ? AND voter_email = ?");
    mysqli_stmt_bind_param($stmt, 'is', $product_id, $voter_email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header('Location: comingsoon.php?cancel=success');
exit; 