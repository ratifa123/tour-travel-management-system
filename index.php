<?php
/**
 * ============================================================================
 * FRONTEND HOMEPAGE
 * ============================================================================
 * Purpose: Display featured packages and attract customers
 * ============================================================================
 */

require_once 'config/db.php';

// Get featured packages
$featured_query = 'SELECT * FROM packages WHERE is_featured = 1 AND is_active = 1 ORDER BY created_at DESC LIMIT 6';
$featured_packages = fetchAll($featured_query);

// Get all packages for display
$all_packages_query = 'SELECT * FROM packages WHERE is_active = 1 ORDER BY created_at DESC LIMIT 9';
$all_packages = fetchAll($all_packages_query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Explore amazing travel packages and book your next adventure">
    <title>Home - Tour & Travel Management System</title>
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
                        <a class="nav-link active" href="index.php">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="packages.php">
                            <i class="fas fa-suitcase"></i> Packages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">
                            <i class="fas fa-info-circle"></i> About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">
                            <i class="fas fa-envelope"></i> Contact
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
            <h1>Discover Your Next Adventure</h1>
            <p>Explore breathtaking destinations and create unforgettable memories</p>
            <a href="packages.php" class="btn btn-light btn-lg mt-3">
                <i class="fas fa-compass"></i> Explore Packages
            </a>
        </div>
    </section>

    <!-- Featured Packages -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Featured Packages</h2>
            
            <?php if (!empty($featured_packages)): ?>
                <div class="row">
                    <?php foreach ($featured_packages as $package): ?>
                        <div class="col-md-4 mb-4">
                            <div class="package-card">
                                <div class="package-image">
                                    <?php if (!empty($package['image']) && file_exists($package['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($package['image']); ?>" alt="<?php echo htmlspecialchars($package['title']); ?>">
                                    <?php else: ?>
                                        <img src="https://via.placeholder.com/400x250?text=<?php echo urlencode($package['title']); ?>" alt="<?php echo htmlspecialchars($package['title']); ?>">
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($package['discount_price'])): ?>
                                        <div class="package-badge">Special Offer</div>
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
                                            <i class="fas fa-moon"></i> <?php echo htmlspecialchars($package['duration_nights']); ?> Nights
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
                                            <?php echo htmlspecialchars(substr($package['description'], 0, 100)) . '...'; ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <div class="package-actions">
                                        <a href="package-detail.php?id=<?php echo $package['id']; ?>" class="btn btn-primary">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                        <a href="package-detail.php?id=<?php echo $package['id']; ?>#booking" class="btn btn-success">
                                            <i class="fas fa-bookmark"></i> Book Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> No featured packages available at this time.
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- All Packages Preview -->
    <section class="section bg-light">
        <div class="container">
            <h2 class="section-title">Popular Destinations</h2>
            
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
                                </div>
                                
                                <div class="package-content">
                                    <div class="package-destination">
                                        <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($package['destination'] ?? 'Worldwide'); ?>
                                    </div>
                                    
                                    <h5 class="package-title"><?php echo htmlspecialchars($package['title']); ?></h5>
                                    
                                    <div class="package-price">
                                        $<?php echo number_format($package['price'], 2); ?>
                                    </div>
                                    
                                    <div class="package-actions">
                                        <a href="package-detail.php?id=<?php echo $package['id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="package-detail.php?id=<?php echo $package['id']; ?>#booking" class="btn btn-success btn-sm">
                                            <i class="fas fa-bookmark"></i> Book
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-4">
                    <a href="packages.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-list"></i> View All Packages
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Why Choose Us?</h2>
            
            <div class="row">
                <div class="col-md-4 mb-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-award" style="font-size: 3rem; color: var(--primary);"></i>
                    </div>
                    <h5>Expert Guides</h5>
                    <p>Professional and experienced guides ensure unforgettable journeys</p>
                </div>
                
                <div class="col-md-4 mb-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-shield-alt" style="font-size: 3rem; color: var(--success);"></i>
                    </div>
                    <h5>Safe & Secure</h5>
                    <p>Your safety is our priority with comprehensive travel insurance</p>
                </div>
                
                <div class="col-md-4 mb-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-dollar-sign" style="font-size: 3rem; color: var(--warning);"></i>
                    </div>
                    <h5>Best Prices</h5>
                    <p>Competitive pricing with flexible payment options available</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 pt-5 pb-3">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-globe"></i> Tour & Travel System</h5>
                    <p class="text-muted">Your gateway to unforgettable travel experiences around the world.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-muted text-decoration-none">Home</a></li>
                        <li><a href="packages.php" class="text-muted text-decoration-none">Packages</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">About Us</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contact Us</h5>
                    <p class="text-muted">
                        <i class="fas fa-phone"></i> +1-800-TOURS-NOW<br>
                        <i class="fas fa-envelope"></i> info@tourtravelco.com<br>
                        <i class="fas fa-map-marker-alt"></i> 123 Travel Street, Adventure City
                    </p>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; 2026 Tour & Travel Management System. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="#" class="text-muted text-decoration-none me-3"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-muted text-decoration-none me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-muted text-decoration-none me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-muted text-decoration-none"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>