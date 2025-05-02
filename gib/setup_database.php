<?php
include 'db_connect.php';

// First, drop existing tables if they exist
$conn->query("SET FOREIGN_KEY_CHECKS = 0");
$conn->query("DROP TABLE IF EXISTS admins");
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

// Create admins table
$sql = "CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Admins table created successfully<br>";
} else {
    echo "Error creating admins table: " . $conn->error . "<br>";
}

// Insert default admin account
$password = password_hash('admin123', PASSWORD_DEFAULT);
$sql = "INSERT INTO admins (username, password, email) VALUES 
        ('admin', '$password', 'admin@example.com')
        ON DUPLICATE KEY UPDATE 
        password = VALUES(password),
        email = VALUES(email)";

if ($conn->query($sql) === TRUE) {
    echo "Default admin account created/updated successfully<br>";
} else {
    echo "Error creating admin account: " . $conn->error . "<br>";
}

echo "Setup completed! You can now log in with:<br>";
echo "Username: admin<br>";
echo "Password: admin123<br>";

$conn->close();
?> 