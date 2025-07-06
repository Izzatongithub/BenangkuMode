<?php
session_start();
require_once '../config/database.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$conn = getDbConnection();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
        
        if ($_POST['action'] === 'delete') {
            // Get product image first
            $sql_get = "SELECT image FROM coming_soon_products WHERE id = '$product_id'";
            $result_get = mysqli_query($conn, $sql_get);
            $product = mysqli_fetch_assoc($result_get);
            
            // Delete from database
            $sql = "DELETE FROM coming_soon_products WHERE id = '$product_id'";
            if (mysqli_query($conn, $sql)) {
                // Delete image file if exists
                if ($product && $product['image']) {
                    $image_path = '../assets/images/products/' . $product['image'];
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                }
                $message = 'Product deleted successfully';
                logActivity('delete_product', "Deleted product ID: $product_id");
            } else {
                $message = 'Error deleting product';
            }
        } elseif ($_POST['action'] === 'toggle_status') {
            $sql = "UPDATE coming_soon_products SET is_active = NOT is_active WHERE id = '$product_id'";
            if (mysqli_query($conn, $sql)) {
                $message = 'Product status updated successfully';
                logActivity('toggle_product_status', "Toggled status for product ID: $product_id");
            } else {
                $message = 'Error updating product status';
            }
        }
    }
}

$sql = "SELECT *
        FROM coming_soon_products
        WHERE is_active = 1
        ORDER BY estimated_release_date ASC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk Coming Soon</title>
    <link rel="stylesheet" href="../assets/css/style.css">
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
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="users.php">
                            <i class="fas fa-users me-2"></i>Users
                        </a>
                        <a class="nav-link" href="products.php">
                            <i class="fas fa-box me-2"></i>Products
                        </a>
                        <a class="nav-link active" href="comingsoon.php">
                            <i class="fas fa-clock-rotate-left me-2"></i>Tambah Coming Soon
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

            <!-- Main content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Coming Soon Product Management</h2>
                        <a href="add_product.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New Product
                        </a>
                    </div>
                    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Coming Soon Products</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Estimated Price</th>
                                            <th>Estimated Release Date</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($product = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td>
                                                    <?php if ($product['image']): ?>
                                                        <img src="../assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                                                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                                             class="product-image">
                                                    <?php else: ?>
                                                        <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($product['description']); ?></small>
                                                </td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($product['estimated_price']); ?></strong>
                                                </td>
                                                <td>
                                                    <?php echo date('d/m/Y', strtotime($product['estimated_release_date'])); ?>
                                                </td>
                                                <td>
                                                    <?php echo date('d/m/Y', strtotime($product['created_at'])); ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="edit_product.php?id=<?php echo $product['id']; ?>" 
                                                           class="btn btn-outline-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form method="POST" style="display: inline;" 
                                                              onsubmit="return confirm('Are you sure you want to toggle this product status?')">
                                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                            <input type="hidden" name="action" value="toggle_status">
                                                            <button type="submit" class="btn btn-outline-warning">
                                                                <i class="fas fa-toggle-on"></i>
                                                            </button>
                                                        </form>
                                                        <form method="POST" style="display: inline;" 
                                                              onsubmit="return confirm('Are you sure you want to delete this product?')">
                                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
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

</body>
</html> 