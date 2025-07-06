-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 06, 2025 at 06:03 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `benangkumode_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, NULL, 'database_setup', 'Database BenangkuMode berhasil dibuat dengan sistem autentikasi lengkap', '127.0.0.1', NULL, '2025-06-28 01:53:01'),
(2, NULL, 'registration', 'New user registered: izzat@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-06-28 02:29:49'),
(3, 2, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-06-28 02:30:05');

-- --------------------------------------------------------

--
-- Table structure for table `coming_soon_products`
--

CREATE TABLE `coming_soon_products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `estimated_price` decimal(10,2) DEFAULT NULL,
  `estimated_release_date` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coming_soon_products`
--

INSERT INTO `coming_soon_products` (`id`, `name`, `description`, `estimated_price`, `estimated_release_date`, `image`, `images`, `is_active`, `created_at`) VALUES
(1, 'bucket cap rajut', 'topi', '75000.00', '2025-07-24', 'comingsoon_6863c124af3d73.20127203.jpeg', NULL, 1, '2025-07-01 11:06:12');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `review_count` int(11) DEFAULT 0,
  `operating_hours` varchar(100) DEFAULT NULL,
  `ticket_price` varchar(100) DEFAULT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `tips` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tips`)),
  `facilities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`facilities`)),
  `is_active` tinyint(1) DEFAULT 1,
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `name`, `description`, `category_id`, `location`, `address`, `latitude`, `longitude`, `rating`, `review_count`, `operating_hours`, `ticket_price`, `contact`, `main_image`, `images`, `features`, `tips`, `facilities`, `is_active`, `is_featured`, `created_at`, `updated_at`) VALUES
(2, 'pantai sire', 'pantai di klu', 1, 'pemenang, klu', 'pemenang, klu', '-8.36615100', '116.10222880', '4.00', 45, '08:00 - 20:00', '10000', 'pantaisire@gmail.com', '6869830766d27.jpeg', NULL, NULL, NULL, NULL, 1, 1, '2025-07-05 19:54:47', '2025-07-05 19:54:47'),
(3, 'pantai sire', 'pantai di klu', 1, 'pemenang, klu', 'pemenang, klu', '-8.36615100', '116.10222880', '4.00', 45, '08:00 - 20:00', '10000', 'pantaisire@gmail.com', '6869831a7fe04.jpeg', NULL, NULL, NULL, NULL, 1, 1, '2025-07-05 19:55:06', '2025-07-05 19:55:06'),
(4, 'pantai sire', 'pantai di klu', 1, 'pemenang, klu', 'pemenang, klu', '-8.36615100', '116.10222880', '4.00', 45, '08:00 - 20:00', '10000', 'pantaisire@gmail.com', '686985d0cd6b0.jpeg', NULL, NULL, NULL, NULL, 1, 1, '2025-07-05 20:06:40', '2025-07-05 20:06:40'),
(5, 'pantai sire', 'pantai di klu', 1, 'pemenang, klu', 'pemenang, klu', '-8.36615100', '116.10222880', '4.00', 45, '08:00 - 20:00', '10000', 'pantaisire@gmail.com', '6869866057418.jpeg', NULL, NULL, NULL, NULL, 1, 1, '2025-07-05 20:09:04', '2025-07-05 20:09:04'),
(6, 'pantai senggigi', 'senggigi', 1, 'senggigi', 'senggigi', '-8.47859300', '116.03628470', '4.00', 65, '08:00-00:00', '15000', '19191991', '686986ec46767.jpeg', NULL, NULL, NULL, NULL, 1, 1, '2025-07-05 20:11:24', '2025-07-05 20:11:24'),
(7, 'pantai senggigi', 'senggigi', 1, 'senggigi', 'senggigi', '-8.47859300', '116.03628470', '4.00', 65, '08:00-00:00', '15000', '19191991', '686987538d5dd.jpeg', NULL, '[\"lorem\"]', '[\"lorem\"]', '[\"lorem\"]', 1, 1, '2025-07-05 20:13:07', '2025-07-05 20:13:07'),
(8, 'gggg', 'gggg', 2, 'ggg', 'gggg', '99.99999999', '111.00000000', '2.00', 2, '08000', '111', '1111', '68698784e0c20.jpeg', NULL, '[\"nnnn\"]', '[\"nnnn\"]', '[\"nnnn\"]', 1, 1, '2025-07-05 20:13:56', '2025-07-05 20:13:56');

-- --------------------------------------------------------

--
-- Table structure for table `destination_categories`
--

CREATE TABLE `destination_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `color` varchar(7) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destination_categories`
--

INSERT INTO `destination_categories` (`id`, `name`, `icon`, `color`, `is_active`, `created_at`) VALUES
(1, 'Beach', 'fas fa-umbrella-beach', '#FF6B6B', 1, '2025-06-28 01:53:01'),
(2, 'Mountain', 'fas fa-mountain', '#4ECDC4', 1, '2025-06-28 01:53:01'),
(3, 'Cultural', 'fas fa-landmark', '#45B7D1', 1, '2025-06-28 01:53:01'),
(4, 'Adventure', 'fas fa-hiking', '#96CEB4', 1, '2025-06-28 01:53:01');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_categories`
--

