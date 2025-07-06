<?php
require_once 'config/database.php';

echo "<h2>Debug Destinations Data</h2>";

// Check connection
if (!$conn) {
    echo "<p style='color: red;'>Database connection failed!</p>";
    exit;
}

echo "<p style='color: green;'>Database connected successfully!</p>";

// Check if destinations table exists
$result = mysqli_query($conn, "SHOW TABLES LIKE 'destinations'");
if (mysqli_num_rows($result) == 0) {
    echo "<p style='color: red;'>Table 'destinations' does not exist!</p>";
    exit;
}

echo "<p style='color: green;'>Table 'destinations' exists!</p>";

// Count total destinations
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM destinations");
$row = mysqli_fetch_assoc($result);
echo "<p>Total destinations in database: <strong>" . $row['total'] . "</strong></p>";

// Count active destinations
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM destinations WHERE is_active = 1");
$row = mysqli_fetch_assoc($result);
echo "<p>Active destinations: <strong>" . $row['total'] . "</strong></p>";

// Show all destinations
echo "<h3>All Destinations:</h3>";
$sql = "SELECT id, name, category_id, location, is_active, created_at FROM destinations ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Name</th><th>Category</th><th>Location</th><th>Active</th><th>Created</th></tr>";
    
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . $row['category_id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['location']) . "</td>";
        echo "<td>" . ($row['is_active'] ? 'Yes' : 'No') . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>No destinations found!</p>";
}

// Test JSON encoding
echo "<h3>JSON Test:</h3>";
$sql = "SELECT * FROM destinations WHERE is_active = 1 ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
$destinations = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $destinations[] = $row;
    }
}

$json = json_encode($destinations);
if (json_last_error() === JSON_ERROR_NONE) {
    echo "<p style='color: green;'>JSON encoding successful!</p>";
    echo "<p>JSON length: " . strlen($json) . " characters</p>";
    echo "<pre>" . htmlspecialchars(substr($json, 0, 500)) . "...</pre>";
} else {
    echo "<p style='color: red;'>JSON encoding failed: " . json_last_error_msg() . "</p>";
}
?> 