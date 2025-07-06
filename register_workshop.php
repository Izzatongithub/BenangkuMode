<?php
session_start();
require_once 'config/database.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
    exit;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Log received data for debugging
error_log("Received POST data: " . print_r($_POST, true));

// Get form data
$workshopTitle = $_POST['workshopTitle'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$age = $_POST['age'] ?? '';
$experience = $_POST['experience'] ?? '';
$specialNeeds = $_POST['specialNeeds'] ?? '';

// Log processed data
error_log("Processed data - Workshop: $workshopTitle, Name: $name, Email: $email, Phone: $phone");

// Validate required fields
if (empty($name) || empty($email) || empty($phone)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nama, email, dan nomor telepon wajib diisi']);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Format email tidak valid']);
    exit;
}

try {
    // Check if table exists
    $tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'workshop_registrations'");
    if (mysqli_num_rows($tableCheck) == 0) {
        throw new Exception('Tabel workshop_registrations tidak ditemukan');
    }
    
    // Check if user exists (for foreign key constraint)
    $userCheck = mysqli_query($conn, "SELECT id FROM users WHERE id = " . intval($_SESSION['user_id']));
    if (mysqli_num_rows($userCheck) == 0) {
        throw new Exception('User tidak ditemukan di database');
    }
    
    // Find workshop by title and check availability
    $workshopSql = "SELECT id, max_participants, current_participants, price FROM workshops WHERE title = ?";
    $workshopStmt = mysqli_prepare($conn, $workshopSql);
    if (!$workshopStmt) {
        throw new Exception('Gagal mempersiapkan query workshop: ' . mysqli_error($conn));
    }
    
    mysqli_stmt_bind_param($workshopStmt, "s", $workshopTitle);
    mysqli_stmt_execute($workshopStmt);
    $workshopResult = mysqli_stmt_get_result($workshopStmt);
    
    if (mysqli_num_rows($workshopResult) == 0) {
        throw new Exception('Workshop tidak ditemukan');
    }
    
    $workshop = mysqli_fetch_assoc($workshopResult);
    $workshopId = $workshop['id'];
    $maxParticipants = $workshop['max_participants'];
    $currentParticipants = $workshop['current_participants'];
    $workshopPrice = $workshop['price'];
    $isFree = $workshopPrice == 0;
    
    // Check if workshop is full
    if ($currentParticipants >= $maxParticipants) {
        throw new Exception('Workshop sudah penuh');
    }
    
    // Check if user already registered for this workshop
    $duplicateCheck = mysqli_query($conn, "SELECT id FROM workshop_registrations WHERE user_id = " . intval($_SESSION['user_id']) . " AND workshop_id = " . intval($workshopId));
    if (mysqli_num_rows($duplicateCheck) > 0) {
        throw new Exception('Anda sudah terdaftar untuk workshop ini');
    }
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Insert registration data
        $sql = "INSERT INTO workshop_registrations (user_id, workshop_id, participant_name, participant_email, participant_phone, participant_age, experience_level, special_needs, workshop_price, payment_status, registration_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            throw new Exception('Gagal mempersiapkan statement: ' . mysqli_error($conn));
        }
        
        // Convert empty age to NULL
        $ageValue = empty($age) ? null : intval($age);
        
        // Set payment status based on workshop type
        $paymentStatus = $isFree ? 'paid' : 'pending';
        
        mysqli_stmt_bind_param($stmt, "iisssissds", 
            $_SESSION['user_id'],
            $workshopId,
            $name,
            $email,
            $phone,
            $ageValue,
            $experience,
            $specialNeeds,
            $workshopPrice,
            $paymentStatus
        );
        
        if (!mysqli_stmt_execute($stmt)) {
            $error = mysqli_stmt_error($stmt);
            error_log("MySQL Error: " . $error);
            throw new Exception('Gagal menyimpan data pendaftaran: ' . $error);
        }
        
        $registrationId = mysqli_insert_id($conn);
        
        // Update workshop current_participants
        $updateSql = "UPDATE workshops SET current_participants = current_participants + 1 WHERE id = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        if (!$updateStmt) {
            throw new Exception('Gagal mempersiapkan update workshop: ' . mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($updateStmt, "i", $workshopId);
        if (!mysqli_stmt_execute($updateStmt)) {
            throw new Exception('Gagal mengupdate jumlah peserta: ' . mysqli_stmt_error($updateStmt));
        }
        
        // Commit transaction
        mysqli_commit($conn);
        
        // Log successful insertion
        error_log("Registration successful - ID: $registrationId, Workshop: $workshopTitle, Updated participants: " . ($currentParticipants + 1));
        
        // Prepare success message based on workshop type
        if ($isFree) {
            $successMessage = 'Pendaftaran workshop gratis berhasil! Kami akan menghubungi Anda segera.';
        } else {
            $successMessage = 'Pendaftaran berhasil! Silakan lakukan pembayaran untuk mengkonfirmasi keikutsertaan Anda.';
        }
        
        // Send success response
        echo json_encode([
            'success' => true, 
            'message' => $successMessage,
            'registration_id' => $registrationId,
            'remaining_slots' => $maxParticipants - ($currentParticipants + 1),
            'workshop_price' => $workshopPrice,
            'is_free' => $isFree,
            'payment_status' => $paymentStatus
        ]);
        
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        throw $e;
    }
    
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($workshopStmt);
    if (isset($updateStmt)) mysqli_stmt_close($updateStmt);
    
} catch (Exception $e) {
    error_log("Registration error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
}

mysqli_close($conn);
?> 