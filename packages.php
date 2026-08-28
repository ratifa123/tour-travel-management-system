<?php
/**
 * ============================================================================
 * ALL PACKAGES PAGE
 * ============================================================================
 * Purpose: Display all available packages with search and filtering
 * ============================================================================
 */

require_once 'config/db.php';

// Get all packages
$packages_query = 'SELECT * FROM packages WHERE is_active = 1 ORDER BY created_at DESC';
$all_packages = fetchAll($packages_query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Packages - Tour & Travel Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-globe"></i> Tour & Travel System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="packages.php">
                            <i class="fas fa-suitcase"></i> Packages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/login.php">
                            <i class="fas fa-lock"></i> Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1>Our Travel Packages</h1>
            <p>Choose from our amazing collection of carefully curated travel experiences</p>
        </div>
    </section>

    <!-- Packages Section -->
    <section class="section">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h2 class="section-title">Explore All Destinations</h2>
                </div>
            </div>
            
            <?php if (!empty($all_packages)): ?>
                <div class="row">
                    <?php foreach ($all_packages as $package): ?>
                        <div class="col-md-4 mb-4">
                            <div class="package-card">
                                <div class="package-image">
                                    <?php if (!empty($package['image']) && file_exists($package['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($package['image']); ?>" alt="<?php echo htmlspecialchars($package['title']); ?>">
                                    <?php else: ?>
                                        <img src="https://via.placeholder.com/400x250?text=<?php echo urlencode($package['title']); ?>" alt="<?php echo htmlspecialchars($package['title']); ?>">
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($package['discount_price'])): ?>
                                        <div class="package-badge">Sale</div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="package-content">
                                    <div class="package-destination">
                                        <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($package['destination'] ?? 'Worldwide'); ?>
                                    </div>
                                    
                                    <h5 class="package-title"><?php echo htmlspecialchars($package['title']); ?></h5>
                                    
                                    <div class="package-info">
                                        <div class="package-info-item">
                                            <i class="fas fa-calendar"></i> <?php echo htmlspecialchars($package['duration_days']); ?> Days
                                        </div>
                                        <div class="package-info-item">
                                            <i class="fas fa-star"></i> <?php echo htmlspecialchars($package['difficulty_level']); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="package-price">
                                        <?php if (!empty($package['discount_price'])): ?>
                                            <span class="package-price-original">$<?php echo number_format($package['price'], 2); ?></span>
                                            $<?php echo number_format($package['discount_price'], 2); ?>
                                        <?php else: ?>
                                            $<?php echo number_format($package['price'], 2); ?>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (!empty($package['description'])): ?>
                                        <p class="package-description">
                                            <?php echo htmlspecialchars(substr($package['description'], 0, 80)) . '...'; ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <div class="package-actions">
                                        <a href="package-detail.php?id=<?php echo $package['id']; ?>" class="btn btn-primary">
                                            <i class="fas fa-info-circle"></i> Details
                                        </a>
                                        <a href="package-detail.php?id=<?php echo $package['id']; ?>#booking" class="btn btn-success">
                                            <i class="fas fa-check"></i> Book
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> No packages available at this time. Please check back later.
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 pt-5 pb-3">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-globe"></i> Tour & Travel System</h5>
                    <p class="text-muted">Your gateway to unforgettable travel experiences.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-muted text-decoration-none">Home</a></li>
                        <li><a href="packages.php" class="text-muted text-decoration-none">Packages</a></li>
                        <li><a href="admin/login.php" class="text-muted text-decoration-none">Admin</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contact</h5>
                    <p class="text-muted">
                        <i class="fas fa-envelope"></i> info@tourtravelco.com
                    </p>
                </div>
            </div>
            <hr class="bg-secondary">
            <p class="text-muted text-center mb-0">&copy; 2026 Tour & Travel. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>