CREATE TABLE `gallery_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_categories`
--

INSERT INTO `gallery_categories` (`id`, `name`, `description`, `is_active`, `created_at`) VALUES
(1, 'Product Gallery', 'Galeri produk unggulan', 1, '2025-06-28 01:53:01'),
(2, 'Workshop Gallery', 'Galeri kegiatan workshop', 1, '2025-06-28 01:53:01'),
(3, 'Customer Creations', 'Kreasi customer', 1, '2025-06-28 01:53:01'),
(4, 'Behind the Scenes', 'Dibalik layar', 1, '2025-06-28 01:53:01');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `dimensions` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','complete','cancelled') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `shipping_method` varchar(50) DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `customer_name`, `customer_email`, `customer_phone`, `customer_address`, `total_amount`, `status`, `payment_method`, `payment_status`, `shipping_method`, `tracking_number`, `notes`, `created_at`, `updated_at`) VALUES
(2, 'ORD-20250701-E74E8511', 'Izzat Nazhiefa', 'izzat@gmail.com', '089999', 'mtm', '395000.00', '', 'cod', 'paid', 'jne', NULL, NULL, '2025-07-01 13:09:51', '2025-07-05 03:48:19'),
(3, 'ORD-20250704-0A0285D4', 'Izzat Nazhiefa', 'izzat@gmail.com', '123', 'jjj', '75000.00', '', 'transfer', 'paid', 'jne', NULL, NULL, '2025-07-04 15:58:20', '2025-07-05 03:48:15'),
(4, 'ORD-20250705-11DCD5B2', 'Izzat Nazhiefa', 'izzat@gmail.com', '333', 'bb', '245000.00', '', 'transfer', 'paid', 'jne', NULL, NULL, '2025-07-05 03:43:53', '2025-07-05 03:44:28'),
(5, 'ORD-20250705-0D780BD2', 'Izzat Nazhiefa', 'izzat@gmail.com', '78987', 'lolol', '245000.00', '', 'transfer', 'paid', 'jne', NULL, NULL, '2025-07-05 03:54:22', '2025-07-05 03:57:21'),
(6, 'ORD-20250705-B8004DF7', 'Izzat Nazhiefa', 'izzat@gmail.com', '66868', 'bnnn', '245000.00', 'pending', 'transfer', 'paid', 'jnt', NULL, NULL, '2025-07-05 03:57:42', '2025-07-05 03:57:44');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(200) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`, `subtotal`, `created_at`) VALUES
(1, 2, 5, 'topi rajut', 2, '75000.00', '150000.00', '2025-07-01 13:09:51'),
(2, 2, 4, 'cardigan rajut', 1, '245000.00', '245000.00', '2025-07-01 13:09:51'),
(3, 3, 5, 'topi rajut', 1, '75000.00', '75000.00', '2025-07-04 15:58:20'),
(4, 4, 4, 'cardigan rajut', 1, '245000.00', '245000.00', '2025-07-05 03:43:53'),
(5, 5, 4, 'cardigan rajut', 1, '245000.00', '245000.00', '2025-07-05 03:54:22'),
(6, 6, 4, 'cardigan rajut', 1, '245000.00', '245000.00', '2025-07-05 03:57:42');

--
-- Triggers `order_items`
--
DELIMITER $$
CREATE TRIGGER `calculate_subtotal` BEFORE INSERT ON `order_items` FOR EACH ROW BEGIN
    SET NEW.subtotal = NEW.quantity * NEW.price;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_subtotal` BEFORE UPDATE ON `order_items` FOR EACH ROW BEGIN
    SET NEW.subtotal = NEW.quantity * NEW.price;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `stock_quantity` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category_id`, `image`, `images`, `stock_quantity`, `is_active`, `is_featured`, `created_at`, `updated_at`) VALUES
(4, 'cardigan rajut', 'warna biru', '245000.00', 1, '6862096a08aea.jpeg', NULL, 2, 1, 0, '2025-06-30 03:50:02', '2025-07-05 03:57:42'),
(5, 'topi rajut', 'mantap', '75000.00', 1, '68621aa967ebf.jpeg', NULL, 5, 1, 0, '2025-06-30 05:03:37', '2025-06-30 05:03:37');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `description`, `image`, `is_active`, `created_at`) VALUES
(1, 'clothing', 'Produk scarf dan shawl merajut', NULL, 1, '2025-06-28 01:53:01'),
(2, 'accessories', 'Produk cardigan dan sweater', NULL, 1, '2025-06-28 01:53:01'),
(3, 'shoes', 'Aksesoris merajut', NULL, 1, '2025-06-28 01:53:01'),
(4, 'bags', 'Dekorasi rumah merajut', NULL, 1, '2025-06-28 01:53:01');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_votes`
--

