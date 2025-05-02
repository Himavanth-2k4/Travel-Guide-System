<?php
session_start();
include 'db_connect.php';

// Initialize variables
$error = '';
$success = '';

// Debug database connection
error_log("Database connection status: " . ($conn ? "Connected" : "Not connected"));
if ($conn) {
    error_log("Database name: " . $conn->query("SELECT DATABASE()")->fetch_row()[0]);
}

// Check if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Debug information
    error_log("Login attempt - Username: " . $username);
    error_log("Login attempt - Password: " . $password);

    // Validate input
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password";
    } else {
        // Check if table exists
        $table_check = $conn->query("SHOW TABLES LIKE 'admins'");
        error_log("Admin table exists: " . ($table_check->num_rows > 0 ? "Yes" : "No"));

        if ($table_check->num_rows > 0) {
            // Check if admin exists
            $admin_check = $conn->query("SELECT COUNT(*) as count FROM admins WHERE username = 'admin'");
            $admin_count = $admin_check->fetch_assoc()['count'];
            error_log("Admin account exists: " . ($admin_count > 0 ? "Yes" : "No"));

            // Prepare and execute query
            $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            error_log("Query result rows: " . $result->num_rows);

            if ($result->num_rows == 1) {
                $admin = $result->fetch_assoc();
                
                // Debug information
                error_log("Stored password hash: " . $admin['password']);
                error_log("Password verification result: " . (password_verify($password, $admin['password']) ? "true" : "false"));
                
                // Verify password
                if (password_verify($password, $admin['password'])) {
                    // Set session variables
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    
                    // Update last login time
                    $update_stmt = $conn->prepare("UPDATE admins SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
                    $update_stmt->bind_param("i", $admin['id']);
                    $update_stmt->execute();
                    
                    // Redirect to dashboard
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    $error = "Invalid credentials";
                }
            } else {
                $error = "Invalid credentials";
            }
        } else {
            $error = "Admin table not found. Please run the SQL setup script.";
        }
    }
}

// Check if admin table exists and has data
$check_table = $conn->query("SHOW TABLES LIKE 'admins'");
if ($check_table->num_rows == 0) {
    $error = "Admin table not found. Please run the SQL setup script.";
} else {
    $check_admin = $conn->query("SELECT COUNT(*) as count FROM admins");
    $admin_count = $check_admin->fetch_assoc()['count'];
    if ($admin_count == 0) {
        $error = "No admin accounts found. Please run the SQL setup script.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Travel Guide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header i {
            font-size: 3rem;
            color: #764ba2;
            margin-bottom: 1rem;
        }
        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 1px solid #e0e0e0;
        }
        .form-control:focus {
            border-color: #764ba2;
            box-shadow: 0 0 0 0.2rem rgba(118, 75, 162, 0.25);
        }
        .btn-login {
            background: #764ba2;
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background: #667eea;
            transform: translateY(-2px);
        }
        .alert {
            border-radius: 10px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <i class="bi bi-person-circle"></i>
                <h2>Admin Login</h2>
                <p class="text-muted">Enter your credentials to access the dashboard</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="username" name="username" required 
                               placeholder="Enter your username">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required 
                               placeholder="Enter your password">
                    </div>
                </div>

                <button type="submit" class="btn btn-login btn-primary w-100">Login</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
</html> 