<?php
require_once 'config/database.php';

$destinations = [];
$sql = "SELECT * FROM destinations ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $destinations[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wisata Lombok - BenangkuMode</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <a href="index.php">
                        <i class="fas fa-scroll"></i>
                        <span>BenangkuMode</span>
                    </a>
                </div>
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a href="about.php" class="nav-link">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a href="products.php" class="nav-link">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a href="workshop.php" class="nav-link">Workshop</a>
                    </li>
                    <li class="nav-item">
                        <a href="comingsoon.php" class="nav-link">Coming Soon</a>
                    </li>
                    <li class="nav-item">
                        <a href="gallery.php" class="nav-link">Galeri</a>
                    </li>
                    <li class="nav-item">
                        <a href="wisata.php" class="nav-link active">Wisata</a>
                    </li>
                </ul>
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero wisata-hero">
        <div class="hero-content">
            <h1 class="hero-title">Jelajahi Keindahan Lombok</h1>
            <p class="hero-subtitle">Temukan destinasi wisata terbaik di Pulau Lombok yang memukau</p>
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number">15+</span>
                    <span class="stat-label">Destinasi</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">4</span>
                    <span class="stat-label">Kabupaten</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">100%</span>
                    <span class="stat-label">Lokal</span>
                </div>
            </div>
        </div>
        <div class="hero-image">
            <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Lombok Landscape">
        </div>
    </section>

    <!-- Search and Filter Section -->
    <section class="search-section">
        <div class="container">
            <div class="search-container">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari destinasi wisata...">
                </div>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">Semua</button>
                    <button class="filter-btn" data-filter="pantai">Pantai</button>
                    <button class="filter-btn" data-filter="gunung">Gunung</button>
                    <button class="filter-btn" data-filter="air-terjun">Air Terjun</button>
                    <button class="filter-btn" data-filter="budaya">Budaya</button>
                    <button class="filter-btn" data-filter="kuliner">Kuliner</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Tourist Destinations -->
    <section class="destinations-section">
        <div class="container">
            <div class="section-header">
                <h2>Destinasi Wisata Lombok</h2>
                <p>Jelajahi keindahan alam dan budaya yang memukau di Pulau Lombok</p>
            </div>
            
            <div class="destinations-grid" id="destinationsGrid">
                <!-- Destinations will be loaded here -->
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <div class="section-header">
                <h2>Peta Wisata Lombok</h2>
                <p>Lihat lokasi semua destinasi wisata dalam satu peta</p>
            </div>
            <div class="map-container">
                <div id="map" class="map"></div>
                <div class="map-info">
                    <h3>Informasi Peta</h3>
                    <p>Klik pada marker untuk melihat detail destinasi</p>
                    <div class="map-legend">
                        <div class="legend-item">
                            <span class="legend-color pantai"></span>
                            <span>Pantai</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color gunung"></span>
                            <span>Gunung</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color air-terjun"></span>
                            <span>Air Terjun</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color budaya"></span>
                            <span>Budaya</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color kuliner"></span>
                            <span>Kuliner</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-scroll"></i>
                        <span>BenangkuMode</span>
                    </div>
                    <p>Karya tangan yang memukau dari Pulau Lombok. Setiap benang bercerita tentang keindahan dan keunikan budaya lokal.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Menu</h3>
                    <ul>
                        <li><a href="index.php">Beranda</a></li>
                        <li><a href="about.php">Tentang Kami</a></li>
                        <li><a href="products.php">Produk</a></li>
                        <li><a href="workshop.php">Workshop</a></li>
                        <li><a href="gallery.php">Galeri</a></li>
                        <li><a href="wisata.php">Wisata</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Layanan</h3>
                    <ul>
                        <li><a href="products.php">Pembelian Produk</a></li>
                        <li><a href="workshop.php">Workshop Knitting</a></li>
                        <li><a href="comingsoon.php">Produk Coming Soon</a></li>
                        <li><a href="gallery.php">Galeri Koleksi</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Kontak</h3>
                    <div class="contact-info">
                        <p><i class="fas fa-map-marker-alt"></i> Lombok, Nusa Tenggara Barat</p>
                        <p><i class="fas fa-phone"></i> +62 812-3456-7890</p>
                        <p><i class="fas fa-envelope"></i> info@benangkumode.com</p>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 BenangkuMode. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="assets/js/script.js"></script>
    <script src="assets/js/wisata.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>
</body>
</html> 