CREATE TABLE `product_votes` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `voter_name` varchar(100) NOT NULL,
  `voter_email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_votes`
--

INSERT INTO `product_votes` (`id`, `product_id`, `voter_name`, `voter_email`, `created_at`) VALUES
(3, 1, 'Izzat Nazhiefa', 'izzat@gmail.com', '2025-07-01 12:03:05'),
(4, 1, 'Guest', 'guest_6868980238ac8@guest.com', '2025-07-05 03:12:08');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'BenangkuMode', 'Nama website', '2025-06-28 01:53:01', '2025-06-28 01:53:01'),
(2, 'site_description', 'Tempat terbaik untuk kerajinan tangan dan workshop knitting', 'Deskripsi website', '2025-06-28 01:53:01', '2025-06-28 01:53:01'),
(3, 'contact_email', 'info@benangkumode.com', 'Email kontak utama', '2025-06-28 01:53:01', '2025-06-28 01:53:01'),
(4, 'contact_phone', '+62 812-3456-7890', 'Nomor telepon kontak', '2025-06-28 01:53:01', '2025-06-28 01:53:01'),
(5, 'address', 'Jl. Contoh No. 123, Jakarta', 'Alamat utama', '2025-06-28 01:53:01', '2025-06-28 01:53:01'),
(6, 'social_facebook', 'https://facebook.com/benangkumode', 'Link Facebook', '2025-06-28 01:53:01', '2025-06-28 01:53:01'),
(7, 'social_instagram', 'https://instagram.com/benangkumode', 'Link Instagram', '2025-06-28 01:53:01', '2025-06-28 01:53:01'),
(8, 'social_twitter', 'https://twitter.com/benangkumode', 'Link Twitter', '2025-06-28 01:53:01', '2025-06-28 01:53:01'),
(9, 'maintenance_mode', '0', 'Mode maintenance (0=off, 1=on)', '2025-06-28 01:53:01', '2025-06-28 01:53:01'),
(10, 'currency', 'IDR', 'Mata uang default', '2025-06-28 01:53:01', '2025-06-28 01:53:01'),
(11, 'timezone', 'Asia/Jakarta', 'Timezone default', '2025-06-28 01:53:01', '2025-06-28 01:53:01'),
(12, 'date_format', 'd/m/Y', 'Format tanggal default', '2025-06-28 01:53:01', '2025-06-28 01:53:01'),
(13, 'time_format', 'H:i', 'Format waktu default', '2025-06-28 01:53:01', '2025-06-28 01:53:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `is_active` tinyint(1) DEFAULT 1,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expiry` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `address`, `role`, `is_active`, `reset_token`, `reset_expiry`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@benangkumode.com', '$2y$10$9m5X5/9oMhS8Gmi3Q/FXpu5zPJYvmJ9DfUfJruiy6nuDWtuDzmfba', 'Administrator', NULL, NULL, 'admin', 1, NULL, NULL, '2025-06-28 01:53:01', '2025-06-28 15:10:06'),
(2, 'izzatnazhiefa_429', 'izzat@gmail.com', '$2y$10$svIFKae6HQlXCYUwXJtau.Sy.O5g5Twu8V9MK2mY2/T/ctCflHQfS', 'Izzat Nazhiefa', '089612345678', 'mataram', 'customer', 1, NULL, NULL, '2025-06-28 02:29:49', '2025-06-28 02:29:49'),
(3, 'admin_1', 'admin1@benangkumode.com', 'admin1', 'joko', NULL, NULL, 'admin', 1, NULL, NULL, '2025-06-28 02:50:24', '2025-06-28 14:16:43'),
(26, 'abdi_667', 'abdi@gmail.com', '$2y$10$gRxPer/rDVbP.b8149u48e/./qZWB6tKqHuwiqyRhGmN.W7yiIOGi', 'abdi', '0899999', 'seruni', 'customer', 1, NULL, NULL, '2025-07-06 02:32:12', '2025-07-06 02:32:12');

