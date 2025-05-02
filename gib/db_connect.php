<?php
$host = "localhost";
$user = "root";
$pass = ""; // set your MySQL password if any
$db = "travel_guide";

// Create connection without selecting database
$conn = new mysqli($host, $user, $pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $db";
if ($conn->query($sql) === TRUE) {
    // Select the database
    $conn->select_db($db);
} else {
    die("Error creating database: " . $conn->error);
}
?>
