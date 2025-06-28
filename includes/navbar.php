<?php
require_once dirname(__FILE__) . '/../config/database.php';
session_start();
?>
<!-- Header -->
<header class="header">
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <h2>BenangkuMode</h2>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Beranda</a>
                </li>
                <li class="nav-item">
                    <a href="about.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a href="products.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">Produk</a>
                </li>
                <li class="nav-item">
                    <a href="workshop.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'workshop.php' ? 'active' : ''; ?>">Workshop</a>
                </li>
                <li class="nav-item">
                    <a href="comingsoon.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'comingsoon.php' ? 'active' : ''; ?>">Coming Soon</a>
                </li>
                <li class="nav-item">
                    <a href="gallery.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : ''; ?>">Galeri</a>
                </li>
                <li class="nav-item">
                    <a href="wisata.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'wisata.php' ? 'active' : ''; ?>">Wisata Lombok</a>
                </li>
            </ul>
            
            <!-- Auth Section -->
            <div class="auth-section">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-menu">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                        </div>
                        <div class="dropdown-menu">
                            <a href="profile.php">Profil Saya</a>
                            <div class="divider"></div>
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="auth-buttons">
                        <a href="login.php" class="btn btn-login">Login</a>
                        <a href="register.php" class="btn btn-register">Register</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </nav>
</header>

<style>
.header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    z-index: 1000;
}

.navbar {
    padding: 1rem 0;
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-logo h2 {
    color: #333;
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.nav-menu {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 2rem;
}

.nav-item {
    margin: 0;
}

.nav-link {
    color: #333;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
    padding: 0.5rem 0;
}

.nav-link:hover,
.nav-link.active {
    color: #667eea;
}

.auth-section {
    display: flex;
    align-items: center;
}

.user-menu {
    position: relative;
    display: inline-block;
}

.user-menu .dropdown-menu {
    display: none;
    position: absolute;
    right: 0;
    background-color: white;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    border-radius: 8px;
    padding: 8px 0;
}

.user-menu:hover .dropdown-menu {
    display: block;
}

.user-menu .dropdown-menu a {
    color: #333;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    transition: background-color 0.3s;
}

.user-menu .dropdown-menu a:hover {
    background-color: #f1f1f1;
}

.user-menu .dropdown-menu .divider {
    border-top: 1px solid #ddd;
    margin: 8px 0;
}

.auth-buttons {
    display: flex;
    gap: 10px;
    align-items: center;
}

.auth-buttons .btn {
    padding: 8px 16px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.auth-buttons .btn-login {
    background: transparent;
    color: #333;
    border: 1px solid #333;
}

.auth-buttons .btn-login:hover {
    background: #333;
    color: white;
}

.auth-buttons .btn-register {
    background: #667eea;
    color: white;
    border: 1px solid #667eea;
}

.auth-buttons .btn-register:hover {
    background: #5a6fd8;
    transform: translateY(-2px);
}

.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.user-avatar:hover {
    transform: scale(1.1);
}

.hamburger {
    display: none;
    flex-direction: column;
    cursor: pointer;
}

.bar {
    width: 25px;
    height: 3px;
    background-color: #333;
    margin: 3px 0;
    transition: 0.3s;
}

@media (max-width: 768px) {
    .hamburger {
        display: flex;
    }
    
    .nav-menu {
        position: fixed;
        left: -100%;
        top: 70px;
        flex-direction: column;
        background-color: white;
        width: 100%;
        text-align: center;
        transition: 0.3s;
        box-shadow: 0 10px 27px rgba(0, 0, 0, 0.05);
        padding: 2rem 0;
    }
    
    .nav-menu.active {
        left: 0;
    }
    
    .nav-menu li {
        margin: 1rem 0;
    }
}
</style>

<script>
const hamburger = document.querySelector(".hamburger");
const navMenu = document.querySelector(".nav-menu");

hamburger.addEventListener("click", () => {
    hamburger.classList.toggle("active");
    navMenu.classList.toggle("active");
});

document.querySelectorAll(".nav-link").forEach(n => n.addEventListener("click", () => {
    hamburger.classList.remove("active");
    navMenu.classList.remove("active");
}));
</script> 