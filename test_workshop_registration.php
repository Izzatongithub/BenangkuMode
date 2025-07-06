<?php
require_once 'config/database.php';

echo "<h2>Test Workshop Registration System</h2>";

// Test 1: Check database connection
echo "<h3>1. Database Connection Test</h3>";
if ($conn) {
    echo "✅ Database connection successful<br>";
} else {
    echo "❌ Database connection failed<br>";
    exit;
}

// Test 2: Check if table exists
echo "<h3>2. Table Existence Test</h3>";
$tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'workshop_registrations'");
if (mysqli_num_rows($tableCheck) > 0) {
    echo "✅ Table 'workshop_registrations' exists<br>";
} else {
    echo "❌ Table 'workshop_registrations' does not exist<br>";
}

// Test 3: Check table structure
echo "<h3>3. Table Structure Test</h3>";
$result = mysqli_query($conn, "DESCRIBE workshop_registrations");
if ($result) {
    echo "✅ Table structure:<br>";
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
} else {
    echo "❌ Failed to get table structure: " . mysqli_error($conn) . "<br>";
}

// Test 4: Check if users table exists (for foreign key)
echo "<h3>4. Users Table Test</h3>";
$usersCheck = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
if (mysqli_num_rows($usersCheck) > 0) {
    echo "✅ Table 'users' exists<br>";
    
    // Count users
    $userCount = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
    $userCountRow = mysqli_fetch_assoc($userCount);
    echo "📊 Total users: " . $userCountRow['count'] . "<br>";
} else {
    echo "❌ Table 'users' does not exist<br>";
}

// Test 5: Test insert query (without actually inserting)
echo "<h3>5. Insert Query Test</h3>";
$testSql = "INSERT INTO workshop_registrations (user_id, workshop_title, participant_name, participant_email, participant_phone, participant_age, experience_level, special_needs, registration_date) VALUES (1, 'Test Workshop', 'Test User', 'test@example.com', '08123456789', 25, 'beginner', 'Test needs', NOW())";

$stmt = mysqli_prepare($conn, $testSql);
if ($stmt) {
    echo "✅ Insert query prepared successfully<br>";
    mysqli_stmt_close($stmt);
} else {
    echo "❌ Failed to prepare insert query: " . mysqli_error($conn) . "<br>";
}

// Test 6: Check current registrations
echo "<h3>6. Current Registrations</h3>";
$registrations = mysqli_query($conn, "SELECT COUNT(*) as count FROM workshop_registrations");
if ($registrations) {
    $regCount = mysqli_fetch_assoc($registrations);
    echo "📊 Total registrations: " . $regCount['count'] . "<br>";
    
    if ($regCount['count'] > 0) {
        echo "📋 Recent registrations:<br>";
        $recentRegs = mysqli_query($conn, "SELECT * FROM workshop_registrations ORDER BY registration_date DESC LIMIT 5");
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>ID</th><th>User ID</th><th>Workshop</th><th>Name</th><th>Email</th><th>Date</th></tr>";
        while ($reg = mysqli_fetch_assoc($recentRegs)) {
            echo "<tr>";
            echo "<td>" . $reg['id'] . "</td>";
            echo "<td>" . $reg['user_id'] . "</td>";
            echo "<td>" . $reg['workshop_title'] . "</td>";
            echo "<td>" . $reg['participant_name'] . "</td>";
            echo "<td>" . $reg['participant_email'] . "</td>";
            echo "<td>" . $reg['registration_date'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "❌ Failed to count registrations: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);
?> 