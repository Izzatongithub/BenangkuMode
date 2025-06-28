# Database BenangkuMode - File Lengkap

## Overview
File `database_complete.sql` adalah file SQL lengkap yang menggabungkan struktur database utama website BenangkuMode dengan sistem autentikasi.

## Fitur Database

### ✅ Struktur Lengkap
- **18 tabel** untuk semua fitur website
- **Sistem autentikasi** lengkap dengan reset password
- **Indexes** untuk performa optimal
- **Foreign keys** untuk integritas data
- **Data sample** untuk testing

### ✅ Tabel Autentikasi
1. **users** - Manajemen user dan autentikasi
2. **activity_logs** - Log aktivitas user

### ✅ Tabel Website
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

## Cara Import

### 1. Via Command Line
```bash
mysql -u root -p < database_complete.sql
```

### 2. Via phpMyAdmin
1. Buka phpMyAdmin
2. Buat database baru: `benangkumode_db`
3. Pilih database tersebut
4. Klik tab "Import"
5. Upload file `database_complete.sql`
6. Klik "Go"

## Data Default

### Admin Account
- **Email:** admin@benangkumode.com
- **Password:** admin123
- **Role:** admin

### Sample Categories
- **Product Categories:** Scarf & Shawl, Cardigan & Sweater, Accessories, Home Decor
- **Workshop Categories:** Basic Knitting, Advanced Techniques, Pattern Design, Color Theory
- **Destination Categories:** Beach, Mountain, Cultural, Adventure
- **Gallery Categories:** Product Gallery, Workshop Gallery, Customer Creations, Behind the Scenes

### Website Settings
- Site name, description, contact info
- Social media links
- Currency, timezone, date format
- Maintenance mode

## Keunggulan File Gabungan

### ✅ Sederhana
- Hanya satu file untuk import
- Tidak perlu import berurutan
- Tidak ada konflik dependency

### ✅ Lengkap
- Semua tabel dalam satu file
- Data sample included
- Indexes untuk performa

### ✅ Aman
- Foreign key constraints
- Proper data types
- Unique constraints

### ✅ Mudah Maintenance
- Satu file untuk backup
- Mudah di-deploy
- Version control friendly

## Troubleshooting

### Error "Table already exists"
```sql
-- Drop database jika perlu
DROP DATABASE IF EXISTS benangkumode_db;
-- Kemudian import ulang
```

### Error "Access denied"
- Pastikan user MySQL memiliki privilege CREATE, INSERT, UPDATE
- Atau gunakan root user untuk import

### Error "Connection failed"
- Pastikan MySQL service running
- Check host, username, password di config

### Error Order Items
Jika mengalami error dengan tabel `order_items`:
```sql
-- Periksa struktur tabel
DESCRIBE order_items;

-- Test trigger subtotal
INSERT INTO orders (order_number, customer_name, customer_email, total_amount) 
VALUES ('TEST-001', 'Test User', 'test@example.com', 0);

INSERT INTO order_items (order_id, product_name, quantity, price) 
VALUES (1, 'Test Product', 2, 50000);

-- Check if subtotal calculated automatically
SELECT * FROM order_items WHERE order_id = 1;
```

**Solusi:**
1. **Foreign Key Error**: Pastikan tabel `orders` dan `products` sudah ada
2. **Subtotal Error**: Pastikan trigger sudah dibuat dengan benar
3. **Constraint Error**: Jalankan ulang `database_complete.sql`

## Backup & Restore

### Backup Database
```bash
mysqldump -u root -p benangkumode_db > backup_benangkumode.sql
```

### Restore Database
```bash
mysql -u root -p benangkumode_db < backup_benangkumode.sql
```

## Support

Untuk bantuan lebih lanjut:
- Email: info@benangkumode.com
- Phone: +62 812-3456-7890 