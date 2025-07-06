<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Get statistics
$conn = getDbConnection();

// Total users
$sql_users = "SELECT COUNT(*) as total FROM users WHERE role = 'customer'";
$result_users = mysqli_query($conn, $sql_users);
$total_users = mysqli_fetch_assoc($result_users)['total'];

// Total products
$sql_products = "SELECT COUNT(*) as total FROM products";
$result_products = mysqli_query($conn, $sql_products);
$total_products = mysqli_fetch_assoc($result_products)['total'];

// Total destinations
$sql_destinations = "SELECT COUNT(*) as total FROM destinations";
$result_destinations = mysqli_query($conn, $sql_destinations);
$total_destinations = mysqli_fetch_assoc($result_destinations)['total'];

// Total orders
$sql_orders = "SELECT COUNT(*) as total FROM order_items";
$result_orders = mysqli_query($conn, $sql_orders);
$total_orders = mysqli_fetch_assoc($result_orders)['total'];

// Recent activities
$sql_activities = "SELECT al.*, u.full_name FROM activity_logs al 
                   LEFT JOIN users u ON al.user_id = u.id 
                   ORDER BY al.created_at DESC LIMIT 10";
$result_activities = mysqli_query($conn, $sql_activities);

// Recent orders
$sql_recent_orders = "SELECT oi.*, u.full_name, p.name as product_name 
                      FROM order_items oi 
                      LEFT JOIN orders o ON oi.order_id = o.id
                      LEFT JOIN users u ON o.customer_email = u.email
                      LEFT JOIN products p ON oi.product_id = p.id 
                      ORDER BY oi.created_at DESC LIMIT 5";
$result_recent_orders = mysqli_query($conn, $sql_recent_orders);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BenangkuMode</title>
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
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border-left: 4px solid #667eea;
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        .bg-primary-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-success-gradient { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
        .bg-warning-gradient { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); }
        .bg-info-gradient { background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%); }
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
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="users.php">
                            <i class="fas fa-users me-2"></i>Users
                        </a>
                        <a class="nav-link" href="products.php">
                            <i class="fas fa-box me-2"></i>Products
                        </a>
<<<<<<< HEAD
                        <a class="nav-link" href="workshops.php">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Workshops
                        </a>
                        <a class="nav-link" href="verifikasi_pembayaran.php">
                            <i class="fas fa-user-check me-2"></i>Pendaftar Workshop
                        </a>
                        <a class="nav-link" href="peserta_workshop.php">
                            <i class="fas fa-users me-2"></i>Peserta per Workshop
=======
                        <a class="nav-link" href="comingsoon.php">
                            <i class="fas fa-clock-rotate-left me-2"></i>Add Coming Soon
>>>>>>> 5439e2e0db5e73709aef8cce568866b3104253fe
                        </a>
                        <a class="nav-link" href="destinations.php">
                            <i class="fas fa-map-marker-alt me-2"></i>Destinations
                        </a>
                        <a class="nav-link" href="orders.php">
                            <i class="fas fa-shopping-cart me-2"></i>Orders
                        </a>
                        <a class="nav-link" href="settings.php">
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
                        <h2>Dashboard</h2>
                        <div class="text-muted">
                            Welcome back, <?php echo $_SESSION['user_name']; ?>!
                        </div>
                    </div>
                    
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-primary-gradient me-3">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0"><?php echo $total_users; ?></h3>
                                        <small class="text-muted">Total Users</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-success-gradient me-3">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0"><?php echo $total_products; ?></h3>
                                        <small class="text-muted">Total Products</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-warning-gradient me-3">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0"><?php echo $total_destinations; ?></h3>
                                        <small class="text-muted">Destinations</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-info-gradient me-3">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0"><?php echo $total_orders; ?></h3>
                                        <small class="text-muted">Total Orders</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Recent Orders -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Recent Orders</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (mysqli_num_rows($result_recent_orders) > 0): ?>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Customer</th>
                                                        <th>Product</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($order = mysqli_fetch_assoc($result_recent_orders)): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                                                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                                            <td>
                                                                <span class="badge bg-<?php echo $order['status'] === 'completed' ? 'success' : 'warning'; ?>">
                                                                    <?php echo ucfirst($order['status']); ?>
                                                                </span>
                                                            </td>
                                                            <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted text-center">No recent orders</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Activities -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Activities</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (mysqli_num_rows($result_activities) > 0): ?>
                                        <div class="activity-list">
                                            <?php while ($activity = mysqli_fetch_assoc($result_activities)): ?>
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="bg-primary rounded-circle p-2 me-3">
                                                        <i class="fas fa-user text-white" style="font-size: 12px;"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-bold"><?php echo htmlspecialchars($activity['full_name'] ?: 'System'); ?></div>
                                                        <div class="text-muted small"><?php echo htmlspecialchars($activity['action']); ?></div>
                                                        <div class="text-muted small"><?php echo date('d/m/Y H:i', strtotime($activity['created_at'])); ?></div>
                                                    </div>
                                                </div>
                                            <?php endwhile; ?>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted text-center">No recent activities</p>
                                    <?php endif; ?>
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