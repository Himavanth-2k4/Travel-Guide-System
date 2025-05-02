
<?php
// Start session for potential user login functionality
session_start();
// Check if the login form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // In a real app, you would validate credentials against a database
    // For demo purposes, we're just setting the session directly
    $_SESSION['user'] = $_POST['username'];
    $_SESSION['user_avatar'] = 'https://randomuser.me/api/portraits/men/32.jpg';
    
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Logout functionality
if (isset($_GET['logout'])) {
    // Destroy the session
    session_destroy();
    
    // Redirect to prevent the logout parameter from staying in the URL
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOVI & JAVI - Discover India</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- All CSS is included inline -->
    <style>
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
        :root {
            --primary-color: #ff9933; /* Saffron from Indian flag */
            --secondary-color: #138808; /* Green from Indian flag */
            --accent-color: #000080; /* Navy blue */
            --light-bg: #f8f9fa;
            --card-border: #e0e0e0;
            --text-dark: #333333;
            --text-light: #6c757d;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background-color: var(--light-bg);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .page-title {
            text-align: center;
            margin-bottom: 2.5rem;
            color: var(--accent-color);
            font-size: 2.5rem;
            position: relative;
        }
        
        .page-title::after {
            content: "";
            display: block;
            width: 100px;
            height: 3px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            margin: 10px auto;
        }
        
        .states-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }
        
        .state-card {
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 1.5rem;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .state-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.12);
        }
        
        .state-name {
            color: var(--accent-color);
            font-size: 1.4rem;
            margin-bottom: 0.8rem;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 8px;
        }
        
        .state-specialty {
            color: var(--text-light);
            margin-bottom: 0.8rem;
        }
        
        .state-details {
            margin-top: 1rem;
            font-size: 0.9rem;
        }
        
        .state-tag {
            display: inline-block;
            background-color: #e9ecef;
            color: var(--text-dark);
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        
        .search-box {
            max-width: 500px;
            margin: 0 auto 30px;
            display: flex;
        }
        
        .search-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid var(--card-border);
            border-radius: 30px 0 0 30px;
            font-size: 1rem;
        }
        
        .search-button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 0 30px 30px 0;
            cursor: pointer;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .states-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
            
            .page-title {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 480px) {
            .states-grid {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 1rem;
            }
        }
        :root {
            --saffron: #FF9933; /* Indian flag saffron */
            --white: #FFFFFF; /* Indian flag white */
            --green: #138808; /* Indian flag green */
            --navy: #000080; /* Complementary color */
            --light-saffron: rgba(255, 153, 51, 0.1);
            --light-green: rgba(19, 136, 8, 0.1);
            --text-dark: #333333;
            --text-light: #666666;
            --box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', Arial, sans-serif;
        }
        
        body {
            color: var(--text-dark);
            background-color: #f8f8f8;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Hero Section with Tricolor Theme */
        .hero-banner {
            position: relative;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/heritage-banner.jpg');
            background-size: cover;
            background-position: center;
            padding: 100px 20px;
            text-align: center;
            color: var(--white);
            overflow: hidden;
        }
        
        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(to right, var(--saffron) 33.33%, var(--white) 33.33%, var(--white) 66.66%, var(--green) 66.66%);
        }
        
        .hero-banner h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .hero-banner p {
            font-size: 1.3rem;
            max-width: 800px;
            margin: 0 auto 30px;
        }
        
        /* Introduction Section */
        .intro-section {
            background-color: var(--white);
            padding: 50px 20px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        
        .intro-section h2 {
            color: var(--saffron);
            font-size: 2.2rem;
            margin-bottom: 20px;
        }
        
        .intro-section p {
            max-width: 900px;
            margin: 0 auto;
            font-size: 1.1rem;
            color: var(--text-light);
        }
        
        /* Tricolor Separator */
        .tricolor-separator {
            height: 4px;
            width: 120px;
            margin: 30px auto;
            display: flex;
        }
        
        .tricolor-separator span {
            height: 100%;
            flex: 1;
        }
        
        .tricolor-separator span:nth-child(1) { background-color: var(--saffron); }
        .tricolor-separator span:nth-child(2) { background-color: var(--white); border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; }
        .tricolor-separator span:nth-child(3) { background-color: var(--green); }
        
        /* Search Section */
        .search-section {
            background-color: var(--white);
            padding: 30px 20px;
            text-align: center;
            box-shadow: var(--box-shadow);
            border-radius: 8px;
            max-width: 800px;
            margin: -25px auto 40px;
            position: relative;
            z-index: 10;
        }
        
        .search-section h3 {
            color: var(--saffron);
            margin-bottom: 15px;
            font-size: 1.4rem;
        }
        
        .search-input {
            width: 100%;
            max-width: 500px;
            padding: 15px 20px;
            border: 2px solid #eee;
            border-radius: 30px;
            font-size: 1rem;
            background-color: #f9f9f9;
            transition: var(--transition);
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--saffron);
            box-shadow: 0 0 0 3px rgba(255, 153, 51, 0.2);
        }
        
        /* Category Filters */
        .category-filters {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin: 25px 0;
        }
        
        .category-btn {
            padding: 8px 16px;
            background-color: #f0f0f0;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: var(--transition);
        }
        
        .category-btn:hover {
            background-color: #e0e0e0;
        }
        
        .category-btn.active {
            background-color: var(--saffron);
            color: white;
        }
        
        /* Heritage Grid */
        .heritage-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 60px;
        }
        
        .heritage-card {
            background-color: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            border-top: 4px solid var(--saffron);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .heritage-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        .heritage-card.cultural { border-top-color: var(--saffron); }
        .heritage-card.natural { border-top-color: var(--green); }
        .heritage-card.mixed { border-top-color: var(--navy); }
        
        .card-image {
            height: 180px;
            background-color: #eee;
            background-size: cover;
            background-position: center;
        }
        
        .card-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .heritage-card h3 {
            color: var(--text-dark);
            font-size: 1.3rem;
            margin-bottom: 10px;
        }
        
        .heritage-card p {
            color: var(--text-light);
            margin-bottom: 15px;
            font-size: 0.95rem;
        }
        
        .heritage-type {
            display: inline-block;
            font-size: 0.8rem;
            padding: 4px 10px;
            border-radius: 12px;
            margin-bottom: 15px;
        }
        
        .type-cultural {
            background-color: var(--light-saffron);
            color: darken(var(--saffron), 20%);
        }
        
        .type-natural {
            background-color: var(--light-green);
            color: darken(var(--green), 10%);
        }
        
        .type-mixed {
            background-color: rgba(0, 0, 128, 0.1);
            color: var(--navy);
        }
        
        .card-footer {
            margin-top: auto;
            padding-top: 15px;
            border-top: 1px solid #eee;
            font-size: 0.9rem;
            color: var(--text-light);
        }
        
        /* Map Section */
        .map-section {
            background-color: var(--white);
            padding: 50px 20px;
            text-align: center;
            margin-bottom: 50px;
            border-radius: 10px;
            box-shadow: var(--box-shadow);
        }
        
        .map-section h2 {
            color: var(--saffron);
            margin-bottom: 20px;
        }
        
        .map-container {
            height: 400px;
            background-color: #f0f0f0;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        /* Stats Section */
        .stats-section {
            background: linear-gradient(rgba(255,153,51,0.1), rgba(19,136,8,0.1));
            padding: 60px 20px;
            margin-bottom: 50px;
            border-radius: 10px;
        }
        
        .stats-section h2 {
            text-align: center;
            color: var(--text-dark);
            margin-bottom: 40px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            text-align: center;
        }
        
        .stat-card {
            padding: 20px;
            background-color: var(--white);
            border-radius: 8px;
            box-shadow: var(--box-shadow);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: var(--saffron);
        }
        
        .stat-card:nth-child(2) .stat-number {
            color: var(--green);
        }
        
        .stat-card:nth-child(3) .stat-number {
            color: var(--navy);
        }
        
        .stat-label {
            color: var(--text-light);
            font-size: 1rem;
        }
        
        /* Info Box */
        .info-box {
            background-color: var(--white);
            border-left: 4px solid var(--saffron);
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 0 8px 8px 0;
            box-shadow: var(--box-shadow);
        }
        
        .info-box h3 {
            color: var(--saffron);
            margin-bottom: 10px;
        }
        
        /* Footer Note */
        .footer-note {
            text-align: center;
            padding: 30px 0;
            background-color: #f0f0f0;
            margin-top: 50px;
            border-top: 1px solid #ddd;
        }
        
        .footer-note p {
            max-width: 800px;
            margin: 0 auto;
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 40px;
            margin-bottom: 60px;
        }
        
        .pagination button {
            padding: 8px 16px;
            margin: 0 5px;
            border: 1px solid #ddd;
            background-color: white;
            cursor: pointer;
            border-radius: 4px;
            transition: var(--transition);
        }
        
        .pagination button.active {
            background-color: var(--saffron);
            color: white;
            border-color: var(--saffron);
        }
        
        .pagination button:hover:not(.active) {
            background-color: #f0f0f0;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero-banner h1 {
                font-size: 2.5rem;
            }
            
            .heritage-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                max-width: 400px;
                margin: 0 auto;
            }
        }
        
        @media (max-width: 576px) {
            .hero-banner h1 {
                font-size: 2rem;
            }
            
            .heritage-grid {
                grid-template-columns: 1fr;
            }
            
            .search-input {
                max-width: 100%;
            }
        }
        :root {
            --saffron: #FF9933; /* Indian flag saffron */
            --white: #FFFFFF; /* Indian flag white */
            --green: #138808; /* Indian flag green */
            --navy: #000080; /* Complementary color */
            --light-saffron: rgba(255, 153, 51, 0.1);
            --light-green: rgba(19, 136, 8, 0.1);
            --text-dark: #333333;
            --text-light: #666666;
            --box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', Arial, sans-serif;
        }
        
        body {
            color: var(--text-dark);
            background-color: #f8f8f8;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Hero Section with Tricolor Theme */
        .hero-banner {
            position: relative;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/heritage-banner.jpg');
            background-size: cover;
            background-position: center;
            padding: 100px 20px;
            text-align: center;
            color: var(--white);
            overflow: hidden;
        }
        
        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(to right, var(--saffron) 33.33%, var(--white) 33.33%, var(--white) 66.66%, var(--green) 66.66%);
        }
        
        .hero-banner h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .hero-banner p {
            font-size: 1.3rem;
            max-width: 800px;
            margin: 0 auto 30px;
        }
        
        /* Introduction Section */
        .intro-section {
            background-color: var(--white);
            padding: 50px 20px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        
        .intro-section h2 {
            color: var(--saffron);
            font-size: 2.2rem;
            margin-bottom: 20px;
        }
        
        .intro-section p {
            max-width: 900px;
            margin: 0 auto;
            font-size: 1.1rem;
            color: var(--text-light);
        }
        
        /* Tricolor Separator */
        .tricolor-separator {
            height: 4px;
            width: 120px;
            margin: 30px auto;
            display: flex;
        }
        
        .tricolor-separator span {
            height: 100%;
            flex: 1;
        }
        
        .tricolor-separator span:nth-child(1) { background-color: var(--saffron); }
        .tricolor-separator span:nth-child(2) { background-color: var(--white); border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; }
        .tricolor-separator span:nth-child(3) { background-color: var(--green); }
        
        /* Search Section */
        .search-section {
            background-color: var(--white);
            padding: 30px 20px;
            text-align: center;
            box-shadow: var(--box-shadow);
            border-radius: 8px;
            max-width: 800px;
            margin: -25px auto 40px;
            position: relative;
            z-index: 10;
        }
        
        .search-section h3 {
            color: var(--saffron);
            margin-bottom: 15px;
            font-size: 1.4rem;
        }
        
        .search-input {
            width: 100%;
            max-width: 500px;
            padding: 15px 20px;
            border: 2px solid #eee;
            border-radius: 30px;
            font-size: 1rem;
            background-color: #f9f9f9;
            transition: var(--transition);
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--saffron);
            box-shadow: 0 0 0 3px rgba(255, 153, 51, 0.2);
        }
        
        /* Category Filters */
        .category-filters {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin: 25px 0;
        }
        
        .category-btn {
            padding: 8px 16px;
            background-color: #f0f0f0;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: var(--transition);
        }
        
        .category-btn:hover {
            background-color: #e0e0e0;
        }
        
        .category-btn.active {
            background-color: var(--saffron);
            color: white;
        }
        
        /* Heritage Grid */
        .heritage-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 60px;
        }
        
        .heritage-card {
            background-color: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            border-top: 4px solid var(--saffron);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .heritage-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        .heritage-card.cultural { border-top-color: var(--saffron); }
        .heritage-card.natural { border-top-color: var(--green); }
        .heritage-card.mixed { border-top-color: var(--navy); }
        
        .card-image {
            height: 180px;
            background-color: #eee;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
        }
        
        .card-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .heritage-card h3 {
            color: var(--text-dark);
            font-size: 1.3rem;
            margin-bottom: 10px;
        }
        
        .heritage-card p {
            color: var(--text-light);
            margin-bottom: 15px;
            font-size: 0.95rem;
        }
        
        .heritage-type {
            display: inline-block;
            font-size: 0.8rem;
            padding: 4px 10px;
            border-radius: 12px;
            margin-bottom: 15px;
        }
        
        .type-cultural {
            background-color: var(--light-saffron);
            color: darken(var(--saffron), 20%);
        }
        
        .type-natural {
            background-color: var(--light-green);
            color: darken(var(--green), 10%);
        }
        
        .type-mixed {
            background-color: rgba(0, 0, 128, 0.1);
            color: var(--navy);
        }
        
        .card-footer {
            margin-top: auto;
            padding-top: 15px;
            border-top: 1px solid #eee;
            font-size: 0.9rem;
            color: var(--text-light);
        }
        
        /* Map Section */
        .map-section {
            background-color: var(--white);
            padding: 50px 20px;
            text-align: center;
            margin-bottom: 50px;
            border-radius: 10px;
            box-shadow: var(--box-shadow);
        }
        
        .map-section h2 {
            color: var(--saffron);
            margin-bottom: 20px;
        }
        
        .map-container {
            height: 400px;
            background-color: #f0f0f0;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        /* Stats Section */
        .stats-section {
            background: linear-gradient(rgba(255,153,51,0.1), rgba(19,136,8,0.1));
            padding: 60px 20px;
            margin-bottom: 50px;
            border-radius: 10px;
        }
        
        .stats-section h2 {
            text-align: center;
            color: var(--text-dark);
            margin-bottom: 40px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            text-align: center;
        }
        
        .stat-card {
            padding: 20px;
            background-color: var(--white);
            border-radius: 8px;
            box-shadow: var(--box-shadow);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: var(--saffron);
        }
        
        .stat-card:nth-child(2) .stat-number {
            color: var(--green);
        }
        
        .stat-card:nth-child(3) .stat-number {
            color: var(--navy);
        }
        
        .stat-label {
            color: var(--text-light);
            font-size: 1rem;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 40px;
            margin-bottom: 60px;
        }
        
        .pagination button {
            padding: 8px 16px;
            margin: 0 5px;
            border: 1px solid #ddd;
            background-color: white;
            cursor: pointer;
            border-radius: 4px;
            transition: var(--transition);
        }
        
        .pagination button.active {
            background-color: var(--saffron);
            color: white;
            border-color: var(--saffron);
        }
        
        .pagination button:hover:not(.active) {
            background-color: #f0f0f0;
        }
        
        /* Footer Note */
        .footer-note {
            text-align: center;
            padding: 30px 0;
            background-color: #f0f0f0;
            margin-top: 50px;
            border-top: 1px solid #ddd;
        }
        
        .footer-note p {
            max-width: 800px;
            margin: 0 auto;
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero-banner h1 {
                font-size: 2.5rem;
            }
            
            .heritage-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                max-width: 400px;
                margin: 0 auto;
            }
        }
        
        @media (max-width: 576px) {
            .hero-banner h1 {
                font-size: 2rem;
            }
            
            .heritage-grid {
                grid-template-columns: 1fr;
            }
            
            .search-input {
                max-width: 100%;
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
                    <li><a href="home.php" class="active">Home</a></li>
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

 
   <!-- Hero Banner with Indian Flag Theme -->
   <div class="hero-banner">
        <div class="container">
            <h1>World Heritage Sites in India</h1>
            <p>Explore India's 70 UNESCO World Heritage Sites spanning millennia of history, diverse cultures, and breathtaking natural landscapes</p>
        </div>
    </div>
    
    <!-- Introduction Section -->
    <div class="intro-section">
        <div class="container">
            <h2>India's Cultural & Natural Treasures</h2>
            <div class="tricolor-separator">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <p>India, with its rich history spanning over 5,000 years, hosts an impressive collection of UNESCO World Heritage Sites that showcase the country's architectural marvels, spiritual traditions, artistic brilliance, and natural wonders. From the iconic Taj Mahal to the ancient caves of Ajanta and Ellora, these sites represent the magnificent tapestry of Indian heritage.</p>
        </div>
    </div>
    
    <!-- Search & Filter Section -->
    <div class="container">
        <div class="search-section">
            <h3>Discover Heritage Sites</h3>
            <input type="text" id="heritageSearch" class="search-input" placeholder="Search by name, state, or type...">
            
            <div class="category-filters">
                <button class="category-btn active" data-filter="all">All Sites</button>
                <button class="category-btn" data-filter="cultural">Cultural</button>
                <button class="category-btn" data-filter="natural">Natural</button>
                <button class="category-btn" data-filter="mixed">Mixed</button>
            </div>
        </div>
        
        <!-- Heritage Sites Grid -->
        <div class="heritage-grid" id="heritageContainer">
            <?php
            // Complete list of heritage sites with all necessary information
            $allSites = [
                [
                    "name" => "Ajanta Caves",
                    "location" => "Aurangabad, Maharashtra",
                    "type" => "cultural",
                    "year" => "1983",
                    "description" => "Buddhist rock-cut cave monuments dating from the 2nd century BCE to 650 CE, featuring paintings and sculptures considered masterpieces of Buddhist religious art.",
                    "image" => "https://indiaforbeginners.com/wp-content/uploads/2020/04/India-for-Beginners-custom-tours-5.jpg"
                ],
                [
                    "name" => "Ellora Caves",
                    "location" => "Aurangabad, Maharashtra",
                    "type" => "cultural",
                    "year" => "1983",
                    "description" => "A complex of Buddhist, Hindu and Jain cave temples built between the 6th and 10th centuries, with the remarkable Kailasa temple carved from a single rock.",
                    "image" => "https://s7ap1.scene7.com/is/image/incredibleindia/ellora-caves-chhatrapati-sambhaji-nagar-maharashtra-attr-hero-5?qlt=82&ts=1727010646173"
                ],
                [
                    "name" => "Agra Fort",
                    "location" => "Agra, Uttar Pradesh",
                    "type" => "cultural",
                    "year" => "1983",
                    "description" => "A 16th-century Mughal monument, this imposing red sandstone fort complex encompasses magnificent palaces, audience halls and mosques.",
                    "image" => "https://thrillingtravel.in/wp-content/uploads/2023/06/Bengali-mahal-agra-fort.jpg"
                ],
                [
                    "name" => "Taj Mahal",
                    "location" => "Agra, Uttar Pradesh",
                    "type" => "cultural",
                    "year" => "1983",
                    "description" => "An immense mausoleum of white marble, built between 1631 and 1648 by Emperor Shah Jahan in memory of his favorite wife, it is the jewel of Muslim art in India.",
                    "image" => "https://media.tacdn.com/media/attractions-splice-spp-674x446/06/71/c3/53.jpg"
                ],
                [
                    "name" => "Sun Temple, Konark",
                    "location" => "Puri, Odisha",
                    "type" => "cultural",
                    "year" => "1984",
                    "description" => "Built in the 13th century, this temple takes the form of a colossal chariot with intricately carved stone wheels, pillars and walls dedicated to the sun god, Surya.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR5rQyy8lUcp2Yv9eOnrl7iXmkvkBOXnIStkw&s"
                ],
                [
                    "name" => "Group of Monuments at Mahabalipuram",
                    "location" => "Tamil Nadu",
                    "type" => "cultural",
                    "year" => "1984",
                    "description" => "Founded by the Pallava kings in the 7th-8th century, these monuments include rathas (temples in the form of chariots), mandapas (cave sanctuaries), and giant open-air rock reliefs.",
                    "image" => "https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0a/4a/8a/c0/shore-temple.jpg?w=1200&h=-1&s=1"
                ],
                [
                    "name" => "Kaziranga National Park",
                    "location" => "Assam",
                    "type" => "natural",
                    "year" => "1985",
                    "description" => "Home to two-thirds of the world's one-horned rhinoceroses, the park also houses significant populations of tigers, elephants, panthers, bears, and thousands of birds.",
                    "image" => "https://static.wixstatic.com/media/12a327_879a7d1e47a742f69b59ef8ac75e260b~mv2_d_2000_1334_s_2.jpg/v1/crop/x_0,y_4,w_2000,h_1326/fill/w_560,h_370,al_c,q_80,usm_0.66_1.00_0.01,enc_avif,quality_auto/DSC01590.jpg"
                ],
                [
                    "name" => "Keoladeo National Park",
                    "location" => "Bharatpur, Rajasthan",
                    "type" => "natural",
                    "year" => "1985",
                    "description" => "A famous avifauna sanctuary that hosts thousands of birds, especially during winter when birds migrate from as far as Siberia and Central Asia.",
                    "image" => "https://www.kadambkunj.com/blog/wp-content/uploads/2021/12/Keoladeo-National-Park-Timing-Entry-Fee-Best-Time-To-Visit-Famous-Bird.jpg"
                ],
                [
                    "name" => "Manas Wildlife Sanctuary",
                    "location" => "Assam",
                    "type" => "natural",
                    "year" => "1985",
                    "description" => "Project Tiger reserve, elephant reserve and biosphere reserve extending across the Bhutan border, known for its rare and endangered endemic wildlife.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSqshVWG2XOhY15bk_m7Sxml0cTvLYl6uy3jQ&s"
                ],
                [
                    "name" => "Churches and Convents of Goa",
                    "location" => "Goa",
                    "type" => "cultural",
                    "year" => "1986",
                    "description" => "The churches and convents of Goa represent the evangelization of Asia and showcase the influence of Portuguese colonial architecture with local Indian styles.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ4y1AsxiQ5SvI3kGqIsxqAZqLah1W_zPwSfA&s"
                ],
                [
                    "name" => "Fatehpur Sikri",
                    "location" => "Agra, Uttar Pradesh",
                    "type" => "cultural",
                    "year" => "1986",
                    "description" => "Built by Emperor Akbar in the 16th century and later abandoned, this fortified city served as the capital of the Mughal Empire for about 10 years.",
                    "image" => "data:image/jpeg;base64,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"
                ],
                [
                    "name" => "Group of Monuments at Hampi",
                    "location" => "Karnataka",
                    "type" => "cultural",
                    "year" => "1986",
                    "description" => "Impressive ruins of the final capital of the last great Hindu Kingdom of Vijayanagar, with beautiful temples, palaces, and other structures dating from the 14th century.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcROlglYGzz0AgYd5BkPtlA22_2CeYyhwPjwnw&s"
                ],
                [
                    "name" => "Khajuraho Monuments",
                    "location" => "Madhya Pradesh",
                    "type" => "cultural",
                    "year" => "1986",
                    "description" => "A group of Hindu and Jain temples built between 950 and 1050 CE, famous for their nagara-style architectural symbolism and intricate stone carvings.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR2sgIodji-Xc6yEYOjUIwMbXy90coPCB7b8A&s"
                ],
                [
                    "name" => "Elephanta Caves",
                    "location" => "Mumbai, Maharashtra",
                    "type" => "cultural",
                    "year" => "1987",
                    "description" => "These 5th to 8th century caves contain a collection of rock art linked to the cult of Shiva, with the most important being the 20-foot high bust of the three-faced Shiva.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR2uGYUp5E4kjIzTwe6UgmpUvXUusxrHsiqHA&s"
                ],
                [
                    "name" => "Great Living Chola Temples",
                    "location" => "Tamil Nadu",
                    "type" => "cultural",
                    "year" => "1987",
                    "description" => "These three great 11th and 12th century Chola temples—the Brihadisvara temples at Thanjavur and Gangaikondacholapuram, and the Airavatesvara temple at Darasuram—reflect the architectural and artistic achievements of the Chola Empire.",
                    "image" => "https://dynamic-media-cdn.tripadvisor.com/media/photo-o/0a/47/52/fc/airavateshwara-temple.jpg?w=1200&h=-1&s=1"
                ],
                [
                    "name" => "Group of Monuments at Pattadakal",
                    "location" => "Karnataka",
                    "type" => "cultural",
                    "year" => "1987",
                    "description" => "This sacred complex along the Malaprabha river features nine Hindu temples and a Jain sanctuary, showcasing the blended architectural styles of northern and southern India.",
                    "image" => "https://s7ap1.scene7.com/is/image/incredibleindia/2-pattadakal-karnataka-attr-hero?qlt=82&ts=1727414995454"
                ],
                [
                    "name" => "Sundarbans National Park",
                    "location" => "West Bengal",
                    "type" => "natural",
                    "year" => "1987",
                    "description" => "The largest mangrove forest in the world, the Sundarbans is a vast network of waterways and islands that is home to the Bengal tiger and numerous other species.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ1wa-mnJGtiiwYFr66hsC7PN4_KU13s7i2Yw&s"
                ],
                [
                    "name" => "Nanda Devi & Valley of Flowers National Parks",
                    "location" => "Uttarakhand",
                    "type" => "natural",
                    "year" => "1988",
                    "description" => "Known for their mountainous landscapes sheltering a unique diversity of flora and fauna, including endangered species such as the Asiatic black bear and snow leopard.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTaiB8A5MLf2sJcis9_dTZFk1ljqFUOcYhuGLHKX2sa6lqexPPm2aeiP6FD74n5-Mbcy7A&usqp=CAU"
                ],
                [
                    "name" => "Buddhist Monuments at Sanchi",
                    "location" => "Madhya Pradesh",
                    "type" => "cultural",
                    "year" => "1989",
                    "description" => "These monuments, particularly the Great Stupa built by Emperor Ashoka in the 3rd century BCE, represent one of the oldest stone structures in India and bear witness to the flourishing of Buddhist art and architecture.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS2mzIiLy5AffrlvsyZHZkWHfspK1_1GxWMdQ&s"
                ],
                [
                    "name" => "Humayun's Tomb",
                    "location" => "Delhi",
                    "type" => "cultural",
                    "year" => "1993",
                    "description" => "This tomb, built in 1570, is of particular cultural significance as it was the first garden-tomb on the Indian subcontinent, inspiring several architectural innovations that culminated in the Taj Mahal.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRw_JDKHRj9wvL_2Fz0wf2JlxLHgk8NJrCBmg&s"
                ],
                [
                    "name" => "Qutb Minar and its Monuments",
                    "location" => "Delhi",
                    "type" => "cultural",
                    "year" => "1993",
                    "description" => "Built in the early 13th century, the Qutb Minar is the tallest brick minaret in the world and an outstanding example of early Indo-Islamic architecture.",
                    "image" => "https://static.toiimg.com/thumb/width-600,height-400,msid-107973446.cms"
                ],
                [
                    "name" => "Mountain Railways of India",
                    "location" => "Various States",
                    "type" => "cultural",
                    "year" => "1999",
                    "description" => "These railways, built in the mid-19th to early 20th centuries, are outstanding examples of innovative transportation systems through rugged and mountainous terrain.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT-b_WcusXEKmoTOybRVwzcGJKtFwFNbVPrSw&s"
                ],
                [
                    "name" => "Mahabodhi Temple Complex",
                    "location" => "Bodh Gaya, Bihar",
                    "type" => "cultural",
                    "year" => "2002",
                    "description" => "One of the four holy sites related to the life of the Lord Buddha, this temple marks the place where Siddhartha Gautama attained enlightenment and became Buddha.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRhReVG2MHGNiEcxLTKEVbU1JeL29jJit6A5A&s"
                ],
                [
                    "name" => "Rock Shelters of Bhimbetka",
                    "location" => "Madhya Pradesh",
                    "type" => "cultural",
                    "year" => "2003",
                    "description" => "These rock shelters exhibit the earliest traces of human life in India, with paintings dating from the Mesolithic period right through to the Historical period.",
                    "image" => "https://s7ap1.scene7.com/is/image/incredibleindia/bhimbetka-rock-shelters-bhopal-madhya-pradesh-2-attr-hero?qlt=82&ts=1726675069637"
                ],
                [
                    "name" => "Chhatrapati Shivaji Terminus",
                    "location" => "Mumbai, Maharashtra",
                    "type" => "cultural",
                    "year" => "2004",
                    "description" => "Formerly known as Victoria Terminus, this historic railway station is an outstanding example of Victorian Gothic Revival architecture blended with Indian traditional forms.",
                    "image" => "https://pohcdn.com/sites/default/files/styles/paragraph__hero_banner__hb_image__1880bp/public/hero_banner/station.jpg"
                ],
                [
                    "name" => "Champaner-Pavagadh Archaeological Park",
                    "location" => "Gujarat",
                    "type" => "cultural",
                    "year" => "2004",
                    "description" => "This largely unexcavated site includes fortifications, palaces, religious buildings, residential areas, and water installations, from the 8th to 14th centuries.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSBplQK8QL6TIZtwlhqNhbkGfjAaXXDUUOc6A&s"
                ],
                [
                    "name" => "Red Fort Complex",
                    "location" => "Delhi",
                    "type" => "cultural",
                    "year" => "2007",
                    "description" => "This fort complex represents the zenith of Mughal creativity, which under Shah Jahan reached its golden age. The Red Fort is considered the highpoint of Mughal architecture.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQS-0BL2EffoJHzcqiIhVGxfkQmUSsm9wVIWg&s"
                ],
                [
                    "name" => "The Jantar Mantar",
                    "location" => "Jaipur, Rajasthan",
                    "type" => "cultural",
                    "year" => "2010",
                    "description" => "An astronomical observation site built in the early 18th century that includes several instruments allowing the observation of astronomical positions with the naked eye.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSlbDxlhIXVNqPPPiELAJx0oKVSjqy5rc6c5A&s"
                ],
                [
                    "name" => "Western Ghats",
                    "location" => "Kerala, Karnataka, TN, MH",
                    "type" => "natural",
                    "year" => "2012",
                    "description" => "Older than the Himalayan mountains, the Western Ghats represent one of the world's best examples of non-equatorial tropical evergreen forests and is recognized as one of the world's eight biodiversity hotspots.",
                    "image" => "https://th-i.thgim.com/public/sci-tech/science/yw2ew9/article23761533.ece/alternates/LANDSCAPE_1200/WESTERNGHATS"
                ],
                [
                    "name" => "Hill Forts of Rajasthan",
                    "location" => "Rajasthan",
                    "type" => "cultural",
                    "year" => "2013",
                    "description" => "These six majestic forts in Rajasthan represent the elaborate, fortified seats of Rajput princely states and showcase the distinctive Rajput military architecture of the region.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSrN1oH_dMUVxu4HjtdOCQOL1BkG0iMybrkcg&s"
                ],
                [
                    "name" => "Rani-ki-Vav (The Queen's Stepwell)",
                    "location" => "Patan, Gujarat",
                    "type" => "cultural",
                    "year" => "2014",
                    "description" => "This stepwell was built in the 11th century as a memorial to a king and designed as an inverted temple highlighting the sanctity of water, with seven levels of stairs and over 500 sculptures.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQCi9mMvGhWe4x24ilNDHBgRRTjYoE8J-gNsw&s"
                ],
                [
                    "name" => "Great Himalayan National Park",
                    "location" => "Himachal Pradesh",
                    "type" => "natural",
                    "year" => "2014",
                    "description" => "This national park in the western Himalayas protects one of the most significant areas for biodiversity conservation in the mountains, housing many endangered species.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRbcUD32VMg8kUYIAbLZSx5eYfZ9qe3gsGBfw&s"
                ],
                [
                    "name" => "Archaeological Site of Nalanda",
                    "location" => "Bihar",
                    "type" => "cultural",
                    "year" => "2016",
                    "description" => "The ancient Nalanda Mahavihara (University) was a renowned center of learning from the 5th century CE to the 13th century CE and represents the development of Buddhism into a religion and educational system.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTk-jCoKlZlLjQMhk6aavdV3t8XtdOF_lnhmQ&s"
                ],
                [
                    "name" => "Khangchendzonga National Park",
                    "location" => "Sikkim",
                    "type" => "mixed",
                    "year" => "2016",
                    "description" => "India's first 'mixed' category site, this national park includes the world's third highest peak and is home to numerous natural ecosystems as well as sacred cultural places.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSeG2DNCsQlbqmFQKbcIOGrlR7mLEaHVg_5ow&s"
                ],
                [
                    "name" => "The Architectural Work of Le Corbusier",
                    "location" => "Chandigarh",
                    "type" => "cultural",
                    "year" => "2016",
                    "description" => "The Capitol Complex in Chandigarh is part of this transnational property spanning seven countries, representing the solutions that the Modern Movement sought to apply to the challenges of inventing new architectural techniques.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTA-2UHxaXinnS_bOr-4Wb1_blu-N7eB5hHcw&s"
                ],
                [
                    "name" => "Historic City of Ahmedabad",
                    "location" => "Gujarat",
                    "type" => "cultural",
                    "year" => "2017",
                    "description" => "Founded in the 15th century, this walled city on the eastern bank of the Sabarmati river presents a rich architectural heritage from the sultanate period.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR654FhloSeSk-5OCV3BmkAPEiRo1P3jdr3Kw&s"
                ],
                [
                    "name" => "Victorian Gothic and Art Deco Ensembles",
                    "location" => "Mumbai, Maharashtra",
                    "type" => "cultural",
                    "year" => "2018",
                    "description" => "These 19th and 20th century public buildings represent the Victorian Neo-Gothic and Art Deco styles that defined Mumbai's landscape during British colonial rule and early independence.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTuDYpw_Dvbqpnmhr2TAG2jc3A5OPjgpSNOeQ&s"
                ],
                [
                    "name" => "Jaipur City",
                    "location" => "Rajasthan",
                    "type" => "cultural",
                    "year" => "2019",
                    "description" => "The fortified city of Jaipur, founded in 1727, is notable for its urban planning, featuring wide streets, bazaars, public squares, and uniform facades of pink-colored buildings.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRlj3jv8jAox5VAHuo1nddmK6kHwbTDuJO-Mw&s"
                ],
                [
                    "name" => "Ramappa Temple",
                    "location" => "Telangana",
                    "type" => "cultural",
                    "year" => "2021",
                    "description" => "Built during the Kakatiya dynasty in the 13th century, this temple is known for its innovative use of lightweight 'floating bricks', sandstone sculptures, and remarkable dance sculptures.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ_E-dgYT3zEXKLzoWN3n1FF746KmQYwWxPbQ&s"
                ],
                [
                    "name" => "Dholavira – Harappan City",
                    "location" => "Gujarat",
                    "type" => "cultural",
                    "year" => "2021",
                    "description" => "One of the most remarkable and well-preserved urban settlements from the ancient Indus Valley Civilization, dating from the 3rd to mid-2nd millennium BCE.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS9LV3e0V0VKfDqDIaevYGMQhUDJjOTcCdIYQ&s"
                ],
                [
                    "name" => "Santiniketan",
                    "location" => "West Bengal",
                    "type" => "cultural",
                    "year" => "2023",
                    "description" => "Founded by Rabindranath Tagore, this institution revolutionized education with its philosophy of learning in harmony with nature, blending Indian and Western cultural influences.",
                    "image" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS0fJhlqz18dKv_RNSD4cloDEojeOpkXH2Mdw&s"
                ]
            ];
            
            // Loop through all sites and create uniform cards
            foreach ($allSites as $site) {
                $typeClass = "type-" . $site["type"];
                $imageExists = (strpos($site["image"], "placeholder") === false);
                
                echo "
                <div class='heritage-card {$site["type"]}' data-type='{$site["type"]}'>
                    <div class='card-image' style='background-image: url({$site["image"]});'>";
                    
                    // If it's a placeholder image, show a text instead
                    if (!$imageExists) {
                        echo "<span>Image coming soon</span>";
                    }
                    
                echo "</div>
                    <div class='card-content'>
                        <span class='heritage-type {$typeClass}'>" . ucfirst($site["type"]) . "</span>
                        <h3>{$site["name"]}</h3>
                        <p>{$site["description"]}</p>
                        <div class='card-footer'>
                            <span>Location: {$site["location"]}</span><br>
                            <span>Year inscribed: {$site["year"]}</span>
                        </div>
                    </div>
                </div>
                ";
            }
            ?>
        </div>
        
        <!-- Pagination -->
        <div class="pagination">
            <button class="active">1</button>
            <button>2</button>
            <button>3</button>
            <button>Next →</button>
        </div>
        
        <!-- Statistics Section -->
        <div class="stats-section">
            <h2>Heritage By Numbers</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">42</div>
                    <div class="stat-label">Cultural Sites</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">7</div>
                    <div class="stat-label">Natural Sites</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">1</div>
                    <div class="stat-label">Mixed Sites</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">19</div>
                    <div class="stat-label">States & UTs</div>
                </div>
            </div>
        </div>
        
        <!-- Map Section -->
<div class="map-section">
    <h2>Heritage Sites Map</h2>
    <p>Explore the geographical distribution of India's World Heritage Sites</p>
    <div id="heritageMap" class="map-container" style="height: 500px; border-radius: 10px; overflow: hidden;"></div>
</div>

<!-- Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Map Script -->
<script>
    // Initialize the map centered on India
    var map = L.map('heritageMap').setView([22.9734, 78.6569], 5);

    // Load and display OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    <!-- Map Section -->
<div class="map-section">
    <h2>Heritage Sites Map</h2>
    <p>Explore the geographical distribution of India's World Heritage Sites</p>
    <div id="heritageMap" class="map-container" style="height: 500px; border-radius: 10px; overflow: hidden;"></div>
</div>

<!-- Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- Map Script -->
<script>
    // Initialize the map centered on India
    var map = L.map('heritageMap').setView([22.9734, 78.6569], 5);

    // Load and display OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Add markers for select heritage sites
    const heritageSites = [
        { name: "Taj Mahal", coords: [27.1751, 78.0421] },
        { name: "Qutub Minar", coords: [28.5244, 77.1855] },
        { name: "Kaziranga National Park", coords: [26.5775, 93.1711] },
        { name: "Mahabalipuram", coords: [12.6229, 80.1958] },
        { name: "Hampi", coords: [15.335, 76.460] },
        { name: "Red Fort Complex", coords: [28.6562, 77.2410] },
        { name: "Sun Temple, Konârak", coords: [19.8876, 86.0945] },
        { name: "Ajanta Caves", coords: [20.5522, 75.7033] },
        { name: "Ellora Caves", coords: [20.0268, 75.1794] },
        { name: "Elephanta Caves", coords: [18.9634, 72.9312] },
        { name: "Chhatrapati Shivaji Terminus", coords: [18.9402, 72.8356] },
        { name: "Fatehpur Sikri", coords: [27.0946, 77.6676] },
        { name: "Jantar Mantar, Jaipur", coords: [26.9247, 75.8245] },
        { name: "Great Living Chola Temples", coords: [10.7836, 79.1378] },
        { name: "Group of Monuments at Pattadakal", coords: [15.9470, 75.8186] },
        { name: "Hill Forts of Rajasthan", coords: [24.8873, 74.6269] },
        { name: "Sundarbans National Park", coords: [21.9497, 88.8915] },
        { name: "Keoladeo National Park", coords: [27.1586, 77.5212] },
        { name: "Western Ghats", coords: [10.1853, 76.7385] },
        { name: "Manas Wildlife Sanctuary", coords: [26.6574, 91.0011] },
        { name: "Rani-ki-Vav", coords: [23.8592, 72.1067] },
        { name: "Nalanda Mahavihara", coords: [25.1364, 85.4439] },
        { name: "Great Himalayan National Park", coords: [31.7216, 77.4552] },
        { name: "Rock Shelters of Bhimbetka", coords: [22.9392, 77.6147] },
        { name: "Buddhist Monuments at Sanchi", coords: [23.4791, 77.7390] }
    ];

    heritageSites.forEach(site => {
        L.marker(site.coords)
         .addTo(map)
         .bindPopup(`<b>${site.name}</b>`);
    });
</script>

</script>

    </div>
    
    <!-- Footer Note -->
    <div class="footer-note">
        <div class="container">
            <p>India continues to propose new sites for UNESCO World Heritage status, with several on the tentative list awaiting evaluation. These sites represent India's commitment to preserving its diverse cultural and natural heritage for future generations.</p>
            <p style="margin-top: 10px;">© <?php echo date('Y'); ?> Indian Heritage Portal</p>
        </div>
    </div>
    
    
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

    <!-- Back to Top Button -->
    <div class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </div>

    <!-- JavaScript -->
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
            
            // Close the dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!userProfile.contains(event.target)) {
                    profileDropdown.classList.remove('show');
                }
            });
        }
        
        // Booking tabs
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabPanes = document.querySelectorAll('.tab-pane');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons and hide all panes
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.style.display = 'none');
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Show the corresponding pane
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId + '-tab').style.display = 'block';
            });
        });
        
        // Destination filters
        const filterButtons = document.querySelectorAll('.filter-btn');
        const destinationCards = document.querySelectorAll('.destination-card');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Get filter value
                const filterValue = this.getAttribute('data-filter');
                
                // Filter destination cards
                destinationCards.forEach(card => {
                    const category = card.getAttribute('data-category');
                    
                    if (filterValue === 'all') {
                        card.style.display = 'block';
                    } else {
                        if (category === filterValue) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });
            });
        });
        

        // Search tags functionality
        const searchTags = document.querySelectorAll('.search-tag');
        const searchInput = document.querySelector('.search-input');
        
        searchTags.forEach(tag => {
            tag.addEventListener('click', function() {
                const tagText = this.textContent.trim();
                searchInput.value = tagText;
                searchInput.focus();
            });
        });
        
        // Set minimum date for date inputs to today
        const dateInputs = document.querySelectorAll('input[type="date"]');
        const today = new Date().toISOString().split('T')[0];
        
        dateInputs.forEach(input => {
            input.setAttribute('min', today);
            
            // Set default date for check-in to today and check-out to tomorrow
            if (input.id === 'check-in') {
                input.value = today;
            } else if (input.id === 'check-out') {
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                input.value = tomorrow.toISOString().split('T')[0];
            } else if (input.id === 'tour-date' || input.id === 'travel-date') {
                input.value = today;
            }
        });
        
        // Header scroll effect
        const header = document.querySelector('.site-header');
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        // Back to top button
        const backToTop = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 500) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });
        
        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
    // Wishlist functionality
    const wishlistButtons = document.querySelectorAll('.wishlist-btn');
    
    wishlistButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const placeId = this.dataset.id;
            
            fetch('toggle_wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `place_id=${placeId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'added') {
                    this.classList.add('wishlisted');
                } else if (data.status === 'removed') {
                    this.classList.remove('wishlisted');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('heritageSearch');
            const container = document.getElementById('heritageContainer');
            const cards = container.getElementsByClassName('heritage-card');
            const filterButtons = document.querySelectorAll('.category-btn');
            
            // Search function
            searchInput.addEventListener('keyup', function() {
                const filter = searchInput.value.toLowerCase();
                filterCards();
            });
            
            // Category filtering
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Filter the cards
                    filterCards();
                });
            });
            
            // Pagination functionality
            const paginationButtons = document.querySelectorAll('.pagination button');
            paginationButtons.forEach(button => {
                button.addEventListener('click', function() {
                    paginationButtons.forEach(btn => btn.classList.remove('active'));
                    if (this.textContent !== 'Next →') {
                        this.classList.add('active');
                    }
                    // In a real implementation, this would load the correct page of results
                });
            });
            
            // Combined filter function
            function filterCards() {
                const searchTerm = searchInput.value.toLowerCase();
                const activeFilter = document.querySelector('.category-btn.active').getAttribute('data-filter');
                
                let visibleCount = 0;
                
                for (let i = 0; i < cards.length; i++) {
                    const card = cards[i];
                    const text = card.textContent.toLowerCase();
                    const type = card.getAttribute('data-type');
                    
                    // Check if card matches both search term and category filter
                    const matchesSearch = text.includes(searchTerm);
                    const matchesFilter = activeFilter === 'all' || type === activeFilter;
                    
                    if (matchesSearch && matchesFilter) {
                        card.style.display = "";
                        visibleCount++;
                    } else {
                        card.style.display = "none";
                    }
                }
                
                // Show a message if no results found
                let noResultsMsg = document.getElementById('noResultsMessage');
                if (visibleCount === 0) {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.id = 'noResultsMessage';
                        noResultsMsg.style.textAlign = 'center';
                        noResultsMsg.style.padding = '30px';
                        noResultsMsg.style.color = '#666';
                        noResultsMsg.innerHTML = '<p>No heritage sites found matching your criteria.</p>';
                        container.after(noResultsMsg);
                    }
                    noResultsMsg.style.display = 'block';
                    document.querySelector('.pagination').style.display = 'none';
                } else {
                    if (noResultsMsg) {
                        noResultsMsg.style.display = 'none';
                    }
                    document.querySelector('.pagination').style.display = 'flex';
                }
            }
            
            // Card hover animations
            Array.from(cards).forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
  

</body>
</html>