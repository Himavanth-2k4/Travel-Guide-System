<?php
session_start();
include 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get admin information
$admin_id = $_SESSION['admin_id'];
$admin_query = $conn->prepare("SELECT username, email, last_login FROM admins WHERE id = ?");
$admin_query->bind_param("i", $admin_id);
$admin_query->execute();
$admin = $admin_query->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .card {
            transition: transform 0.2s;
            margin-bottom: 20px;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .management-section {
            margin-top: 30px;
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
                    <a href="admin_dashboard.php" class="active">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="#destinations">
                        <i class="bi bi-geo-alt"></i> Manage Destinations
                    </a>
                    <a href="#guides">
                        <i class="bi bi-people"></i> Manage Guides
                    </a>
                    <a href="logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <h2 class="mb-4">Welcome, <?php echo htmlspecialchars($admin['username']); ?></h2>
                
                <!-- Admin Info Card -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Admin Information</h5>
                                <p><strong>Username:</strong> <?php echo htmlspecialchars($admin['username']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
                                <p><strong>Last Login:</strong> <?php echo $admin['last_login'] ? date('Y-m-d H:i:s', strtotime($admin['last_login'])) : 'Never'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Destinations Management -->
                <div class="management-section" id="destinations">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Destinations Management</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6>Manage Travel Destinations</h6>
                                <button class="btn btn-primary" onclick="location.href='manage_destinations.php'">
                                    <i class="bi bi-plus-circle"></i> Add New Destination
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="text-center">No destinations added yet</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guides Management -->
                <div class="management-section" id="guides">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Guides Management</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6>Manage Travel Guides</h6>
                                <button class="btn btn-primary" onclick="location.href='manage_guides.php'">
                                    <i class="bi bi-plus-circle"></i> Add New Guide
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Specialization</th>
                                            <th>Experience</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6" class="text-center">No guides added yet</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 