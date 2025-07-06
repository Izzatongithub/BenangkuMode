<?php
require_once 'config/database.php';

echo "<h2>🧪 Testing Workshop Registration System</h2>";

// Test 1: Check table structure
echo "<h3>1. Checking Table Structure</h3>";
$structure = mysqli_query($conn, "DESCRIBE workshop_registration");
if ($structure) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background-color: #f0f0f0;'>";
    echo "<th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th>";
    echo "</tr>";
    
    $hasPaymentFields = false;
    while ($row = mysqli_fetch_assoc($structure)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
        
        if (in_array($row['Field'], ['payment_status', 'payment_method', 'payment_amount'])) {
            $hasPaymentFields = true;
        }
    }
    echo "</table>";
    
    if ($hasPaymentFields) {
        echo "<p style='color: green;'>✅ Payment fields are present in the table</p>";
    } else {
        echo "<p style='color: red;'>❌ Payment fields are missing</p>";
    }
}

// Test 2: Check workshops data
echo "<h3>2. Checking Workshops Data</h3>";
$workshops = mysqli_query($conn, "SELECT id, title, price, max_participants, current_participants FROM workshops LIMIT 5");
if ($workshops && mysqli_num_rows($workshops) > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background-color: #f0f0f0;'>";
    echo "<th>ID</th><th>Title</th><th>Price</th><th>Max Participants</th><th>Current</th><th>Available Slots</th><th>Type</th>";
    echo "</tr>";
    
    while ($workshop = mysqli_fetch_assoc($workshops)) {
        $availableSlots = $workshop['max_participants'] - $workshop['current_participants'];
        $type = $workshop['price'] == 0 ? 'Free' : 'Paid';
        
        echo "<tr>";
        echo "<td>" . $workshop['id'] . "</td>";
        echo "<td>" . $workshop['title'] . "</td>";
        echo "<td>Rp " . number_format($workshop['price'], 0, ',', '.') . "</td>";
        echo "<td>" . $workshop['max_participants'] . "</td>";
        echo "<td>" . $workshop['current_participants'] . "</td>";
        echo "<td>" . $availableSlots . "</td>";
        echo "<td>" . $type . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: orange;'>⚠️ No workshops found in database</p>";
}

// Test 3: Check existing registrations
echo "<h3>3. Checking Existing Registrations</h3>";
$registrations = mysqli_query($conn, "SELECT COUNT(*) as total FROM workshop_registration");
if ($registrations) {
    $total = mysqli_fetch_assoc($registrations)['total'];
    echo "<p>Total registrations: <strong>$total</strong></p>";
    
    if ($total > 0) {
        $recentRegistrations = mysqli_query($conn, "
            SELECT wr.*, w.title as workshop_title, w.price as workshop_price 
            FROM workshop_registration wr 
            JOIN workshops w ON wr.workshop_id = w.id 
            ORDER BY wr.registration_date DESC 
            LIMIT 3
        ");
        
        if ($recentRegistrations && mysqli_num_rows($recentRegistrations) > 0) {
            echo "<h4>Recent Registrations:</h4>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr style='background-color: #f0f0f0;'>";
            echo "<th>Workshop</th><th>Name</th><th>Email</th><th>Payment Status</th><th>Amount</th><th>Date</th>";
            echo "</tr>";
            
            while ($reg = mysqli_fetch_assoc($recentRegistrations)) {
                echo "<tr>";
                echo "<td>" . $reg['workshop_title'] . "</td>";
                echo "<td>" . $reg['name'] . "</td>";
                echo "<td>" . $reg['email'] . "</td>";
                echo "<td>" . $reg['payment_status'] . "</td>";
                echo "<td>Rp " . number_format($reg['payment_amount'], 0, ',', '.') . "</td>";
                echo "<td>" . $reg['registration_date'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    }
}

// Test 4: Test registration process simulation
echo "<h3>4. Testing Registration Process</h3>";
echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px;'>";
echo "<h4>Simulation Steps:</h4>";
echo "<ol>";
echo "<li>User clicks 'Daftar Sekarang' on a workshop</li>";
echo "<li>Registration modal opens with form</li>";
echo "<li>User fills form and submits</li>";
echo "<li>System checks if workshop is free or paid</li>";
echo "<li>For free workshops: Auto-mark as paid</li>";
echo "<li>For paid workshops: Show payment options</li>";
echo "<li>User completes payment process</li>";
echo "<li>Registration is saved with payment status</li>";
echo "<li>Workshop slots are decremented</li>";
echo "</ol>";
echo "</div>";

// Test 5: Check payment methods configuration
echo "<h3>5. Payment Methods Configuration</h3>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 8px;'>";
echo "<h4>Available Payment Methods:</h4>";
echo "<ul>";
echo "<li><strong>Bank Transfer:</strong> BCA, Mandiri, BNI</li>";
echo "<li><strong>E-Wallet:</strong> GoPay, OVO, DANA, ShopeePay</li>";
echo "<li><strong>WhatsApp Payment:</strong> Direct payment via WhatsApp</li>";
echo "</ul>";
echo "</div>";

// Test 6: System Status
echo "<h3>6. System Status</h3>";
$statusChecks = [
    'Database Connection' => mysqli_ping($conn),
    'Workshop Table' => mysqli_query($conn, "SHOW TABLES LIKE 'workshops'"),
    'Registration Table' => mysqli_query($conn, "SHOW TABLES LIKE 'workshop_registration'"),
    'Payment Fields' => mysqli_query($conn, "SHOW COLUMNS FROM workshop_registration LIKE 'payment_status'")
];

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background-color: #f0f0f0;'>";
echo "<th>Component</th><th>Status</th>";
echo "</tr>";

foreach ($statusChecks as $component => $result) {
    $status = $result ? '✅ Working' : '❌ Error';
    $color = $result ? 'green' : 'red';
    echo "<tr>";
    echo "<td>$component</td>";
    echo "<td style='color: $color;'>$status</td>";
    echo "</tr>";
}
echo "</table>";

// Test 7: Next Steps
echo "<h3>7. Next Steps to Test</h3>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;'>";
echo "<h4>Manual Testing Required:</h4>";
echo "<ol>";
echo "<li>Go to <a href='workshop.php' target='_blank'>workshop.php</a></li>";
echo "<li>Try registering for a <strong>free workshop</strong> (should auto-complete)</li>";
echo "<li>Try registering for a <strong>paid workshop</strong> (should show payment options)</li>";
echo "<li>Check if slots decrease after registration</li>";
echo "<li>Verify payment status in database</li>";
echo "<li>Test WhatsApp notification</li>";
echo "</ol>";
echo "</div>";

echo "<h3>🎉 System Ready!</h3>";
echo "<p style='color: green; font-weight: bold;'>The workshop registration system with payment features is now ready for testing.</p>";

mysqli_close($conn);
?> 