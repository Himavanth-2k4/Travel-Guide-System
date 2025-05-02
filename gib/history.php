
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
            --saffron: #FF9933;
            --white: #FFFFFF;
            --green: #138808;
            --navy: #000080;
            --light-saffron: rgba(255, 153, 51, 0.1);
            --light-green: rgba(19, 136, 8, 0.1);
            --chakra-blue: #0000CD;
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
        
        /* Hero Banner with Tricolor Theme */
        .hero-banner {
            position: relative;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/history-banner.jpg');
            background-size: cover;
            background-position: center;
            padding: 150px 20px 100px;
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
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .hero-banner p {
            font-size: 1.3rem;
            max-width: 800px;
            margin: 0 auto 30px;
        }
        
        /* Introduction Section */
        .intro-section {
            background-color: var(--white);
            padding: 60px 20px;
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
        
        /* Timeline Tabs */
        .era-tabs {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin: 25px 0;
        }
        
        .era-tab {
            padding: 8px 16px;
            background-color: #f0f0f0;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: var(--transition);
        }
        
        .era-tab:hover {
            background-color: #e0e0e0;
        }
        
        .era-tab.active {
            background-color: var(--saffron);
            color: white;
        }
        
        /* Timeline Container */
        .timeline-container {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .timeline-container::after {
            content: '';
            position: absolute;
            width: 6px;
            background: linear-gradient(to bottom, var(--saffron), var(--white), var(--green));
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -3px;
        }
        
        /* Timeline Blocks */
        .timeline-block {
            padding: 10px 40px;
            position: relative;
            background-color: inherit;
            width: 50%;
            margin-bottom: 30px;
        }
        
        .timeline-block::after {
            content: '';
            position: absolute;
            width: 25px;
            height: 25px;
            right: -17px;
            background-color: var(--white);
            border: 4px solid var(--saffron);
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }
        
        .timeline-left {
            left: 0;
        }
        
        .timeline-right {
            left: 50%;
        }
        
        .timeline-right::after {
            left: -16px;
        }
        
        .timeline-content {
            padding: 20px 30px;
            background-color: white;
            position: relative;
            border-radius: 10px;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            border-top: 4px solid var(--saffron);
        }
        
        .timeline-content:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .timeline-ancient .timeline-content { border-top-color: var(--saffron); }
        .timeline-medieval .timeline-content { border-top-color: var(--chakra-blue); }
        .timeline-colonial .timeline-content { border-top-color: var(--white); border-top: 4px solid #aaa; }
        .timeline-modern .timeline-content { border-top-color: var(--green); }
        
        .timeline-content h2 {
            color: var(--text-dark);
            margin-bottom: 15px;
            font-size: 1.4rem;
        }
        
        .timeline-content p {
            margin-bottom: 15px;
            line-height: 1.7;
        }
        
        .timeline-year {
            display: inline-block;
            padding: 5px 15px;
            background-color: var(--light-saffron);
            color: var(--saffron);
            border-radius: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }
        
        .timeline-ancient .timeline-year { background-color: var(--light-saffron); color: darken(var(--saffron), 10%); }
        .timeline-medieval .timeline-year { background-color: rgba(0, 0, 205, 0.1); color: var(--chakra-blue); }
        .timeline-colonial .timeline-year { background-color: rgba(120, 120, 120, 0.1); color: #555; }
        .timeline-modern .timeline-year { background-color: var(--light-green); color: var(--green); }
        
        /* Achievement Section */
        .achievements-section {
            background: linear-gradient(rgba(255,153,51,0.05), rgba(19,136,8,0.05));
            padding: 80px 0;
            margin: 50px 0;
        }
        
        .achievements-title {
            text-align: center;
            color: var(--text-dark);
            margin-bottom: 50px;
        }
        
        .achievements-title h2 {
            font-size: 2.2rem;
            color: var(--saffron);
        }
        
        .achievements-title p {
            max-width: 700px;
            margin: 20px auto;
            color: var(--text-light);
        }
        
        .achievements-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .achievement-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }
        
        .achievement-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .achievement-icon {
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            background-color: var(--light-saffron);
            color: var(--saffron);
        }
        
        .achievement-card:nth-child(2n) .achievement-icon {
            background-color: var(--light-green);
            color: var(--green);
        }
        
        .achievement-content {
            padding: 25px;
        }
        
        .achievement-content h3 {
            font-size: 1.4rem;
            margin-bottom: 15px;
            color: var(--text-dark);
        }
        
        .achievement-content p {
            color: var(--text-light);
            line-height: 1.7;
        }
        
        /* Cultural Heritage Section */
        .culture-section {
            padding: 80px 20px;
            background-color: white;
        }
        
        .culture-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .culture-title h2 {
            font-size: 2.2rem;
            color: var(--saffron);
        }
        
        .culture-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .culture-card {
            height: 250px;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            box-shadow: var(--box-shadow);
        }
        
        .culture-bg {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            transition: transform 0.5s ease;
        }
        
        .culture-card:hover .culture-bg {
            transform: scale(1.1);
        }
        
        .culture-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            padding: 20px;
            color: white;
        }
        
        .culture-overlay h3 {
            font-size: 1.3rem;
            margin-bottom: 5px;
        }
        
        /* Great Indians Section */
        .greats-section {
            padding: 80px 20px;
            background: linear-gradient(rgba(255,153,51,0.05), rgba(19,136,8,0.05));
        }
        
        .greats-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .greats-title h2 {
            font-size: 2.2rem;
            color: var(--saffron);
        }
        
        .greats-cards {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 20px;
            padding: 20px 0;
            scroll-behavior: smooth;
        }
        
        .greats-cards::-webkit-scrollbar {
            height: 8px;
        }
        
        .greats-cards::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .greats-cards::-webkit-scrollbar-thumb {
            background: var(--saffron);
            border-radius: 10px;
        }
        
        .great-card {
            min-width: 250px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            flex: 0 0 auto;
        }
        
        .great-card:hover {
            transform: translateY(-10px);
        }
        
        .great-img {
            height: 200px;
            width: 100%;
            object-fit: contain;
        }
        
        .great-info {
            padding: 20px;
        }
        
        .great-info h3 {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }
        
        .great-info .years {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .great-info p {
            font-size: 0.9rem;
            color: var(--text-light);
        }
        
        /* Quote Section */
        .quote-section {
            background-color: var(--saffron);
            color: white;
            padding: 80px 20px;
            text-align: center;
            margin: 50px 0;
        }
        
        .quote-text {
            font-size: 2rem;
            font-style: italic;
            max-width: 900px;
            margin: 0 auto 20px;
            line-height: 1.5;
        }
        
        .quote-author {
            font-size: 1.2rem;
        }
        
        /* Stats Section */
        .stats-section {
            padding: 60px 20px;
            background-color: white;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .stat-card {
            text-align: center;
            padding: 30px 20px;
            border-radius: 10px;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--saffron);
            margin-bottom: 10px;
        }
        
        .stat-card:nth-child(2) .stat-number { color: var(--chakra-blue); }
        .stat-card:nth-child(3) .stat-number { color: var(--green); }
        .stat-card:nth-child(4) .stat-number { color: var(--text-dark); }
        
        .stat-text {
            color: var(--text-light);
            font-size: 1rem;
        }
        
        /* Did You Know Section */
        .facts-section {
            background-color: #f8f8f8;
            padding: 60px 20px;
        }
        
        .facts-title {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .facts-title h2 {
            font-size: 2.2rem;
            color: var(--saffron);
        }
        
        .facts-container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .fact-card {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: var(--box-shadow);
            border-left: 5px solid var(--saffron);
        }
        
        .fact-card:nth-child(2) { border-left-color: var(--chakra-blue); }
        .fact-card:nth-child(3) { border-left-color: var(--green); }
        
        .fact-card h3 {
            font-size: 1.3rem;
            color: var(--text-dark);
            margin-bottom: 10px;
        }
        
        .fact-card p {
            color: var(--text-light);
        }
        
        /* Footer Call to Action */
        .cta-section {
            background: linear-gradient(rgba(255,153,51,0.9), rgba(19,136,8,0.9)), url('images/india-bg.jpg');
            background-size: cover;
            background-position: center;
            padding: 80px 20px;
            text-align: center;
            color: white;
        }
        
        .cta-content {
            max-width: 700px;
            margin: 0 auto;
        }
        
        .cta-content h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .cta-content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        
        .cta-button {
            display: inline-block;
            padding: 12px 30px;
            background-color: white;
            color: var(--saffron);
            font-weight: bold;
            border-radius: 30px;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .cta-button:hover {
            background-color: var(--saffron);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        /* Media Queries */
        @media screen and (max-width: 768px) {
            .timeline-container::after {
                left: 31px;
            }
            
            .timeline-block {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }
            
            .timeline-block::after {
                left: 15px;
                right: auto;
            }
            
            .timeline-right {
                left: 0%;
            }
            
            .hero-banner h1 {
                font-size: 2.5rem;
            }
            
            .quote-text {
                font-size: 1.5rem;
            }
        }
        
        @media screen and (max-width: 576px) {
            .culture-grid, .achievements-grid {
                grid-template-columns: 1fr;
            }
            
            .hero-banner h1 {
                font-size: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
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
  <div class="hero-banner" style="background-image: url('https://c4.wallpaperflare.com/wallpaper/240/29/214/indian-flag-indonesia-flag-wallpaper-preview.jpg')">
        <div class="container">
            <h1>5000+ Years of Indian History</h1>
            <p>A journey through time — from ancient civilizations to modern India, the story of a nation that shaped world history</p>
        </div>
    </div>
    
    <!-- Introduction Section -->
    <div class="intro-section">
        <div class="container">
            <h2>The Land of Ancient Wisdom & Modern Progress</h2>
            <div class="tricolor-separator">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <p>India, one of the world's oldest civilizations, boasts a rich tapestry of history spanning over five millennia. From the sophisticated urban planning of the Indus Valley Civilization to becoming the world's largest democracy, India's journey through time reflects remarkable resilience, cultural continuity, and adaptability. Its historical narrative encompasses magnificent empires, profound philosophical traditions, scientific innovations, and artistic achievements that continue to influence global culture today.</p>
        </div>
    </div>
    
    <!-- Search & Filter Section -->
    <div class="container">
        <div class="search-section">
            <h3>Explore India's Historical Timeline</h3>
            <input type="text" id="historySearch" class="search-input" placeholder="Search a period, dynasty, or notable figure...">
            
            <div class="era-tabs">
                <button class="era-tab active" data-era="all">All Eras</button>
                <button class="era-tab" data-era="ancient">Ancient</button>
                <button class="era-tab" data-era="medieval">Medieval</button>
                <button class="era-tab" data-era="colonial">Colonial</button>
                <button class="era-tab" data-era="modern">Modern</button>
            </div>
        </div>
    </div>
    
    <!-- Timeline Section -->
    <div class="timeline-container">
        <?php
        $timeline = [
            [
                "era" => "ancient",
                "title" => "🪨 Prehistoric India",
                "year" => "Before 3300 BCE",
                "side" => "left",
                "content" => "The earliest traces of human life in the Indian subcontinent date back to the Stone Age. Paleolithic and Mesolithic tools have been discovered across India. Bhimbetka rock shelters in Madhya Pradesh feature some of the oldest cave paintings dating back 30,000 years. These early humans lived by hunting, gathering, and slowly began domesticating animals and plants, establishing the foundations for early civilizations."
            ],
            [
                "era" => "ancient",
                "title" => "🏙️ Indus Valley Civilization",
                "year" => "3300–1300 BCE",
                "side" => "right",
                "content" => "One of the world's oldest urban civilizations and contemporaneous with ancient Egypt and Mesopotamia. This advanced bronze age civilization flourished in areas now in India and Pakistan. Major cities like Harappa, Mohenjo-daro, and Dholavira feature remarkable urban planning with grid-like streets, sophisticated drainage systems, public baths, and granaries. They developed standardized weights, measures, and a script that remains undeciphered. The civilization traded extensively with Mesopotamia and had a highly organized social structure."
            ],
            [
                "era" => "ancient",
                "title" => "🕉️ Vedic Period",
                "year" => "1500–600 BCE",
                "side" => "left",
                "content" => "This period witnessed the composition of the Vedas—Rigveda, Samaveda, Yajurveda, and Atharvaveda—the oldest scriptures of Hinduism. Society became stratified into varnas (castes): Brahmins, Kshatriyas, Vaishyas, and Shudras. Core concepts of dharma (duty), karma (action), and yajna (ritual sacrifice) emerged. Sanskrit flourished as the language of scripture and philosophy. The Upanishads, which form the philosophical foundation of Hinduism, were composed in the later Vedic period."
            ],
            [
                "era" => "ancient",
                "title" => "👑 Rise of Mahajanapadas & New Religions",
                "year" => "600–321 BCE",
                "side" => "right",
                "content" => "16 Mahajanapadas (great states) emerged across North India, marking the transition from tribal societies to complex state systems. Urbanization and trade expanded significantly. This era saw the birth of two major world religions: Gautama Buddha founded Buddhism and Mahavira revitalized Jainism. Both emphasized non-violence (ahimsa), self-discipline, and salvation through inner realization rather than ritualistic practices. These teachings challenged Brahmanical orthodoxy and influenced Indian thought permanently."
            ],
            [
                "era" => "ancient",
                "title" => "🦁 Maurya Empire",
                "year" => "321–185 BCE",
                "side" => "left",
                "content" => "India's first great empire was founded by Chandragupta Maurya, guided by the brilliant statesman Chanakya (author of Arthashastra, an ancient Indian treatise on statecraft). Emperor Ashoka the Great expanded it across the Indian subcontinent. After witnessing the bloodshed of the Kalinga War, Ashoka embraced Buddhism and promoted dhamma (moral law) through rock and pillar edicts. He sent Buddhist missionaries throughout Asia, spreading Indian cultural influence. The Lion Capital of Ashoka at Sarnath is now India's national emblem."
            ],
            [
                "era" => "ancient",
                "title" => "🏛️ Post-Mauryan Period",
                "year" => "185 BCE–320 CE",
                "side" => "right",
                "content" => "After the decline of the Mauryas, several powers emerged including the Shungas, Satavahanas, and Indo-Greeks. This period saw extensive cross-cultural exchanges with Greeks, Romans, and Chinese. The Silk Road facilitated trade and cultural diffusion. Buddhism spread to Central Asia and China. The earliest surviving Hindu temples were built during this time. Gandhara and Mathura school of art flourished, creating distinctive styles in sculpture that blended Indian and Hellenistic elements."
            ],
            [
                "era" => "ancient",
                "title" => "🌟 Golden Age – Gupta Empire",
                "year" => "320–550 CE",
                "side" => "left",
                "content" => "Often described as the 'Golden Age' of India. The Guptas ruled a large territory in northern India, bringing political stability and prosperity. Remarkable advances in science, mathematics, astronomy, religion, and philosophy occurred. Aryabhata calculated pi and the solar year, introduced the concept of zero, and proposed that the Earth rotates on its axis. Kalidasa, considered India's greatest Sanskrit poet and dramatist, created literary masterpieces. Nalanda University became a renowned center of learning. Hinduism flourished with grand temples and classical art forms."
            ],
            [
                "era" => "medieval",
                "title" => "⚔️ Early Medieval Period & Regional Kingdoms",
                "year" => "600–1200 CE",
                "side" => "right",
                "content" => "After the Guptas, India saw the rise of regional powers: Pallavas, Chalukyas, Rashtrakutas, Palas, Senas, and Cholas dominated different regions. The Chola Dynasty built a maritime empire extending to Southeast Asia. Magnificent temple architecture flourished, especially in South India, with the Kailasa temple at Ellora and Brihadeshwara temple at Thanjavur. Chola bronze sculptures reached artistic perfection. Indian cultural influence spread to Southeast Asia, influencing the art and architecture of Cambodia, Thailand, and Indonesia."
            ],
            [
                "era" => "medieval",
                "title" => "🕌 Delhi Sultanate Period",
                "year" => "1206–1526 CE",
                "side" => "left",
                "content" => "Muslim rule was established in northern India with the Delhi Sultanate, founded by Qutb-ud-din Aibak. Five dynasties ruled: the Mamluks, Khaljis, Tughlaqs, Sayyids, and Lodis. Persian culture, Islamic architecture, and administrative systems were introduced. The Qutub Minar and other distinctive Indo-Islamic monuments were constructed. The Sultanate faced Mongol invasions and internal revolts. Sufi saints like Nizamuddin Auliya spread mystical Islam. The Bhakti movement gained momentum with saints like Kabir and Guru Nanak preaching religious harmony."
            ],
            [
                "era" => "medieval",
                "title" => "🏰 Rise of Regional Powers",
                "year" => "1300–1526 CE",
                "side" => "right",
                "content" => "Several independent kingdoms flourished alongside the Delhi Sultanate. The Vijayanagara Empire in South India preserved Hindu culture and built magnificent cities. The Bahmanis in the Deccan, Gujarat Sultanate, and Bengal Sultanate developed distinctive regional cultures. This period saw a synthesis of Hindu and Islamic architectural styles. The Krishna Deva Raya period of Vijayanagara was particularly prosperous, becoming a center for South Indian art, literature, and music."
            ],
            [
                "era" => "medieval",
                "title" => "🏰 Mughal Empire",
                "year" => "1526–1857 CE",
                "side" => "left",
                "content" => "Established by Babur after defeating Ibrahim Lodi at the First Battle of Panipat, the Mughal Empire reached its zenith under Akbar the Great, who expanded the empire and implemented policies of religious tolerance and administrative reform. Jahangir, Shah Jahan (who built the Taj Mahal), and Aurangzeb were other notable rulers. This era witnessed a cultural renaissance, with Persian-influenced miniature painting, Urdu literature, Hindustani classical music, and magnificent architecture. The empire declined after Aurangzeb, facing Maratha resistance and British expansion."
            ],
            [
                "era" => "medieval",
                "title" => "🗡️ Maratha Empire & Other Powers",
                "year" => "1674–1818 CE",
                "side" => "right",
                "content" => "Founded by Shivaji Maharaj, the Maratha Empire challenged Mughal supremacy and controlled much of central and northern India by the 18th century. The Sikhs under Ranjit Singh established a powerful state in Punjab. The Rajputs maintained semi-autonomous kingdoms in Rajasthan. Other notable powers included the Mysore Kingdom under Haider Ali and Tipu Sultan, and the Nizam of Hyderabad. These regional powers would eventually confront European colonial expansion, particularly the British East India Company."
            ],
            [
                "era" => "colonial",
                "title" => "🚢 Early Colonial Period",
                "year" => "1757–1857 CE",
                "side" => "left",
                "content" => "The British East India Company gained control of Bengal after the Battle of Plassey (1757), marking the beginning of British rule in India. Through military campaigns and political maneuvering, the Company expanded control across the subcontinent. The colonial economy transformed with new land revenue systems, commercialization of agriculture, and deindustrialization of traditional crafts. English education was introduced, creating a new educated Indian class. Social reform movements emerged addressing practices like sati, child marriage, and caste discrimination."
            ],
            [
                "era" => "colonial",
                "title" => "⚔️ First War of Independence",
                "year" => "1857 CE",
                "side" => "right",
                "content" => "Also known as the Sepoy Mutiny, this was a significant uprising against British rule. It began as a mutiny of sepoys (Indian soldiers) but became a widespread rebellion involving various sections of the population. Leaders included Rani Lakshmibai of Jhansi, Tantia Tope, Bahadur Shah Zafar, and Mangal Pandey. Though ultimately unsuccessful, it led to the dissolution of the East India Company, with India coming under the direct control of the British Crown—the period known as the British Raj."
            ],
            [
                "era" => "colonial",
                "title" => "🧠 Indian Renaissance",
                "year" => "1850–1900 CE",
                "side" => "left",
                "content" => "A period of social and religious reform movements that sought to modernize Indian society while preserving its cultural identity. Raja Ram Mohan Roy founded the Brahmo Samaj, challenging practices like sati and advocating women's rights. Swami Vivekananda introduced Hindu philosophy to the West and called for national revival. Ishwar Chandra Vidyasagar advocated for widow remarriage. Sir Syed Ahmad Khan promoted modern education among Muslims. These reformers helped shape a new Indian identity that balanced tradition with modernity."
            ],
            [
                "era" => "colonial",
                "title" => "🏛️ Rise of Nationalism",
                "year" => "1885–1920 CE",
                "side" => "right",
                "content" => "The Indian National Congress, founded in 1885, became the primary platform for nationalist aspirations. Leaders like Dadabhai Naoroji, Gopal Krishna Gokhale, and Bal Gangadhar Tilak developed early visions of independence. The partition of Bengal in 1905 triggered widespread protests and the Swadeshi movement promoting indigenous goods. Revolutionary movements emerged alongside constitutional politics, with figures like Bhagat Singh and Chandrashekhar Azad willing to use violence against colonial rule."
            ],
            [
                "era" => "colonial",
                "title" => "✊ Gandhian Era",
                "year" => "1920–1947 CE",
                "side" => "left",
                "content" => "Mahatma Gandhi transformed the freedom struggle with his philosophy of non-violence (ahimsa) and civil disobedience (satyagraha). Major movements included Non-Cooperation (1920-22), Civil Disobedience (1930-34), and Quit India (1942). Other key leaders emerged: Jawaharlal Nehru, Sardar Patel, Subhas Chandra Bose (who formed the Indian National Army), and B.R. Ambedkar (who advocated for Dalit rights). World War II weakened Britain's hold on India. Religious tensions grew, leading to demands for separate states."
            ],
            [
                "era" => "modern",
                "title" => "🇮🇳 Independence & Partition",
                "year" => "1947 CE",
                "side" => "right",
                "content" => "India gained independence on August 15, 1947, ending nearly 200 years of British rule. However, the joy of freedom was tempered by the tragedy of partition, as the subcontinent was divided into India and Pakistan along religious lines. Massive population exchanges occurred with an estimated 15 million people displaced and hundreds of thousands killed in communal violence. Prime Minister Jawaharlal Nehru delivered the famous 'Tryst with Destiny' speech as India awoke to freedom."
            ],
            [
                "era" => "modern",
                "title" => "📜 Early Republic",
                "year" => "1947–1964 CE",
                "side" => "left",
                "content" => "India adopted a new Constitution in 1950, becoming a sovereign democratic republic. Under Prime Minister Nehru, the country embraced democracy, secularism, and socialist economic planning. Five-Year Plans were introduced to develop industry and agriculture. India maintained a policy of non-alignment in the Cold War while establishing itself as a leader of the decolonizing world. The integration of princely states by Sardar Patel unified the country. India faced challenges including the 1962 war with China and ongoing tensions with Pakistan."
            ],
            [
                "era" => "modern",
                "title" => "💪 Indira Gandhi Era",
                "year" => "1966–1984 CE",
                "side" => "right",
                "content" => "Indira Gandhi emerged as a powerful leader, nationalizing banks and abolishing royal privileges. The 1971 war with Pakistan led to the creation of Bangladesh, establishing India's military dominance in South Asia. The Green Revolution transformed agriculture, making India self-sufficient in food production. Nuclear capabilities were developed with the 'Smiling Buddha' test of 1974. However, this period also saw the controversial Emergency (1975-77), when democratic rights were suspended, and growing regional and communal tensions that would eventually lead to her assassination in 1984."
            ],
            [
                "era" => "modern",
                "title" => "🏢 Economic Liberalization",
                "year" => "1991–2000 CE",
                "side" => "left",
                "content" => "Facing an economic crisis, Finance Minister Manmohan Singh under Prime Minister P.V. Narasimha Rao introduced sweeping economic reforms in 1991. The License Raj was dismantled, markets were opened to foreign investment, and privatization began. These reforms triggered rapid economic growth and the rise of India's information technology sector. The middle class expanded, and consumerism grew. India conducted nuclear tests in 1998 under Prime Minister Vajpayee, declaring itself a nuclear weapons state amid international opposition."
            ],
            [
                "era" => "modern",
                "title" => "🌐 21st Century India",
                "year" => "2000–Present",
                "side" => "right",
                "content" => "India emerged as one of the world's fastest-growing major economies and a global IT powerhouse. Space exploration advanced with the Chandrayaan and Mangalyaan missions. Digital infrastructure expanded rapidly with initiatives like Aadhaar and Digital India. Under Prime Minister Narendra Modi (2014-present), major reforms were implemented including GST and demonetization. India's global influence continued to grow with an increasingly assertive foreign policy. The COVID-19 pandemic presented unprecedented challenges, while also showcasing India's pharmaceutical capabilities as the 'pharmacy of the world.'"
            ]
        ];
        
        foreach ($timeline as $era) {
            echo "
            <div class='timeline-block timeline-{$era['side']} timeline-{$era['era']}' data-era='{$era['era']}'>
                <div class='timeline-content'>
                    <span class='timeline-year'>{$era['year']}</span>
                    <h2>{$era['title']}</h2>
                    <p>{$era['content']}</p>
                </div>
            </div>
            ";
        }
        ?>
    </div>
    
    <!-- Achievements Section -->
    <div class="achievements-section">
        <div class="achievements-title">
            <h2>India's Historic Contributions to World Civilization</h2>
            <div class="tricolor-separator">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <p>From mathematics to medicine, philosophy to astronomy, India's intellectual traditions have shaped human knowledge across millennia.</p>
        </div>
        
        <div class="achievements-grid">
            <div class="achievement-card">
                <div class="achievement-icon">
                    <span>0</span>
                </div>
                <div class="achievement-content">
                    <h3>Mathematics Revolution</h3>
                    <p>Ancient India gave the world the numerical system we use today, including the concept of zero, decimal system, and the place value system. Mathematicians like Aryabhata, Brahmagupta, and Bhaskara II made groundbreaking contributions to algebra, trigonometry, and calculus, centuries before they appeared in Europe.</p>
                </div>
            </div>
            
            <div class="achievement-card">
                <div class="achievement-icon">
                    <span>🧠</span>
                </div>
                <div class="achievement-content">
                    <h3>Philosophical Thought</h3>
                    <p>The six major schools of Indian philosophy—Nyaya, Vaisheshika, Samkhya, Yoga, Mimamsa, and Vedanta—developed sophisticated logical and metaphysical systems. Buddhism and Jainism added diverse perspectives on reality, consciousness, and ethics. These philosophies influenced thought in China, Tibet, Southeast Asia, and later the Western world.</p>
                </div>
            </div>
            
            <div class="achievement-card">
                <div class="achievement-icon">
                    <span>⚕️</span>
                </div>
                <div class="achievement-content">
                    <h3>Medical Sciences</h3>
                    <p>Ayurveda, one of the world's oldest medical systems, developed comprehensive approaches to health and disease. Ancient physicians like Charaka and Sushruta described complex surgical procedures, medicinal preparations, and anatomical structures. Sushruta is recognized as the 'Father of Surgery' for techniques including rhinoplasty (nose reconstruction) performed over 2,600 years ago.</p>
                </div>
            </div>
            
            <div class="achievement-card">
                <div class="achievement-icon">
                    <span>🔭</span>
                </div>
                <div class="achievement-content">
                    <h3>Astronomy & Physics</h3>
                    <p>Ancient Indian astronomers calculated the solar year with remarkable precision, tracked planetary movements, and developed sophisticated calendars. Varahamihira and Brahmagupta made significant contributions to understanding celestial bodies. The Kerala School of Astronomy and Mathematics in the 14th-16th centuries developed mathematical series for planetary positions and infinite series approximations centuries before European mathematicians.</p>
                </div>
            </div>
            
            <div class="achievement-card">
                <div class="achievement-icon">
                    <span>🎭</span>
                </div>
                <div class="achievement-content">
                    <h3>Arts & Literature</h3>
                    <p>The Natya Shastra, written by Bharata Muni around 200 BCE, is the foundational text on performing arts, covering drama, dance, music, and aesthetics. Sanskrit literature produced epics like the Mahabharata and Ramayana, along with Kalidasa's poetic masterpieces. Indian musical traditions developed sophisticated systems of ragas and talas that influenced music across Asia.</p>
                </div>
            </div>
            
            <div class="achievement-card">
                <div class="achievement-icon">
                    <span>🏛️</span>
                </div>
                <div class="achievement-content">
                    <h3>Urban Planning & Architecture</h3>
                    <p>The Indus Valley Civilization built planned cities with grid layouts, advanced drainage systems, and public baths 4,500 years ago. Later, India developed scientific principles of architecture (Vastu Shastra) and created magnificent temple architecture. The rock-cut caves of Ajanta and Ellora, the Sun Temple at Konark, and the Taj Mahal represent the pinnacle of different architectural traditions.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quote Section -->
    <div class="quote-section">
        <div class="container">
            <div class="quote-text">"We owe a lot to the Indians, who taught us how to count, without which no worthwhile scientific discovery could have been made."</div>
            <div class="quote-author">— Albert Einstein</div>
        </div>
    </div>
    
    <!-- Cultural Heritage Section -->
    <div class="culture-section">
        <div class="container">
            <div class="culture-title">
                <h2>India's Living Cultural Heritage</h2>
                <div class="tricolor-separator">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            
            <div class="culture-grid">
                <div class="culture-card">
                    <div class="culture-bg" style="background-image: url('https://t4.ftcdn.net/jpg/02/86/24/91/360_F_286249147_xHhILwuzwbskSaCVXiLi9IT4BgQu3Fcr.jpg');"></div>
                    <div class="culture-overlay">
                        <h3>Classical Dance Forms</h3>
                        <p>Eight classical traditions including Bharatanatyam, Kathak, and Odissi</p>
                    </div>
                </div>
                
                <div class="culture-card">
                    <div class="culture-bg" style="background-image: url('https://cdn.magicdecor.in/com/2023/02/29224634/image-1683704783-1982-710x488.jpg');"></div>
                    <div class="culture-overlay">
                        <h3>Musical Traditions</h3>
                        <p>Hindustani and Carnatic traditions with ancient roots</p>
                    </div>
                </div>
                
                <div class="culture-card">
                    <div class="culture-bg" style="background-image: url('https://d7hftxdivxxvm.cloudfront.net/?quality=80&resize_to=width&src=https%3A%2F%2Fd32dm0rphc51dk.cloudfront.net%2F5r2GdyBokii-7Zxtlz-lGA%2Fnormalized.jpg&width=910');"></div>
                    <div class="culture-overlay">
                        <h3>Textile Arts</h3>
                        <p>From Banarasi silk to Kanchipuram sarees and intricate embroidery</p>
                    </div>
                </div>
                
                <div class="culture-card">
                    <div class="culture-bg" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSLYzamDRhxLErB5smMRr00sbtw382lfdQsfA&s');"></div>
                    <div class="culture-overlay">
                        <h3>Festivals & Celebrations</h3>
                        <p>Diwali, Holi, Eid, Christmas, and countless regional celebrations</p>
                    </div>
                </div>
                
                <div class="culture-card">
                    <div class="culture-bg" style="background-image: url('https://static.toiimg.com/thumb/msid-101282760,width-1280,height-720,resizemode-4/101282760.jpg');"></div>
                    <div class="culture-overlay">
                        <h3>Culinary Heritage</h3>
                        <p>Diverse regional cuisines with ancient spice traditions</p>
                    </div>
                </div>
                
                <div class="culture-card">
                    <div class="culture-bg" style="background-image: url('https://thumbs.dreamstime.com/b/man-lotus-pose-practices-yoga-meditation-emanating-energy-modern-setting-man-lotus-pose-practices-yoga-352693648.jpg');"></div>
                    <div class="culture-overlay">
                        <h3>Yoga & Spiritual Practices</h3>
                        <p>Ancient traditions for physical, mental, and spiritual wellbeing</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Great Indians Section -->
    <div class="greats-section">
        <div class="container">
            <div class="greats-title">
                <h2>Great Indians Who Shaped History</h2>
                <div class="tricolor-separator">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            
            <div class="greats-cards">
                <div class="great-card">
                    <img src="https://i.pinimg.com/736x/52/5e/44/525e445081de72184e88e09266722307.jpg" alt="Emperor Ashoka" class="great-img">
                    <div class="great-info">
                        <h3>Emperor Ashoka</h3>
                        <div class="years">304-232 BCE</div>
                        <p>Unified most of the Indian subcontinent and renounced violence after the Kalinga War, spreading Buddhism across Asia.</p>
                    </div>
                </div>
                
                <div class="great-card">
                    <img src="https://vskkarnataka.org/files/2024/01/Aryabhata-5_1.jpg" alt="Aryabhata" class="great-img">
                    <div class="great-info">
                        <h3>Aryabhata</h3>
                        <div class="years">476-550 CE</div>
                        <p>Mathematician-astronomer who calculated π, explained solar and lunar eclipses, and proposed Earth's rotation on its axis.</p>
                    </div>
                </div>
                
                <div class="great-card">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQT3uGzgrbYlsnIZPIqsUkgGtpH0BLFwKPj0g&s" alt="Emperor Akbar" class="great-img">
                    <div class="great-info">
                        <h3>Emperor Akbar</h3>
                        <div class="years">1542-1605 CE</div>
                        <p>Greatest Mughal emperor who expanded the empire, promoted religious tolerance, and created a syncretic culture.</p>
                    </div>
                </div>
                
                <div class="great-card">
                    <img src="data:image/jpeg;base64,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" alt="Mahatma Gandhi" class="great-img">
                    <div class="great-info">
                        <h3>Mahatma Gandhi</h3>
                        <div class="years">1869-1948 CE</div>
                        <p>Father of the Nation who led India to independence through non-violence, inspiring freedom movements worldwide.</p>
                    </div>
                </div>
                
                <div class="great-card">
                    <img src="https://www.theartlifegallery.com/blog/wp-content/uploads/2023/08/Image-1-31.jpg" alt="Rabindranath Tagore" class="great-img">
                    <div class="great-info">
                        <h3>Rabindranath Tagore</h3>
                        <div class="years">1861-1941 CE</div>
                        <p>First non-European Nobel laureate in Literature, polymath who reshaped Bengali literature and Indian art.</p>
                    </div>
                </div>
                
                <div class="great-card">
                    <img src="https://akm-img-a-in.tosshub.com/indiatoday/images/story/202408/apj-abdul-kalam-235706401-16x9_0.jpg?VersionId=ysXZfwdNgS4ahFt6DcXy19sp8laINtt5&size=690:388" alt="A.P.J. Abdul Kalam" class="great-img">
                    <div class="great-info">
                        <h3>A.P.J. Abdul Kalam</h3>
                        <div class="years">1931-2015 CE</div>
                        <p>"Missile Man of India" and people's President who championed India's space and defense programs.</p>
                    </div>
                </div>
                
                <div class="great-card">
                    <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxIQEBUQEBIVDw8VEBAPDxUPEBUPEBUVFRUWFhUWFRUYHSggGBolGxUVITEhJSkrLi4uFx8zODMtNygtLisBCgoKDg0OGxAQGi0gICUtLS8vLy0tLS0tLS0tLS0tLS8vLS0tLS0rLS0tLS0tLS0tLS0tLS0tLi0tLS0tLS0tK//AABEIAKgBLAMBIgACEQEDEQH/xAAbAAABBQEBAAAAAAAAAAAAAAADAAECBAUGB//EAD4QAAICAQEECAMGBQMDBQAAAAECAAMRBAUSITEGEyJBUWFxgTKRoQdCUrHB0RQjYnKCM/DxFdLhQ2NzksL/xAAbAQACAwEBAQAAAAAAAAAAAAADBAECBQAGB//EACwRAAMAAgIBAgUDBAMAAAAAAAABAgMRBBIhMUEFEyJRcWGx0SMykaEGM4H/2gAMAwEAAhEDEQA/APKQJICOBJgR9Iz2xgJILJqsmqwikG6IBZMJCqkIqS6ko7AiuEWuGWuFWuEUA3ZXFUmKpZWuEWuXWMG8hVFUkKpbFUmKoRYwbyFMVSQpl0VSQqlljKvIUhTHFMvCqP1Ut8sr8wo9THFMvCqS6md8sj5pQ6mLqZf6qLqZ3yyPmmf1MXUzQ6qZ22btxN1PjbhwPEDvPlBZeuOXVBMKrLaiTO1+vVDuphm7/wAI/czGutZzljk/QeghTQfCEp0LOcKDMLLyHb234PQ4eKsa0l5KO7EVnQr0cs8IC7YNo+4T6QCzx9xh4aMTdhq72Tk3Dw5iWLdnWLzUiV3QjmJdX7plKx+zRcp2iDwcY8xxHyl7cBGRxHcRymCZY0WrNZ8UPMfqPON4uR7UJZeKtbg02rgykurhgGU5B5SDVx3rv0EO+nplIrIFJbZINklXIRWVCsiRLDLBlZRoIqAESOIYiQxKNF0wiiEURKIVVhUgTYlWFVY6rDIkIpBVRFUhlSSRIZUhpkDVEFrhVSERIZUhpgBVglrhVrhVrhFSFUAnYFa5MVw6pJhIRQDdgBXJCuGCyW5LdSroB1cfch9yPuSepHYCtcfch1WPid1K9ivuRbksYjNgDJ5czI0kcnswdvbQNYFdf+owzn8I/cyOy9kYXLjLtxbPE8Y3RjQnX64lh2F3rW9BwUfl8p3o2cAfeeK+Kc2smTS9D3Pwzhzhxp+5zNOwlJ5Tb0OyEUcFA9prU6TE0KtKJjVkb9TQrSMwaIeEj/Bg902mrgmrnLIDMO7ZanmJh7T6LVtnAwfKds1crXJDRZB47tjYbVHxEx2TE9Y27pFZDmebbSpCuRH8Obt4YHJj0tojsrVbjbrfAx+R8ZuOk5dhOj2Td1lQz8SncPty+k2eHk39DMTn4tf1F/6ReuBZJfdIB0jrkRmii6wTLLliQDrBOQ80VSJHEMwkCINoKmFUQqLIIIdBCSgdMmiw6LIoIZBDyhemSRYdEjIsOgh5kXpiVYZViVYVRDSgNMSrCBYlEIohUgTYwEkFjgSWJfRRsYCPiPFJ0RsbEfEeKdogcCPHikFRpm9IL9zTue9huD/Ln9MzTnOdMn7Fa+Ls3yAH/wCotzL6YKa+w3wY78iV+v7G79k6KgvsPMhKx9Sf0nX7wJnE9BCV0zH8VjfQATq9JmfPc73kZ9EwxqEzUqSXK5SqaXKgYtckUhXR9zhJW1kyBOINLyU14A2CVNSvCWrG4wN0PPg7Ry22fhM852nxYz1PaNG8CJ5ftyg12sDnBORmN8V/UVzf2mUwmz0aYfzF7+y35iYzTa6Lpk2HyQfPP7Tb4f8A3Ix+dr5NGrYsrusu2CVnWbVIwYoqWLK7rLjiV3EDSGZZUYQeJYcQWIFoPLCIIdBBoIZBCSgVMKglisQVYlhBDyhemEQQ6iDQQ6CMShemTUQqiQUQqw0oDTJCTAkRCCXRQQjxR5YoNHiiknCijxSSCcUUUqQKYPS6jNSv+F8H0b/yB85vTB6Sapsrp13B1oyWtcVqMHON5iAvLmYnz3K49dh34cqfInqdF0U0e7pac8N4M/hwLE5+U3KNqaMHc69N4cD2v1755xp9o6yyoU79ddFY6nfQBsgfdUqe36jh5y/peht9y76FyviVRPkOc8JeOU32Z9Bmm4Wkel6eypuKOp9DmaNYGMgzxi/ZOq0xylh3h91huE+hBwZ0PRzpYtgCOwrfOGFrbp9iecDeJ63Pk5te/g9HewYzM3WbY09fCyxEPdvMBOf6QdKaNNXk2ixz8KVMHY/L4R5meeVaa3XWG2z+WjEsoB4kf3Hu852PBtdq8Ip43peWel63pnoUOOtDn+kE/XlKVHTHTWnCsF/vdUP1M5zR9FKHHC1A3lW1w88sMzL12xKwSpCNjgHob81/cQqnE/G2TqvbR6Sm0KCM9bWPWxP3nLdL6tHbWc6itbQMoQ4Y58CB3Thf+lsbV0q15vZwEbe3QykZHZxwHfnPdOx2d9nIUb2ptyeZWoYHux4n5CE+XGPT7Ae9X40efNzx+U6boxVipm/E/D0A/cmaG1ei6/xNVVIFaNwY88Y5kk+U1rqtNSBSlbdWOHWA9/ecd/GPcbnYsWRVSEeXwsmbG4ky3EA4lzUVlWKnu8PoZUeem2qSa9GeY05bl+xVsEruJasEr2CCpB5ZWcQJEsOIIwFIYlk0EOggVliuEkHQZJYrECksIIxIvTCoIdYFIdYeRegiwiyCwiwqBMkJMSEmJcox44jRxLFRRxGjicQKKKKScSBjxlilSB5mdItMr6ZyVBdd0ocdocctg+YE0cwOqXeXHiRn0zEviMVXGvX5/wAD/wAMpTyo7em9f58Gdo6er0WnuAyuHB8N7fbP6wt23dQaTalfWVLYtTNY7BQzA7uKkIwOGN4nmRL2xkNdb6E/6tNh1WmP46WIJI8SrZyP6vKaWn19ZdjZQ2+3xCpBun1B4TwVWlW2tn0GVVRpexyFW0dRajWmv+Wm6bN0ndXeJCgBiSD2TyPt3zGfZr6rVGulQWbt8TgAYGST3D956HtmxzWQKxRQO2+Qq4A5ndXhM/oFps2NqGGDa2U8qx8Pz/QQsZVKdJaB3jdalvZye3uhur0NQtuVeqJ3d6p98KTy3uAxmb+spbq62qA6tkRc8wMADHh856xtbQpqNO1FoyjoVbx8iPMHB9pwPR9DRYdm6pQbB2ad7ATUVDO6ayeHWqBgqeYA8JDy1knfugeLrGzA2z0euYo2le69DWhYFxvK4zvjAYKFPAgiBu2LehqU2MbWJDiw9YEy3ZAcHPLnxxnlPSNNs4V/6ZU45C6rtjyORMPpNrq6GF+osUsvGqteDE92F/XlKLPdfSkXUSnvZm7E2cf+rorHfNGjLOwGBvHsD6OZ2+oImF0F2daq3a7UKVv1LKVU8CtS/CCO7OfkBNLVW8ZTI/OvsRjW9sx9oEAliMgAn94PU212glAAm7gfKF1R7QzyJ3T7iUtNUqVgA/E7gegOMygV+DO2kMPjwRAf/qJQcS5rH3nZu4nh6chKjie948OMMS/sjwue++aqXu2VnEr2CWnld51Eyyq8ERDvAmL0MSTSWK4BJYSXkpQdJYSASHSMSL0HSGWASHWGkXoIsIsGsKsKgTJCSkRJS5Rjx40eWIFHEaOJxAooopJwooopxwo6jiPURog2CPUGUyrcNfowmJ6ufyjdfZFepRQ+8liHepsrbctrbxVh+XIyK9GdeD/L1tZH4rtIps+aEA/KbGzQMCbYOBPmHdrwfSb9do8/2l0UYDe1urfVAYPVqoopOOPaC8WEu9Hay9pIXCgDAAwAO7hDba1XWPjBKIQzgc8Z/wCYXY239P1h3AVyN3tLu5x3jxkOqoMo6R+rOk1edz5TD29s1bq8aioW18xw7SnxBHEHzEvajagIwq75zwVcZPlx4QNeo1Np3HqStCRkizfIHmMAZk7c+UBmX7o5ijoglvZTXayuv8A1BZR5Dem3sfoFotO4t3GvtHEPqH6zBHI45Z88S7rtH1bdZX8P3x+su6XVbwlnmv7lLxz6yS1Y4Gc7rBxm/qn4TC1YyZRMtjMraadnPnKO0qOrUbp4DAT35ia20WATj/vEobbwawynIO6R7xniyqywn91+4Pk1rFT/AEZgmBaHaBae+o8Giu8ruJYeBsEBYxJVeBMO8CYvQxJJJZSV0liuWgrYeuWFleuWFjMi1Bkh1gUhlhpAUEWEWDWEWFQJkxHjCPLFGPHjR5YgUcRo4nECiiiknCiiinHCkWkoxnEo6jYmoyo49wm7Zd2PacZsm/Ax4H6ToK9RkY8RPmvNwfJz3D9mfR+JkWfBGRfb/ZUZQM+LNDr0ertXiCp5jdODmYuv14rKls88YAJYnwAHOVbOlF1pNaI6KOBUOKnP9x5j0EV6sf6VXhPR1Gi2MKnBG8xB5sScD35Tbc8OGD6HP5ThtPZeRuGpiG/Fqc1n1yT+UHr9nW1DeD1UN/7LMW+YIktHPj9n5rydTZrMHdY84LTvutgcu6crptLrr03zaN0f6Zsrwz+hBzjzOZt7MZyB1gw44Njl7SGtFKxpbRr6izImPc2TLups4TPHOdKBpaKm1vhx48Jka3Zb16dL85oJwc80JbdGR4E44+c1NeSxAHE5wB4k8BNfpRQtWyrq25DSsnq27ge5bE1fht1jvslsz/iOOcmPozg2gWEobH1+8Oqc9oDsk948PUTReewVq52jx+TE8VdWAcSu8sPK9kHRaCvZK5EsWQLRehmRJLCSsksJLSRRYSWEleuHSMSL0HSHWASGUw8i9BVhFg1hFhECZNY8islLlGSjxo4liBRRRTiBRRR8SThopICLEjZ2yMUliMVk7OJ0Putnu5Ga9Wp5eUxcQld5HpPMf8g4PZLPP4f8nqP+Pc5S3x6f6r+DavqVnWwcxxHvG1NdVhzbXlu51JV/TI5+8pae/jkHhmdBoalccZ5Xqet7aMC9FHCsW2AcgWA9uAlzQV1Egmo73P8AmEvj2M3VpQHkIdqkAzwzOOeYqm0nnKt9gEPqLQJgbR1mIPXkiUW7tXmC6zhMvT2ljnul7R0vqLBWn+Tcwo8TDQtvSJvUrbNDo7ojZb1rfAnw+bH9ufymF9q22MBNGhyWxdd5Kp7APqwz/h5zu9VdVotMzt2aqkLN4nH5sT9TPCtdqn1V1mos+OxixGchRyVR5AAD2m9wOP5MPkZuz2Z3FeI4EHIPpOk0WqFqb3JuTDwP7TCtUjuzB6TWtU+QMgjDA8M+HvNft0f6GdyMPzp8eqOjeV7IGra1b8zuH+rl8+UM3HjCOkzLeOo8UtFawQDCWLBAEQNMNJFZYrldDDoZaSaLKSwkqoZYSMSL0iykKsAkOsPIvQZZNTBrCLCoEyclIyQlyjJRxGjyxUUcCLEecQIR4opBAoosR8TiBopLETHAyeA7yeAkbOXkjGYePKZeu20oyKSGIwGc5KL5DHxt5D3MwNVc9nFyzeG/x+Sjsr8veLZM00nKXb9jS43ByvVt9ft9zphrwrYBDjv3WB/LkZsaHbO5x5r5fr4TK+zPZSar+Kpbg3V0vWcfCwZxn68Y+s2e1btW2a7FO6wB/wB5E8TzMCxZGl6HuuLl+ZCVPz+50Nm3M8Yz7dGOf1nHFLCcDB+ks0bNuJ47qD1LGLOV9xk2NVtjPIykFeztHl3SxpNmonFjvt4nl7CbWzNj2akjd/l1d7sOf9o7/wApRQ6epJdqVujJ2ZorL3FVQ4/eP3VHiZ6HsvZiaavcTiebsfiY+J/aH2bsyvTV7lYx3sT8THxJmf0r24ui0z3NgsOzUp+/Y3wr6d58gZpYMHX8mXyOQ8n4OB+1fbnWWLoaz2UIs1GOW/gGtD6A73us42ivC8pCresdrLCXdmZ3J5lmOSZcAGJ6XjYflwY+fLt6Rm6oSkFJyfaXtb/xjiYKiklc+PH5ylrdBZrUbM60YkadS6fCxHl3fKG1ScZTaLZHoKkqXk0qtsfjX3X9jLI1lZ4hx7nB+RmAYoL5zB1xYfp4OhQw6Sshh0MclmdSLSGHQyqhlisxiWL0i0hhlMrIYdDDyxekHUwggVMKDDJgWgoMnBLCAy6BkxJKJAScsVZKKMI4E4qICSAiEVjhQWYhVHEljgCVb0R5b0h8QeovSsb1jBF8zz9B3+053afSrmunGe7fccP8V/U/Kc+1zWtvWMXbxY5+XhE8nMlPUeTU4/wq7+rJ9K/2dLrOkvdSuf6rOXsv7zFu1FlzfzHLAcT4DwwvLJgB4AZJOJpaPT4GT6+p8Yv9eWtN/k1YwYeOtyv5Hpp4AkY/CO4Dn85HUnhLTjwlXV8o31UzpA1Tqts6f7H9Ru7RZO59NYPdWrI+mZ6F002ZprVVrbU01x7FVjsF3jjO6c8x+U88+yHQGzXtd9ymls/3WdlR8g59hNr7UdPbayahe1pq80YH3Wzxc+ROBn+nznm+a13rxs2eLO7S3o57aGhv0xy6hl+69RFlbDxDCH0Tai04Sqwg44lCqjPeWPDGTMuyilGQMuc1oXxwILrnI8xvD5Raq66pWr661UBI3Rc+6CP6c8uEyE8bfobVcfJ12ns9Q2J0RVAH1J61+e6P9Mf935TqFUAYHAd2Jl9E9qfxWjpuJy5Tds/vXst9Rn3mmxmjEyl4MXJVOn2IWvwnh/2hdIf4zV9UhzRQWRPBn5O/03R6Hxnon2ibe/hNGxRsXW/yafEEjtN7Lk+uJ4dUN3B8MD2/3iPcbH9XZ+wtdG1TXwhscJGlsiTsPCbfsZVP6jJ1R4n3/I4lxUwoHlKVvM/4j5sB+80XHCKP1YxkekkYmvHGZjTS2gZmNEsz8jeH+0iY0RjRdhjeQw6GKKaEmRQdDDo0UUPLF6RYQwyNFFDyxekGQwymKKGQGkEBkwYooVAmgiSUUUnYNjiEAiinNlGVdpbQTTpvuePJVHxMfAfvOH2ptSzUNlzhQeyg+EfufOKKZPLy076+x6P4ZxoWNZNeWUsQtRjxRVGmzU2dp88T/wAD/wAzUx8o8U1MMqYWjOzU6sE0ztbb+0aKWyPUlsK2z1T7MNEaNCH5XamxnB7xWvZB+QJ9WE6zWbPR6moI/lvWa/YjgfXviinm/Vts0m9NaPGBX1i73/qVBVsHcyghAw8+QPsZY2y4zZb3M2/V2hzZs8gc5UZ9CI0UxNf1dHqJprFs7b7L7X6l9/IS2xraR5gBbPQE8R6GdnqLMCKKa+KdeDzfIrdNnhPTrbJ1msbBzVTvU1eBwe2/uw+SiYfVxRTewQlCEbfkvaF+GO8cIbUWcIooWW+oo5TszQ2WH/yJ9Axmhe4xFFFt+oTKvKMLXNkzOaKKJ5X5Hca0iJMgTHigQp//2Q==" alt="B.R. Ambedkar" class="great-img">
                    <div class="great-info">
                        <h3>B.R. Ambedkar</h3>
                        <div class="years">1891-1956 CE</div>
                        <p>Chief architect of the Indian Constitution, social reformer who fought for Dalit rights and social justice.</p>
                    </div>
                </div>
                
                <div class="great-card">
                    <img src="https://english.cdn.zeenews.com/sites/default/files/2025/02/26/1681503-ef3.png?im=Resize=(1200,900)" alt="C.V. Raman" class="great-img">
                    <div class="great-info">
                        <h3>C.V. Raman</h3>
                        <div class="years">1888-1970 CE</div>
                        <p>Physicist who won the Nobel Prize for his work on light scattering (Raman Effect), pioneering Indian science.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Section -->
    <div class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">5000+</div>
                    <div class="stat-text">Years of Continuous Civilization</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">40</div>
                    <div class="stat-text">UNESCO World Heritage Sites</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">1.3B+</div>
                    <div class="stat-text">People Representing Incredible Diversity</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">75+</div>
                    <div class="stat-text">Years as the World's Largest Democracy</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Did You Know Section -->
    <div class="facts-section">
        <div class="container">
            <div class="facts-title">
                <h2>Did You Know?</h2>
                <div class="tricolor-separator">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            
            <div class="facts-container">
                <div class="fact-card">
                    <h3>World's First University</h3>
                    <p>Takshashila University, established around 700 BCE, is considered the world's first university. Located in present-day Pakistan, it attracted students from across Asia to study subjects ranging from medicine to astronomy. Nalanda University, established in the 5th century CE, had over 10,000 students and 2,000 teachers at its peak.</p>
                </div>
                
                <div class="fact-card">
                    <h3>The World's Most Ancient Living Civilization</h3>
                    <p>India is home to the world's oldest continuously inhabited city, Varanasi, with a history dating back at least 3,500 years. The Sanskrit language, with a documented history of over 3,500 years, is considered the mother of many Indo-European languages and the most computer-friendly language due to its precise grammar.</p>
                </div>
                
                <div class="fact-card">
                    <h3>Pioneers in Medicine</h3>
                    <p>Sushruta, known as the "Father of Surgery," described over 300 surgical procedures and 120 surgical instruments in the 6th century BCE. The world's first recorded cataract surgery and plastic surgery techniques were performed in ancient India. Yoga, with origins dating back 5,000 years, was developed as a comprehensive approach to physical, mental, and spiritual health.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Call to Action -->
    <div class="cta-section">
        <div class="cta-content">
            <h2>Explore India's Heritage Today</h2>
            <p>Discover the monuments, museums, and living traditions that bring India's remarkable history to life.</p>
            <a href="heritage.php" class="cta-button">Explore Heritage Sites</a>
        </div>
    </div>
    

    <!-- Footer Section -->
    
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
            const searchInput = document.getElementById('historySearch');
            const timelineBlocks = document.querySelectorAll('.timeline-block');
            const eraTabs = document.querySelectorAll('.era-tab');
            
            // Search function
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                filterTimeline();
            });
            
            // Era filtering
            eraTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    eraTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    filterTimeline();
                });
            });
            
            // Combined filter function
            function filterTimeline() {
                const searchTerm = searchInput.value.toLowerCase();
                const activeEra = document.querySelector('.era-tab.active').getAttribute('data-era');
                
                timelineBlocks.forEach(block => {
                    const blockText = block.textContent.toLowerCase();
                    const blockEra = block.getAttribute('data-era');
                    
                    // Check if block matches both search term and era filter
                    const matchesSearch = blockText.includes(searchTerm);
                    const matchesEra = activeEra === 'all' || blockEra === activeEra;
                    
                    if (matchesSearch && matchesEra) {
                        block.style.display = "";
                    } else {
                        block.style.display = "none";
                    }
                });
            }
            
            // Animate timeline blocks on scroll
            function checkScroll() {
                timelineBlocks.forEach(block => {
                    const blockPosition = block.getBoundingClientRect().top;
                    const screenPosition = window.innerHeight / 1.2;
                    
                    if (blockPosition < screenPosition) {
                        block.querySelector('.timeline-content').style.opacity = "1";
                        block.querySelector('.timeline-content').style.transform = "translateY(0)";
                    }
                });
            }
            
            // Initially hide timeline contents for animation
            timelineBlocks.forEach(block => {
                const content = block.querySelector('.timeline-content');
                content.style.opacity = "0";
                content.style.transform = "translateY(20px)";
                content.style.transition = "all 0.5s ease";
            });
            
            // Check scroll position on page load and scroll
            checkScroll();
            window.addEventListener('scroll', checkScroll);
        });
    </script>
  

</body>
</html>