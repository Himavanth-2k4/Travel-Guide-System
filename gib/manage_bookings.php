<?php
session_start();
include 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['assign_guide'])) {
        $booking_id = $_POST['booking_id'];
        $guide_id = $_POST['guide_id'];
        
        $stmt = $conn->prepare("UPDATE bookings SET guide_id = ?, status = 'Assigned' WHERE id = ?");
        $stmt->bind_param("ii", $guide_id, $booking_id);
        $stmt->execute();
    }
    
    if (isset($_POST['update_status'])) {
        $booking_id = $_POST['booking_id'];
        $status = $_POST['status'];
        
        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $booking_id);
        $stmt->execute();
    }
}

// Get all bookings with user and destination details
$bookings = $conn->query("
    SELECT b.*, u.username, u.email, d.name as destination_name, g.name as guide_name
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN destinations d ON b.destination_id = d.id
    LEFT JOIN guides g ON b.guide_id = g.id
    ORDER BY b.created_at DESC
");

// Get all guides for the dropdown
$guides = $conn->query("SELECT * FROM guides ORDER BY name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .main-content {
            padding: 20px;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
        }
        .status-pending {
            background-color: #ffc107;
            color: #000;
        }
        .status-confirmed {
            background-color: #28a745;
            color: #fff;
        }
        .status-cancelled {
            background-color: #dc3545;
            color: #fff;
        }
        .status-assigned {
            background-color: #17a2b8;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <h3 class="text-center py-3">Admin Panel</h3>
                <nav>
                    <a href="admin_dashboard.php">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="manage_destinations.php">
                        <i class="bi bi-geo-alt"></i> Manage Destinations
                    </a>
                    <a href="manage_bookings.php" class="active">
                        <i class="bi bi-calendar-check"></i> Manage Bookings
                    </a>
                    <a href="manage_guides.php">
                        <i class="bi bi-people"></i> Manage Guides
                    </a>
                    <a href="logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <h2 class="mb-4">Manage Bookings</h2>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>User</th>
                                <th>Destination</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Guide</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($booking = $bookings->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $booking['id']; ?></td>
                                <td>
                                    <?php echo $booking['username']; ?><br>
                                    <small class="text-muted"><?php echo $booking['email']; ?></small>
                                </td>
                                <td><?php echo $booking['destination_name']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($booking['status']); ?>">
                                        <?php echo $booking['status']; ?>
                                    </span>
                                </td>
                                <td><?php echo $booking['guide_name'] ?? 'Not Assigned'; ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignGuideModal<?php echo $booking['id']; ?>">
                                        <i class="bi bi-person-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#updateStatusModal<?php echo $booking['id']; ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Assign Guide Modal -->
                            <div class="modal fade" id="assignGuideModal<?php echo $booking['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Assign Guide</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST">
                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                <div class="mb-3">
                                                    <label for="guide_id" class="form-label">Select Guide</label>
                                                    <select class="form-select" id="guide_id" name="guide_id" required>
                                                        <option value="">Select a guide</option>
                                                        <?php while ($guide = $guides->fetch_assoc()): ?>
                                                        <option value="<?php echo $guide['id']; ?>"><?php echo $guide['name']; ?></option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <button type="submit" name="assign_guide" class="btn btn-primary">Assign Guide</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Update Status Modal -->
                            <div class="modal fade" id="updateStatusModal<?php echo $booking['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Status</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST">
                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select class="form-select" id="status" name="status" required>
                                                        <option value="Pending" <?php echo $booking['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="Confirmed" <?php echo $booking['status'] == 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                                        <option value="Cancelled" <?php echo $booking['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                        <option value="Assigned" <?php echo $booking['status'] == 'Assigned' ? 'selected' : ''; ?>>Assigned</option>
                                                    </select>
                                                </div>
                                                <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 