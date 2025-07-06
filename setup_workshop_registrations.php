<?php
require_once 'config/database.php';

// SQL to create workshop_registrations table
$sql = "CREATE TABLE IF NOT EXISTS `workshop_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `workshop_title` varchar(255) NOT NULL,
  `participant_name` varchar(255) NOT NULL,
  `participant_email` varchar(255) NOT NULL,
  `participant_phone` varchar(50) NOT NULL,
  `participant_age` int(3) DEFAULT NULL,
  `experience_level` enum('beginner','intermediate','advanced') DEFAULT 'beginner',
  `special_needs` text DEFAULT NULL,
  `registration_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `registration_date` (`registration_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

// Execute the SQL
if (mysqli_query($conn, $sql)) {
    echo "Tabel workshop_registrations berhasil dibuat atau sudah ada.<br>";
    
    // Check if table exists and has the correct structure
    $result = mysqli_query($conn, "DESCRIBE workshop_registrations");
    if ($result) {
        echo "Struktur tabel workshop_registrations:<br>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "- " . $row['Field'] . " (" . $row['Type'] . ")<br>";
        }
    }
} else {
    echo "Error creating table: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);
?> 