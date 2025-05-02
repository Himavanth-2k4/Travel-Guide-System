<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Initialize variables
$message = '';
$messageType = '';

// Get user data from database
$username = $_SESSION['user'];
$stmt = $conn->prepare("SELECT id, email, password FROM users WHERE name = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get user profile data
$stmt = $conn->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();

// Handle profile update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Profile update form
    if (isset($_POST['update_profile'])) {
        $fullName = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $city = trim($_POST['city']);
        $bio = trim($_POST['bio']);
        
        // Simple validation
        if (empty($fullName) || empty($email)) {
            $message = 'Full name and email are required fields';
            $messageType = 'error';
        } else {
            // Start transaction
            $conn->begin_transaction();
            
            try {
                // Update email in users table
                $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
                $stmt->bind_param("si", $email, $user['id']);
                $stmt->execute();

                // Check if profile exists
                if ($profile) {
                    // Update existing profile
                    $stmt = $conn->prepare("UPDATE user_profiles SET full_name = ?, phone = ?, city = ?, bio = ? WHERE user_id = ?");
                    $stmt->bind_param("ssssi", $fullName, $phone, $city, $bio, $user['id']);
                } else {
                    // Create new profile
                    $stmt = $conn->prepare("INSERT INTO user_profiles (user_id, full_name, phone, city, bio) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("issss", $user['id'], $fullName, $phone, $city, $bio);
                }
                $stmt->execute();

                $conn->commit();
                $message = 'Profile updated successfully!';
                $messageType = 'success';
                
                // Refresh profile data after update
                $stmt = $conn->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
                $stmt->bind_param("i", $user['id']);
                $stmt->execute();
                $profile = $stmt->get_result()->fetch_assoc();
            } catch (Exception $e) {
                $conn->rollback();
                $message = 'Error updating profile: ' . $e->getMessage();
                $messageType = 'error';
            }
        }
    }
    // Password change form
    else if (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        // Verify current password
        if (!password_verify($currentPassword, $user['password'])) {
            $message = 'Current password is incorrect';
            $messageType = 'error';
        } else if (empty($newPassword) || empty($confirmPassword)) {
            $message = 'All password fields are required';
            $messageType = 'error';
        } else if ($newPassword !== $confirmPassword) {
            $message = 'New passwords do not match';
            $messageType = 'error';
        } else {
            // Hash new password and update
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashedPassword, $user['id']);
            
            if ($stmt->execute()) {
                $message = 'Password updated successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error updating password';
                $messageType = 'error';
            }
        }
    }
    // Handle profile picture upload
    else if (isset($_POST['upload_picture'])) {
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['profile_picture']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $uploadDir = 'uploads/avatars/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $newFilename = uniqid() . '.' . $ext;
                $uploadPath = $uploadDir . $newFilename;
                
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
                    // Update database with new avatar path
                    $stmt = $conn->prepare("UPDATE user_profiles SET avatar_url = ? WHERE user_id = ?");
                    $stmt->bind_param("si", $uploadPath, $user['id']);
                    
                    if ($stmt->execute()) {
                        // Refresh profile data after update
                        $stmt = $conn->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
                        $stmt->bind_param("i", $user['id']);
                        $stmt->execute();
                        $profile = $stmt->get_result()->fetch_assoc();
                        
                        $message = 'Profile picture uploaded successfully!';
                        $messageType = 'success';
                    } else {
                        $message = 'Error updating profile picture in database';
                        $messageType = 'error';
                    }
                } else {
                    $message = 'Error uploading file';
                    $messageType = 'error';
                }
            } else {
                $message = 'Invalid file type. Please upload JPG, JPEG, PNG or GIF files only.';
                $messageType = 'error';
            }
        }
    }
}

