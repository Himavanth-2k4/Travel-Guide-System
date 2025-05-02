<?php
// Start session for user functionality
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = ""; 
$db = "travel_guide";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle trip plan submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_plan'])) {
    if (isset($_SESSION['user'])) {
        $user_name = $conn->real_escape_string($_SESSION['user']);
        $email = $conn->real_escape_string($_POST['email']);
        $destination = $conn->real_escape_string($_POST['destination']);
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $num_travelers = intval($_POST['num_travelers']);
        $accommodation = $conn->real_escape_string($_POST['accommodation']);
        $transportation = $conn->real_escape_string($_POST['transportation']);
        $activities = $conn->real_escape_string($_POST['activities']);
        $special_requirements = $conn->real_escape_string($_POST['special_requirements']);
        $budget_range = $conn->real_escape_string($_POST['budget_range']);
        
        $sql = "INSERT INTO trips (user_name, email, destination, start_date, end_date, 
                num_travelers, accommodation_type, transportation_type, activities, 
                special_requirements, budget_range) 
                VALUES ('$user_name', '$email', '$destination', '$start_date', '$end_date', 
                $num_travelers, '$accommodation', '$transportation', '$activities', 
                '$special_requirements', '$budget_range')";
        
        if ($conn->query($sql)) {
            $success_message = "Trip plan submitted successfully! Our team will contact you soon.";
        } else {
            $error_message = "Error submitting trip plan: " . $conn->error;
        }
    } else {
        $error_message = "Please login to submit a trip plan";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Your Trip - HOVI & JAVI</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Include all the CSS from your about us page here */
        .about-container {
            max-width: 1200px;
            margin: 120px auto 50px;
            padding: 0 20px;
        }

        .about-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .about-header h1 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 15px;
        }

        .about-header p {
            font-size: 1.1rem;
            color: #666;
            max-width: 800px;
            margin: 0 auto;
        }

        .about-mission {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 40px;
        }

        .about-mission h2 {
            color: #FF6B35;
            margin-bottom: 20px;
        }

        .about-mission p {
            font-size: 1.05rem;
            color: #555;
            line-height: 1.7;
        }

        .team-section {
            margin-top: 50px;
        }

        .team-section h2 {
            text-align: center;
            font-size: 2rem;
            color: #333;
            margin-bottom: 40px;
        }

        .team-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .team-member {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .team-member:hover {
            transform: translateY(-10px);
        }

        .member-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            border: 5px solid #f0f0f0;
        }

        .member-name {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 10px;
        }

        .member-role {
            font-size: 1rem;
            color: #FF6B35;
            margin-bottom: 15px;
        }

        .member-bio {
            font-size: 0.95rem;
            color: #666;
            line-height: 1.6;
        }

        /* Responsive styles */
        @media (max-width: 992px) {
            .dropdown-content {
                min-width: 450px;
                right: -150px;
            }
            
            .region-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .about-container {
                margin-top: 100px;
            }
            
            .navbar {
                flex-wrap: wrap;
                padding: 15px 5%;
                gap: 15px;
            }

            .search-container-nav {
                order: 3;
                width: 100%;
                margin: 10px 0;
            }

            .nav-links {
                display: none;
                width: 100%;
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .dropdown {
                width: 100%;
            }

            .dropdown-content {
                position: static;
                width: 100%;
                min-width: unset;
                display: none;
                margin-top: 10px;
                margin-bottom: 10px;
                right: 0;
            }
            
            .region-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .dropdown:hover .dropdown-content,
            .dropdown:focus .dropdown-content,
            .dropdown:active .dropdown-content {
                display: block;
            }

            .hamburger {
                display: flex;
            }

            .navbar.active .nav-links {
                display: flex;
            }
            
            .about-mission {
                padding: 30px 20px;
            }
        }
        
        @media (max-width: 480px) {
            .region-container {
                grid-template-columns: 1fr;
            }
            
            .about-header h1 {
                font-size: 2rem;
            }
            
            .team-container {
                grid-template-columns: 1fr;
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
        /* Make sure to merge and resolve any conflicts */
        
        /* Your existing CSS content goes here */
        
        /* Additional CSS for blogs */
        .blog-section {
            padding: 4rem 0;
            background-color: var(--off-white);
        }

        .blog-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .blog-form {
            background: var(--white);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 3rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            font-family: inherit;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .rating-input {
            display: flex;
            gap: 0.5rem;
            font-size: 1.5rem;
        }

        .rating-input input[type="radio"] {
            display: none;
        }

        .rating-input label {
            color: #ddd;
            cursor: pointer;
        }

        .rating-input label:hover,
        .rating-input label:hover ~ label,
        .rating-input input[type="radio"]:checked ~ label {
            color: var(--primary);
        }

        .blog-card {
            background: var(--white);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .blog-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .blog-meta {
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .blog-rating {
            color: var(--primary);
            font-size: 1.2rem;
        }

        .blog-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .blog-location {
            color: var(--primary);
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .blog-content {
            line-height: 1.8;
            color: var(--text-dark);
        }

        .alert {
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }
        /* Additional CSS for trip planning */
        .plan-section {
            padding: 4rem 0;
            background-color: var(--off-white);
        }

        .plan-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .plan-form {
            background: var(--white);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 3rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-dark);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            font-family: inherit;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 153, 51, 0.3);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .alert {
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }

        .feature-box {
            background: var(--white);
            padding: 2rem;
            border-radius: var(--radius);
            text-align: center;
            transition: var(--transition);
        }

        .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 153, 51, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .feature-icon i {
            font-size: 24px;
            color: var(--primary);
        }

        .feature-title {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }

        .feature-text {
            color: var(--text-light);
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
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
                    <li><a href="plan.php" class="active">Plan Your Trip</a></li>
                    <li><a href="blogs.php">Blogs</a></li>
                    <li><a href="aboutus.php">AboutUs</a></li>
                </ul>

                <div class="auth-buttons">
                    <?php if(isset($_SESSION['user'])): ?>
                        <div class="user-profile" id="userProfile">
                            <span class="user-name"><?php echo $_SESSION['user']; ?></span>
                            <i class="fas fa-chevron-down"></i>
                            <div class="dropdown-content" id="profileDropdown">
                                <a href="profile.php"><i class="fas fa-user"></i> My Profile</a>
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

    <!-- Plan Section -->
    <section class="plan-section">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-title">Plan Your Perfect Indian Adventure</h2>
                <p class="section-subtitle">Tell us about your dream trip to India, and let our experts create a personalized itinerary for you</p>
            </div>

            <div class="plan-container">
                <?php if(isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>

                <?php if(isset($error_message)): ?>
                    <div class="alert alert-error"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <!-- Trip Planning Form -->
                <div class="plan-form">
                    <form method="POST" action="">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="destination">Preferred Destination</label>
                                <input type="text" id="destination" name="destination" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="num_travelers">Number of Travelers</label>
                                <input type="number" id="num_travelers" name="num_travelers" min="1" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="budget_range">Budget Range</label>
                                <select id="budget_range" name="budget_range" class="form-control" required>
                                    <option value="">Select Budget Range</option>
                                    <option value="economy">Economy (₹20,000 - ₹50,000)</option>
                                    <option value="standard">Standard (₹50,000 - ₹1,00,000)</option>
                                    <option value="luxury">Luxury (₹1,00,000+)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="accommodation">Preferred Accommodation</label>
                                <select id="accommodation" name="accommodation" class="form-control" required>
                                    <option value="">Select Accommodation Type</option>
                                    <option value="hotel">Hotel</option>
                                    <option value="resort">Resort</option>
                                    <option value="homestay">Homestay</option>
                                    <option value="heritage">Heritage Property</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="transportation">Preferred Transportation</label>
                                <select id="transportation" name="transportation" class="form-control" required>
                                    <option value="">Select Transportation Type</option>
                                    <option value="private">Private Vehicle</option>
                                    <option value="train">Train</option>
                                    <option value="flight">Flight</option>
                                    <option value="mixed">Mixed</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="activities">Preferred Activities</label>
                            <textarea id="activities" name="activities" class="form-control" placeholder="Tell us what activities interest you (e.g., sightseeing, adventure sports, cultural experiences)"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="special_requirements">Special Requirements</label>
                            <textarea id="special_requirements" name="special_requirements" class="form-control" placeholder="Any special requirements or preferences?"></textarea>
                        </div>

                        <button type="submit" name="submit_plan" class="btn btn-primary btn-block">Submit Trip Plan</button>
                    </form>
                </div>

                <!-- Features Grid -->
                <div class="features-grid">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h3 class="feature-title">Expert Planning</h3>
                        <p class="feature-text">Our travel experts will create a personalized itinerary based on your preferences</p>
                    </div>

                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <h3 class="feature-title">Tailored Experience</h3>
                        <p class="feature-text">Every detail of your trip is customized to match your interests and style</p>
                    </div>

                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">Safe Travel</h3>
                        <p class="feature-text">Your safety is our priority with verified services and 24/7 support</p>
                    </div>

                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <h3 class="feature-title">Best Value</h3>
                        <p class="feature-text">Get the best experiences within your budget with our price guarantee</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Include Footer -->
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
                    if (isset($_SESSION['newsletter_message']) && isset($_SESSION['newsletter_status'])) {
                        $status_class = $_SESSION['newsletter_status'];
                        echo '<div class="alert alert-' . $status_class . '">' . $_SESSION['newsletter_message'] . '</div>';
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
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const menuToggle = document.getElementById('menuToggle');
            const navMenu = document.getElementById('navMenu');
            
            if (menuToggle && navMenu) {
                menuToggle.addEventListener('click', function() {
                    navMenu.classList.toggle('active');
                    menuToggle.classList.toggle('active');
                });
            }
            
            // User profile dropdown
            const userProfile = document.getElementById('userProfile');
            const profileDropdown = document.getElementById('profileDropdown');
            
            if (userProfile && profileDropdown) {
                userProfile.addEventListener('click', function() {
                    profileDropdown.classList.toggle('show');
                });
                
                document.addEventListener('click', function(event) {
                    if (!userProfile.contains(event.target)) {
                        profileDropdown.classList.remove('show');
                    }
                });
            }

            // Date validation
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');

            if (startDate && endDate) {
                // Set minimum date as today
                const today = new Date().toISOString().split('T')[0];
                startDate.min = today;

                startDate.addEventListener('change', function() {
                    endDate.min = startDate.value;
                    if (endDate.value && endDate.value < startDate.value) {
                        endDate.value = startDate.value;
                    }
                });
            }
        });
    </script>
</body>
</html>