<?php
require_once 'config/database.php';

echo "<h2>Add Payment Fields to Workshop Registrations</h2>";

// Check current table structure
echo "<h3>1. Current Table Structure</h3>";
$result = mysqli_query($conn, "DESCRIBE workshop_registrations");
if ($result) {
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Get existing columns
$columns = [];
$result = mysqli_query($conn, "DESCRIBE workshop_registrations");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $columns[] = $row['Field'];
    }
}

// Add payment fields
echo "<h3>2. Adding Payment Fields</h3>";

$paymentFields = [
    'workshop_price' => "DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Harga workshop'",
    'payment_status' => "ENUM('pending','paid','cancelled','refunded') DEFAULT 'pending' COMMENT 'Status pembayaran'",
    'payment_method' => "VARCHAR(50) DEFAULT NULL COMMENT 'Metode pembayaran'",
    'payment_date' => "DATETIME DEFAULT NULL COMMENT 'Tanggal pembayaran'",
    'payment_reference' => "VARCHAR(100) DEFAULT NULL COMMENT 'Referensi pembayaran'",
    'payment_amount' => "DECIMAL(10,2) DEFAULT NULL COMMENT 'Jumlah yang dibayar'"
];

foreach ($paymentFields as $field => $definition) {
    if (in_array($field, $columns)) {
        echo "✅ Field '$field' already exists<br>";
    } else {
        $sql = "ALTER TABLE workshop_registrations ADD COLUMN $field $definition";
        if (mysqli_query($conn, $sql)) {
            echo "✅ Successfully added field '$field'<br>";
        } else {
            echo "❌ Failed to add field '$field': " . mysqli_error($conn) . "<br>";
        }
    }
}

// Show final table structure
echo "<h3>3. Final Table Structure</h3>";
$result = mysqli_query($conn, "DESCRIBE workshop_registrations");
if ($result) {
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

mysqli_close($conn);
?> 