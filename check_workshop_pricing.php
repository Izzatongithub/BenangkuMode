<?php
require_once 'config/database.php';

echo "<h2>Workshop Pricing System Analysis</h2>";

// Check workshop table structure
echo "<h3>1. Workshop Table Structure</h3>";
$result = mysqli_query($conn, "DESCRIBE workshops");
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

// Check current workshops and their pricing
echo "<h3>2. Current Workshops and Pricing</h3>";
$workshops = mysqli_query($conn, "SELECT id, title, price, max_participants, current_participants FROM workshops ORDER BY id DESC");

if ($workshops) {
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>ID</th><th>Workshop</th><th>Price</th><th>Type</th><th>Current/Max</th><th>Available</th></tr>";
    while ($workshop = mysqli_fetch_assoc($workshops)) {
        $price = $workshop['price'];
        $type = $price == 0 ? 'Gratis' : 'Berbayar';
        $typeColor = $price == 0 ? 'green' : 'blue';
        $available = $workshop['max_participants'] - $workshop['current_participants'];
        
        echo "<tr>";
        echo "<td>" . $workshop['id'] . "</td>";
        echo "<td>" . $workshop['title'] . "</td>";
        echo "<td>Rp " . number_format($price, 0, ',', '.') . "</td>";
        echo "<td style='color: $typeColor; font-weight: bold;'>" . $type . "</td>";
        echo "<td>" . $workshop['current_participants'] . "/" . $workshop['max_participants'] . "</td>";
        echo "<td>" . $available . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Check if we need to add payment fields to workshop_registrations
echo "<h3>3. Workshop Registrations Table Structure</h3>";
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

// Check if payment fields exist
$columns = [];
$result = mysqli_query($conn, "DESCRIBE workshop_registrations");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $columns[] = $row['Field'];
    }
}

echo "<h3>4. Payment System Requirements</h3>";
$requiredFields = ['workshop_price', 'payment_status', 'payment_method', 'payment_date'];

foreach ($requiredFields as $field) {
    if (in_array($field, $columns)) {
        echo "✅ Field '$field' exists<br>";
    } else {
        echo "❌ Field '$field' missing<br>";
    }
}

mysqli_close($conn);
?> 