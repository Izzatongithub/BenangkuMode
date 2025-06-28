# Solusi Masalah Login Admin - BenangkuMode

## 🔧 **Langkah-langkah Perbaikan**

### Langkah 1: Jalankan Script Debug
Buka browser dan akses:
```
http://localhost/BenangkuMode/debug_login.php
```

Script ini akan menampilkan status koneksi database dan akun admin.

### Langkah 2: Jalankan Script Perbaikan
Jika ada masalah, akses:
```
http://localhost/BenangkuMode/fix_login.php
```

Script ini akan:
- Menghapus akun admin lama
- Membuat akun admin baru
- Memperbaiki password
- Membuat tabel yang diperlukan
- Test login process

### Langkah 3: Coba Login
Setelah menjalankan script perbaikan, coba login dengan:
- **Email:** `admin@benangkumode.com`
- **Password:** `admin123`

## 🐛 **Kemungkinan Penyebab Masalah**

### 1. Password Hash Tidak Valid
- Password di database mungkin tidak di-hash dengan benar
- Hash password mungkin rusak atau tidak sesuai

### 2. Session Tidak Berfungsi
- Session PHP tidak dapat disimpan
- Permission session directory tidak benar

### 3. Database Connection
- Koneksi database tidak stabil
- Query tidak berhasil dieksekusi

### 4. File Helper Tidak Load
- File `includes/helpers.php` tidak ditemukan
- Function `isLoggedIn()` atau `isAdmin()` tidak tersedia

## 🔍 **Cara Debug Manual**

### 1. Periksa Database Langsung
Buka phpMyAdmin dan jalankan query:
```sql
SELECT * FROM users WHERE email = 'admin@benangkumode.com';
```

### 2. Test Password Hash
Jalankan query ini di phpMyAdmin:
```sql
SELECT 
    email,
    password,
    CASE 
        WHEN password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
        THEN 'Valid Hash' 
        ELSE 'Invalid Hash' 
    END as hash_status
FROM users 
WHERE email = 'admin@benangkumode.com';
```

### 3. Buat Akun Admin Manual
Jalankan query ini di phpMyAdmin:
```sql
-- Hapus akun admin lama
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

## 🛠️ **Perbaikan File**

### 1. Periksa File `config/database.php`
Pastikan file berisi:
```php
<?php
// Konfigurasi database
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'benangkumode_db';

// Koneksi database menggunakan prosedur mysqli
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Cek koneksi
if (!$conn) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}

// Set charset ke UTF-8
mysqli_set_charset($conn, "utf8");

// Fungsi untuk mendapatkan koneksi database
function getDbConnection() {
    global $conn;
    return $conn;
}
?>
```

### 2. Periksa File `includes/helpers.php`
Pastikan file ada dan berisi fungsi `isLoggedIn()` dan `isAdmin()`.

### 3. Periksa File `login.php`
Pastikan file memanggil helper:
```php
require_once 'config/database.php';
require_once 'includes/helpers.php';
```

## 🔒 **Perbaikan Session**

### 1. Periksa Session Directory
- Windows: `C:\Windows\Temp`
- Linux: `/tmp`

### 2. Test Session
Tambahkan kode ini di `login.php` untuk debug:
```php
// Tambahkan setelah session_start()
echo "Session ID: " . session_id() . "<br>";
echo "Session Status: " . session_status() . "<br>";
```

## 🌐 **Perbaikan Web Server**

### 1. Restart Apache
- XAMPP: Stop dan Start Apache
- Linux: `sudo service apache2 restart`

### 2. Clear Browser Cache
- Clear cache dan cookies browser
- Coba browser lain

### 3. Periksa Error Log
- XAMPP: `C:\xampp\apache\logs\error.log`
- Linux: `/var/log/apache2/error.log`

## ✅ **Checklist Lengkap**

- [ ] Database `benangkumode_db` ada
- [ ] Tabel `users` ada dengan struktur yang benar
- [ ] Akun admin dengan email `admin@benangkumode.com` ada
- [ ] Password admin adalah `admin123`
- [ ] Role admin adalah `admin`
- [ ] Status admin adalah `is_active = 1`
- [ ] File `config/database.php` berisi koneksi yang benar
- [ ] File `includes/helpers.php` ada dan berfungsi
- [ ] File `login.php` memanggil helper
- [ ] Session PHP berfungsi
- [ ] Web server (Apache) berjalan
- [ ] Tidak ada error di log

## 🆘 **Jika Masih Bermasalah**

1. **Jalankan script perbaikan:**
   ```
   http://localhost/BenangkuMode/fix_login.php
   ```

2. **Buat akun admin baru dengan email berbeda:**
   ```sql
   INSERT INTO users (username, email, password, full_name, role, is_active) 
   VALUES ('admin2', 'admin2@benangkumode.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', 1);
   ```

3. **Test dengan kredensial baru:**
   - Email: `admin2@benangkumode.com`
   - Password: `admin123`

4. **Periksa error log PHP dan Apache**

---

**Catatan:** Setelah berhasil login, hapus file `debug_login.php` dan `fix_login.php` untuk keamanan. 