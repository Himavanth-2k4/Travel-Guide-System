
<?php
// Database connection
require_once 'db_connect.php';

// Set default response
$response = array(
    'status' => 'error',
    'message' => 'An unexpected error occurred.'
);

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate email
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        
        // Check if email is valid
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                // Check if email already exists
                $checkStmt = $conn->prepare("SELECT id FROM newsletter_subscriptions WHERE email = ?");
                $checkStmt->bind_param("s", $email);
                $checkStmt->execute();
                $result = $checkStmt->get_result();
                
                if ($result->num_rows > 0) {
                    // Email already subscribed
                    $response = array(
                        'status' => 'info',
                        'message' => 'This email is already subscribed to our newsletter.'
                    );
                } else {
                    // Insert new subscription
                    $stmt = $conn->prepare("INSERT INTO newsletter_subscriptions (email) VALUES (?)");
                    $stmt->bind_param("s", $email);
                    
                    if ($stmt->execute()) {
                        $response = array(
                            'status' => 'success',
                            'message' => 'Thank you for subscribing to our newsletter!'
                        );
                    } else {
                        $response = array(
                            'status' => 'error',
                            'message' => 'Failed to subscribe. Please try again later.'
                        );
                    }
                    $stmt->close();
                }
                $checkStmt->close();
            } catch (Exception $e) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Database error occurred. Please try again later.'
                );
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Please enter a valid email address.'
            );
        }
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Email address is required.'
        );
    }
}

// Return JSON response for AJAX requests
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} else {
    // Redirect back to the page for non-AJAX requests
    $redirectUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    
    // Start session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Set session message
    $_SESSION['newsletter_message'] = $response['message'];
    $_SESSION['newsletter_status'] = $response['status'];
    
    header("Location: $redirectUrl");
    exit;
}
?>
