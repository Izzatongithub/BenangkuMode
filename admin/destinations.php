<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$conn = getDbConnection();
$message = '';

// Handle destination actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $destination_id = mysqli_real_escape_string($conn, $_POST['destination_id']);
        
        if ($_POST['action'] === 'delete') {
            // Get destination image first
            $sql_get = "SELECT image FROM destinations WHERE id = '$destination_id'";
            $result_get = mysqli_query($conn, $sql_get);
            $destination = mysqli_fetch_assoc($result_get);
            
            // Delete from database
            $sql = "DELETE FROM destinations WHERE id = '$destination_id'";
            if (mysqli_query($conn, $sql)) {
                // Delete image file if exists
                if ($destination && $destination['image']) {
                    $image_path = '../assets/images/destinations/' . $destination['image'];
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                }
                $message = 'Destination deleted successfully';
                logActivity('delete_destination', "Deleted destination ID: $destination_id");
            } else {
                $message = 'Error deleting destination';
            }
        } elseif ($_POST['action'] === 'toggle_status') {
            $sql = "UPDATE destinations SET is_active = NOT is_active WHERE id = '$destination_id'";
            if (mysqli_query($conn, $sql)) {
                $message = 'Destination status updated successfully';
                logActivity('toggle_destination_status', "Toggled status for destination ID: $destination_id");
            } else {
                $message = 'Error updating destination status';
            }
        }
    }
}

// Get destinations list
$sql = "SELECT * FROM destinations ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinations Management - Admin</title>
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
        .destination-image {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
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
                            <i class="fas fa-clock-rotate-left me-2"></i>Add Coming Soon
                        </a>
                        <a class="nav-link" href="workshops.php">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Workshops
                        </a>
                        <a class="nav-link" href="verifikasi_pembayaran.php">
                            <i class="fas fa-user-check me-2"></i>Pendaftar Workshop
                        </a>
                        <a class="nav-link" href="peserta_workshop.php">
                            <i class="fas fa-users me-2"></i>Peserta per Workshop
                        </a>
                        <a class="nav-link active" href="destinations.php">
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
                        <h2>Destinations Management</h2>
                        <a href="add_destination.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New Destination
                        </a>
                    </div>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <?php echo $message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">All Destinations</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Location</th>
                                            <th>Price</th>
                                            <th>Rating</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($destination = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td>
                                                    <?php if ($destination['main_image']): ?>
                                                        <img src="../assets/images/destinations/<?php echo htmlspecialchars($destination['main_image']); ?>" 
                                                             alt="<?php echo htmlspecialchars($destination['name']); ?>" 
                                                             class="destination-image">
                                                    <?php else: ?>
                                                        <div class="destination-image bg-light d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($destination['name']); ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($destination['description']); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($destination['location']); ?></td>
                                                <td><?php echo formatCurrency($destination['ticket_price']); ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="text-warning me-1">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <i class="fas fa-star<?php echo $i <= $destination['rating'] ? '' : '-o'; ?>"></i>
                                                            <?php endfor; ?>
                                                        </span>
                                                        <span class="text-muted">(<?php echo $destination['rating']; ?>)</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo $destination['is_active'] ? 'success' : 'secondary'; ?>">
                                                        <?php echo $destination['is_active'] ? 'Active' : 'Inactive'; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('d/m/Y', strtotime($destination['created_at'])); ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="edit_destination.php?id=<?php echo $destination['id']; ?>" 
                                                           class="btn btn-outline-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="POST" style="display: inline;" 
                                                              onsubmit="return confirm('Are you sure you want to toggle this destination status?')">
                                                            <input type="hidden" name="destination_id" value="<?php echo $destination['id']; ?>">
                                                            <input type="hidden" name="action" value="toggle_status">
                                                            <button type="submit" class="btn btn-outline-warning">
                                                                <i class="fas fa-toggle-on"></i>
                                                            </button>
                                                        </form>
                                                        <form method="POST" style="display: inline;" 
                                                              onsubmit="return confirm('Are you sure you want to delete this destination?')">
                                                            <input type="hidden" name="destination_id" value="<?php echo $destination['id']; ?>">
                                                            <input type="hidden" name="action" value="delete">
                                                            <button type="submit" class="btn btn-outline-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
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