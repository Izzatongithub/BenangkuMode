<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$conn = getDbConnection();
$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    
    // Validation
    if (empty($name) || empty($description) || empty($category) || empty($price) || empty($stock)) {
        $error = 'All fields are required';
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = 'Price must be a positive number';
    } elseif (!is_numeric($stock) || $stock < 0) {
        $error = 'Stock must be a non-negative number';
    } else {
        // Handle image upload
        $image_name = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            
            if (!in_array($file_extension, $allowed_types)) {
                $error = 'Invalid image format. Allowed: JPG, JPEG, PNG, GIF';
            } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) { // 5MB
                $error = 'Image size too large. Maximum 5MB';
            } else {
                $image_name = uniqid() . '.' . $file_extension;
                $upload_path = '../assets/images/products/' . $image_name;
                
                // Create directory if not exists
                if (!is_dir('../assets/images/products/')) {
                    mkdir('../assets/images/products/', 0755, true);
                }
                
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $error = 'Error uploading image';
                }
            }
        }
        
        if (empty($error)) {
            // Insert product
            $sql = "INSERT INTO products (name, description, price, category_id, stock_quantity, image, is_active) 
                    VALUES ('$name', '$description', $price, $category, $stock, '$image_name', 1)";
            
            if (mysqli_query($conn, $sql)) {
                $message = 'Product added successfully';
                logActivity('add_product', "Added new product: $name");
                
                // Clear form
                $name = $description = $category = $price = $stock = '';
            } else {
                $error = 'Error adding product: ' . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Admin</title>
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
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            border: 2px dashed #ddd;
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
                        <a class="nav-link active" href="products.php">
                            <i class="fas fa-box me-2"></i>Products
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
                        <h2>Add New Product</h2>
                        <a href="products.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Products
                        </a>
                    </div>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo $message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Product Information</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Product Name *</label>
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description *</label>
                                            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($description ?? ''); ?></textarea>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="category" class="form-label">Category *</label>
                                                    <select class="form-select" id="category" name="category" required>
                                                        <!-- <option value="">Select Category</option> -->
                                                        <!-- <option value="clothing" <?php echo ($category ?? '') === 'clothing' ? 'selected' : ''; ?>>Clothing</option>
                                                        <option value="accessories" <?php echo ($category ?? '') === 'accessories' ? 'selected' : ''; ?>>Accessories</option>
                                                        <option value="shoes" <?php echo ($category ?? '') === 'shoes' ? 'selected' : ''; ?>>Shoes</option>
                                                        <option value="bags" <?php echo ($category ?? '') === 'bags' ? 'selected' : ''; ?>>Bags</option>
                                                        <option value="other" <?php echo ($category ?? '') === 'other' ? 'selected' : ''; ?>>Other</option> -->
                                                        <option selected>Select category</option>
                                                            <?php

                                                            $no = 1;
                                                            $qry = mysqli_query($conn, "SELECT * FROM product_categories");
                                                            while ($data = mysqli_fetch_array($qry)) {
                                                            ?>
                                                                <option data="<?= $data['name'] ?>" value="<?= $data['id'] ?>"><?= $data['name'] ?>
                                                                </option>
                                                            <?php }
                                                            ?>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="price" class="form-label">Price (Rp) *</label>
                                                    <input type="number" class="form-control" id="price" name="price" 
                                                           value="<?php echo htmlspecialchars($price ?? ''); ?>" min="0" step="1000" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="stock" class="form-label">Stock *</label>
                                                    <input type="number" class="form-control" id="stock" name="stock" 
                                                           value="<?php echo htmlspecialchars($stock ?? ''); ?>" min="0" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Product Image</label>
                                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                            <div class="form-text">Max 5MB. Allowed: JPG, JPEG, PNG, GIF</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Image Preview</label>
                                            <div class="image-preview d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-image text-muted fa-3x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Add Product
                                    </button>
                                    <a href="products.php" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image preview
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.querySelector('.image-preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="img-fluid" style="max-width: 100%; max-height: 200px; border-radius: 8px;">`;
                }
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '<i class="fas fa-image text-muted fa-3x"></i>';
            }
        });
    </script>
</body>
</html> 