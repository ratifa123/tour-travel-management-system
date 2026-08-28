<?php
/**
 * ============================================================================
 * ADD NEW PACKAGE
 * ============================================================================
 * Purpose: Create new tour package with image upload
 * ============================================================================
 */

require_once 'check_auth.php';

$page_title = 'Add New Package';
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $duration_days = intval($_POST['duration_days'] ?? 0);
    $duration_nights = intval($_POST['duration_nights'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $discount_price = !empty($_POST['discount_price']) ? floatval($_POST['discount_price']) : null;
    $destination = trim($_POST['destination'] ?? '');
    $itinerary = trim($_POST['itinerary'] ?? '');
    $highlights = trim($_POST['highlights'] ?? '');
    $included = trim($_POST['included'] ?? '');
    $excluded = trim($_POST['excluded'] ?? '');
    $difficulty_level = trim($_POST['difficulty_level'] ?? 'Moderate');
    $best_season = trim($_POST['best_season'] ?? '');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Validate required fields
    if (empty($title) || empty($price) || empty($itinerary)) {
        $error = 'Please fill in all required fields.';
    } elseif ($_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        $error = 'Please upload a package image.';
    } else {
        // Handle file upload
        $image_path = '';
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../assets/images/packages/';
            
            // Create directory if not exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // Validate file type
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($_FILES['image']['type'], $allowed_types)) {
                $error = 'Invalid image format. Please upload JPG, PNG, GIF, or WebP.';
            } else {
                // Generate unique filename
                $filename = 'package_' . time() . '_' . rand(1000, 9999) . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $file_path = $upload_dir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
                    $image_path = 'assets/images/packages/' . $filename;
                } else {
                    $error = 'Error uploading image. Please try again.';
                }
            }
        }
        
        // Insert into database
        if (empty($error)) {
            $insert_query = 'INSERT INTO packages (title, description, duration_days, duration_nights, price, discount_price, destination, image, itinerary, highlights, included, excluded, difficulty_level, best_season, is_featured, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())';
            
            $result = executeUpdate($insert_query, [
                $title,
                $description,
                $duration_days,
                $duration_nights,
                $price,
                $discount_price,
                $destination,
                $image_path,
                $itinerary,
                $highlights,
                $included,
                $excluded,
                $difficulty_level,
                $best_season,
                $is_featured,
                $is_active
            ]);
            
            if ($result > 0) {
                $success = 'Package created successfully!';
                // Redirect after 2 seconds
                echo '<meta http-equiv="refresh" content="2;url=packages.php">';
            } else {
                $error = 'Error creating package. Please try again.';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - Tour & Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-globe"></i> Tour & Travel System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="packages.php">
                            <i class="fas fa-briefcase"></i> Packages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="bookings.php">
                            <i class="fas fa-calendar-check"></i> Bookings
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-main">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h1><i class="fas fa-plus-circle"></i> Add New Package</h1>
                </div>
                <div class="col-md-4 text-end">
                    <a href="packages.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Packages
                    </a>
                </div>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="" enctype="multipart/form-data" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="form-label">
                                        <i class="fas fa-heading"></i> Package Title *
                                    </label>
                                    <input type="text" class="form-control" id="title" name="title" required placeholder="e.g., Paris City Escape">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="destination" class="form-label">
                                        <i class="fas fa-map-marker-alt"></i> Destination
                                    </label>
                                    <input type="text" class="form-control" id="destination" name="destination" placeholder="e.g., Paris, France">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left"></i> Description
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief package description"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="duration_days" class="form-label">
                                        <i class="fas fa-calendar"></i> Days *
                                    </label>
                                    <input type="number" class="form-control" id="duration_days" name="duration_days" required min="1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="duration_nights" class="form-label">
                                        <i class="fas fa-moon"></i> Nights *
                                    </label>
                                    <input type="number" class="form-control" id="duration_nights" name="duration_nights" required min="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="price" class="form-label">
                                        <i class="fas fa-dollar-sign"></i> Price ($) *
                                    </label>
                                    <input type="number" class="form-control" id="price" name="price" required min="0" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="discount_price" class="form-label">
                                        <i class="fas fa-tag"></i> Discount Price ($)
                                    </label>
                                    <input type="number" class="form-control" id="discount_price" name="discount_price" min="0" step="0.01">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="image" class="form-label">
                                <i class="fas fa-image"></i> Package Image *
                            </label>
                            <input type="file" class="form-control" id="image" name="image" required accept="image/*">
                            <small class="text-muted">Accepted formats: JPG, PNG, GIF, WebP. Max size: 5MB</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="itinerary" class="form-label">
                                <i class="fas fa-list"></i> Itinerary (Day-by-Day) *
                            </label>
                            <textarea class="form-control" id="itinerary" name="itinerary" rows="6" required placeholder="Day 1: ...
Day 2: ...
Day 3: ..."></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="highlights" class="form-label">
                                        <i class="fas fa-star"></i> Highlights
                                    </label>
                                    <textarea class="form-control" id="highlights" name="highlights" rows="4" placeholder="• Highlight 1
• Highlight 2
• Highlight 3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="best_season" class="form-label">
                                        <i class="fas fa-sun"></i> Best Season
                                    </label>
                                    <input type="text" class="form-control" id="best_season" name="best_season" placeholder="e.g., Spring, Summer, Fall">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="included" class="form-label">
                                        <i class="fas fa-check"></i> What's Included
                                    </label>
                                    <textarea class="form-control" id="included" name="included" rows="4" placeholder="• Accommodation
• Meals
• Tours
• Transport"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="excluded" class="form-label">
                                        <i class="fas fa-times"></i> What's Excluded
                                    </label>
                                    <textarea class="form-control" id="excluded" name="excluded" rows="4" placeholder="• International flights
• Travel insurance
• Personal expenses"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="difficulty_level" class="form-label">
                                        <i class="fas fa-sliders-h"></i> Difficulty Level
                                    </label>
                                    <select class="form-select" id="difficulty_level" name="difficulty_level">
                                        <option value="Easy">Easy</option>
                                        <option value="Moderate" selected>Moderate</option>
                                        <option value="Challenging">Challenging</option>
                                        <option value="Expert">Expert</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                                    <label class="form-check-label" for="is_featured">
                                        <i class="fas fa-star"></i> Featured Package
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-toggle-on"></i> Active
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Create Package
                            </button>
                            <a href="packages.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 pt-5 pb-3">
        <div class="container">
            <p class="text-muted mb-0">&copy; 2026 Tour & Travel Management System. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>