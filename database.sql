-- BenangkuMode Database Structure
-- Created for handcraft knitting business website

-- Create database
CREATE DATABASE IF NOT EXISTS benangkumode_db;
USE benangkumode_db;

-- Users table for admin and customer management
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

-- Product categories
CREATE TABLE product_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image VARCHAR(255),
    images JSON, -- Multiple images stored as JSON
    stock_quantity INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES product_categories(id) ON DELETE SET NULL
);

-- Coming soon products
CREATE TABLE coming_soon_products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    estimated_price DECIMAL(10,2),
    estimated_release_date DATE,
    image VARCHAR(255),
    images JSON,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Votes for coming soon products
CREATE TABLE product_votes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    voter_name VARCHAR(100) NOT NULL,
    voter_email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES coming_soon_products(id) ON DELETE CASCADE
);

-- Workshop categories
CREATE TABLE workshop_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Workshops table
CREATE TABLE workshops (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    category_id INT,
    instructor VARCHAR(100),
    max_participants INT DEFAULT 20,
    current_participants INT DEFAULT 0,
    price DECIMAL(10,2) NOT NULL,
    duration VARCHAR(50), -- e.g., "2 hours", "1 day"
    start_date DATETIME,
    end_date DATETIME,
    location VARCHAR(200),
    image VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    is_past_event BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES workshop_categories(id) ON DELETE SET NULL
);

-- Workshop registrations
CREATE TABLE workshop_registrations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    workshop_id INT NOT NULL,
    participant_name VARCHAR(100) NOT NULL,
    participant_email VARCHAR(100) NOT NULL,
    participant_phone VARCHAR(20),
    participant_age INT,
    experience_level ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'beginner',
    special_requests TEXT,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (workshop_id) REFERENCES workshops(id) ON DELETE CASCADE
);

-- Tourist destination categories
CREATE TABLE destination_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(50), -- Font Awesome icon class
    color VARCHAR(7), -- Hex color code
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tourist destinations
CREATE TABLE destinations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    category_id INT,
    location VARCHAR(200),
    address TEXT,
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    rating DECIMAL(3,2) DEFAULT 0.00,
    review_count INT DEFAULT 0,
    operating_hours VARCHAR(100),
    ticket_price VARCHAR(100),
    contact VARCHAR(100),
    main_image VARCHAR(255),
    images JSON, -- Multiple images stored as JSON
    features JSON, -- Features array stored as JSON
    tips JSON, -- Tips array stored as JSON
    facilities JSON, -- Facilities array stored as JSON
    is_active BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES destination_categories(id) ON DELETE SET NULL
);

-- Gallery categories
CREATE TABLE gallery_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Gallery images
CREATE TABLE gallery_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200),
    description TEXT,
    category_id INT,
    image_path VARCHAR(255) NOT NULL,
    thumbnail_path VARCHAR(255),
    file_size INT,
    dimensions VARCHAR(20), -- e.g., "1920x1080"
    is_active BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES gallery_categories(id) ON DELETE SET NULL
);

-- Newsletter subscribers
CREATE TABLE newsletter_subscribers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    name VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact messages
CREATE TABLE contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders table (for future e-commerce functionality)
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20),
    customer_address TEXT,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Order items
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- Settings table for website configuration
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text', 'number', 'boolean', 'json') DEFAULT 'text',
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default data

-- Default admin user (password: admin123)
INSERT INTO users (username, email, password, full_name, role) VALUES 
('admin', 'admin@benangkumode.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');

-- Product categories
INSERT INTO product_categories (name, description) VALUES 
('Scarf', 'Berbagai jenis scarf dan syal'),
('Hat', 'Topi dan beanie'),
('Bag', 'Tas dan pouch'),
('Accessories', 'Aksesoris lainnya'),
('Home Decor', 'Dekorasi rumah');

-- Workshop categories
INSERT INTO workshop_categories (name, description) VALUES 
('Basic Knitting', 'Dasar-dasar merajut'),
('Advanced Techniques', 'Teknik merajut tingkat lanjut'),
('Pattern Making', 'Membuat pola rajut'),
('Yarn Selection', 'Pemilihan benang'),
('Project Completion', 'Menyelesaikan proyek');

