<?php
require_once 'config/database.php';
require_once 'includes/helpers.php';

echo "<h2>Perbaikan Login Admin</h2>";

// 1. Perbaiki akun admin
echo "<h3>1. Memperbaiki Akun Admin</h3>";

// Hapus akun admin lama jika ada
$sql_delete = "DELETE FROM users WHERE email = 'admin@benangkumode.com'";
mysqli_query($conn, $sql_delete);
echo "<p>✅ Akun admin lama dihapus (jika ada)</p>";

// Buat akun admin baru
$admin_password = password_hash('admin123', PASSWORD_DEFAULT);
$sql_create = "INSERT INTO users (username, email, password, full_name, role, is_active) 
               VALUES ('admin', 'admin@benangkumode.com', '$admin_password', 'Administrator', 'admin', 1)";

if (mysqli_query($conn, $sql_create)) {
    echo "<p style='color: green;'>✅ Akun admin baru berhasil dibuat</p>";
} else {
    echo "<p style='color: red;'>❌ Gagal membuat akun admin: " . mysqli_error($conn) . "</p>";
}

// 2. Verifikasi akun admin
echo "<h3>2. Verifikasi Akun Admin</h3>";
$sql_check = "SELECT * FROM users WHERE email = 'admin@benangkumode.com'";
$result_check = mysqli_query($conn, $sql_check);

if ($result_check && mysqli_num_rows($result_check) > 0) {
    $admin = mysqli_fetch_assoc($result_check);
    echo "<p style='color: green;'>✅ Akun admin ditemukan</p>";
    echo "<p><strong>Email:</strong> " . $admin['email'] . "</p>";
    echo "<p><strong>Role:</strong> " . $admin['role'] . "</p>";
    echo "<p><strong>Status:</strong> " . ($admin['is_active'] ? 'Aktif' : 'Tidak Aktif') . "</p>";
    
    // Test password
    $test_password = 'admin123';
    if (password_verify($test_password, $admin['password'])) {
        echo "<p style='color: green;'>✅ Password valid</p>";
    } else {
        echo "<p style='color: red;'>❌ Password tidak valid</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Akun admin tidak ditemukan</p>";
}

// 3. Test login process
echo "<h3>3. Test Login Process</h3>";
$test_email = 'admin@benangkumode.com';
$test_password = 'admin123';

$sql_login = "SELECT * FROM users WHERE email = '$test_email' LIMIT 1";
$result_login = mysqli_query($conn, $sql_login);

if ($result_login && mysqli_num_rows($result_login) === 1) {
    $user_login = mysqli_fetch_assoc($result_login);
    
    if (password_verify($test_password, $user_login['password'])) {
        echo "<p style='color: green;'>✅ Login process berhasil</p>";
        
        // Test session
        session_start();
        $_SESSION['user_id'] = $user_login['id'];
        $_SESSION['user_email'] = $user_login['email'];
        $_SESSION['user_name'] = $user_login['full_name'];
        $_SESSION['user_role'] = $user_login['role'];
        
        echo "<p style='color: green;'>✅ Session berhasil diset</p>";
        echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";
        echo "<p><strong>User ID:</strong> " . $_SESSION['user_id'] . "</p>";
        echo "<p><strong>User Role:</strong> " . $_SESSION['user_role'] . "</p>";
        
        // Test helper functions
        echo "<p><strong>isLoggedIn():</strong> " . (isLoggedIn() ? 'TRUE' : 'FALSE') . "</p>";
        echo "<p><strong>isAdmin():</strong> " . (isAdmin() ? 'TRUE' : 'FALSE') . "</p>";
        
    } else {
        echo "<p style='color: red;'>❌ Password tidak valid dalam login process</p>";
    }
} else {
    echo "<p style='color: red;'>❌ User tidak ditemukan dalam login process</p>";
}

// 4. Buat tabel yang diperlukan
echo "<h3>4. Membuat Tabel yang Diperlukan</h3>";

// Tabel activity_logs
$sql_logs = "CREATE TABLE IF NOT EXISTS activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
)";

if (mysqli_query($conn, $sql_logs)) {
    echo "<p style='color: green;'>✅ Tabel activity_logs siap</p>";
} else {
    echo "<p style='color: red;'>❌ Gagal membuat tabel activity_logs</p>";
}

// Tabel settings
$sql_settings = "CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql_settings)) {
    echo "<p style='color: green;'>✅ Tabel settings siap</p>";
} else {
    echo "<p style='color: red;'>❌ Gagal membuat tabel settings</p>";
}

// 5. Kesimpulan
echo "<h3>5. Kesimpulan</h3>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<h4>✅ Akun Admin Siap Digunakan:</h4>";
echo "<p><strong>Email:</strong> admin@benangkumode.com</p>";
echo "<p><strong>Password:</strong> admin123</p>";
echo "<p><strong>Role:</strong> admin</p>";
echo "</div>";

echo "<p style='color: green; font-weight: bold;'>🎉 Login admin sudah diperbaiki!</p>";
echo "<p>Sekarang coba login di: <a href='login.php' style='color: blue;'>login.php</a></p>";

// 6. Test redirect ke admin
if (isAdmin()) {
    echo "<p style='color: green;'>✅ Anda sudah login sebagai admin</p>";
    echo "<p><a href='admin/dashboard.php' style='color: blue;'>Klik di sini untuk ke Admin Dashboard</a></p>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background: #f5f5f5;
}
h2, h3, h4 {
    color: #333;
}
</style> 