<?php
require_once 'config/database.php';

echo "<h2>Test Workshop Slots System</h2>";

// Test 1: Check current workshops and their slots
echo "<h3>1. Current Workshops and Slots</h3>";
$workshops = mysqli_query($conn, "SELECT id, title, max_participants, current_participants, (max_participants - current_participants) as available_slots FROM workshops ORDER BY id DESC");

if ($workshops) {
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>ID</th><th>Workshop</th><th>Max</th><th>Current</th><th>Available</th><th>Status</th></tr>";
    while ($workshop = mysqli_fetch_assoc($workshops)) {
        $status = $workshop['available_slots'] <= 0 ? 'Penuh' : ($workshop['available_slots'] <= 3 ? 'Hampir Penuh' : 'Tersedia');
        $statusColor = $workshop['available_slots'] <= 0 ? 'red' : ($workshop['available_slots'] <= 3 ? 'orange' : 'green');
        
        echo "<tr>";
        echo "<td>" . $workshop['id'] . "</td>";
        echo "<td>" . $workshop['title'] . "</td>";
        echo "<td>" . $workshop['max_participants'] . "</td>";
        echo "<td>" . $workshop['current_participants'] . "</td>";
        echo "<td>" . $workshop['available_slots'] . "</td>";
        echo "<td style='color: $statusColor; font-weight: bold;'>" . $status . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "❌ Failed to get workshops: " . mysqli_error($conn) . "<br>";
}

// Test 2: Check recent registrations
echo "<h3>2. Recent Workshop Registrations</h3>";
$registrations = mysqli_query($conn, "SELECT wr.id, wr.workshop_title, wr.participant_name, wr.participant_email, wr.registration_date, w.current_participants, w.max_participants FROM workshop_registrations wr LEFT JOIN workshops w ON wr.workshop_title = w.title ORDER BY wr.registration_date DESC LIMIT 10");

if ($registrations) {
    if (mysqli_num_rows($registrations) > 0) {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>ID</th><th>Workshop</th><th>Participant</th><th>Email</th><th>Registration Date</th><th>Current/Max</th></tr>";
        while ($reg = mysqli_fetch_assoc($registrations)) {
            echo "<tr>";
            echo "<td>" . $reg['id'] . "</td>";
            echo "<td>" . $reg['workshop_title'] . "</td>";
            echo "<td>" . $reg['participant_name'] . "</td>";
            echo "<td>" . $reg['participant_email'] . "</td>";
            echo "<td>" . $reg['registration_date'] . "</td>";
            echo "<td>" . $reg['current_participants'] . "/" . $reg['max_participants'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "📋 No registrations found<br>";
    }
} else {
    echo "❌ Failed to get registrations: " . mysqli_error($conn) . "<br>";
}

// Test 3: Test slot update logic
echo "<h3>3. Test Slot Update Logic</h3>";
$testWorkshop = mysqli_query($conn, "SELECT id, title, max_participants, current_participants FROM workshops WHERE current_participants < max_participants LIMIT 1");
if ($testWorkshop && mysqli_num_rows($testWorkshop) > 0) {
    $workshop = mysqli_fetch_assoc($testWorkshop);
    echo "Testing with workshop: " . $workshop['title'] . "<br>";
    echo "Current participants: " . $workshop['current_participants'] . "<br>";
    echo "Max participants: " . $workshop['max_participants'] . "<br>";
    echo "Available slots: " . ($workshop['max_participants'] - $workshop['current_participants']) . "<br>";
    
    // Test the update query (without actually executing)
    $updateSql = "UPDATE workshops SET current_participants = current_participants + 1 WHERE id = " . $workshop['id'];
    echo "Update SQL: " . $updateSql . "<br>";
    
    $stmt = mysqli_prepare($conn, "UPDATE workshops SET current_participants = current_participants + 1 WHERE id = ?");
    if ($stmt) {
        echo "✅ Update query prepared successfully<br>";
        mysqli_stmt_close($stmt);
    } else {
        echo "❌ Failed to prepare update query: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "❌ No workshop available for testing (all workshops are full)<br>";
}

// Test 4: Check for duplicate registrations
echo "<h3>4. Check for Duplicate Registrations</h3>";
$duplicates = mysqli_query($conn, "SELECT user_id, workshop_title, COUNT(*) as count FROM workshop_registrations GROUP BY user_id, workshop_title HAVING COUNT(*) > 1");

if ($duplicates) {
    if (mysqli_num_rows($duplicates) > 0) {
        echo "⚠️ Found duplicate registrations:<br>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>User ID</th><th>Workshop</th><th>Count</th></tr>";
        while ($dup = mysqli_fetch_assoc($duplicates)) {
            echo "<tr>";
            echo "<td>" . $dup['user_id'] . "</td>";
            echo "<td>" . $dup['workshop_title'] . "</td>";
            echo "<td>" . $dup['count'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "✅ No duplicate registrations found<br>";
    }
} else {
    echo "❌ Failed to check duplicates: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);
?> 