// Get updated user data
$userData = [
    'username' => $username,
    'fullName' => $profile['full_name'] ?? '',
    'email' => $user['email'] ?? '',
    'phone' => $profile['phone'] ?? '',
    'city' => $profile['city'] ?? '',
    'bio' => $profile['bio'] ?? '',
    'avatar' => $profile['avatar_url'] ?? 'https://randomuser.me/api/portraits/men/32.jpg',
    'joinDate' => $profile['join_date'] ?? date('F Y'),
    'lastLogin' => $profile['last_login'] ?? date('d M Y, h:i A')
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - HOVI & JAVI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Include your existing CSS here -->
    <style>
/* Profile page enhanced styles */
.profile-page {
    padding: 2rem 0;
    background-color: #f8f9fa;
    min-height: calc(100vh - 200px);
}

.page-header {
    margin-bottom: 1.5rem;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
}

.page-title {
    margin: 0;
    font-size: 2rem;
    color: #333;
    font-weight: 600;
}

.breadcrumb {
    margin: 0.5rem 0;
}

.breadcrumb ol {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
}

.breadcrumb li {
    display: flex;
    align-items: center;
    color: #777;
}

.breadcrumb li:not(:last-child)::after {
    content: '/';
    margin: 0 0.5rem;
    color: #ccc;
}

.breadcrumb a {
    color: #007bff;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.profile-content {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
}

/* Sidebar Styles */
.profile-sidebar {
    flex: 0 0 300px;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.profile-card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    overflow: hidden;
}
.profile-avatar-container {
    position: relative;
    padding: 1.5rem;
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
}
.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.avatar-upload-label {
    position: absolute;
    bottom: 1.5rem;
    right: calc(50% - 60px);
    background-color: rgba(255,255,255,0.9);
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    transition: all 0.2s;
}

.avatar-upload-label:hover {
    background-color: #fff;
    transform: scale(1.1);
}

.avatar-upload-label i {
    color: #333;
}

.avatar-upload-label span {
    display: none;
}

.profile-info {
    padding: 1.5rem;
    text-align: center;
}

.profile-name {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
}

.profile-username {
    margin: 0.25rem 0 1rem;
    font-size: 0.9rem;
    color: #777;
}

.profile-meta {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-top: 1rem;
}

.profile-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.profile-meta-item i {
    color: #007bff;
    width: 18px;
}

.profile-nav {
    border-top: 1px solid #eee;
}

.profile-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.profile-nav li {
    border-bottom: 1px solid #f5f5f5;
}

.profile-nav li:last-child {
    border-bottom: none;
}

.profile-nav a {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    color: #555;
    text-decoration: none;
    transition: all 0.2s;
}

.profile-nav a i {
    width: 20px;
    color: #777;
}

.profile-nav li.active a {
    background-color: #f5f8ff;
    color: #007bff;
    font-weight: 500;
    border-left: 3px solid #007bff;
}

.profile-nav li.active a i {
    color: #007bff;
}

.profile-nav a:hover {
    background-color: #f8f9fa;
}

.logout-link {
    color: #dc3545 !important;
}

.logout-link i {
    color: #dc3545 !important;
}

.activity-summary {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    padding: 1.5rem;
}

.activity-summary h4 {
    margin-top: 0;
    margin-bottom: 1rem;
    color: #333;
    font-size: 1.1rem;
}

.activity-stats {
    display: flex;
    justify-content: space-between;
}

.stat-item {
    text-align: center;
    flex: 1;
}

.stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: 600;
    color: #007bff;
}

.stat-label {
    display: block;
    font-size: 0.85rem;
    color: #777;
}

/* Main Content Styles */
.profile-main {
    flex: 1;
    min-width: 0;
}

.profile-section {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 1.5rem;
    padding: 2rem;
}

.profile-section:last-child {
    margin-bottom: 0;
}

.section-header {
    margin-bottom: 1.5rem;
    border-bottom: 1px solid #eee;
    padding-bottom: 1rem;
}

.section-header h2 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 600;
    color: #333;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-header h2 i {
    font-size: 1.1rem;
    color: #007bff;
}

.section-desc {
    margin: 0.5rem 0 0;
    color: #777;
    font-size: 0.9rem;
}

.form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-row .form-group {
    flex: 1;
    min-width: 0;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #555;
    font-size: 0.95rem;
}
.input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 1rem;
    color: #777;
}

.input-group input {
    padding-left: 2.5rem !important;
}

.form-group input, .form-group textarea, .form-group select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    background-color: #fff;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.form-group input:focus, .form-group textarea:focus, .form-group select:focus {
    border-color: #80bdff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(0,123,255,.25);
}

.form-group input[disabled] {
    background-color: #f8f9fa;
    cursor: not-allowed;
}

.form-text {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.85rem;
    color: #777;
}
.password-toggle {
    position: absolute;
    right: 1rem;
    background: none;
    border: none;
    color: #777;
    cursor: pointer;
    padding: 0;
}

.password-toggle:focus {
    outline: none;
}

