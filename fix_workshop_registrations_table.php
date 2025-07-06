<?php
require_once 'config/database.php';

echo "<h2>Fix Workshop Registrations Table</h2>";

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
} else {
    echo "❌ Failed to get table structure: " . mysqli_error($conn) . "<br>";
}

// Check if user_id column exists
echo "<h3>2. Check for user_id Column</h3>";
$columns = [];
$result = mysqli_query($conn, "DESCRIBE workshop_registrations");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $columns[] = $row['Field'];
    }
}

if (in_array('user_id', $columns)) {
    echo "✅ Column 'user_id' exists<br>";
} else {
    echo "❌ Column 'user_id' does not exist<br>";
    echo "Available columns: " . implode(', ', $columns) . "<br>";
    
    // Add user_id column if it doesn't exist
    echo "<h3>3. Adding user_id Column</h3>";
    $addColumnSql = "ALTER TABLE workshop_registrations ADD COLUMN user_id int(11) NOT NULL AFTER id";
    if (mysqli_query($conn, $addColumnSql)) {
        echo "✅ Successfully added user_id column<br>";
        
        // Add index for user_id
        $addIndexSql = "ALTER TABLE workshop_registrations ADD INDEX user_id (user_id)";
        if (mysqli_query($conn, $addIndexSql)) {
            echo "✅ Successfully added index for user_id<br>";
        } else {
            echo "❌ Failed to add index: " . mysqli_error($conn) . "<br>";
        }
        
        // Add foreign key constraint
        $addFkSql = "ALTER TABLE workshop_registrations ADD CONSTRAINT workshop_registrations_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE";
        if (mysqli_query($conn, $addFkSql)) {
            echo "✅ Successfully added foreign key constraint<br>";
        } else {
            echo "❌ Failed to add foreign key constraint: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "❌ Failed to add user_id column: " . mysqli_error($conn) . "<br>";
    }
}

// Check if other required columns exist
echo "<h3>4. Check Required Columns</h3>";
$requiredColumns = [
    'workshop_title' => 'varchar(255)',
    'participant_name' => 'varchar(255)',
    'participant_email' => 'varchar(255)',
    'participant_phone' => 'varchar(50)',
    'participant_age' => 'int(3)',
    'experience_level' => 'enum',
    'special_needs' => 'text',
    'registration_date' => 'datetime',
    'status' => 'enum'
];

foreach ($requiredColumns as $column => $type) {
    if (in_array($column, $columns)) {
        echo "✅ Column '$column' exists<br>";
    } else {
        echo "❌ Column '$column' missing<br>";
        
        // Add missing column
        $addColumnSql = "ALTER TABLE workshop_registrations ADD COLUMN $column ";
        switch ($column) {
            case 'workshop_title':
                $addColumnSql .= "varchar(255) NOT NULL";
                break;
            case 'participant_name':
                $addColumnSql .= "varchar(255) NOT NULL";
                break;
            case 'participant_email':
                $addColumnSql .= "varchar(255) NOT NULL";
                break;
            case 'participant_phone':
                $addColumnSql .= "varchar(50) NOT NULL";
                break;
            case 'participant_age':
                $addColumnSql .= "int(3) DEFAULT NULL";
                break;
            case 'experience_level':
                $addColumnSql .= "enum('beginner','intermediate','advanced') DEFAULT 'beginner'";
                break;
            case 'special_needs':
                $addColumnSql .= "text DEFAULT NULL";
                break;
            case 'registration_date':
                $addColumnSql .= "datetime NOT NULL DEFAULT CURRENT_TIMESTAMP";
                break;
            case 'status':
                $addColumnSql .= "enum('pending','confirmed','cancelled') DEFAULT 'pending'";
                break;
        }
        
        if (mysqli_query($conn, $addColumnSql)) {
            echo "✅ Successfully added column '$column'<br>";
        } else {
            echo "❌ Failed to add column '$column': " . mysqli_error($conn) . "<br>";
        }
    }
}

// Show final table structure
echo "<h3>5. Final Table Structure</h3>";
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

// Test insert query
echo "<h3>6. Test Insert Query</h3>";
$testSql = "INSERT INTO workshop_registrations (user_id, workshop_title, participant_name, participant_email, participant_phone, participant_age, experience_level, special_needs, registration_date) VALUES (1, 'Test Workshop', 'Test User', 'test@example.com', '08123456789', 25, 'beginner', 'Test needs', NOW())";

$stmt = mysqli_prepare($conn, $testSql);
if ($stmt) {
    echo "✅ Insert query prepared successfully<br>";
    
    // Test execution (but don't actually insert)
    if (mysqli_stmt_execute($stmt)) {
        echo "✅ Insert query executed successfully<br>";
        // Rollback the test insert
        mysqli_rollback($conn);
        echo "✅ Test insert rolled back<br>";
    } else {
        echo "❌ Insert query execution failed: " . mysqli_stmt_error($stmt) . "<br>";
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo "❌ Failed to prepare insert query: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);
?> 