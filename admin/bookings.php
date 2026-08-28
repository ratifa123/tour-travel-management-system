<?php
/**
 * ============================================================================
 * BOOKING MANAGEMENT - LIST VIEW
 * ============================================================================
 * Purpose: Display and manage customer booking requests
 * ============================================================================
 */

require_once 'check_auth.php';

$page_title = 'Manage Bookings';
$message = '';
$message_type = '';

// Handle status update
if (isset($_POST['update_status'])) {
    $booking_id = intval($_POST['booking_id']);
    $status = trim($_POST['status']);
    
    $update_query = 'UPDATE bookings SET status = ?, updated_at = NOW() WHERE id = ?';
    $result = executeUpdate($update_query, [$status, $booking_id]);
    
    if ($result > 0) {
        $message = 'Booking status updated successfully.';
        $message_type = 'success';
    } else {
        $message = 'Error updating booking status.';
        $message_type = 'danger';
    }
}

// Get all bookings with pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

$bookings_query = 'SELECT b.*, p.title as package_name FROM bookings b JOIN packages p ON b.package_id = p.id ORDER BY b.created_at DESC LIMIT ? OFFSET ?';
$bookings = fetchAll($bookings_query, [$per_page, $offset]);

// Get total bookings count
$count_query = 'SELECT COUNT(*) as total FROM bookings';
$count_result = fetchOne($count_query);
$total_bookings = $count_result['total'] ?? 0;
$total_pages = ceil($total_bookings / $per_page);

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
                        <a class="nav-link" href="packages.php">
                            <i class="fas fa-briefcase"></i> Packages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="bookings.php">
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
            <h1 class="mb-4"><i class="fas fa-calendar-check"></i> Manage Bookings</h1>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo htmlspecialchars($message_type); ?> alert-dismissible fade show" role="alert">
                    <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($bookings)): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Package</th>
                                        <th>Travel Date</th>
                                        <th>Guests</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                        <tr>
                                            <td><strong>#<?php echo htmlspecialchars($booking['id']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                                            <td><?php echo htmlspecialchars($booking['customer_email']); ?></td>
                                            <td><?php echo htmlspecialchars($booking['customer_phone']); ?></td>
                                            <td><?php echo htmlspecialchars($booking['package_name']); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($booking['travel_date'])); ?></td>
                                            <td><?php echo htmlspecialchars($booking['total_guests']); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo strtolower($booking['status']); ?>">
                                                    <?php echo htmlspecialchars($booking['status']); ?>
                                                </span>
                                            </td>
                                            <td>$<?php echo number_format($booking['total_amount'] ?? 0, 2); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal<?php echo $booking['id']; ?>">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                        
                                        <!-- Booking Detail Modal -->
                                        <div class="modal fade" id="bookingModal<?php echo $booking['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Booking #<?php echo htmlspecialchars($booking['id']); ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <h6>Customer Information</h6>
                                                                <p><strong>Name:</strong> <?php echo htmlspecialchars($booking['customer_name']); ?></p>
                                                                <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['customer_email']); ?></p>
                                                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($booking['customer_phone']); ?></p>
                                                                <p><strong>Address:</strong> <?php echo htmlspecialchars($booking['customer_address'] ?? 'N/A'); ?></p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6>Booking Details</h6>
                                                                <p><strong>Package:</strong> <?php echo htmlspecialchars($booking['package_name']); ?></p>
                                                                <p><strong>Travel Date:</strong> <?php echo date('F d, Y', strtotime($booking['travel_date'])); ?></p>
                                                                <p><strong>Total Guests:</strong> <?php echo htmlspecialchars($booking['total_guests']); ?></p>
                                                                <p><strong>Amount:</strong> $<?php echo number_format($booking['total_amount'] ?? 0, 2); ?></p>
                                                            </div>
                                                        </div>
                                                        
                                                        <?php if (!empty($booking['special_requests'])): ?>
                                                            <div class="mb-3">
                                                                <h6>Special Requests</h6>
                                                                <p><?php echo nl2br(htmlspecialchars($booking['special_requests'])); ?></p>
                                                            </div>
                                                        <?php endif; ?>
                                                        
                                                        <div class="mb-3">
                                                            <h6>Update Status</h6>
                                                            <form method="POST" action="">
                                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                                <div class="input-group">
                                                                    <select class="form-select" name="status">
                                                                        <option value="Pending" <?php echo $booking['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                                        <option value="Confirmed" <?php echo $booking['status'] === 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                                                        <option value="Cancelled" <?php echo $booking['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                                        <option value="Completed" <?php echo $booking['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                                    </select>
                                                                    <button class="btn btn-primary" type="submit" name="update_status">
                                                                        <i class="fas fa-save"></i> Update
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No bookings found.
                        </div>
                    <?php endif; ?>
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