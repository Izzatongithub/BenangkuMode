# Sistem Login dan Registrasi BenangkuMode

## Overview
Sistem autentikasi lengkap untuk website BenangkuMode yang mencakup login, registrasi, lupa password, dan manajemen profil user. Menggunakan gaya kode procedural yang sederhana dan mudah dipahami.

## Fitur yang Tersedia

### 1. Login (`login.php`)
- Form login dengan email dan password
- Validasi input
- Redirect berdasarkan role (admin/customer)
- Remember me functionality
- Social login buttons (placeholder)
- Link ke lupa password dan registrasi

### 2. Registrasi (`register.php`)
- Form registrasi lengkap
- Validasi email unik
- Password strength checker (JavaScript)
- Konfirmasi password
- Terms and conditions checkbox
- Newsletter subscription option
- Auto-generate username

### 3. Lupa Password (`forgot-password.php`)
- Form untuk request reset password
- Generate reset token
- Email notification (placeholder)
- Security: tidak reveal jika email ada atau tidak

### 4. Reset Password (`reset-password.php`)
- Validasi token dari URL
- Form untuk password baru
- Password strength checker (JavaScript)
- Auto-clear token setelah reset

### 5. Logout (`logout.php`)
- Destroy session
- Clear cookies
- Log activity
- Redirect ke home

### 6. Profil User (`profile.php`)
- View dan edit informasi profil
- Update password (opsional)
- Form validation
- Session update

## Database Structure

### File Database Lengkap
Sistem ini menggunakan satu file SQL lengkap: `database_complete.sql`

File ini mencakup:
- Struktur database utama website
- Sistem autentikasi lengkap
- Tabel untuk semua fitur website
- Data sample dan konfigurasi default
- Indexes untuk performa optimal

### Tabel Utama untuk Autentikasi

#### Tabel `users`
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
    reset_token VARCHAR(255) NULL,
    reset_expiry TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### Tabel `activity_logs`
```sql
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
```

## Setup dan Instalasi

### 1. Import Database Lengkap
```bash
# Import database lengkap dengan satu file
mysql -u root -p < database_complete.sql
```

### 2. Konfigurasi Database
Edit file `config/database.php` sesuai dengan konfigurasi database Anda:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'benangkumode_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 3. Default Admin Account
Setelah import database, akun admin default tersedia:
- Email: `admin@benangkumode.com`
- Password: `admin123`

### 4. Data Sample yang Tersedia
File database lengkap juga menyertakan:
- Kategori produk sample
- Kategori workshop sample
- Kategori destinasi wisata sample
- Kategori galeri sample
- Pengaturan website default

## Gaya Kode

### Pendekatan Procedural
Sistem ini menggunakan gaya kode procedural yang sederhana:

```php
// Database functions
$user = dbFetchOne("SELECT * FROM users WHERE id = ?", array($userId));
$result = dbInsert("INSERT INTO users (...) VALUES (...)", array($params));
$affected = dbUpdate("UPDATE users SET ... WHERE id = ?", array($params));

// Helper functions
if (isLoggedIn()) {
    // User is logged in
}

if (isAdmin()) {
    // User is admin
}

logActivity('action', 'details');
```

### JavaScript Style
Menggunakan JavaScript ES5 yang kompatibel dengan browser lama:

```javascript
// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    var password = this.value;
    var strengthDiv = document.getElementById('passwordStrength');
    
    var strength = 0;
    var message = '';
    var className = '';
    
    // Check password strength
    if (password.length >= 6) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    
    // Set strength level
    if (strength < 2) {
        message = 'Lemah';
        className = 'strength-weak';
    } else if (strength < 4) {
        message = 'Sedang';
        className = 'strength-medium';
    } else {
        message = 'Kuat';
        className = 'strength-strong';
    }
    
    strengthDiv.textContent = 'Kekuatan password: ' + message;
    strengthDiv.className = 'password-strength ' + className;
});
```

## Security Features

### 1. Password Security
- Password hashing menggunakan `password_hash()` dengan `PASSWORD_DEFAULT`
- Password strength validation
- Minimum 6 karakter
- Password confirmation

### 2. Session Management
- Secure session handling
- Session timeout
- CSRF protection (basic)
- Secure logout dengan cookie cleanup

### 3. Input Validation
- Email validation
- Input sanitization
- SQL injection prevention dengan prepared statements
- XSS prevention

### 4. Password Reset Security
- Secure token generation
- Token expiration (1 jam)
- One-time use tokens
- No email enumeration

## File Structure

```
BenangkuMode/
├── login.php              # Halaman login
├── register.php           # Halaman registrasi
├── forgot-password.php    # Halaman lupa password
├── reset-password.php     # Halaman reset password
├── logout.php             # Proses logout
├── profile.php            # Halaman profil user
├── database_complete.sql  # Database lengkap dengan auth
├── config/
│   └── database.php       # Konfigurasi database dan helper functions
└── AUTH_README.md         # Dokumentasi ini
```

