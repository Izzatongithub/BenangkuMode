# Admin Panel - BenangkuMode

Panel admin untuk mengelola website BenangkuMode dengan fitur lengkap menggunakan mysqli tanpa prosedur.

## Fitur Admin Panel

### 1. Dashboard
- Statistik pengguna, produk, destinasi, dan pesanan
- Aktivitas terbaru
- Pesanan terbaru
- Informasi sistem

### 2. Manajemen Users
- Lihat semua pengguna
- Tambah pengguna baru
- Edit data pengguna
- Hapus pengguna (kecuali admin)
- Toggle status aktif/nonaktif
- Ganti password

### 3. Manajemen Products
- Lihat semua produk
- Tambah produk baru
- Edit produk
- Hapus produk
- Upload gambar produk
- Toggle status aktif/nonaktif
- Kelola stok

### 4. Manajemen Destinations
- Lihat semua destinasi wisata
- Tambah destinasi baru
- Edit destinasi
- Hapus destinasi
- Upload gambar destinasi
- Toggle status aktif/nonaktif
- Kelola rating

### 5. Manajemen Orders
- Lihat semua pesanan
- Update status pesanan (pending, processing, completed, cancelled)
- Hapus pesanan
- Detail pelanggan dan produk

### 6. Settings
- Pengaturan website
- Informasi sistem
- Statistik website

## Struktur File

```
admin/
├── dashboard.php          # Dashboard utama
├── users.php              # Manajemen users
├── add_user.php           # Tambah user baru
├── edit_user.php          # Edit user
├── products.php           # Manajemen products
├── add_product.php        # Tambah product baru
├── edit_product.php       # Edit product
├── destinations.php       # Manajemen destinations
├── add_destination.php    # Tambah destination baru
├── edit_destination.php   # Edit destination
├── orders.php             # Manajemen orders
├── settings.php           # Pengaturan website
└── README.md              # Dokumentasi ini
```

## Cara Akses

1. Login dengan akun admin:
   - Email: `admin@benangkumode.com`
   - Password: `admin123`

2. Setelah login sebagai admin, akan otomatis diarahkan ke dashboard admin

3. Atau klik link "Admin Panel" di navbar jika sudah login sebagai admin

## Keamanan

- Semua halaman admin dilindungi dengan pengecekan `isLoggedIn()` dan `isAdmin()`
- Validasi input untuk mencegah SQL injection
- Sanitasi data sebelum disimpan ke database
- Log aktivitas admin untuk audit trail

## Database Tables

### Users
- `id`, `full_name`, `email`, `password`, `role`, `is_active`, `created_at`

### Products
- `id`, `name`, `description`, `category`, `price`, `stock`, `image`, `is_active`, `created_at`

### Destinations
- `id`, `name`, `description`, `location`, `price`, `rating`, `image`, `is_active`, `created_at`

### Order Items
- `id`, `user_id`, `product_id`, `quantity`, `total_price`, `status`, `created_at`

### Settings
- `id`, `setting_key`, `setting_value`, `created_at`, `updated_at`

### Activity Logs
- `id`, `user_id`, `action`, `details`, `ip_address`, `user_agent`, `created_at`

## Upload Images

- Direktori upload: `../assets/images/products/` dan `../assets/images/destinations/`
- Format yang didukung: JPG, JPEG, PNG, GIF
- Maksimal ukuran: 5MB
- Nama file otomatis di-generate dengan `uniqid()`

## Logging

Semua aktivitas admin dicatat dalam tabel `activity_logs`:
- Login/logout
- CRUD operations (Create, Read, Update, Delete)
- Perubahan status
- Update settings

## Responsive Design

Admin panel menggunakan Bootstrap 5 dengan desain responsive yang dapat diakses dari desktop maupun mobile.

## Dependencies

- PHP 7.4+
- MySQL/MariaDB
- Bootstrap 5.3.0
- Font Awesome 6.0.0
- jQuery (untuk beberapa fitur)

## Troubleshooting

### Error Upload Image
- Pastikan direktori `assets/images/` memiliki permission write
- Cek ukuran file tidak melebihi 5MB
- Pastikan format file didukung

### Error Database
- Cek koneksi database di `../config/database.php`
- Pastikan semua tabel sudah dibuat sesuai `database.sql`

### Access Denied
- Pastikan sudah login sebagai admin
- Cek session tidak expired
- Pastikan role user adalah 'admin' 