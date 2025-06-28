# Troubleshooting Login Admin - BenangkuMode

## Masalah: Tidak Bisa Login Sebagai Admin

Jika Anda tidak bisa login sebagai admin, ikuti langkah-langkah troubleshooting berikut:

## 🔍 **Langkah 1: Pemeriksaan Database**

### 1.1 Jalankan Script Pemeriksaan
Buka browser dan akses:
```
http://localhost/BenangkuMode/check_admin.php
```

Script ini akan menampilkan:
- Status koneksi database
- Struktur tabel users
- Status akun admin
- Validasi password

### 1.2 Jalankan Script Perbaikan
Jika ada masalah, akses:
```
http://localhost/BenangkuMode/fix_database.php
```

Script ini akan:
- Memperbaiki akun admin
- Membuat tabel yang hilang
- Menambahkan pengaturan default

## 🔧 **Langkah 2: Periksa Konfigurasi Database**

### 2.1 File `config/database.php`
Pastikan konfigurasi database benar:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');  // Sesuaikan dengan username MySQL Anda
define('DB_PASS', '');      // Sesuaikan dengan password MySQL Anda
define('DB_NAME', 'benangkumode_db'); // Sesuaikan dengan nama database
```

### 2.2 Import Database
Jika database belum diimport:

1. Buka phpMyAdmin
2. Buat database baru: `benangkumode_db`
3. Import file `database.sql`

Atau via command line:
```bash
mysql -u root -p benangkumode_db < database.sql
```

## 🔑 **Langkah 3: Akun Admin Default**

### 3.1 Kredensial Login
- **Email:** `admin@benangkumode.com`
- **Password:** `admin123`

### 3.2 Jika Password Tidak Bekerja
Jalankan query SQL berikut di phpMyAdmin:

```sql
UPDATE users 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE email = 'admin@benangkumode.com';
```

Atau buat akun admin baru:

```sql
INSERT INTO users (username, email, password, full_name, role, is_active) 
VALUES ('admin', 'admin@benangkumode.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', 1);
```

## 🗄️ **Langkah 4: Periksa Struktur Tabel**

### 4.1 Tabel Users
Pastikan tabel `users` memiliki struktur:

```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 4.2 Tabel yang Diperlukan
Pastikan tabel berikut ada:
- `users`
- `products`
- `destinations`
- `order_items`
- `settings`
- `activity_logs`

## 🐛 **Langkah 5: Debug Login**

### 5.1 Periksa Error Log
Cek error log PHP di:
- XAMPP: `C:\xampp\php\logs\php_error_log`
- Linux: `/var/log/apache2/error.log`

### 5.2 Tambahkan Debug di login.php
Tambahkan kode debug sementara di `login.php`:

```php
// Tambahkan setelah line 15
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Tambahkan setelah query SELECT
echo "Query: " . $sql . "<br>";
echo "Result rows: " . mysqli_num_rows($result) . "<br>";
if ($result && mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
    echo "User found: " . $user['email'] . "<br>";
    echo "Password verify: " . (password_verify($password, $user['password']) ? 'true' : 'false') . "<br>";
}
```

## 🔒 **Langkah 6: Periksa Session**

### 6.1 Session Configuration
Pastikan session berfungsi dengan menambahkan di `login.php`:

```php
// Tambahkan setelah session_start()
echo "Session ID: " . session_id() . "<br>";
echo "Session status: " . session_status() . "<br>";
```

### 6.2 Session Directory
Pastikan direktori session dapat ditulis:
- Windows: `C:\Windows\Temp`
- Linux: `/tmp`

## 🌐 **Langkah 7: Periksa Web Server**

### 7.1 Apache/Nginx
Pastikan web server berjalan dan dapat mengakses file PHP.

### 7.2 File Permissions
Pastikan file memiliki permission yang benar:
- Windows: Tidak ada masalah permission
- Linux: `chmod 644 *.php`

## 📋 **Langkah 8: Checklist Lengkap**

- [ ] Database `benangkumode_db` sudah dibuat
- [ ] File `database.sql` sudah diimport
- [ ] Konfigurasi database di `config/database.php` benar
- [ ] Tabel `users` ada dan memiliki struktur yang benar
- [ ] Akun admin dengan email `admin@benangkumode.com` ada
- [ ] Password admin adalah `admin123`
- [ ] Role admin adalah `admin`
- [ ] Status admin adalah `is_active = 1`
- [ ] Web server (Apache) berjalan
- [ ] PHP dapat mengakses MySQL
- [ ] Session PHP berfungsi

## 🆘 **Jika Masih Bermasalah**

### 8.1 Buat Akun Admin Manual
Jalankan query ini di phpMyAdmin:

```sql
-- Hapus akun admin lama (jika ada)
DELETE FROM users WHERE email = 'admin@benangkumode.com';

-- Buat akun admin baru
INSERT INTO users (username, email, password, full_name, role, is_active) 
VALUES (
    'admin', 
    'admin@benangkumode.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'Administrator', 
    'admin', 
    1
);
```

### 8.2 Test Login Langsung
Buat file `test_login.php`:

```php
<?php
require_once 'config/database.php';

$conn = getDbConnection();
$email = 'admin@benangkumode.com';
$password = 'admin123';

$sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
    echo "User found: " . $user['email'] . "<br>";
    echo "Role: " . $user['role'] . "<br>";
    echo "Active: " . ($user['is_active'] ? 'Yes' : 'No') . "<br>";
    
    if (password_verify($password, $user['password'])) {
        echo "✅ Password valid!";
    } else {
        echo "❌ Password invalid!";
    }
} else {
    echo "❌ User not found!";
}
?>
```

## 📞 **Kontak Support**

Jika semua langkah di atas sudah dilakukan tetapi masih tidak bisa login, silakan:

1. Jalankan `check_admin.php` dan screenshot hasilnya
2. Periksa error log PHP
3. Pastikan semua checklist sudah dicentang
4. Coba buat akun admin baru dengan email yang berbeda

---

**Catatan:** Setelah berhasil login, hapus file `check_admin.php` dan `fix_database.php` untuk keamanan. 