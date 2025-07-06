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

// Get destination ID from URL
$destination_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$destination_id) {
    redirect('destinations.php');
}

// Get destination data
$sql = "SELECT * FROM destinations WHERE id = $destination_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    redirect('destinations.php');
}

$destination = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Validation
    if (empty($name) || empty($description) || empty($location) || empty($price) || empty($rating)) {
        $error = 'All fields are required';
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = 'Price must be a positive number';
    } elseif (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        $error = 'Rating must be between 1 and 5';
    } else {
        // Handle image upload
        $image_name = $destination['image']; // Keep existing image by default
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            
            if (!in_array($file_extension, $allowed_types)) {
                $error = 'Invalid image format. Allowed: JPG, JPEG, PNG, GIF';
            } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) { // 5MB
                $error = 'Image size too large. Maximum 5MB';
            } else {
                $new_image_name = uniqid() . '.' . $file_extension;
                $upload_path = '../assets/images/destinations/' . $new_image_name;
                
                // Create directory if not exists
                if (!is_dir('../assets/images/destinations/')) {
                    mkdir('../assets/images/destinations/', 0755, true);
                }
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    // Delete old image if exists
                    if ($destination['image'] && file_exists('../assets/images/destinations/' . $destination['image'])) {
                        unlink('../assets/images/destinations/' . $destination['image']);
                    }
                    $image_name = $new_image_name;
                } else {
                    $error = 'Error uploading image';
                }
            }
        }
        
        if (empty($error)) {
            // Update destination
            $sql = "UPDATE destinations SET name = '$name', description = '$description', location = '$location', 
                    price = $price, rating = $rating, image = '$image_name', is_active = $is_active 
                    WHERE id = $destination_id";
            
            if (mysqli_query($conn, $sql)) {
                $message = 'Destination updated successfully';
                logActivity('update_destination', "Updated destination ID: $destination_id");
                
                // Refresh destination data
                $result = mysqli_query($conn, "SELECT * FROM destinations WHERE id = $destination_id");
                $destination = mysqli_fetch_assoc($result);
            } else {
                $error = 'Error updating destination: ' . mysqli_error($conn);
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
    <title>Edit Destination - Admin</title>
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
                        <h2>Edit Destination</h2>
                        <a href="destinations.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Destinations
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
                            <h5 class="mb-0">Destination Information</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Destination Name *</label>
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   value="<?php echo htmlspecialchars($destination['name']); ?>" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description *</label>
                                            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($destination['description']); ?></textarea>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="location" class="form-label">Location *</label>
                                            <input type="text" class="form-control" id="location" name="location" 
                                                   value="<?php echo htmlspecialchars($destination['location']); ?>" required>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="price" class="form-label">Price (Rp) *</label>
                                                    <input type="number" class="form-control" id="price" name="price" 
                                                           value="<?php echo $destination['price']; ?>" min="0" step="1000" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="rating" class="form-label">Rating (1-5) *</label>
                                                    <select class="form-select" id="rating" name="rating" required>
                                                        <option value="1" <?php echo $destination['rating'] == 1 ? 'selected' : ''; ?>>1 Star</option>
                                                        <option value="2" <?php echo $destination['rating'] == 2 ? 'selected' : ''; ?>>2 Stars</option>
                                                        <option value="3" <?php echo $destination['rating'] == 3 ? 'selected' : ''; ?>>3 Stars</option>
                                                        <option value="4" <?php echo $destination['rating'] == 4 ? 'selected' : ''; ?>>4 Stars</option>
                                                        <option value="5" <?php echo $destination['rating'] == 5 ? 'selected' : ''; ?>>5 Stars</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                       <?php echo $destination['is_active'] ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="is_active">
                                                    Active Destination
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Destination Image</label>
                                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                            <div class="form-text">Max 5MB. Allowed: JPG, JPEG, PNG, GIF</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Current Image</label>
                                            <div class="image-preview d-flex align-items-center justify-content-center bg-light">
                                                <?php if ($destination['image']): ?>
                                                    <img src="../assets/images/destinations/<?php echo htmlspecialchars($destination['image']); ?>" 
                                                         alt="<?php echo htmlspecialchars($destination['name']); ?>" 
                                                         class="img-fluid" style="max-width: 100%; max-height: 200px; border-radius: 8px;">
                                                <?php else: ?>
                                                    <i class="fas fa-image text-muted fa-3x"></i>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Destination
                                    </button>
                                    <a href="destinations.php" class="btn btn-secondary">
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
                // Show current image
                <?php if ($destination['image']): ?>
                    preview.innerHTML = `<img src="../assets/images/destinations/<?php echo htmlspecialchars($destination['image']); ?>" class="img-fluid" style="max-width: 100%; max-height: 200px; border-radius: 8px;">`;
                <?php else: ?>
                    preview.innerHTML = '<i class="fas fa-image text-muted fa-3x"></i>';
                <?php endif; ?>
            }
        });
    </script>
</body>
</html> 