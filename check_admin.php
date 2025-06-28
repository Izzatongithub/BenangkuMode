<?php
require_once 'config/database.php';

echo "<h2>Pemeriksaan Database dan Akun Admin</h2>";

try {
    $conn = getDbConnection();
    echo "<p style='color: green;'>✅ Koneksi database berhasil</p>";
    
    // Check if users table exists
    $sql_check_table = "SHOW TABLES LIKE 'users'";
    $result_check = mysqli_query($conn, $sql_check_table);
    
    if (mysqli_num_rows($result_check) > 0) {
        echo "<p style='color: green;'>✅ Tabel users ditemukan</p>";
        
        // Check table structure
        $sql_structure = "DESCRIBE users";
        $result_structure = mysqli_query($conn, $sql_structure);
        
        echo "<h3>Struktur Tabel Users:</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        
        while ($row = mysqli_fetch_assoc($result_structure)) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Check if admin user exists
        $sql_check_admin = "SELECT * FROM users WHERE email = 'admin@benangkumode.com'";
        $result_admin = mysqli_query($conn, $sql_check_admin);
        
        if (mysqli_num_rows($result_admin) > 0) {
            $admin = mysqli_fetch_assoc($result_admin);
            echo "<p style='color: green;'>✅ Akun admin ditemukan</p>";
            echo "<p><strong>Email:</strong> " . $admin['email'] . "</p>";
            echo "<p><strong>Nama:</strong> " . $admin['full_name'] . "</p>";
            echo "<p><strong>Role:</strong> " . $admin['role'] . "</p>";
            echo "<p><strong>Status:</strong> " . ($admin['is_active'] ? 'Aktif' : 'Tidak Aktif') . "</p>";
            
            // Test password
            $test_password = 'admin123';
            if (password_verify($test_password, $admin['password'])) {
                echo "<p style='color: green;'>✅ Password admin valid</p>";
            } else {
                echo "<p style='color: red;'>❌ Password admin tidak valid</p>";
                
                // Fix password
                $new_password = password_hash($test_password, PASSWORD_DEFAULT);
                $sql_fix_password = "UPDATE users SET password = '$new_password' WHERE email = 'admin@benangkumode.com'";
                
                if (mysqli_query($conn, $sql_fix_password)) {
                    echo "<p style='color: green;'>✅ Password admin berhasil diperbaiki</p>";
                } else {
                    echo "<p style='color: red;'>❌ Gagal memperbaiki password admin</p>";
                }
            }
        } else {
            echo "<p style='color: red;'>❌ Akun admin tidak ditemukan</p>";
            
            // Create admin user
            $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
            $sql_create_admin = "INSERT INTO users (username, email, password, full_name, role, is_active) 
                                VALUES ('admin', 'admin@benangkumode.com', '$admin_password', 'Administrator', 'admin', 1)";
            
            if (mysqli_query($conn, $sql_create_admin)) {
                echo "<p style='color: green;'>✅ Akun admin berhasil dibuat</p>";
                echo "<p><strong>Email:</strong> admin@benangkumode.com</p>";
                echo "<p><strong>Password:</strong> admin123</p>";
            } else {
                echo "<p style='color: red;'>❌ Gagal membuat akun admin: " . mysqli_error($conn) . "</p>";
            }
        }
        
        // Check all users
        $sql_all_users = "SELECT id, username, email, full_name, role, is_active FROM users";
        $result_all = mysqli_query($conn, $sql_all_users);
        
        echo "<h3>Semua Users:</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Nama</th><th>Role</th><th>Status</th></tr>";
        
        while ($user = mysqli_fetch_assoc($result_all)) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . $user['username'] . "</td>";
            echo "<td>" . $user['email'] . "</td>";
            echo "<td>" . $user['full_name'] . "</td>";
            echo "<td>" . $user['role'] . "</td>";
            echo "<td>" . ($user['is_active'] ? 'Aktif' : 'Tidak Aktif') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } else {
        echo "<p style='color: red;'>❌ Tabel users tidak ditemukan</p>";
        echo "<p>Silakan import database.sql terlebih dahulu</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
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
table {
    background: white;
    margin: 10px 0;
}
th {
    background: #f0f0f0;
    padding: 8px;
}
td {
    padding: 8px;
}
</style> 