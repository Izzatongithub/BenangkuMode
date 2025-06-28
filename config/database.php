<?php
/**
 * Database Configuration for BenangkuMode
 * 
 * This file contains database connection settings and helper functions
 * for the BenangkuMode website.
 */

// Koneksi database procedural mysqli
$conn = mysqli_connect('localhost', 'root', '', 'benangkumode_db');
if (!$conn) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}

/**
 * Get database connection
 */
function getDbConnection() {
    global $conn;
    
    return $conn;
}

/**
 * Execute a query
 */
function dbQuery($sql, $params = array()) {
    define('DEBUG_MODE', true);
    try {
        $conn = getDbConnection();
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_execute($stmt);
        return $stmt;
    } catch (Exception $e) {
        if (DEBUG_MODE) {
            throw new Exception("Query failed: " . $e->getMessage());
        } else {
            throw new Exception("Database error occurred.");
        }
    }
}

/**
 * Fetch all records
 */
function dbFetchAll($sql, $params = array()) {
    $stmt = dbQuery($sql, $params);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Fetch single record
 */
function dbFetchOne($sql, $params = array()) {
    $stmt = dbQuery($sql, $params);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

/**
 * Insert record and return last insert ID
 */
function dbInsert($sql, $params = array()) {
    dbQuery($sql, $params);
    $conn = getDbConnection();
    return mysqli_insert_id($conn);
}

/**
 * Update record and return affected rows
 */
function dbUpdate($sql, $params = array()) {
    $stmt = dbQuery($sql, $params);
    $result = mysqli_stmt_affected_rows($stmt);
    return $result;
}

/**
 * Delete record and return affected rows
 */
function dbDelete($sql, $params = array()) {
    $stmt = dbQuery($sql, $params);
    $result = mysqli_stmt_affected_rows($stmt);
    return $result;
}

/**
 * Begin transaction
 */
function dbBeginTransaction() {
    $conn = getDbConnection();
    mysqli_begin_transaction($conn);
    return true;
}

/**
 * Commit transaction
 */
function dbCommit() {
    $conn = getDbConnection();
    mysqli_commit($conn);
    return true;
}

/**
 * Rollback transaction
 */
function dbRollback() {
    $conn = getDbConnection();
    mysqli_rollback($conn);
    return true;
}

/**
 * Helper function to get database instance (for backward compatibility)
 */
function db() {
    return (object) array(
        'fetchAll' => 'dbFetchAll',
        'fetchOne' => 'dbFetchOne',
        'insert' => 'dbInsert',
        'update' => 'dbUpdate',
        'delete' => 'dbDelete',
        'beginTransaction' => 'dbBeginTransaction',
        'commit' => 'dbCommit',
        'rollback' => 'dbRollback'
    );
}

/**
 * Sanitize input data
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Generate random string
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

/**
 * Format currency
 */
function formatCurrency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

/**
 * Format date
 */
function formatDate($date, $format = 'd/m/Y') {
    return date($format, strtotime($date));
}

/**
 * Format datetime
 */
function formatDateTime($datetime, $format = 'd/m/Y H:i') {
    return date($format, strtotime($datetime));
}

/**
 * Get setting value
 */
function getSetting($key, $default = null) {
    try {
        $result = dbFetchOne("SELECT setting_value FROM settings WHERE setting_key = ?", array($key));
        return $result ? $result['setting_value'] : $default;
    } catch (Exception $e) {
        return $default;
    }
}

/**
 * Set setting value
 */
function setSetting($key, $value) {
    try {
        $existing = dbFetchOne("SELECT id FROM settings WHERE setting_key = ?", array($key));
        
        if ($existing) {
            return dbUpdate("UPDATE settings SET setting_value = ?, updated_at = NOW() WHERE setting_key = ?", array($value, $key));
        } else {
            return dbInsert("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)", array($key, $value));
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Redirect to URL
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Get current URL
 */
function getCurrentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Get base URL
 */
function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['SCRIPT_NAME']);
    return $protocol . "://" . $host . $path;
}

/**
 * Upload file
 */
function uploadFile($file, $destination, $allowedTypes = array('jpg', 'jpeg', 'png', 'gif')) {
    try {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload error');
        }
        
        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileTmp = $file['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Check file type
        if (!in_array($fileExt, $allowedTypes)) {
            throw new Exception('Invalid file type');
        }
        
        // Check file size (5MB max)
        if ($fileSize > 5 * 1024 * 1024) {
            throw new Exception('File too large');
        }
        
        // Generate unique filename
        $newFileName = uniqid() . '.' . $fileExt;
        $uploadPath = $destination . '/' . $newFileName;
        
        // Create directory if not exists
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        
        // Move uploaded file
        if (move_uploaded_file($fileTmp, $uploadPath)) {
            return $newFileName;
        } else {
            throw new Exception('Failed to move uploaded file');
        }
    } catch (Exception $e) {
        if (DEBUG_MODE) {
            throw $e;
        } else {
            throw new Exception('File upload failed');
        }
    }
}

/**
 * Delete file
 */
function deleteFile($filePath) {
    if (file_exists($filePath)) {
        return unlink($filePath);
    }
    return false;
}

/**
 * Send JSON response
 */
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

/**
 * Log activity
 */
function logActivity($action, $details = '', $userId = null) {
    try {
        $userId = $userId ? $userId : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null);
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        
        dbInsert(
            "INSERT INTO activity_logs (user_id, action, details, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)",
            array($userId, $action, $details, $ip, $userAgent)
        );
    } catch (Exception $e) {
        // Silently fail for logging
    }
}
?> 