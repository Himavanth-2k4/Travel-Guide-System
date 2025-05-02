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
    if (isset($_POST['add_guide'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $specialization = $_POST['specialization'];
        $experience = $_POST['experience'];
        
        // Handle image upload
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "uploads/guides/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $image = $target_dir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $image);
        }
        
        $stmt = $conn->prepare("INSERT INTO guides (name, email, phone, specialization, experience, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $phone, $specialization, $experience, $image);
        $stmt->execute();
    }
    
    if (isset($_POST['delete_guide'])) {
        $id = $_POST['guide_id'];
        $stmt = $conn->prepare("DELETE FROM guides WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

// Get all guides
$guides = $conn->query("SELECT * FROM guides ORDER BY name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Guides</title>
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
        .guide-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
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
                    <a href="manage_bookings.php">
                        <i class="bi bi-calendar-check"></i> Manage Bookings
                    </a>
                    <a href="manage_guides.php" class="active">
                        <i class="bi bi-people"></i> Manage Guides
                    </a>
                    <a href="logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Manage Guides</h2>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGuideModal">
                        <i class="bi bi-plus"></i> Add New Guide
                    </button>
                </div>

                <div class="row">
                    <?php while ($guide = $guides->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <img src="<?php echo $guide['image']; ?>" alt="<?php echo $guide['name']; ?>" class="guide-image mb-3">
                                <h5 class="card-title"><?php echo $guide['name']; ?></h5>
                                <p class="card-text">
                                    <i class="bi bi-envelope"></i> <?php echo $guide['email']; ?><br>
                                    <i class="bi bi-telephone"></i> <?php echo $guide['phone']; ?><br>
                                    <i class="bi bi-star"></i> <?php echo $guide['specialization']; ?><br>
                                    <i class="bi bi-clock-history"></i> <?php echo $guide['experience']; ?> years experience
                                </p>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-info" onclick="editGuide(<?php echo $guide['id']; ?>)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="guide_id" value="<?php echo $guide['id']; ?>">
                                        <button type="submit" name="delete_guide" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this guide?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Guide Modal -->
    <div class="modal fade" id="addGuideModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Guide</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Guide Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="specialization" class="form-label">Specialization</label>
                            <input type="text" class="form-control" id="specialization" name="specialization" required>
                        </div>
                        <div class="mb-3">
                            <label for="experience" class="form-label">Years of Experience</label>
                            <input type="number" class="form-control" id="experience" name="experience" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Profile Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        </div>
                        <button type="submit" name="add_guide" class="btn btn-primary">Add Guide</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editGuide(id) {
            // Implement edit functionality
            alert('Edit functionality will be implemented here');
        }
    </script>
</body>
</html> 