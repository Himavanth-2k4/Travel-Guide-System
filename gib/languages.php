
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
            --primary: #FF9933; /* Saffron from Indian flag */
            --secondary: #138808; /* Green from Indian flag */
            --accent: #000080; /* Navy blue */
            --light-bg: #f8f9fa;
            --card-border: #e0e0e0;
            --text-primary: #333333;
            --text-secondary: #666666;
            --font-main: 'Poppins', sans-serif;
            --font-heading: 'Montserrat', sans-serif;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: var(--font-main);
            color: var(--text-primary);
            background-color: var(--light-bg);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('images/language-banner.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 20px;
            text-align: center;
        }
        
        .hero h1 {
            font-family: var(--font-heading);
            font-size: 3.5rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .hero p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .tricolor-line {
            height: 5px;
            width: 150px;
            margin: 20px auto;
            display: flex;
        }
        
        .tricolor-line span {
            height: 100%;
            flex: 1;
        }
        
        .tricolor-line span:nth-child(1) { background-color: #FF9933; }
        .tricolor-line span:nth-child(2) { background-color: #FFFFFF; }
        .tricolor-line span:nth-child(3) { background-color: #138808; }
        
        /* Introduction Section */
        .intro {
            text-align: center;
            padding: 60px 20px;
        }
        
        .intro h2 {
            color: var(--accent);
            font-size: 2.2rem;
            margin-bottom: 20px;
        }
        
        .intro p {
            max-width: 800px;
            margin: 0 auto;
            font-size: 1.1rem;
        }
        
        /* Search Section */
        .search-section {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 30px;
            margin: 20px auto 40px;
            max-width: 600px;
            text-align: center;
        }
        
        .search-section h3 {
            margin-bottom: 15px;
            color: var(--accent);
        }
        
        .search-input {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 30px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255,153,51,0.2);
        }
        
        /* Languages Grid */
        .languages-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }
        
        .language-card {
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .language-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .card-header {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .card-header h2 {
            color: var(--accent);
            font-size: 1.8rem;
            margin-bottom: 5px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .script-display {
            font-size: 2rem;
            margin: 15px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 8px;
            text-align: center;
        }
        
        .language-details {
            margin-top: 15px;
        }
        
        .language-details p {
            margin-bottom: 8px;
        }
        
        .language-family {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            margin-top: 10px;
        }
        
        .indo-aryan { 
            background-color: rgba(255,153,51,0.15);
            color: #d17000;
            border-top: 3px solid #FF9933;
        }
        
        .dravidian { 
            background-color: rgba(19,136,8,0.15);
            color: #0b7304;
            border-top: 3px solid #138808;
        }
        
        .tibeto-burman { 
            background-color: rgba(65,105,225,0.15);
            color: #1e56c9;
            border-top: 3px solid #4169E1;
        }
        
        .austro-asiatic { 
            background-color: rgba(153,50,204,0.15);
            color: #7d28a6;
            border-top: 3px solid #9932CC;
        }
        
        /* Language Families Section */
        .families-section {
            background-color: white;
            padding: 50px 20px;
            margin: 40px 0;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .families-section h2 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--accent);
        }
        
        .families-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .family-card {
            padding: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        
        .family-card h3 {
            margin-bottom: 10px;
        }
        
        /* Interactive Map Section */
        .map-section {
            text-align: center;
            margin: 50px 0;
        }
        
        .map-section h2 {
            margin-bottom: 20px;
            color: var(--accent);
        }
        
        .map-container {
            background-color: #f0f0f0;
            padding: 30px;
            border-radius: 12px;
            margin-top: 20px;
        }
        
        /* Cultural Insights Section */
        .cultural-insights {
            margin: 50px 0;
            padding: 40px 20px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .cultural-insights h2 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--accent);
        }
        
        .insights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .insight-card {
            padding: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        
        .insight-card h3 {
            margin-bottom: 10px;
        }
        
        /* Footer Note */
        .footer-note {
            text-align: center;
            padding: 30px 0;
            background-color: #f0f0f0;
        }
        
        .footer-note p {
            max-width: 800px;
            margin: 0 auto;
            color: var(--text-secondary);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .languages-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }
        
        @media (max-width: 576px) {
            .languages-grid {
                grid-template-columns: 1fr;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .intro h2, .families-section h2, .map-section h2, .cultural-insights h2 {
                font-size: 1.8rem;
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

  <!-- Hero Section -->
  <div class="hero">
        <div class="container">
            <h1>Languages of India</h1>
            <div class="tricolor-line">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <p>Exploring the rich linguistic tapestry of India with 22 officially recognized languages and over 1600 dialects</p>
        </div>
    </div>
    
    <!-- Introduction Section -->
    <div class="intro">
        <div class="container">
            <h2>India's Linguistic Heritage</h2>
            <p>India's linguistic diversity is a testament to its rich cultural heritage, with languages from four major families: Indo-Aryan, Dravidian, Austro-Asiatic, and Tibeto-Burman. The Eighth Schedule of the Indian Constitution recognizes 22 official languages, each with its unique script, literature, and cultural significance.</p>
        </div>
    </div>
    
    <!-- Search Section -->
    <div class="container">
        <div class="search-section">
            <h3>Find a Language</h3>
            <input type="text" id="languageSearch" class="search-input" placeholder="Search by language name, script, or region...">
        </div>
        
        <!-- Languages Grid -->
        <div class="languages-grid" id="languagesContainer">
            <?php
            // Comprehensive array with detailed language information
            $languages = [
                [
                    "name" => "Hindi",
                    "desc" => "India's most widely spoken language and one of the two official languages at the national level.",
                    "script" => "हिन्दी",
                    "family" => "Indo-Aryan",
                    "speakers" => "~600 million",
                    "states" => "Uttar Pradesh, Bihar, Rajasthan, Madhya Pradesh, etc.",
                    "literature" => "Works by Premchand, Kabir, and modern writers",
                    "class" => "indo-aryan"
                ],
                [
                    "name" => "Bengali",
                    "desc" => "Known for its rich literary heritage and melodious sound. The language of Nobel laureate Rabindranath Tagore.",
                    "script" => "বাংলা",
                    "family" => "Indo-Aryan",
                    "speakers" => "~98 million in India",
                    "states" => "West Bengal, Tripura, parts of Assam",
                    "literature" => "Works by Rabindranath Tagore, Kazi Nazrul Islam",
                    "class" => "indo-aryan"
                ],
                [
                    "name" => "Telugu",
                    "desc" => "Known as the 'Italian of the East' for its melodious quality. Second most spoken language in India.",
                    "script" => "తెలుగు",
                    "family" => "Dravidian",
                    "speakers" => "~82 million",
                    "states" => "Andhra Pradesh, Telangana",
                    "literature" => "Classical literature dating back to 11th century",
                    "class" => "dravidian"
                ],
                [
                    "name" => "Marathi",
                    "desc" => "Language with a rich literary and cultural history, dating back to around 900 AD.",
                    "script" => "मराठी",
                    "family" => "Indo-Aryan",
                    "speakers" => "~83 million",
                    "states" => "Maharashtra, parts of Goa",
                    "literature" => "Medieval texts to modern literature",
                    "class" => "indo-aryan"
                ],
                [
                    "name" => "Tamil",
                    "desc" => "One of the longest-surviving classical languages in the world, with literature dating back to 300 BCE.",
                    "script" => "தமிழ்",
                    "family" => "Dravidian",
                    "speakers" => "~70 million",
                    "states" => "Tamil Nadu, Puducherry",
                    "literature" => "Ancient Sangam literature, Thirukkural",
                    "class" => "dravidian"
                ],
                [
                    "name" => "Urdu",
                    "desc" => "Known for its poetic expressions and Persianized vocabulary, culturally significant across North India.",
                    "script" => "اُردُو",
                    "family" => "Indo-Aryan",
                    "speakers" => "~50 million (native in India)",
                    "states" => "Jammu & Kashmir, UP, Bihar, Telangana",
                    "literature" => "Poetry by Ghalib, Mir, and Faiz",
                    "class" => "indo-aryan"
                ],
                [
                    "name" => "Gujarati",
                    "desc" => "An Indo-Aryan language native to Gujarat with a literary history going back to the 12th century.",
                    "script" => "ગુજરાતી",
                    "family" => "Indo-Aryan",
                    "speakers" => "~56 million",
                    "states" => "Gujarat, Dadra and Nagar Haveli",
                    "literature" => "Medieval poets like Narsinh Mehta",
                    "class" => "indo-aryan"
                ],
                [
                    "name" => "Kannada",
                    "desc" => "One of the oldest Dravidian languages with a history of over 2000 years, awarded classical language status.",
                    "script" => "ಕನ್ನಡ",
                    "family" => "Dravidian",
                    "speakers" => "~44 million",
                    "states" => "Karnataka",
                    "literature" => "Rich tradition from ancient poets to modern authors",
                    "class" => "dravidian"
                ],
                [
                    "name" => "Odia",
                    "desc" => "One of the classical languages of India with a literary tradition of over 1000 years.",
                    "script" => "ଓଡ଼ିଆ",
                    "family" => "Indo-Aryan",
                    "speakers" => "~38 million",
                    "states" => "Odisha",
                    "literature" => "Ancient literature including works by Jayadeva",
                    "class" => "indo-aryan"
                ],
                [
                    "name" => "Punjabi",
                    "desc" => "Language of the Sikh scriptures and vibrant folk culture, written in Gurmukhi script in India.",
                    "script" => "ਪੰਜਾਬੀ",
                    "family" => "Indo-Aryan",
                    "speakers" => "~30 million (in India)",
                    "states" => "Punjab, Haryana, Delhi",
                    "literature" => "Guru Granth Sahib, works by Waris Shah",
                    "class" => "indo-aryan"
                ],
                [
                    "name" => "Malayalam",
                    "desc" => "Known for its complex linguistic structure and rich literature, with high literacy in Kerala.",
                    "script" => "മലയാളം",
                    "family" => "Dravidian",
                    "speakers" => "~38 million",
                    "states" => "Kerala, Lakshadweep",
                    "literature" => "Ancient works to modern fiction",
                    "class" => "dravidian"
                ],
                [
                    "name" => "Assamese",
                    "desc" => "The easternmost Indo-Aryan language with a rich literary tradition dating to the 14th century.",
                    "script" => "অসমীয়া",
                    "family" => "Indo-Aryan",
                    "speakers" => "~15 million",
                    "states" => "Assam",
                    "literature" => "Works of Sankardev and modern authors",
                    "class" => "indo-aryan"
                ],
                [
                    "name" => "Maithili",
                    "desc" => "Ancient language with rich folk traditions, recognized officially in the Constitution in 2003.",
                    "script" => "मैथिली",
                    "family" => "Indo-Aryan",
                    "speakers" => "~13 million",
                    "states" => "Bihar, Jharkhand",
                    "literature" => "Works by Vidyapati and medieval court poetry",
                    "class" => "indo-aryan"
                ],
                [
                    "name" => "Sanskrit",
                    "desc" => "Ancient Indo-Aryan language, repository of India's heritage, philosophy, and religious texts.",
                    "script" => "संस्कृतम्",
                    "family" => "Indo-Aryan",
                    "speakers" => "~24,000 (native speakers)",
                    "states" => "Ceremonial importance across India",
                    "literature" => "Vedas, Upanishads, epics like Mahabharata",
                    "class" => "indo-aryan"
                ],
                [
                    "name" => "Konkani",
                    "desc" => "Coastal language with Portuguese influence, spoken in multiple scripts based on region.",
                    "script" => "कोंकणी",
                    "family" => "Indo-Aryan",
                    "speakers" => "~8 million",
                    "states" => "Goa, coastal Karnataka, Maharashtra",
                    "literature" => "Modern literature by regional authors",
                    "class" => "indo-aryan"
                ],
                [
                    "name" => "Manipuri",
                    "desc" => "Ancient language of Manipur with its own script and rich tradition of dance and literature.",
                    "script" => "মৈতৈলোন্",
                    "family" => "Tibeto-Burman",
                    "speakers" => "~1.8 million",
                    "states" => "Manipur",
                    "literature" => "Ancient manuscripts and modern works",
                    "class" => "tibeto-burman"
                ],
                [
                    "name" => "Sindhi",
                    "desc" => "Language of the Sindhi diaspora in India after partition, written in Devanagari and Perso-Arabic.",
                    "script" => "سنڌي / सिन्धी",
                    "family" => "Indo-Aryan",
                    "speakers" => "~2.5 million",
                    "states" => "Gujarat, Rajasthan, Maharashtra",
                    "literature" => "Sufi poetry and modern works",
                    "class" => "indo-aryan"
                ],
                [
                    "name" => "Dogri",
                    "desc" => "Language of the Dogra people in Jammu region, recognized in 2003 in the Constitution.",
                    "script" => "डोगरी",
                    "family" => "Indo-Aryan",
                    "speakers" => "~2.5 million",
                    "states" => "Jammu and Kashmir, Himachal Pradesh",
                    "literature" => "Folk tales and modern poetry",
                    "class" => "indo-aryan"
                ],
                [
                    "name" => "Santhali",
                    "desc" => "Largest tribal language in India with its own indigenous script called Ol Chiki.",
                    "script" => "ᱥᱟᱱᱛᱟᱞᱤ",
                    "family" => "Austro-Asiatic",
                    "speakers" => "~7 million",
                    "states" => "Jharkhand, West Bengal, Odisha",
                    "literature" => "Rich oral traditions and written works",
                    "class" => "austro-asiatic"
                ],
                [
                    "name" => "Kashmiri",
                    "desc" => "Language of the Kashmir Valley with Persian, Sanskrit and Arabic influences.",
                    "script" => "कॉशुर / کٲشُر",
                    "family" => "Indo-Aryan",
                    "speakers" => "~7 million",
                    "states" => "Jammu and Kashmir",
                    "literature" => "Poetry by Lal Ded and other poets",
                    "class" => "indo-aryan"
                ],
                [
                    "name" => "Nepali",
                    "desc" => "Official language of Sikkim and prominent in Northeast India and Darjeeling.",
                    "script" => "नेपाली",
                    "family" => "Indo-Aryan",
                    "speakers" => "~3 million (in India)",
                    "states" => "Sikkim, West Bengal (Darjeeling)",
                    "literature" => "Works by Bhanubhakta Acharya and others",
                    "class" => "indo-aryan"
                ],
                [
                    "name" => "Bodo",
                    "desc" => "Major tribal language of Northeast India, particularly Assam, using Devanagari script.",
                    "script" => "बर'",
                    "family" => "Tibeto-Burman",
                    "speakers" => "~1.5 million",
                    "states" => "Assam, Northeast India",
                    "literature" => "Oral traditions and modern literature",
                    "class" => "tibeto-burman"
                ],
            ];

            // Loop through each language and create detailed cards
            foreach ($languages as $lang) {
                echo "
                <div class='language-card {$lang['class']}'>
                    <div class='card-header'>
                        <h2>{$lang['name']}</h2>
                        <span class='language-family {$lang['class']}'>{$lang['family']} Family</span>
                    </div>
                    <div class='card-body'>
                        <div class='script-display'>{$lang['script']}</div>
                        <p>{$lang['desc']}</p>
                        <div class='language-details'>
                            <p><strong>Native Speakers:</strong> {$lang['speakers']}</p>
                            <p><strong>States/Regions:</strong> {$lang['states']}</p>
                            <p><strong>Literary Heritage:</strong> {$lang['literature']}</p>
                        </div>
                    </div>
                </div>
                ";
            }
            ?>
        </div>
        
        <!-- Language Families Section -->
        <div class="families-section">
            <h2>Language Families of India</h2>
            <div class="families-grid">
                <div class="family-card" style="border-left: 4px solid #FF9933;">
                    <h3>Indo-Aryan Family</h3>
                    <p>Part of the Indo-European language family, these languages dominate North, West, and East India. They evolved from Sanskrit and include Hindi, Bengali, Marathi, and many others.</p>
                </div>
                
                <div class="family-card" style="border-left: 4px solid #138808;">
                    <h3>Dravidian Family</h3>
                    <p>Native to South India, Dravidian languages include Tamil, Telugu, Kannada, and Malayalam. They have ancient origins predating Indo-Aryan languages in the subcontinent.</p>
                </div>
                
                <div class="family-card" style="border-left: 4px solid #4169E1;">
                    <h3>Tibeto-Burman Family</h3>
                    <p>Prevalent in the Himalayan region and Northeast India, this family includes languages like Bodo, Manipuri, and numerous tribal languages with connections to Tibet and Myanmar.</p>
                </div>
                
                <div class="family-card" style="border-left: 4px solid #9932CC;">
                    <h3>Austro-Asiatic Family</h3>
                    <p>Includes tribal languages like Santali and Mundari, primarily spoken in eastern and central India. These languages have connections to Southeast Asian linguistic traditions.</p>
                </div>
            </div>
        </div>
        
        <!-- Map Section -->
        <div class="map-section">
            <h2>Linguistic Map of India</h2>
            <p>The geographical distribution of major languages across different states of India.</p>
            <div class="map-container">
                <p style="padding: 30px; color: #666;">[Interactive map would be displayed here, showing different language regions of India color-coded by language family]</p>
            </div>
        </div>
        
        <!-- Cultural Insights Section -->
        <div class="cultural-insights">
            <h2>Cultural Significance of Languages</h2>
            <div class="insights-grid">
                <div class="insight-card">
                    <h3>Literature & Poetry</h3>
                    <p>India's languages have produced extensive literature, from ancient texts like the Vedas to modern novels and poetry. Eight Indian languages have been designated as "Classical Languages" based on their ancient literary heritage.</p>
                </div>
                
                <div class="insight-card">
                    <h3>Scripts & Calligraphy</h3>
                    <p>Most Indian languages have their own unique scripts, many derived from the ancient Brahmi script. These scripts have evolved their own calligraphic traditions and artistic expressions.</p>
                </div>
                
                <div class="insight-card">
                    <h3>Cinema & Media</h3>
                    <p>India produces films in numerous languages, with robust film industries in Hindi, Tamil, Telugu, Malayalam, Bengali, and others. Regional language media plays a crucial role in cultural expression.</p>
                </div>
                
                <div class="insight-card">
                    <h3>Linguistic Policy</h3>
                    <p>India follows a Three-Language Formula in education and recognizes 22 languages in its Constitution's Eighth Schedule, promoting multilingualism while preserving linguistic diversity.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Note -->
    <div class="footer-note">
        <div class="container">
            <p>India recognizes 22 official languages in the Eighth Schedule of the Constitution, but is home to over 1600 languages and dialects across the country, making it one of the most linguistically diverse nations in the world.</p>
            <p style="margin-top: 10px;">© 2024 Indian Languages Portal</p>
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
            const searchInput = document.getElementById('languageSearch');
            const container = document.getElementById('languagesContainer');
            const cards = container.getElementsByClassName('language-card');
            
            // Enhanced search function
            searchInput.addEventListener('keyup', function() {
                const filter = searchInput.value.toLowerCase();
                let resultsFound = false;
                
                for (let i = 0; i < cards.length; i++) {
                    const card = cards[i];
                    const text = card.textContent.toLowerCase();
                    
                    if (text.includes(filter)) {
                        card.style.display = "";
                        resultsFound = true;
                    } else {
                        card.style.display = "none";
                    }
                }
                
                // Show a message if no results are found
                let noResultsMsg = document.getElementById('noResultsMessage');
                if (filter.length > 0 && !resultsFound) {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.id = 'noResultsMessage';
                        noResultsMsg.style.textAlign = 'center';
                        noResultsMsg.style.padding = '30px';
                        noResultsMsg.style.color = '#666';
                        noResultsMsg.innerHTML = '<p>No languages found matching your search.</p>';
                        container.parentNode.insertBefore(noResultsMsg, container.nextSibling);
                    }
                    noResultsMsg.style.display = 'block';
                } else if (noResultsMsg) {
                    noResultsMsg.style.display = 'none';
                }
            });
            
            // Add animation effects on card hover
            Array.from(cards).forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px)';
                    this.style.boxShadow = '0 12px 30px rgba(0,0,0,0.15)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.08)';
                });
            });
        });
    </script>
  

</body>
</html>