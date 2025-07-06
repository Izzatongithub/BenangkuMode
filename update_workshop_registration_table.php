<?php
require_once 'config/database.php';

echo "<h2>Updating Workshop Registration Table</h2>";

// Check if table exists
$checkTable = "SHOW TABLES LIKE 'workshop_registration'";
$tableExists = mysqli_query($conn, $checkTable);

if (mysqli_num_rows($tableExists) == 0) {
    echo "<p style='color: red;'>❌ Table 'workshop_registration' does not exist!</p>";
    echo "<p>Please create the table first using the previous script.</p>";
    exit;
}

echo "<p style='color: green;'>✅ Table 'workshop_registration' exists</p>";

// Array of columns to add
$columnsToAdd = [
    'payment_status' => "ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending'",
    'payment_method' => "VARCHAR(50) NULL",
    'payment_amount' => "DECIMAL(10,2) DEFAULT 0.00",
    'payment_date' => "DATETIME NULL",
    'payment_proof' => "VARCHAR(255) NULL",
    'payment_instructions' => "TEXT NULL",
    'workshop_price' => "DECIMAL(10,2) DEFAULT 0.00",
    'registration_date' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
];

$successCount = 0;
$errorCount = 0;

foreach ($columnsToAdd as $columnName => $columnDefinition) {
    // Check if column already exists
    $checkColumn = "SHOW COLUMNS FROM workshop_registration LIKE '$columnName'";
    $columnExists = mysqli_query($conn, $checkColumn);
    
    if (mysqli_num_rows($columnExists) > 0) {
        echo "<p style='color: orange;'>⚠️ Column '$columnName' already exists</p>";
        continue;
    }
    
    // Add column
    $addColumn = "ALTER TABLE workshop_registration ADD COLUMN $columnName $columnDefinition";
    
    if (mysqli_query($conn, $addColumn)) {
        echo "<p style='color: green;'>✅ Added column '$columnName'</p>";
        $successCount++;
    } else {
        echo "<p style='color: red;'>❌ Failed to add column '$columnName': " . mysqli_error($conn) . "</p>";
        $errorCount++;
    }
}

// Add indexes for better performance
$indexes = [
    'idx_payment_status' => 'payment_status',
    'idx_registration_date' => 'registration_date',
    'idx_workshop_user' => 'workshop_id, user_id'
];

foreach ($indexes as $indexName => $columns) {
    // Check if index already exists
    $checkIndex = "SHOW INDEX FROM workshop_registration WHERE Key_name = '$indexName'";
    $indexExists = mysqli_query($conn, $checkIndex);
    
    if (mysqli_num_rows($indexExists) > 0) {
        echo "<p style='color: orange;'>⚠️ Index '$indexName' already exists</p>";
        continue;
    }
    
    // Add index
    $addIndex = "ALTER TABLE workshop_registration ADD INDEX $indexName ($columns)";
    
    if (mysqli_query($conn, $addIndex)) {
        echo "<p style='color: green;'>✅ Added index '$indexName'</p>";
        $successCount++;
    } else {
        echo "<p style='color: red;'>❌ Failed to add index '$indexName': " . mysqli_error($conn) . "</p>";
        $errorCount++;
    }
}

// Show final table structure
echo "<h3>Final Table Structure:</h3>";
$showStructure = "DESCRIBE workshop_registration";
$structure = mysqli_query($conn, $showStructure);

if ($structure) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background-color: #f0f0f0;'>";
    echo "<th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th>";
    echo "</tr>";
    
    while ($row = mysqli_fetch_assoc($structure)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<h3>Summary:</h3>";
echo "<p>✅ Successfully added: $successCount items</p>";
echo "<p>❌ Errors: $errorCount items</p>";

if ($errorCount == 0) {
    echo "<p style='color: green; font-weight: bold;'>🎉 Workshop registration table updated successfully!</p>";
    echo "<p>The table now supports:</p>";
    echo "<ul>";
    echo "<li>Payment status tracking (pending/paid/cancelled)</li>";
    echo "<li>Payment method and amount</li>";
    echo "<li>Payment date and proof</li>";
    echo "<li>Workshop price at registration time</li>";
    echo "<li>Registration timestamp</li>";
    echo "<li>Performance indexes</li>";
    echo "</ul>";
} else {
    echo "<p style='color: red; font-weight: bold;'>⚠️ Some errors occurred. Please check the details above.</p>";
}

mysqli_close($conn);
?> 