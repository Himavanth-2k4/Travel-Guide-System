-- Drop existing tables if they exist (optional, uncomment if needed)
-- DROP TABLE IF EXISTS admins;
-- DROP TABLE IF EXISTS guides;

-- Create admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Create guides table
CREATE TABLE IF NOT EXISTS guides (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    experience INT NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Active', 'Inactive') DEFAULT 'Active'
);

-- Add guide_id column to bookings table if it doesn't exist
ALTER TABLE bookings
ADD COLUMN IF NOT EXISTS guide_id INT,
ADD COLUMN IF NOT EXISTS status ENUM('Pending', 'Confirmed', 'Cancelled', 'Assigned') DEFAULT 'Pending',
ADD FOREIGN KEY (guide_id) REFERENCES guides(id) ON DELETE SET NULL;

-- Insert default admin account (password: admin123)
INSERT INTO admins (username, password, email) VALUES 
('admin', '$2y$10$PtF8zf9F.ZSbiHcNCL9TZu9hNxakwbEcllcYL.Cd04v1.RO6FRdle', 'admin@example.com')
ON DUPLICATE KEY UPDATE 
    password = VALUES(password),
    email = VALUES(email); 