-- Destination categories
INSERT INTO destination_categories (name, icon, color) VALUES 
('Pantai', 'fas fa-water', '#3498db'),
('Gunung', 'fas fa-mountain', '#27ae60'),
('Air Terjun', 'fas fa-tint', '#9b59b6'),
('Budaya', 'fas fa-landmark', '#e74c3c'),
('Kuliner', 'fas fa-utensils', '#f39c12');

-- Gallery categories
INSERT INTO gallery_categories (name, description) VALUES 
('Produk', 'Galeri produk BenangkuMode'),
('Workshop', 'Foto-foto kegiatan workshop'),
('Lombok', 'Keindahan alam Lombok'),
('Tim', 'Foto tim BenangkuMode');

-- Sample destinations
INSERT INTO destinations (name, description, category_id, location, address, latitude, longitude, rating, review_count, operating_hours, ticket_price, contact, main_image) VALUES 
('Pantai Pink', 'Pantai dengan pasir berwarna pink yang unik dan memukau', 1, 'Sekotong, Lombok Barat', 'Sekotong, Lombok Barat, Nusa Tenggara Barat', -8.7893, 115.9210, 4.8, 234, '06:00 - 18:00', 'Rp 10.000', '+62 812-3456-7890', 'pantai-pink.jpg'),
('Gunung Rinjani', 'Gunung berapi aktif tertinggi kedua di Indonesia', 2, 'Lombok Utara', 'Lombok Utara, Nusa Tenggara Barat', -8.4111, 116.4572, 4.9, 567, '24 jam', 'Rp 150.000', '+62 812-3456-7891', 'gunung-rinjani.jpg'),
('Air Terjun Tiu Kelep', 'Air terjun setinggi 45 meter dengan kolam alami', 3, 'Sembalun, Lombok Timur', 'Sembalun, Lombok Timur, Nusa Tenggara Barat', -8.4111, 116.4572, 4.7, 189, '07:00 - 17:00', 'Rp 15.000', '+62 812-3456-7892', 'air-terjun-tiu-kelep.jpg');

-- Sample products
INSERT INTO products (name, description, price, category_id, stock_quantity, is_featured) VALUES 
('Scarf Merah Classic', 'Scarf merah dengan pola klasik yang elegan', 150000, 1, 10, TRUE),
('Beanie Winter Blue', 'Beanie biru hangat untuk musim dingin', 120000, 2, 15, TRUE),
('Tas Pouch Multifungsi', 'Tas pouch serbaguna dengan motif tradisional', 200000, 3, 8, FALSE);

-- Sample workshops
INSERT INTO workshops (title, description, category_id, instructor, max_participants, price, duration, start_date, location) VALUES 
('Dasar Merajut untuk Pemula', 'Belajar teknik dasar merajut dari nol', 1, 'Sari Handayani', 15, 250000, '3 jam', '2024-02-15 09:00:00', 'Studio BenangkuMode, Mataram'),
('Pola Rajut Lanjutan', 'Teknik membuat pola rajut yang kompleks', 2, 'Budi Santoso', 10, 350000, '4 jam', '2024-02-20 13:00:00', 'Studio BenangkuMode, Mataram');

-- Website settings
INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES 
('site_name', 'BenangkuMode', 'text', 'Nama website'),
('site_description', 'Karya tangan yang memukau dari Pulau Lombok', 'text', 'Deskripsi website'),
('contact_phone', '+62 812-3456-7890', 'text', 'Nomor telepon kontak'),
('contact_email', 'info@benangkumode.com', 'text', 'Email kontak'),
('contact_address', 'Lombok, Nusa Tenggara Barat', 'text', 'Alamat kontak'),
('google_maps_api_key', 'YOUR_GOOGLE_MAPS_API_KEY', 'text', 'Google Maps API Key'),
('whatsapp_number', '+62 812-3456-7890', 'text', 'Nomor WhatsApp'),
('social_instagram', '@benangkumode', 'text', 'Instagram handle'),
('social_facebook', 'BenangkuMode', 'text', 'Facebook page'),
('social_youtube', 'BenangkuMode', 'text', 'YouTube channel');

-- Create indexes for better performance
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_active ON products(is_active);
CREATE INDEX idx_workshops_category ON workshops(category_id);
CREATE INDEX idx_workshops_date ON workshops(start_date);
CREATE INDEX idx_destinations_category ON destinations(category_id);
CREATE INDEX idx_destinations_active ON destinations(is_active);
CREATE INDEX idx_gallery_category ON gallery_images(category_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_date ON orders(created_at); 