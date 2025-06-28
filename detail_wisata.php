<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Wisata - BenangkuMode</title>
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

    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav class="breadcrumb">
                <a href="index.php">Beranda</a>
                <span class="separator">/</span>
                <a href="wisata.php">Wisata</a>
                <span class="separator">/</span>
                <span id="destinationName">Detail Destinasi</span>
            </nav>
        </div>
    </section>

    <!-- Destination Detail -->
    <section class="destination-detail">
        <div class="container">
            <div class="detail-content">
                <!-- Main Image and Info -->
                <div class="detail-main">
                    <div class="detail-image">
                        <img id="mainImage" src="" alt="Destination Image">
                        <div class="image-overlay">
                            <button class="gallery-btn" onclick="openGallery()">
                                <i class="fas fa-images"></i>
                                Lihat Galeri
                            </button>
                        </div>
                    </div>
                    <div class="detail-info">
                        <div class="detail-header">
                            <h1 id="detailTitle">Nama Destinasi</h1>
                            <div class="detail-meta">
                                <span class="category-badge" id="categoryBadge">Kategori</span>
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <span id="rating">4.5</span>
                                    <span class="review-count">(150 ulasan)</span>
                                </div>
                            </div>
                        </div>
                        <div class="detail-description" id="description">
                            <!-- Description will be loaded here -->
                        </div>
                        <div class="detail-features">
                            <h3>Fitur Utama</h3>
                            <div class="features-grid" id="featuresGrid">
                                <!-- Features will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location and Map -->
                <div class="detail-location">
                    <div class="location-info">
                        <h3>Lokasi & Informasi</h3>
                        <div class="location-details">
                            <div class="location-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <strong>Alamat:</strong>
                                    <p id="address">Alamat destinasi</p>
                                </div>
                            </div>
                            <div class="location-item">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <strong>Jam Operasional:</strong>
                                    <p id="operatingHours">24 jam</p>
                                </div>
                            </div>
                            <div class="location-item">
                                <i class="fas fa-money-bill-wave"></i>
                                <div>
                                    <strong>Harga Tiket:</strong>
                                    <p id="ticketPrice">Gratis</p>
                                </div>
                            </div>
                            <div class="location-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>Kontak:</strong>
                                    <p id="contact">+62 812-3456-7890</p>
                                </div>
                            </div>
                        </div>
                        <div class="action-buttons">
                            <button class="btn btn-primary" onclick="openDirections()">
                                <i class="fas fa-directions"></i>
                                Petunjuk Arah
                            </button>
                            <button class="btn btn-secondary" onclick="shareLocation()">
                                <i class="fas fa-share"></i>
                                Bagikan
                            </button>
                        </div>
                    </div>
                    <div class="location-map">
                        <div id="detailMap" class="map"></div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="detail-additional">
                    <div class="additional-section">
                        <h3>Tips Berkunjung</h3>
                        <div class="tips-content" id="tipsContent">
                            <!-- Tips will be loaded here -->
                        </div>
                    </div>
                    <div class="additional-section">
                        <h3>Fasilitas</h3>
                        <div class="facilities-grid" id="facilitiesGrid">
                            <!-- Facilities will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Related Destinations -->
                <div class="related-destinations">
                    <h3>Destinasi Terkait</h3>
                    <div class="related-grid" id="relatedGrid">
                        <!-- Related destinations will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Image Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content gallery-modal">
            <span class="close" onclick="closeGallery()">&times;</span>
            <div class="gallery-container">
                <div class="gallery-main">
                    <img id="galleryMainImage" src="" alt="Gallery Image">
                    <button class="gallery-nav prev" onclick="prevImage()">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="gallery-nav next" onclick="nextImage()">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="gallery-thumbnails" id="galleryThumbnails">
                    <!-- Thumbnails will be loaded here -->
                </div>
            </div>
        </div>
    </div>

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
    <script src="assets/js/detail_wisata.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initDetailMap" async defer></script>
</body>
</html> 