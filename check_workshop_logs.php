<?php
echo "<h2>Workshop Registration Debug Information</h2>";

// Check PHP error log location
echo "<h3>1. PHP Error Log Location</h3>";
echo "PHP Error Log: " . ini_get('error_log') . "<br>";
echo "Display Errors: " . (ini_get('display_errors') ? 'On' : 'Off') . "<br>";
echo "Error Reporting: " . ini_get('error_reporting') . "<br>";

// Check if we can write to error log
echo "<h3>2. Error Log Test</h3>";
$testMessage = "Test workshop registration log - " . date('Y-m-d H:i:s');
if (error_log($testMessage)) {
    echo "✅ Error log write test successful<br>";
} else {
    echo "❌ Error log write test failed<br>";
}

// Test database connection
echo "<h3>3. Database Connection Test</h3>";
require_once 'config/database.php';

if ($conn) {
    echo "✅ Database connection successful<br>";
    
    // Test simple query
    $testQuery = mysqli_query($conn, "SELECT 1 as test");
    if ($testQuery) {
        echo "✅ Database query test successful<br>";
    } else {
        echo "❌ Database query test failed: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "❌ Database connection failed<br>";
}

// Check session
echo "<h3>4. Session Test</h3>";
session_start();
if (isset($_SESSION['user_id'])) {
    echo "✅ User session exists - User ID: " . $_SESSION['user_id'] . "<br>";
} else {
    echo "❌ No user session found<br>";
}

// Test form data simulation
echo "<h3>5. Form Data Test</h3>";
$_POST['workshopTitle'] = 'Test Workshop';
$_POST['name'] = 'Test User';
$_POST['email'] = 'test@example.com';
$_POST['phone'] = '08123456789';
$_POST['age'] = '25';
$_POST['experience'] = 'beginner';
$_POST['specialNeeds'] = 'Test needs';

echo "Simulated form data:<br>";
foreach ($_POST as $key => $value) {
    echo "- $key: $value<br>";
}

// Test the registration logic
echo "<h3>6. Registration Logic Test</h3>";
if (isset($_SESSION['user_id'])) {
    try {
        // Check if table exists
        $tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'workshop_registrations'");
        if (mysqli_num_rows($tableCheck) > 0) {
            echo "✅ Table workshop_registrations exists<br>";
            
            // Check if user exists
            $userCheck = mysqli_query($conn, "SELECT id FROM users WHERE id = " . intval($_SESSION['user_id']));
            if (mysqli_num_rows($userCheck) > 0) {
                echo "✅ User exists in database<br>";
                
                // Test insert query preparation
                $sql = "INSERT INTO workshop_registrations (user_id, workshop_title, participant_name, participant_email, participant_phone, participant_age, experience_level, special_needs, registration_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                $stmt = mysqli_prepare($conn, $sql);
                
                if ($stmt) {
                    echo "✅ Insert query prepared successfully<br>";
                    
                    // Test parameter binding
                    $ageValue = empty($_POST['age']) ? null : intval($_POST['age']);
                    $bindResult = mysqli_stmt_bind_param($stmt, "isssssss", 
                        $_SESSION['user_id'],
                        $_POST['workshopTitle'],
                        $_POST['name'],
                        $_POST['email'],
                        $_POST['phone'],
                        $ageValue,
                        $_POST['experience'],
                        $_POST['specialNeeds']
                    );
                    
                    if ($bindResult) {
                        echo "✅ Parameter binding successful<br>";
                    } else {
                        echo "❌ Parameter binding failed<br>";
                    }
                    
                    mysqli_stmt_close($stmt);
                } else {
                    echo "❌ Failed to prepare insert query: " . mysqli_error($conn) . "<br>";
                }
            } else {
                echo "❌ User not found in database<br>";
            }
        } else {
            echo "❌ Table workshop_registrations does not exist<br>";
        }
    } catch (Exception $e) {
        echo "❌ Exception: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ Cannot test registration logic - no user session<br>";
}

mysqli_close($conn);
?> 