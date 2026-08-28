<?php
/**
 * ============================================================================
 * PACKAGE MANAGEMENT - LIST VIEW
 * ============================================================================
 * Purpose: Display all tour packages with CRUD operations
 * ============================================================================
 */

require_once 'check_auth.php';

$page_title = 'Manage Packages';
$message = '';
$message_type = '';

// Handle delete request
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $package_id = intval($_GET['delete']);
    $delete_query = 'DELETE FROM packages WHERE id = ?';
    $result = executeUpdate($delete_query, [$package_id]);
    
    if ($result > 0) {
        $message = 'Package deleted successfully.';
        $message_type = 'success';
    } else {
        $message = 'Error deleting package.';
        $message_type = 'danger';
    }
}

// Get all packages
$packages_query = 'SELECT * FROM packages ORDER BY created_at DESC';
$packages = fetchAll($packages_query);

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
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h1><i class="fas fa-briefcase"></i> Manage Packages</h1>
                </div>
                <div class="col-md-4 text-end">
                    <a href="package-add.php" class="btn btn-success">
                        <i class="fas fa-plus"></i> Add New Package
                    </a>
                </div>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo htmlspecialchars($message_type); ?> alert-dismissible fade show" role="alert">
                    <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($packages)): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Destination</th>
                                        <th>Duration</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($packages as $package): ?>
                                        <tr>
                                            <td><strong>#<?php echo htmlspecialchars($package['id']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($package['title']); ?></td>
                                            <td><?php echo htmlspecialchars($package['destination'] ?? 'N/A'); ?></td>
                                            <td>
                                                <?php echo htmlspecialchars($package['duration_days']); ?> days / 
                                                <?php echo htmlspecialchars($package['duration_nights']); ?> nights
                                            </td>
                                            <td>$<?php echo number_format($package['price'], 2); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo $package['is_active'] ? 'success' : 'danger'; ?>">
                                                    <?php echo $package['is_active'] ? 'Active' : 'Inactive'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="package-edit.php?id=<?php echo $package['id']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="packages.php?delete=<?php echo $package['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No packages found. <a href="package-add.php">Create one now</a>
                        </div>
                    <?php endif; ?>
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
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>