.password-strength {
    background-color: #f8f9fa;
    border-radius: 4px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.password-strength h4 {
    margin-top: 0;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
    color: #555;
}

.password-requirements {
    padding-left: 1.5rem;
    margin: 0;
}

.password-requirements li {
    font-size: 0.85rem;
    color: #777;
    margin-bottom: 0.25rem;
}

.form-actions {
    margin-top: 2rem;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

/* Button Styles */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    font-weight: 500;
    line-height: 1;
    text-align: center;
    text-decoration: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.btn-primary {
    background-color: #007bff;
    color: #fff;
}

.btn-primary:hover {
    background-color: #0069d9;
}

.btn-secondary {
    background-color: #6c757d;
    color: #fff;
}

.btn-secondary:hover {
    background-color: #5a6268;
}
.btn-outline {
    background-color: transparent;
    border: 1px solid #ddd;
    color: #555;
}

.btn-outline:hover {
    background-color: #f8f9fa;
}

/* Alert Styles */
.alert {
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.close-alert {
    background: none;
    border: none;
    font-size: 1.25rem;
    line-height: 1;
    color: inherit;
    opacity: 0.7;
    padding: 0;
    cursor: pointer;
}

.close-alert:hover {
    opacity: 1;
}

/* Security Options Styles */
.security-options {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.security-option {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border: 1px solid #eee;
    border-radius: 4px;
    transition: all 0.2s;
}

.security-option:hover {
    background-color: #f8f9fa;
    border-color: #ddd;
}

.option-info h4 {
    margin: 0;
    font-size: 1rem;
    color: #333;
}

.option-info p {
    margin: 0.25rem 0 0;
    font-size: 0.85rem;
    color: #777;
}

/* Toggle Switch */
.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #007bff;
}

input:focus + .slider {
    box-shadow: 0 0 1px #007bff;
}

input:checked + .slider:before {
    transform: translateX(26px);
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .profile-content {
        flex-direction: column;
    }
    
    .profile-sidebar {
        flex: 0 0 100%;
    }
    
    .profile-main {
        flex: 0 0 100%;
    }
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .form-row {
        flex-direction: column;
    }
    
    .form-row .form-group {
        flex: 0 0 100%;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions button {
        width: 100%;
    }
    
    .activity-stats {
        flex-wrap: wrap;
        gap: 1rem;
    }
}

@media (max-width: 576px) {
    .profile-section {
        padding: 1.5rem;
    }
}
   /* Base styles */
   .wishlist-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 2;
}

.wishlist-btn i {
    color: #666;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.wishlist-btn.wishlisted {
    background: var(--primary);
}

.wishlist-btn.wishlisted i {
    color: white;
}

.wishlist-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Card hover effect enhancement */
.destination-card {
    position: relative;
    overflow: hidden;
}

.card-zoom {
    position: relative;
}

.card-image {
    transition: transform 0.5s ease;
}

.destination-card:hover .card-image {
    transform: scale(1.1);
}
        :root {
            --primary: #FF9933; /* Saffron color from Indian flag */
            --primary-dark: #E58A2E;
            --secondary: #138808; /* Green color from Indian flag */
            --secondary-dark: #107007;
            --accent: #0066CC; /* Blue similar to Ashoka Chakra */
            --text-dark: #333333;
            --text-light: #666666;
            --white: #FFFFFF;
            --off-white: #F9F9F9;
            --border-color: #E0E0E0;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
            --radius: 0.5rem;
            --transition: all 0.3s ease;
            --transition-slow: all 0.5s ease;
        }

        /* Reset and base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background-color: var(--off-white);
            overflow-x: hidden;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        img {
            max-width: 100%;
            height: auto;
            transition: var(--transition);
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: var(--transition);
        }

        ul {
            list-style: none;
        }

        button {
            cursor: pointer;
            font-family: inherit;
            transition: var(--transition);
        }

        /* Loading animation */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .loading {
            animation: pulse 1.5s infinite;
            background-color: #f3f3f3;
            border-radius: var(--radius);
        }

        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            line-height: 1.3;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 0.875rem;
        }

        h3 {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }

        p {
            margin-bottom: 1rem;
        }

        /* Header & Navigation */
        .site-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: var(--white);
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .site-header.scrolled {
            box-shadow: var(--shadow-lg);
        }

        .navbar {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            transition: var(--transition);
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo-emoji {
            font-size: 1.5rem;
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
        }

        .nav-links a {
            font-weight: 500;
            color: var(--text-dark);
            position: relative;
        }

        .nav-links a:hover, .nav-links a.active {
            color: var(--primary);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 0;
            height: 2px;
            background-color: var(--primary);
            transition: var(--transition);
        }

        .nav-links a:hover::after, .nav-links a.active::after {
            width: 100%;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .user-profile {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }

        .user-name {
            font-weight: 500;
            font-size: 0.9rem;
        }
        .dropdown-content {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: var(--white);
            min-width: 200px;
            box-shadow: var(--shadow-lg);
            border-radius: var(--radius);
            padding: 0.5rem 0;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: var(--transition);
        }

        .dropdown-content.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-content a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .dropdown-content a:hover {
            background-color: rgba(255, 153, 51, 0.1);
            color: var(--primary);
            padding-left: 1.75rem;
        }

        .dropdown-content .logout-btn {
            color: #e74c3c;
        }

        .dropdown-content .logout-btn:hover {
            background-color: rgba(231, 76, 60, 0.1);
        }

        .mobile-menu-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 0.5rem;
            z-index: 1001;
        }

        .mobile-menu-toggle span {
            display: block;
            width: 25px;
            height: 3px;
            background-color: var(--text-dark);
            transition: var(--transition);
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius);
            font-weight: 500;
            transition: var(--transition);
            border: none;
        }

        .btn svg {
            width: 18px;
            height: 18px;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 153, 51, 0.3);
        }

        .btn-outline {
            border: 1px solid var(--primary);
            background-color: transparent;
            color: var(--primary);
        }

        .btn-outline:hover {
            background-color: var(--primary);
            color: var(--white);
            transform: translateY(-2px);
        }

        .btn-block {
            width: 100%;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            padding: 6rem 0;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1524492412937-b28074a5d7da?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80') no-repeat center/cover;
            overflow: hidden;
            color: var(--white);
        }

        .hero-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.8;
            z-index: -1;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            max-width: 700px;
            animation: fadeInUp 1s ease;
        }

        .hero-title span {
            color: var(--primary);
            text-shadow: 0 0 10px rgba(255, 153, 51, 0.5);
        }

        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2.5rem;
            max-width: 600px;
            color: rgba(255, 255, 255, 0.9);
            animation: fadeInUp 1s ease 0.2s forwards;
            opacity: 0;
        }

        .search-container {
            max-width: 700px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: var(--radius);
            padding: 2rem;
            box-shadow: var(--shadow-lg);
            margin-bottom: 3rem;
            animation: fadeInUp 1s ease 0.4s forwards;
            opacity: 0;
        }

        .search-form {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .search-input {
            flex: 1;
            border: 1px solid var(--border-color);
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 1rem;
            transition: var(--transition);
        }

        .search-input:focus {
            outline: 2px solid var(--primary);
            border-color: transparent;
            box-shadow: 0 0 0 3px rgba(255, 153, 51, 0.3);
        }

        .search-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: var(--primary);
            color: var(--white);
            border: none;
            border-radius: var(--radius);
            padding: 0 1.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .search-button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .search-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            animation: fadeInUp 1s ease 0.6s forwards;
            opacity: 0;
        }

        .search-tag {
            background-color: rgba(255, 153, 51, 0.1);
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: var(--transition);
            border: 1px solid transparent;
        }

        .search-tag:hover {
            background-color: rgba(255, 153, 51, 0.2);
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            animation: fadeInUp 1s ease 0.8s forwards;
            opacity: 0;
        }

        .stat-box {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            padding: 1.5rem;
            border-radius: var(--radius);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: var(--transition);
        }

        .stat-box:hover {
            transform: translateY(-5px);
            background-color: rgba(255, 255, 255, 0.2);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--white);
            line-height: 1.2;
            text-shadow: 0 0 10px rgba(255, 153, 51, 0.5);
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Sections */
        section {
            padding: 5rem 0;
            position: relative;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
        }

        .section-title {
            font-size: 2.25rem;
            font-weight: 700;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            width: 60px;
            height: 3px;
            background-color: var(--primary);
        }

        .section-subtitle {
            color: var(--text-light);
            max-width: 600px;
            margin-top: 1rem;
        }

        .text-center {
            text-align: center;
        }

        .view-all-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            color: var(--primary);
            transition: var(--transition);
        }

        .view-all-link:hover {
            color: var(--primary-dark);
            transform: translateX(5px);
        }

        /* Features Section */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-box {
            background-color: var(--white);
            border-radius: var(--radius);
            padding: 2rem;
            transition: var(--transition);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .feature-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background-color: var(--primary);
            transform: scaleX(0);
            transform-origin: left;
            transition: var(--transition-slow);
        }

        .feature-box:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: rgba(255, 153, 51, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            transition: var(--transition);
        }

        .feature-box:hover .feature-icon {
            background-color: var(--primary);
        }

        .feature-box:hover .feature-icon i {
            color: var(--white);
        }

        .feature-icon i {
            font-size: 25px;
            color: var(--primary);
            transition: var(--transition);
        }

        .feature-title {
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
        }

        .feature-text {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        /* Destinations Section */
        .filter-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .filter-btn {
            background-color: transparent;
            border: 1px solid var(--border-color);
            padding: 0.5rem 1.25rem;
            border-radius: 30px;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .filter-btn:hover, .filter-btn.active {
            background-color: var(--primary);
            border-color: var(--primary);
            color: var(--white);
            transform: translateY(-2px);
        }

        .destinations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }

        .destination-card {
            background-color: var(--white);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
        }

        .destination-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .card-zoom {
            height: 220px;
            overflow: hidden;
            position: relative;
        }

        .card-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .destination-card:hover .card-image {
            transform: scale(1.1);
        }

        .card-content {
            padding: 1.5rem;
        }

        .destination-category {
            display: inline-block;
            background-color: rgba(255, 153, 51, 0.1);
            color: var(--primary);
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .destination-title {
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
            transition: var(--transition);
        }

        .destination-card:hover .destination-title {
            color: var(--primary);
        }

        .destination-desc {
            color: var(--text-light);
            font-size: 0.95rem;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .destination-location {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-light);
        }

        .destination-location i {
            color: var(--primary);
        }

        /* Booking Section */
        .booking-section {
            position: relative;
            background-color: rgba(255, 153, 51, 0.05);
        }

        .booking-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ff9933' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
            z-index: -1;
        }

        .booking-container {
            max-width: 800px;
            margin: 0 auto 3rem;
        }

        .booking-tabs {
            background-color: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: var(--transition);
        }

        .booking-tabs:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .tabs-header {
            display: flex;
            border-bottom: 1px solid var(--border-color);
        }

        .tab-btn {
            flex: 1;
            text-align: center;
            padding: 1rem;
            background: none;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }

        .tab-btn::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0;
            height: 3px;
            background-color: var(--primary);
            transition: var(--transition);
        }

        .tab-btn:hover::after, .tab-btn.active::after {
            width: 100%;
        }

        .tab-btn.active {
            color: var(--primary);
        }

        .booking-form {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .input-icon {
            position: relative;
        }

        .input-icon .icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .input-icon input {
            padding-left: 3rem;
        }

        input[type="text"], input[type="email"], input[type="password"], input[type="date"], select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 1rem;
            transition: var(--transition);
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 153, 51, 0.3);
        }

        /* Packages Grid */
        .packages-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .package-card {
            background-color: var(--white);
            border-radius: var(--radius);
            padding: 2rem;
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid transparent;
        }

        .package-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .package-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--primary);
        }

        .package-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .package-description {
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }

        .package-price {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        /* Testimonials Section */
        .testimonials-section {
            background-color: var(--white);
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .testimonial-card {
            background-color: var(--off-white);
            border-radius: var(--radius);
            padding: 2rem;
            position: relative;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: 1rem;
            left: 1.5rem;
            font-size: 4rem;
            color: rgba(255, 153, 51, 0.2);
            font-family: serif;
            line-height: 1;
        }

        .testimonial-content {
            margin-bottom: 1.5rem;
            font-style: italic;
            color: var(--text-dark);
            position: relative;
            z-index: 1;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }

        .author-info h4 {
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .author-info p {
            font-size: 0.875rem;
            color: var(--text-light);
            margin-bottom: 0;
        }

        /* Footer */
        .site-footer {
            background-color: #1a1a2e;
            color: rgba(255, 255, 255, 0.85);
            padding-top: 4rem;
            position: relative;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }

        .footer-logo .logo-text {
            color: var(--white);
            -webkit-text-fill-color: var(--white);
            background: none;
        }

        .footer-desc {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }

        .footer-heading {
            font-size: 1.25rem;
            color: var(--white);
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
        }

        .footer-heading::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -8px;
            width: 40px;
            height: 2px;
            background-color: var(--primary);
        }

        .footer-links li {
            margin-bottom: 1rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-links a:hover {
            color: var(--primary);
            padding-left: 5px;
        }

        .footer-links a i {
            width: 16px;
            font-size: 14px;
        }

        .social-icons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .social-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            transition: var(--transition);
        }

        .social-icon:hover {
            background-color: var(--primary);
            transform: translateY(-3px);
        }

        .social-icon i {
            font-size: 18px;
            color: var(--white);
        }

        .contact-list li {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .contact-list i {
            width: 20px;
            color: var(--primary);
            font-size: 16px;
        }

        .subscribe-form {
            margin-top: 1.5rem;
        }

        .subscribe-form input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: var(--radius);
            color: var(--white);
            margin-bottom: 1rem;
        }

        .subscribe-form input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .subscribe-form input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 153, 51, 0.3);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .copyright {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
        }

        .footer-legal {
            display: flex;
            gap: 1.5rem;
            font-size: 0.875rem;
        }

        .footer-legal a {
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-legal a:hover {
            color: var(--primary);
        }

        /* Back to top button */
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 50px;
            height: 50px;
            background-color: var(--primary);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 999;
            box-shadow: var(--shadow);
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background-color: var(--primary-dark);
            transform: translateY(-5px);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .hero-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-section {
                padding: 4rem 0;
            }

            .search-form {
                flex-direction: column;
            }

            .nav-menu {
                position: fixed;
                top: 80px;
                left: -100%;
                width: 80%;
                height: calc(100vh - 80px);
                background-color: var(--white);
                flex-direction: column;
                align-items: flex-start;
                padding: 2rem;
                transition: left 0.3s ease;
                box-shadow: var(--shadow-lg);
                z-index: 999;
            }

            .nav-menu.active {
                left: 0;
            }

            .nav-links {
                flex-direction: column;
                width: 100%;
                gap: 1rem;
            }

            .auth-buttons {
                flex-direction: column;
                width: 100%;
                margin-top: 1rem;
            }

            .mobile-menu-toggle {
                display: flex;
            }

            .mobile-menu-toggle.active span:nth-child(1) {
                transform: translateY(8px) rotate(45deg);
            }

            .mobile-menu-toggle.active span:nth-child(2) {
                opacity: 0;
            }

            .mobile-menu-toggle.active span:nth-child(3) {
                transform: translateY(-8px) rotate(-45deg);
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .footer-grid {
                grid-template-columns: 1fr;
            }
            
            .hero-stats {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .footer-legal {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .section-title {
                font-size: 1.75rem;
            }

            .hero-title {
                font-size: 2rem;
            }
        }
</style>
</head>
<body>
      <!-- Header and Navigation -->
      <header class="site-header">
    <nav class="navbar">
        <div class="container">
            <a href="home.php" class="logo">
                <span class="logo-text">HOVI & JAVI</span>
            </a>

            <div class="mobile-menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="nav-menu" id="navMenu">
                <ul class="nav-links">
                    <li><a href="home.php" >Home</a></li>
                    <li><a href="destinations.php">Destinations</a></li>
                    <li><a href="plan.php">Plan Your Trip</a></li>
                    <li><a href="blogs.php">Blogs</a></li>
                    <li><a href="aboutus.php">AboutUs</a></li>
                </ul>

                <div class="auth-buttons">
                    <?php if(isset($_SESSION['user'])): ?>
                        <div class="user-profile" id="userProfile">
                            <span class="user-name"><?php echo $_SESSION['user']; ?></span>
                            <i class="fas fa-chevron-down"></i>
                            <div class="dropdown-content" id="profileDropdown">
                                <a href="profile.php"><i class="fas fa-user" class="active"></i> My Profile</a>
                                <a href="bookings.php"><i class="fas fa-calendar-check"></i> My Bookings</a>
                                <a href="wishlist.php"><i class="fas fa-heart"></i> Wishlist</a>

                                <!-- ✅ Modified logout as list item -->
                                
                                    <a href="logout.php">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>Logout</span>
                                    </a>
                                
                            </div>
                        </div>
                    <?php else: ?>
                       
                                <a href="login.php">
                                    <i class="fas fa-sign-in-alt"></i>
                                    <span>Login</span>
                                </a>
                            
                           
                        
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>

    <main class="profile-page">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">My Profile</h1>
                <nav class="breadcrumb">
                    <ol>
                        <li><a href="index.php">Home</a></li>
                        <li>My Profile</li>
                    </ol>
                </nav>
            </div>
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : 'danger'; ?>" id="alert-message">
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="close-alert" onclick="this.parentElement.style.display='none';">&times;</button>
                </div>
            <?php endif; ?>
            
            <div class="profile-content">
                <!-- Sidebar -->
                <aside class="profile-sidebar">
                    <div class="profile-card">
                        <div class="profile-avatar-container">
                            <img src="<?php echo htmlspecialchars($userData['avatar']); ?>" alt="Profile Avatar" class="profile-avatar">
                            <form method="POST" action="" enctype="multipart/form-data" class="profile-picture-form">
                                <label for="profile_picture" class="avatar-upload-label">
                                    <i class="fas fa-camera"></i>
                                    <span>Update</span>
                                </label>
                                <input type="file" id="profile_picture" name="profile_picture" style="display:none" onchange="document.getElementById('picture-form-submit').click();">
                                <button type="submit" id="picture-form-submit" name="upload_picture" style="display:none">Upload</button>
                            </form>
                        </div>
                        
                        <div class="profile-info">
                            <h3 class="profile-name"><?php echo htmlspecialchars($userData['fullName'] ?: $userData['username']); ?></h3>
                            <p class="profile-username">@<?php echo htmlspecialchars($userData['username']); ?></p>
                            
                            <div class="profile-meta">
                                <div class="profile-meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo htmlspecialchars($userData['city'] ?: 'Not specified'); ?></span>
                                </div>
                                <div class="profile-meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Member since <?php echo htmlspecialchars($userData['joinDate']); ?></span>
                                </div>
                                <div class="profile-meta-item">
                                    <i class="fas fa-clock"></i>
                                    <span>Last active: <?php echo htmlspecialchars($userData['lastLogin']); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <nav class="profile-nav">
                            <ul>
                                <li class="active"><a href="profile.php"><i class="fas fa-user"></i> Profile Information</a></li>
                                <li><a href="bookings.php"><i class="fas fa-calendar-check"></i> My Bookings</a></li>
                                <li><a href="wishlist.php"><i class="fas fa-heart"></i> My Wishlist</a></li>
                                <li><a href="settings.php"><i class="fas fa-cog"></i> Account Settings</a></li>
                                <li><a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                            </ul>
                        </nav>
                    </div>
                </aside>
                
                <!-- Main Content -->
                <div class="profile-main">
                    <div class="profile-section">
                        <div class="section-header">
                            <h2><i class="fas fa-user-edit"></i> Profile Information</h2>
                            <p class="section-desc">Update your personal information and how it appears on your profile</p>
                        </div>
                        
                        <form method="POST" action="" class="profile-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <div class="input-group">
                                        <span class="input-icon"><i class="fas fa-user"></i></span>
                                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>" disabled>
                                    </div>
                                    <small class="form-text">Username cannot be changed</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="full_name">Full Name *</label>
                                    <div class="input-group">
                                        <span class="input-icon"><i class="fas fa-user-circle"></i></span>
                                        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($userData['fullName']); ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email Address *</label>
                                    <div class="input-group">
                                        <span class="input-icon"><i class="fas fa-envelope"></i></span>
                                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-icon"><i class="fas fa-phone"></i></span>
                                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($userData['phone']); ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="city">City</label>
                                <div class="input-group">
                                    <span class="input-icon"><i class="fas fa-map-marker-alt"></i></span>
                                    <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($userData['city']); ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="bio">Bio</label>
                                <textarea id="bio" name="bio" rows="4" placeholder="Tell us a bit about yourself"><?php echo htmlspecialchars($userData['bio']); ?></textarea>
                                <small class="form-text">Brief description for your profile</small>
                            </div>
                            
                            <div class="form-actions">
                                <button type="reset" class="btn btn-outline">Cancel</button>
                                <button type="submit" name="update_profile" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="profile-section">
                        <div class="section-header">
                            <h2><i class="fas fa-lock"></i> Change Password</h2>
                            <p class="section-desc">Update your password to maintain account security</p>
                        </div>
                        
                        <form method="POST" action="" class="password-form">
                            <div class="form-group">
                                <label for="current_password">Current Password *</label>
                                <div class="input-group">
                                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                                    <input type="password" id="current_password" name="current_password" required>
                                    <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="new_password">New Password *</label>
                                    <div class="input-group">
                                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                                        <input type="password" id="new_password" name="new_password" required>
                                        <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="confirm_password">Confirm New Password *</label>
                                    <div class="input-group">
                                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                                        <input type="password" id="confirm_password" name="confirm_password" required>
                                        <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="password-strength">
                                <h4>Password strength requirements:</h4>
                                <ul class="password-requirements">
                                    <li>At least 8 characters long</li>
                                    <li>Contains uppercase and lowercase letters</li>
                                    <li>Contains at least one number</li>
                                    <li>Contains at least one special character</li>
                                </ul>
                            </div>
                            
                            <div class="form-actions">
                                <button type="reset" class="btn btn-outline">Cancel</button>
                                <button type="submit" name="change_password" class="btn btn-secondary">
                                    <i class="fas fa-key"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

      
<!-- Footer Section -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Company Info -->
            <div class="footer-section">
                <div class="footer-logo">
                    <span class="logo-emoji">🇮🇳</span>
                    <span class="logo-text">HOVI & JAVI</span>
                </div>
                <p class="footer-desc">
                    Discover the beauty and diversity of India with our expert-guided tours and personalized travel experiences.
                </p>
                <div class="social-icons">
                    <a href="#" class="social-icon">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-icon">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-icon">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="social-icon">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="footer-section">
                <h3 class="footer-heading">Explore</h3>
                <ul class="footer-links">
                    <li><a href="destinations.php"><i class="fas fa-map-marker-alt"></i> Destinations</a></li>
                    <li><a href="tours.php"><i class="fas fa-route"></i> Tour Packages</a></li>
                    <li><a href="experiences.php"><i class="fas fa-star"></i> Cultural Experiences</a></li>
                    <li><a href="adventure.php"><i class="fas fa-hiking"></i> Adventure Tours</a></li>
                    <li><a href="spiritual.php"><i class="fas fa-praying-hands"></i> Spiritual Journeys</a></li>
                    <li><a href="guide.php"><i class="fas fa-info-circle"></i> Travel Guide</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div class="footer-section">
                <h3 class="footer-heading">Contact</h3>
                <ul class="contact-list">
                    <li class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>123 Tourism Street, New Delhi, India - 110001</span>
                    </li>
                    <li class="contact-item">
                        <i class="fas fa-phone-alt"></i>
                        <a href="tel:+919876543210">+91 98765 43210</a>
                    </li>
                    <li class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:info@hoviandjavi.com">info@hoviandjavi.com</a>
                    </li>
                    <li class="contact-item">
                        <i class="fas fa-clock"></i>
                        <span>Mon - Sat: 9:00 AM - 6:00 PM</span>
                    </li>
                </ul>
            </div>
            
            <!-- Newsletter -->
            <div class="footer-section">
                <h3 class="footer-heading">Subscribe</h3>
                <p class="footer-desc">
                    Subscribe to our newsletter for travel tips, special offers, and cultural insights.
                </p>
                
                <?php
                // Display newsletter subscription message if exists
                if (isset($_SESSION['newsletter_message']) && isset($_SESSION['newsletter_status'])) {
                    $status_class = $_SESSION['newsletter_status'];
                    echo '<div class="alert alert-' . $status_class . '">' . $_SESSION['newsletter_message'] . '</div>';
                    
                    // Clear the session message after displaying
                    unset($_SESSION['newsletter_message']);
                    unset($_SESSION['newsletter_status']);
                }
                ?>
                
                <form action="subscribe_newsletter.php" method="POST" class="subscribe-form">
                    <input type="email" name="email" placeholder="Your email address" required>
                    <button type="submit" class="btn btn-primary btn-block">Subscribe</button>
                </form>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p class="copyright">
                &copy; <?php echo date("Y"); ?> HOVI & JAVI Travel Guide. All rights reserved.
            </p>
            <div class="footer-legal">
                <a href="terms.php">Terms & Conditions</a>
                <a href="privacy.php">Privacy Policy</a>
                <a href="cookies.php">Cookies Policy</a>
                <a href="faq.php">FAQs</a>
            </div>
        </div>
    </div>
</footer>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alertMessage = document.getElementById('alert-message');
            if (alertMessage) {
                setTimeout(function() {
                    alertMessage.style.display = 'none';
                }, 5000);
            }
        });
    </script>
    <script>
    // Dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
        const userProfile = document.getElementById('userProfile');
        if (userProfile) {
            userProfile.addEventListener('click', function(e) {
                e.stopPropagation();
                const dropdown = document.getElementById('profileDropdown');
                dropdown.classList.toggle('show');
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            const dropdowns = document.getElementsByClassName('dropdown-content');
            for (let i = 0; i < dropdowns.length; i++) {
                const openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        });

        // Mobile menu toggle
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');
        if (menuToggle && navMenu) {
            menuToggle.addEventListener('click', function() {
                this.classList.toggle('active');
                navMenu.classList.toggle('active');
            });
        }
    });
</script>
</body>
</html>