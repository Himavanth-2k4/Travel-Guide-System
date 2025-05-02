<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

if (!isset($_POST['place_id'])) {
    echo json_encode(['error' => 'Place ID not provided']);
    exit;
}

$user_id = $_SESSION['user'];
$place_id = $_POST['place_id'];

try {
    // Check if place is already in wishlist
    $stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND place_id = ?");
    $stmt->execute([$user_id, $place_id]);
    $result = $stmt->get_result();
    $existing = $result->fetch_assoc();

    if ($existing) {
        // Remove from wishlist
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND place_id = ?");
        $stmt->execute([$user_id, $place_id]);
        echo json_encode(['status' => 'removed']);
    } else {
        // Add to wishlist
        $stmt = $conn->prepare("INSERT INTO wishlist (user_id, place_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $place_id]);
        echo json_encode(['status' => 'added']);
    }
} catch(Exception $e) {
    echo json_encode(['error' => 'Database error']);
}
?>