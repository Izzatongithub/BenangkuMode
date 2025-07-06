<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$conn = getDbConnection();
$message = '';

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update_settings') {
        $site_name = mysqli_real_escape_string($conn, $_POST['site_name']);
        $site_description = mysqli_real_escape_string($conn, $_POST['site_description']);
        $contact_email = mysqli_real_escape_string($conn, $_POST['contact_email']);
        $contact_phone = mysqli_real_escape_string($conn, $_POST['contact_phone']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        
        // Update settings
        $settings = [
            'site_name' => $site_name,
            'site_description' => $site_description,
            'contact_email' => $contact_email,
            'contact_phone' => $contact_phone,
            'address' => $address
        ];
        
        $success = true;
        foreach ($settings as $key => $value) {
            $sql = "INSERT INTO settings (setting_key, setting_value) VALUES ('$key', '$value') 
                    ON DUPLICATE KEY UPDATE setting_value = '$value'";
            if (!mysqli_query($conn, $sql)) {
                $success = false;
                break;
            }
        }
        
        if ($success) {
            $message = 'Settings updated successfully';
            logActivity('update_settings', 'Updated website settings');
        } else {
            $message = 'Error updating settings';
        }
    }
}

// Get current settings
$sql = "SELECT setting_key, setting_value FROM settings";
$result = mysqli_query($conn, $sql);
$settings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Get system info
$sql_users = "SELECT COUNT(*) as total FROM users WHERE role = 'customer'";
$result_users = mysqli_query($conn, $sql_users);
$total_users = mysqli_fetch_assoc($result_users)['total'];

$sql_products = "SELECT COUNT(*) as total FROM products";
$result_products = mysqli_query($conn, $sql_products);
$total_products = mysqli_fetch_assoc($result_products)['total'];

$sql_orders = "SELECT COUNT(*) as total FROM order_items";
$result_orders = mysqli_query($conn, $sql_orders);
$total_orders = mysqli_fetch_assoc($result_orders)['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 0;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border-left: 4px solid #667eea;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar p-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white">Admin Panel</h4>
                        <small class="text-white-50">BenangkuMode</small>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="users.php">
                            <i class="fas fa-users me-2"></i>Users
                        </a>
                        <a class="nav-link" href="products.php">
                            <i class="fas fa-box me-2"></i>Products
                        </a>
                        <a class="nav-link" href="comingsoon.php">
                            <i class="fas fa-clock-rotate-left me-2"></i>Tambah Coming Soon
                        </a>
                        <a class="nav-link" href="destinations.php">
                            <i class="fas fa-map-marker-alt me-2"></i>Destinations
                        </a>
                        <a class="nav-link" href="orders.php">
                            <i class="fas fa-shopping-cart me-2"></i>Orders
                        </a>
                        <a class="nav-link active" href="settings.php">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                        <hr class="text-white-50">
                        <a class="nav-link" href="../index.php">
                            <i class="fas fa-home me-2"></i>Back to Site
                        </a>
                        <a class="nav-link" href="../logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Settings</h2>
                    </div>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <?php echo $message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <!-- Website Settings -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Website Settings</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <input type="hidden" name="action" value="update_settings">
                                        
                                        <div class="mb-3">
                                            <label for="site_name" class="form-label">Site Name</label>
                                            <input type="text" class="form-control" id="site_name" name="site_name" 
                                                   value="<?php echo htmlspecialchars($settings['site_name'] ?? 'BenangkuMode'); ?>" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="site_description" class="form-label">Site Description</label>
                                            <textarea class="form-control" id="site_description" name="site_description" rows="3"><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="contact_email" class="form-label">Contact Email</label>
                                            <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                                   value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="contact_phone" class="form-label">Contact Phone</label>
                                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" 
                                                   value="<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($settings['address'] ?? ''); ?></textarea>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Save Settings
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- System Info -->
                        <div class="col-md-4">
                            <div class="info-card mb-3">
                                <h6><i class="fas fa-info-circle me-2"></i>System Information</h6>
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="h4 text-primary"><?php echo $total_users; ?></div>
                                        <small class="text-muted">Users</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="h4 text-success"><?php echo $total_products; ?></div>
                                        <small class="text-muted">Products</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="h4 text-warning"><?php echo $total_orders; ?></div>
                                        <small class="text-muted">Orders</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="info-card mb-3">
                                <h6><i class="fas fa-server me-2"></i>Server Info</h6>
                                <div class="small">
                                    <div class="d-flex justify-content-between">
                                        <span>PHP Version:</span>
                                        <span><?php echo PHP_VERSION; ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>MySQL Version:</span>
                                        <span><?php echo mysqli_get_server_info($conn); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Server Time:</span>
                                        <span><?php echo date('Y-m-d H:i:s'); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="info-card">
                                <h6><i class="fas fa-user me-2"></i>Current Admin</h6>
                                <div class="small">
                                    <div><strong>Name:</strong> <?php echo $_SESSION['user_name']; ?></div>
                                    <div><strong>Email:</strong> <?php echo $_SESSION['user_email']; ?></div>
                                    <div><strong>Role:</strong> <?php echo ucfirst($_SESSION['user_role']); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 