-- --------------------------------------------------------

--
-- Table structure for table `workshops`
--

CREATE TABLE `workshops` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `instructor` varchar(100) DEFAULT NULL,
  `max_participants` int(11) DEFAULT 20,
  `current_participants` int(11) DEFAULT 0,
  `price` decimal(10,2) NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_past_event` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workshops`
--

INSERT INTO `workshops` (`id`, `title`, `description`, `category_id`, `instructor`, `max_participants`, `current_participants`, `price`, `duration`, `start_date`, `end_date`, `location`, `image`, `is_active`, `is_past_event`, `created_at`, `updated_at`) VALUES
(1, 'Dasar Merajut', 'Mempelajari teknik dasar merajut fashion', 1, 'Izzat Nazhiefa S.Kom', 10, 2, '50000.00', '5 Jam', '2025-07-01 16:52:00', '2025-07-03 16:52:00', 'Gomong', 'workshop_6863a4940e1b77.15297775.jpeg', 1, 0, '2025-07-01 08:53:07', '2025-07-06 03:40:07'),
(3, 'Teknik dasar menjahit', 'Belajar teknik dasar menjahit', 1, 'Fadlullah hasan', 10, 1, '0.00', '4 Jam', '2025-07-07 18:15:00', '2025-07-12 18:15:00', 'Kekalik', '', 1, 0, '2025-07-01 10:19:32', '2025-07-06 02:57:41'),
(4, 'Teknik dasar memotong', 'belajar memotong', 1, 'yusri abdi', 10, 0, '0.00', '2 jam', '2025-07-01 18:20:00', '2025-07-02 18:20:00', 'seruni', '', 0, 1, '2025-07-01 10:20:43', '2025-07-01 10:20:43');

-- --------------------------------------------------------

--
-- Table structure for table `workshop_categories`
--

CREATE TABLE `workshop_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workshop_categories`
--

INSERT INTO `workshop_categories` (`id`, `name`, `description`, `is_active`, `created_at`) VALUES
(1, 'Basic Knitting', 'Workshop merajut dasar', 1, '2025-06-28 01:53:01'),
(2, 'Advanced Techniques', 'Workshop teknik lanjutan', 1, '2025-06-28 01:53:01'),
(3, 'Pattern Design', 'Workshop desain pola', 1, '2025-06-28 01:53:01'),
(4, 'Color Theory', 'Workshop teori warna', 1, '2025-06-28 01:53:01');

-- --------------------------------------------------------

--
-- Table structure for table `workshop_registrations`
--

