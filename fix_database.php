<?php
require_once 'config/database.php';

echo "<h2>Perbaikan Database dan Akun Admin</h2>";

try {
    $conn = getDbConnection();
    echo "<p style='color: green;'>✅ Koneksi database berhasil</p>";
    
    // Check if users table exists
    $sql_check_table = "SHOW TABLES LIKE 'users'";
    $result_check = mysqli_query($conn, $sql_check_table);
    
    if (mysqli_num_rows($result_check) > 0) {
        echo "<p style='color: green;'>✅ Tabel users ditemukan</p>";
        
        // Check if admin user exists
        $sql_check_admin = "SELECT * FROM users WHERE email = 'admin@benangkumode.com'";
        $result_admin = mysqli_query($conn, $sql_check_admin);
        
        if (mysqli_num_rows($result_admin) > 0) {
            $admin = mysqli_fetch_assoc($result_admin);
            echo "<p style='color: green;'>✅ Akun admin ditemukan</p>";
            
            // Always update admin password to ensure it works
            $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
            $sql_update_admin = "UPDATE users SET 
                                password = '$admin_password',
                                role = 'admin',
                                is_active = 1,
                                full_name = 'Administrator'
                                WHERE email = 'admin@benangkumode.com'";
            
            if (mysqli_query($conn, $sql_update_admin)) {
                echo "<p style='color: green;'>✅ Akun admin berhasil diperbaiki</p>";
            } else {
                echo "<p style='color: red;'>❌ Gagal memperbaiki akun admin: " . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Akun admin tidak ditemukan, membuat akun baru...</p>";
            
            // Create admin user
            $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
            $sql_create_admin = "INSERT INTO users (username, email, password, full_name, role, is_active) 
                                VALUES ('admin', 'admin@benangkumode.com', '$admin_password', 'Administrator', 'admin', 1)";
            
            if (mysqli_query($conn, $sql_create_admin)) {
                echo "<p style='color: green;'>✅ Akun admin berhasil dibuat</p>";
            } else {
                echo "<p style='color: red;'>❌ Gagal membuat akun admin: " . mysqli_error($conn) . "</p>";
            }
        }
        
        // Check if required tables exist
        $required_tables = ['products', 'destinations', 'order_items', 'settings', 'activity_logs'];
        
        foreach ($required_tables as $table) {
            $sql_check = "SHOW TABLES LIKE '$table'";
            $result = mysqli_query($conn, $sql_check);
            
            if (mysqli_num_rows($result) > 0) {
                echo "<p style='color: green;'>✅ Tabel $table ditemukan</p>";
            } else {
                echo "<p style='color: red;'>❌ Tabel $table tidak ditemukan</p>";
            }
        }
        
        // Create activity_logs table if not exists
        $sql_create_logs = "CREATE TABLE IF NOT EXISTS activity_logs (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT,
            action VARCHAR(100) NOT NULL,
            details TEXT,
            ip_address VARCHAR(45),
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        )";
        
        if (mysqli_query($conn, $sql_create_logs)) {
            echo "<p style='color: green;'>✅ Tabel activity_logs siap</p>";
        } else {
            echo "<p style='color: red;'>❌ Gagal membuat tabel activity_logs: " . mysqli_error($conn) . "</p>";
        }
        
        // Create settings table if not exists
        $sql_create_settings = "CREATE TABLE IF NOT EXISTS settings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            setting_key VARCHAR(100) UNIQUE NOT NULL,
            setting_value TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        if (mysqli_query($conn, $sql_create_settings)) {
            echo "<p style='color: green;'>✅ Tabel settings siap</p>";
        } else {
            echo "<p style='color: red;'>❌ Gagal membuat tabel settings: " . mysqli_error($conn) . "</p>";
        }
        
        // Insert default settings
        $default_settings = [
            'site_name' => 'BenangkuMode',
            'site_description' => 'Karya tangan yang memukau dari Pulau Lombok',
            'contact_email' => 'info@benangkumode.com',
            'contact_phone' => '+62 812-3456-7890',
            'address' => 'Lombok, Nusa Tenggara Barat'
        ];
        
        foreach ($default_settings as $key => $value) {
            $sql_insert_setting = "INSERT IGNORE INTO settings (setting_key, setting_value) VALUES ('$key', '$value')";
            mysqli_query($conn, $sql_insert_setting);
        }
        
        echo "<p style='color: green;'>✅ Pengaturan default ditambahkan</p>";
        
        // Final check
        $sql_final_check = "SELECT * FROM users WHERE email = 'admin@benangkumode.com'";
        $result_final = mysqli_query($conn, $sql_final_check);
        $admin_final = mysqli_fetch_assoc($result_final);
        
        echo "<h3>✅ Akun Admin Siap Digunakan:</h3>";
        echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<p><strong>Email:</strong> " . $admin_final['email'] . "</p>";
        echo "<p><strong>Password:</strong> admin123</p>";
        echo "<p><strong>Role:</strong> " . $admin_final['role'] . "</p>";
        echo "<p><strong>Status:</strong> " . ($admin_final['is_active'] ? 'Aktif' : 'Tidak Aktif') . "</p>";
        echo "</div>";
        
        echo "<p style='color: green; font-weight: bold;'>🎉 Database berhasil diperbaiki! Sekarang Anda bisa login sebagai admin.</p>";
        
    } else {
        echo "<p style='color: red;'>❌ Tabel users tidak ditemukan</p>";
        echo "<p>Silakan import database.sql terlebih dahulu dengan menjalankan:</p>";
        echo "<code>mysql -u username -p database_name < database.sql</code>";
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
code {
    background: #f0f0f0;
    padding: 5px;
    border-radius: 3px;
    font-family: monospace;
}
</style> 