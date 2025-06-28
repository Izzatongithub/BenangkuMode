<?php
require_once 'config/database.php';
require_once 'includes/helpers.php';

echo "<h2>Debug Login Admin</h2>";

// Test koneksi database
echo "<h3>1. Test Koneksi Database</h3>";
if ($conn) {
    echo "<p style='color: green;'>✅ Koneksi database berhasil</p>";
} else {
    echo "<p style='color: red;'>❌ Koneksi database gagal</p>";
    exit();
}

// Test query users table
echo "<h3>2. Test Query Tabel Users</h3>";
$sql_test = "SELECT * FROM users WHERE email = 'admin@benangkumode.com'";
$result_test = mysqli_query($conn, $sql_test);

if ($result_test) {
    echo "<p style='color: green;'>✅ Query berhasil</p>";
    
    if (mysqli_num_rows($result_test) > 0) {
        $user = mysqli_fetch_assoc($result_test);
        echo "<p style='color: green;'>✅ User admin ditemukan</p>";
        echo "<p><strong>Email:</strong> " . $user['email'] . "</p>";
        echo "<p><strong>Role:</strong> " . $user['role'] . "</p>";
        echo "<p><strong>Is Active:</strong> " . ($user['is_active'] ? 'Yes' : 'No') . "</p>";
        
        // Test password verification
        echo "<h3>3. Test Password Verification</h3>";
        $test_password = 'admin123';
        $password_verify_result = password_verify($test_password, $user['password']);
        
        echo "<p><strong>Test Password:</strong> $test_password</p>";
        echo "<p><strong>Password Verify Result:</strong> " . ($password_verify_result ? 'TRUE' : 'FALSE') . "</p>";
        
        if ($password_verify_result) {
            echo "<p style='color: green;'>✅ Password valid!</p>";
        } else {
            echo "<p style='color: red;'>❌ Password tidak valid!</p>";
            
            // Update password
            $new_hash = password_hash($test_password, PASSWORD_DEFAULT);
            $sql_update = "UPDATE users SET password = '$new_hash' WHERE email = 'admin@benangkumode.com'";
            if (mysqli_query($conn, $sql_update)) {
                echo "<p style='color: green;'>✅ Password berhasil diupdate</p>";
            } else {
                echo "<p style='color: red;'>❌ Gagal update password</p>";
            }
        }
        
    } else {
        echo "<p style='color: red;'>❌ User admin tidak ditemukan</p>";
        
        // Create admin user
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $sql_create = "INSERT INTO users (username, email, password, full_name, role, is_active) 
                      VALUES ('admin', 'admin@benangkumode.com', '$admin_password', 'Administrator', 'admin', 1)";
        
        if (mysqli_query($conn, $sql_create)) {
            echo "<p style='color: green;'>✅ User admin berhasil dibuat</p>";
        } else {
            echo "<p style='color: red;'>❌ Gagal membuat user admin</p>";
        }
    }
} else {
    echo "<p style='color: red;'>❌ Query gagal</p>";
}

// Test session
echo "<h3>4. Test Session</h3>";
session_start();
echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";

// Test login process
echo "<h3>5. Test Login Process</h3>";
$test_email = 'admin@benangkumode.com';
$test_password = 'admin123';

$sql_login = "SELECT * FROM users WHERE email = '$test_email' LIMIT 1";
$result_login = mysqli_query($conn, $sql_login);

if ($result_login && mysqli_num_rows($result_login) === 1) {
    $user_login = mysqli_fetch_assoc($result_login);
    
    if (password_verify($test_password, $user_login['password'])) {
        echo "<p style='color: green;'>✅ Login process berhasil</p>";
        
        // Simulate session setting
        $_SESSION['user_id'] = $user_login['id'];
        $_SESSION['user_email'] = $user_login['email'];
        $_SESSION['user_name'] = $user_login['full_name'];
        $_SESSION['user_role'] = $user_login['role'];
        
        echo "<p style='color: green;'>✅ Session berhasil diset</p>";
        echo "<p><strong>isLoggedIn():</strong> " . (isLoggedIn() ? 'TRUE' : 'FALSE') . "</p>";
        echo "<p><strong>isAdmin():</strong> " . (isAdmin() ? 'TRUE' : 'FALSE') . "</p>";
        
    } else {
        echo "<p style='color: red;'>❌ Password tidak valid dalam login process</p>";
    }
} else {
    echo "<p style='color: red;'>❌ User tidak ditemukan dalam login process</p>";
}

echo "<h3>6. Kesimpulan</h3>";
echo "<p>Jika semua test berhasil (hijau), login seharusnya berfungsi.</p>";
echo "<p>Coba akses: <a href='login.php'>login.php</a></p>";
?>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background: #f5f5f5;
}
h2, h3 {
    color: #333;
}
</style> 