CREATE TABLE `workshop_registrations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `workshop_title` varchar(255) NOT NULL,
  `participant_name` varchar(255) NOT NULL,
  `participant_email` varchar(255) NOT NULL,
  `participant_phone` varchar(50) NOT NULL,
  `participant_age` int(3) DEFAULT NULL,
  `experience_level` enum('beginner','intermediate','advanced') DEFAULT 'beginner',
  `special_needs` text DEFAULT NULL,
  `registration_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `workshop_price` decimal(10,2) DEFAULT 0.00 COMMENT 'Harga workshop',
  `payment_status` enum('pending','paid','cancelled','refunded') DEFAULT 'pending' COMMENT 'Status pembayaran',
  `payment_method` varchar(50) DEFAULT NULL COMMENT 'Metode pembayaran',
  `payment_date` datetime DEFAULT NULL COMMENT 'Tanggal pembayaran',
  `payment_reference` varchar(100) DEFAULT NULL COMMENT 'Referensi pembayaran',
  `payment_amount` decimal(10,2) DEFAULT NULL COMMENT 'Jumlah yang dibayar',
  `payment_proof` varchar(255) DEFAULT NULL,
  `workshop_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workshop_registrations`
--

INSERT INTO `workshop_registrations` (`id`, `user_id`, `workshop_title`, `participant_name`, `participant_email`, `participant_phone`, `participant_age`, `experience_level`, `special_needs`, `registration_date`, `status`, `workshop_price`, `payment_status`, `payment_method`, `payment_date`, `payment_reference`, `payment_amount`, `payment_proof`, `workshop_id`) VALUES
(7, 26, '', 'abdi', 'abdi@gmail.com', '89989', 19, 'beginner', 'mmm', '2025-07-06 11:40:07', 'pending', '50000.00', 'pending', NULL, NULL, NULL, NULL, 'assets/images/payment_proof/proof_7_1751774549.jpeg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_activity_logs_user` (`user_id`),
  ADD KEY `idx_activity_logs_action` (`action`),
  ADD KEY `idx_activity_logs_date` (`created_at`);

--
-- Indexes for table `coming_soon_products`
--
ALTER TABLE `coming_soon_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_destinations_category` (`category_id`),
  ADD KEY `idx_destinations_active` (`is_active`),
  ADD KEY `idx_destinations_featured` (`is_featured`);

--
-- Indexes for table `destination_categories`
--
ALTER TABLE `destination_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_categories`
--
ALTER TABLE `gallery_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_gallery_category` (`category_id`),
  ADD KEY `idx_gallery_active` (`is_active`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_orders_status` (`status`),
  ADD KEY `idx_orders_date` (`created_at`),
  ADD KEY `idx_orders_customer` (`customer_email`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_items_order` (`order_id`),
  ADD KEY `idx_order_items_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_products_category` (`category_id`),
  ADD KEY `idx_products_active` (`is_active`),
  ADD KEY `idx_products_featured` (`is_featured`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product_votes`
--
ALTER TABLE `product_votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_reset_token` (`reset_token`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_active` (`is_active`);

--
-- Indexes for table `workshops`
--
ALTER TABLE `workshops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_workshops_category` (`category_id`),
  ADD KEY `idx_workshops_date` (`start_date`),
  ADD KEY `idx_workshops_active` (`is_active`);

--
-- Indexes for table `workshop_categories`
--
ALTER TABLE `workshop_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workshop_registrations`
--
ALTER TABLE `workshop_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `registration_date` (`registration_date`),
  ADD KEY `workshop_id` (`workshop_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `coming_soon_products`
--
ALTER TABLE `coming_soon_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `destination_categories`
--
ALTER TABLE `destination_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gallery_categories`
--
ALTER TABLE `gallery_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_votes`
--
ALTER TABLE `product_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `workshops`
--
ALTER TABLE `workshops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `workshop_categories`
--
ALTER TABLE `workshop_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `workshop_registrations`
--
ALTER TABLE `workshop_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `destinations`
--
ALTER TABLE `destinations`
  ADD CONSTRAINT `destinations_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `destination_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD CONSTRAINT `gallery_images_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `gallery_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_votes`
--
ALTER TABLE `product_votes`
  ADD CONSTRAINT `product_votes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `coming_soon_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workshops`
--
ALTER TABLE `workshops`
  ADD CONSTRAINT `workshops_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `workshop_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `workshop_registrations`
--
ALTER TABLE `workshop_registrations`
  ADD CONSTRAINT `workshop_registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `workshop_registrations_ibfk_2` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
