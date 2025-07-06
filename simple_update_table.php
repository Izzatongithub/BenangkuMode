<?php
require_once 'config/database.php';

echo "<h2>Updating Workshop Registration Table</h2>";

// Query untuk menambah kolom-kolom baru
$queries = [
    "ALTER TABLE workshop_registration ADD COLUMN payment_status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending'",
    "ALTER TABLE workshop_registration ADD COLUMN payment_method VARCHAR(50) NULL",
    "ALTER TABLE workshop_registration ADD COLUMN payment_amount DECIMAL(10,2) DEFAULT 0.00",
    "ALTER TABLE workshop_registration ADD COLUMN payment_date DATETIME NULL",
    "ALTER TABLE workshop_registration ADD COLUMN payment_proof VARCHAR(255) NULL",
    "ALTER TABLE workshop_registration ADD COLUMN payment_instructions TEXT NULL",
    "ALTER TABLE workshop_registration ADD COLUMN workshop_price DECIMAL(10,2) DEFAULT 0.00",
    "ALTER TABLE workshop_registration ADD COLUMN registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
];

$successCount = 0;
$errorCount = 0;

foreach ($queries as $query) {
    if (mysqli_query($conn, $query)) {
        echo "<p style='color: green;'>✅ Success: " . substr($query, 0, 50) . "...</p>";
        $successCount++;
    } else {
        echo "<p style='color: red;'>❌ Error: " . mysqli_error($conn) . "</p>";
        $errorCount++;
    }
}

// Tambah index
$indexQueries = [
    "ALTER TABLE workshop_registration ADD INDEX idx_payment_status (payment_status)",
    "ALTER TABLE workshop_registration ADD INDEX idx_registration_date (registration_date)",
    "ALTER TABLE workshop_registration ADD INDEX idx_workshop_user (workshop_id, user_id)"
];

foreach ($indexQueries as $query) {
    if (mysqli_query($conn, $query)) {
        echo "<p style='color: green;'>✅ Index added successfully</p>";
        $successCount++;
    } else {
        echo "<p style='color: orange;'>⚠️ Index already exists or error: " . mysqli_error($conn) . "</p>";
    }
}

echo "<h3>Summary:</h3>";
echo "<p>✅ Success: $successCount queries</p>";
echo "<p>❌ Errors: $errorCount queries</p>";

if ($errorCount == 0) {
    echo "<p style='color: green; font-weight: bold;'>🎉 Table updated successfully!</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>⚠️ Some errors occurred.</p>";
}

mysqli_close($conn);
?> 