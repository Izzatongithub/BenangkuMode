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
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $description = mysqli_real_escape_string($conn, $_POST['description'] ?? '');
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id'] ?? '');
    $location = mysqli_real_escape_string($conn, $_POST['location'] ?? '');
    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $latitude = mysqli_real_escape_string($conn, $_POST['latitude'] ?? '');
    $longitude = mysqli_real_escape_string($conn, $_POST['longitude'] ?? '');
    $rating = mysqli_real_escape_string($conn, $_POST['rating'] ?? '0.00');
    $review_count = mysqli_real_escape_string($conn, $_POST['review_count'] ?? '0');
    $operating_hours = mysqli_real_escape_string($conn, $_POST['operating_hours'] ?? '');
    $ticket_price = mysqli_real_escape_string($conn, $_POST['ticket_price'] ?? '');
    $contact = mysqli_real_escape_string($conn, $_POST['contact'] ?? '');
    $features = mysqli_real_escape_string($conn, $_POST['features'] ?? '');
    $tips = mysqli_real_escape_string($conn, $_POST['tips'] ?? '');
    $facilities = mysqli_real_escape_string($conn, $_POST['facilities'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    // Validation
    if (empty($name) || empty($description)) {
        $error = 'Name and Description are required';
    } elseif (!empty($rating) && (!is_numeric($rating) || $rating < 0 || $rating > 5)) {
        $error = 'Rating must be between 0 and 5';
    } elseif (!empty($latitude) && !is_numeric($latitude)) {
        $error = 'Latitude must be a valid number';
    } elseif (!empty($longitude) && !is_numeric($longitude)) {
        $error = 'Longitude must be a valid number';
    } else {
        // Handle image upload
        $image_name = '';
        if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            $file_extension = strtolower(pathinfo($_FILES['main_image']['name'], PATHINFO_EXTENSION));
            
            if (!in_array($file_extension, $allowed_types)) {
                $error = 'Invalid image format. Allowed: JPG, JPEG, PNG, GIF';
            } elseif ($_FILES['main_image']['size'] > 5 * 1024 * 1024) { // 5MB
                $error = 'Image size too large. Maximum 5MB';
            } else {
                $image_name = uniqid() . '.' . $file_extension;
                $upload_path = '../assets/images/destinations/' . $image_name;
                
                // Create directory if not exists
                if (!is_dir('../assets/images/destinations/')) {
                    mkdir('../assets/images/destinations/', 0755, true);
                }
                
                if (!move_uploaded_file($_FILES['main_image']['tmp_name'], $upload_path)) {
                    $error = 'Error uploading image';
                }
            }
        }
        
        if (empty($error)) {
            // Insert destination (without features, tips, facilities first)
            $sql = "INSERT INTO destinations (name, description, category_id, location, address, latitude, longitude, rating, review_count, operating_hours, ticket_price, contact, main_image, is_active, is_featured) 
                    VALUES ('$name', '$description', " . ($category_id ? $category_id : "NULL") . ", '$location', '$address', " . ($latitude ? $latitude : "NULL") . ", " . ($longitude ? $longitude : "NULL") . ", $rating, $review_count, '$operating_hours', '$ticket_price', '$contact', '$image_name', $is_active, $is_featured)";
            
            if (mysqli_query($conn, $sql)) {
                $destination_id = mysqli_insert_id($conn);
                
                // Update with features, tips, facilities if they exist
                if (!empty($features) || !empty($tips) || !empty($facilities)) {
                    $update_sql = "UPDATE destinations SET ";
                    $update_parts = [];
                    
                    if (!empty($features)) {
                        // Try JSON format
                        $features_json = json_encode(explode("\n", trim($features)));
                        $update_parts[] = "features = '$features_json'";
                    }
                    if (!empty($tips)) {
                        // Try JSON format
                        $tips_json = json_encode(explode("\n", trim($tips)));
                        $update_parts[] = "tips = '$tips_json'";
                    }
                    if (!empty($facilities)) {
                        // Try JSON format
                        $facilities_json = json_encode(explode("\n", trim($facilities)));
                        $update_parts[] = "facilities = '$facilities_json'";
                    }
                    
                    if (!empty($update_parts)) {
                        $update_sql .= implode(', ', $update_parts);
                        $update_sql .= " WHERE id = $destination_id";
                        
                        if (!mysqli_query($conn, $update_sql)) {
                            // Log warning but don't fail the operation
                            error_log("Warning: Could not update features/tips/facilities for destination ID $destination_id: " . mysqli_error($conn));
                        }
                    }
                }
                
                $message = 'Destination added successfully';
                logActivity('add_destination', "Added new destination: $name");
                
                // Clear form
                $name = $description = $category_id = $location = $address = $latitude = $longitude = $rating = $review_count = $operating_hours = $ticket_price = $contact = $features = $tips = $facilities = '';
                $is_active = $is_featured = 0;
            } else {
                $error = 'Error adding destination: ' . mysqli_error($conn);
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
    <title>Add Destination - Admin</title>
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
                            <i class="fas fa-clock-rotate-left me-2"></i>Tambah Coming Soon
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
                        <h2>Add New Destination</h2>
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
                                <!-- Basic Information -->
                                <h6 class="mb-3 text-primary"><i class="fas fa-info-circle me-2"></i>Basic Information</h6>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Destination Name *</label>
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Category</label>
                                            <select class="form-select" id="category_id" name="category_id">
                                                <option value="">Select Category</option>
                                                <option value="1" <?php echo ($category_id ?? '') === '1' ? 'selected' : ''; ?>>Beach</option>
                                                <option value="2" <?php echo ($category_id ?? '') === '2' ? 'selected' : ''; ?>>Mountain</option>
                                                <option value="3" <?php echo ($category_id ?? '') === '3' ? 'selected' : ''; ?>>City</option>
                                                <option value="4" <?php echo ($category_id ?? '') === '4' ? 'selected' : ''; ?>>Cultural</option>
                                                <option value="5" <?php echo ($category_id ?? '') === '5' ? 'selected' : ''; ?>>Adventure</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description *</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($description ?? ''); ?></textarea>
                                </div>

                                <!-- Location Information -->
                                <h6 class="mb-3 text-primary"><i class="fas fa-map-marker-alt me-2"></i>Location Information</h6>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="location" class="form-label">Location</label>
                                            <input type="text" class="form-control" id="location" name="location" 
                                                   value="<?php echo htmlspecialchars($location ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($address ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="latitude" class="form-label">Latitude</label>
                                            <input type="number" class="form-control" id="latitude" name="latitude" 
                                                   value="<?php echo htmlspecialchars($latitude ?? ''); ?>" step="0.00000001">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="longitude" class="form-label">Longitude</label>
                                            <input type="number" class="form-control" id="longitude" name="longitude" 
                                                   value="<?php echo htmlspecialchars($longitude ?? ''); ?>" step="0.00000001">
                                        </div>
                                    </div>
                                </div>

                                <!-- Rating & Reviews -->
                                <h6 class="mb-3 text-primary"><i class="fas fa-star me-2"></i>Rating & Reviews</h6>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="rating" class="form-label">Rating (0-5)</label>
                                            <select class="form-select" id="rating" name="rating">
                                                <option value="0.00" <?php echo ($rating ?? '') === '0.00' ? 'selected' : ''; ?>>No Rating</option>
                                                <option value="1.00" <?php echo ($rating ?? '') === '1.00' ? 'selected' : ''; ?>>1 Star</option>
                                                <option value="2.00" <?php echo ($rating ?? '') === '2.00' ? 'selected' : ''; ?>>2 Stars</option>
                                                <option value="3.00" <?php echo ($rating ?? '') === '3.00' ? 'selected' : ''; ?>>3 Stars</option>
                                                <option value="4.00" <?php echo ($rating ?? '') === '4.00' ? 'selected' : ''; ?>>4 Stars</option>
                                                <option value="5.00" <?php echo ($rating ?? '') === '5.00' ? 'selected' : ''; ?>>5 Stars</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="review_count" class="form-label">Review Count</label>
                                            <input type="number" class="form-control" id="review_count" name="review_count" 
                                                   value="<?php echo htmlspecialchars($review_count ?? '0'); ?>" min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="ticket_price" class="form-label">Ticket Price</label>
                                            <input type="text" class="form-control" id="ticket_price" name="ticket_price" 
                                                   value="<?php echo htmlspecialchars($ticket_price ?? ''); ?>" placeholder="e.g., Rp 50,000">
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact & Hours -->
                                <h6 class="mb-3 text-primary"><i class="fas fa-clock me-2"></i>Contact & Operating Hours</h6>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contact" class="form-label">Contact</label>
                                            <input type="text" class="form-control" id="contact" name="contact" 
                                                   value="<?php echo htmlspecialchars($contact ?? ''); ?>" placeholder="Phone or email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="operating_hours" class="form-label">Operating Hours</label>
                                            <input type="text" class="form-control" id="operating_hours" name="operating_hours" 
                                                   value="<?php echo htmlspecialchars($operating_hours ?? ''); ?>" placeholder="e.g., 08:00-17:00">
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Information -->
                                <h6 class="mb-3 text-primary"><i class="fas fa-list me-2"></i>Additional Information</h6>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="features" class="form-label">Features</label>
                                            <textarea class="form-control" id="features" name="features" rows="3" placeholder="Key features of the destination"><?php echo htmlspecialchars($features ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="tips" class="form-label">Travel Tips</label>
                                            <textarea class="form-control" id="tips" name="tips" rows="3" placeholder="Useful tips for visitors"><?php echo htmlspecialchars($tips ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="facilities" class="form-label">Facilities</label>
                                            <textarea class="form-control" id="facilities" name="facilities" rows="3" placeholder="Available facilities"><?php echo htmlspecialchars($facilities ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Upload -->
                                <h6 class="mb-3 text-primary"><i class="fas fa-image me-2"></i>Image</h6>
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Main Image</label>
                                            <input type="file" class="form-control" id="image" name="main_image" accept="image/*">
                                            <div class="form-text">Max 5MB. Allowed: JPG, JPEG, PNG, GIF</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Image Preview</label>
                                            <div class="image-preview d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-image text-muted fa-3x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <h6 class="mb-3 text-primary"><i class="fas fa-toggle-on me-2"></i>Status</h6>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                   <?php echo ($is_active ?? 1) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="is_active">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                                   <?php echo ($is_featured ?? 0) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="is_featured">
                                                Featured Destination
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Add Destination
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
                preview.innerHTML = '<i class="fas fa-image text-muted fa-3x"></i>';
            }
        });
    </script>
</body>
</html> 