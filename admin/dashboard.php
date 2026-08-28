<?php
/**
 * ============================================================================
 * ADMIN DASHBOARD
 * ============================================================================
 * Purpose: Display admin statistics and overview
 * ============================================================================
 */

require_once 'check_auth.php';

$page_title = 'Admin Dashboard';

// Get total packages
$total_packages_query = 'SELECT COUNT(*) as total FROM packages WHERE is_active = 1';
$total_packages_result = fetchOne($total_packages_query);
$total_packages = $total_packages_result['total'] ?? 0;

// Get total bookings
$total_bookings_query = 'SELECT COUNT(*) as total FROM bookings';
$total_bookings_result = fetchOne($total_bookings_query);
$total_bookings = $total_bookings_result['total'] ?? 0;

// Get pending bookings
$pending_bookings_query = 'SELECT COUNT(*) as total FROM bookings WHERE status = "Pending"';
$pending_bookings_result = fetchOne($pending_bookings_query);
$pending_bookings = $pending_bookings_result['total'] ?? 0;

// Get confirmed bookings
$confirmed_bookings_query = 'SELECT COUNT(*) as total FROM bookings WHERE status = "Confirmed"';
$confirmed_bookings_result = fetchOne($confirmed_bookings_query);
$confirmed_bookings = $confirmed_bookings_result['total'] ?? 0;

// Get total revenue
$revenue_query = 'SELECT SUM(total_amount) as total FROM bookings WHERE payment_status = "Paid"';
$revenue_result = fetchOne($revenue_query);
$total_revenue = $revenue_result['total'] ?? 0;

// Get recent bookings
$recent_bookings_query = 'SELECT b.*, p.title as package_name FROM bookings b JOIN packages p ON b.package_id = p.id ORDER BY b.created_at DESC LIMIT 5';
$recent_bookings = fetchAll($recent_bookings_query);

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
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="packages.php">
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
        <div class="container-fluid">
            <h1 class="mb-4"><i class="fas fa-chart-line"></i> Dashboard</h1>
            
            <!-- Statistics Row -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="stat-widget">
                        <div class="stat-icon text-primary">
                            <i class="fas fa-suitcase"></i>
                        </div>
                        <div class="stat-number"><?php echo $total_packages; ?></div>
                        <div class="stat-label">Total Packages</div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="stat-widget danger">
                        <div class="stat-icon text-danger">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <div class="stat-number"><?php echo $pending_bookings; ?></div>
                        <div class="stat-label">Pending Bookings</div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="stat-widget success">
                        <div class="stat-icon text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-number"><?php echo $confirmed_bookings; ?></div>
                        <div class="stat-label">Confirmed Bookings</div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="stat-widget warning">
                        <div class="stat-icon text-warning">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-number">$<?php echo number_format($total_revenue, 2); ?></div>
                        <div class="stat-label">Total Revenue</div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Bookings -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-list"></i> Recent Bookings
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($recent_bookings)): ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Customer</th>
                                                <th>Package</th>
                                                <th>Travel Date</th>
                                                <th>Guests</th>
                                                <th>Status</th>
                                                <th>Amount</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_bookings as $booking): ?>
                                                <tr>
                                                    <td><strong>#<?php echo htmlspecialchars($booking['id']); ?></strong></td>
                                                    <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($booking['package_name']); ?></td>
                                                    <td><?php echo date('M d, Y', strtotime($booking['travel_date'])); ?></td>
                                                    <td><?php echo htmlspecialchars($booking['total_guests']); ?></td>
                                                    <td>
                                                        <span class="badge badge-<?php echo strtolower($booking['status']); ?>">
                                                            <?php echo htmlspecialchars($booking['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td>$<?php echo number_format($booking['total_amount'], 2); ?></td>
                                                    <td>
                                                        <a href="bookings.php?view=<?php echo $booking['id']; ?>" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> No bookings yet.
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer text-end">
                            <a href="bookings.php" class="btn btn-primary btn-sm">
                                View All Bookings <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 pt-5 pb-3">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; 2026 Tour & Travel Management System. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="#" class="text-muted text-decoration-none me-3"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-muted text-decoration-none me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-muted text-decoration-none me-3"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>