## Helper Functions

### Database Functions
```php
dbFetchOne($sql, $params)      # Fetch single record
dbFetchAll($sql, $params)      # Fetch all records
dbInsert($sql, $params)        # Insert record
dbUpdate($sql, $params)        # Update record
dbDelete($sql, $params)        # Delete record
dbBeginTransaction()           # Begin transaction
dbCommit()                     # Commit transaction
dbRollback()                   # Rollback transaction
```

### Authentication Functions
```php
isLoggedIn()           # Check if user is logged in
isAdmin()              # Check if user is admin
redirect($url)         # Redirect to URL
```

### Utility Functions
```php
sanitize($data)                # Sanitize input data
validateEmail($email)          # Validate email format
generateRandomString($length)  # Generate random string
formatDate($date)              # Format date
logActivity($action, $details) # Log user activity
```

## Usage Examples

### Check Login Status
```php
<?php
session_start();
require_once 'config/database.php';

if (isLoggedIn()) {
    echo "Welcome, " . $_SESSION['user_name'];
} else {
    echo "Please login";
}
?>
```

### Protect Admin Pages
```php
<?php
session_start();
require_once 'config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}
?>
```

### Database Operations
```php
// Get user data
$user = dbFetchOne("SELECT * FROM users WHERE id = ?", array($userId));

// Insert new record
$newId = dbInsert("INSERT INTO users (name, email) VALUES (?, ?)", array($name, $email));

// Update record
$affected = dbUpdate("UPDATE users SET name = ? WHERE id = ?", array($newName, $userId));

// Log activity
logActivity('page_view', 'User viewed products page');
```

## Database Features

### Tabel yang Tersedia
1. **users** - Manajemen user dan autentikasi
2. **activity_logs** - Log aktivitas user
3. **product_categories** - Kategori produk
4. **products** - Data produk
5. **coming_soon_products** - Produk yang akan datang
6. **product_votes** - Voting produk
7. **workshop_categories** - Kategori workshop
8. **workshops** - Data workshop
9. **workshop_registrations** - Registrasi workshop
10. **destination_categories** - Kategori destinasi wisata
11. **destinations** - Data destinasi wisata
12. **gallery_categories** - Kategori galeri
13. **gallery_images** - Data gambar galeri
14. **newsletter_subscribers** - Subscriber newsletter
15. **contact_messages** - Pesan kontak
16. **orders** - Data pesanan (e-commerce)
17. **order_items** - Item pesanan ⭐ **DIPERBAIKI**
    - **FIXED**: Struktur tabel dengan default values
    - **FIXED**: Trigger otomatis untuk subtotal
    - **FIXED**: Foreign key constraints yang robust
    - **FIXED**: Error handling yang lebih baik
18. **settings** - Pengaturan website

### Indexes untuk Performa
- Indexes untuk pencarian user
- Indexes untuk reset token
- Indexes untuk kategori dan status
- Indexes untuk tanggal dan aktivitas

## Customization

### 1. Email Configuration
Untuk implementasi email reset password, edit `forgot-password.php`:
```php
// Replace placeholder with actual email sending
$to = $user['email'];
$subject = "Reset Password - BenangkuMode";
$message = "Click here to reset your password: " . getBaseUrl() . "reset-password.php?token=" . $resetToken;
mail($to, $subject, $message);
```

### 2. Social Login
Implementasi social login dengan OAuth providers:
- Google OAuth
- Facebook Login
- Twitter OAuth

### 3. Additional Security
- Implement rate limiting
- Add CAPTCHA for login/register
- Enable two-factor authentication
- Add IP blocking for failed attempts

## Troubleshooting

### Common Issues

1. **Session not working**
   - Check `session_start()` is called
   - Verify PHP session configuration
   - Check file permissions

2. **Database connection failed**
   - Verify database credentials in `config/database.php`
   - Check if database exists
   - Ensure MySQL service is running

3. **Password reset not working**
   - Check if `reset_token` and `reset_expiry` columns exist
   - Verify email configuration
   - Check token expiration time

4. **Login redirect loop**
   - Check `isLoggedIn()` function
   - Verify session variables are set correctly
   - Check for conflicting redirects

## Browser Compatibility

### JavaScript
- ES5 compatible (no arrow functions)
- Works with older browsers
- No modern JavaScript features

### CSS
- Modern CSS with fallbacks
- Bootstrap 5.3.0
- Font Awesome 6.0.0

### PHP
- PHP 7.0+ compatible
- Procedural style
- No object-oriented patterns

## Support

Untuk bantuan lebih lanjut, silakan hubungi:
- Email: info@benangkumode.com
- Phone: +62 812-3456-7890

## License

Sistem ini dikembangkan khusus untuk BenangkuMode. Semua hak cipta dilindungi. 