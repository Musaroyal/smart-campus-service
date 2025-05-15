<?php
// Database initialization script

// Database connection parameters
$host = 'localhost';
$user = 'root';  // Default for XAMPP
$pass = '';      // Default empty password for XAMPP

// Create database if it doesn't exist
try {
    $conn = new PDO("mysql:host=$host", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $conn->exec("CREATE DATABASE IF NOT EXISTS smart_campus");
    echo "Database 'smart_campus' created or already exists.<br>";
    
    // Select the database
    $conn->exec("USE smart_campus");
    
    // Create users table
    $conn->exec("CREATE TABLE IF NOT EXISTS `users_db` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(100) NOT NULL,
        `email` VARCHAR(100) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `student_no` VARCHAR(20) NULL,
        `course_name` VARCHAR(100) NULL,
        `course_code` VARCHAR(50) NULL,
        `role` ENUM('student', 'lecturer', 'admin') NOT NULL DEFAULT 'student',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Table 'users_db' created or already exists.<br>";
    
    // Create rooms table
    $conn->exec("CREATE TABLE IF NOT EXISTS `rooms` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `room_number` VARCHAR(20) NOT NULL,
        `building` VARCHAR(100) NOT NULL,
        `capacity` INT NOT NULL,
        `features` TEXT NULL,
        `is_available` BOOLEAN DEFAULT TRUE
    )");
    echo "Table 'rooms' created or already exists.<br>";
    
    // Create room_bookings table
    $conn->exec("CREATE TABLE IF NOT EXISTS `room_bookings` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `room_id` INT NOT NULL,
        `booking_date` DATE NOT NULL,
        `start_time` TIME NOT NULL,
        `end_time` TIME NOT NULL,
        `purpose` VARCHAR(255) NULL,
        `group_size` INT DEFAULT 1,
        `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`user_id`) REFERENCES `users_db`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE CASCADE
    )");
    echo "Table 'room_bookings' created or already exists.<br>";
    
    // Create timetable table
    $conn->exec("CREATE TABLE IF NOT EXISTS `timetable` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `course_code` VARCHAR(50) NOT NULL,
        `day_of_week` ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
        `start_time` TIME NOT NULL,
        `end_time` TIME NOT NULL,
        `room` VARCHAR(50) NOT NULL,
        `lecturer` VARCHAR(100) NULL,
        `subject` VARCHAR(100) NOT NULL
    )");
    echo "Table 'timetable' created or already exists.<br>";
    
    // Create maintenance_requests table
    $conn->exec("CREATE TABLE IF NOT EXISTS `maintenance_requests` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `issue_type` VARCHAR(100) NOT NULL,
        `location` VARCHAR(100) NOT NULL,
        `description` TEXT NOT NULL,
        `status` ENUM('reported', 'in-progress', 'resolved') DEFAULT 'reported',
        `reported_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `resolved_at` TIMESTAMP NULL,
        FOREIGN KEY (`user_id`) REFERENCES `users_db`(`id`) ON DELETE CASCADE
    )");
    echo "Table 'maintenance_requests' created or already exists.<br>";
    
    // Create notifications table
    $conn->exec("CREATE TABLE IF NOT EXISTS `notifications` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `title` VARCHAR(100) NOT NULL,
        `message` TEXT NOT NULL,
        `is_read` BOOLEAN DEFAULT FALSE,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`user_id`) REFERENCES `users_db`(`id`) ON DELETE CASCADE
    )");
    echo "Table 'notifications' created or already exists.<br>";
    
    // Check if rooms table has data
    $stmt = $conn->query("SELECT COUNT(*) FROM rooms");
    $room_count = $stmt->fetchColumn();
    
    if ($room_count == 0) {
        // Insert sample data for rooms
        $conn->exec("INSERT INTO `rooms` (`room_number`, `building`, `capacity`, `features`, `is_available`) VALUES
            ('R101', 'Main Library', 4, 'Whiteboard, Power outlets', TRUE),
            ('R102', 'Main Library', 8, 'Whiteboard, Smart board, Power outlets', TRUE),
            ('R201', 'Engineering Building', 6, 'Projector, Whiteboard, Power outlets', TRUE),
            ('R202', 'Engineering Building', 12, 'Projector, Computers, Power outlets', TRUE),
            ('R301', 'Science Building', 4, 'Whiteboard, Power outlets', TRUE)");
        echo "Sample room data added.<br>";
    }
    
    // Check if timetable table has data
    $stmt = $conn->query("SELECT COUNT(*) FROM timetable");
    $timetable_count = $stmt->fetchColumn();
    
    if ($timetable_count == 0) {
        // Insert sample timetable data
        $conn->exec("INSERT INTO `timetable` (`course_code`, `day_of_week`, `start_time`, `end_time`, `room`, `lecturer`, `subject`) VALUES
            ('CS101', 'Monday', '09:00:00', '11:00:00', 'LT1', 'Dr. Smith', 'Introduction to Programming'),
            ('CS101', 'Wednesday', '09:00:00', '11:00:00', 'LT1', 'Dr. Smith', 'Introduction to Programming'),
            ('MTH201', 'Tuesday', '11:00:00', '13:00:00', 'LT2', 'Prof. Johnson', 'Calculus II'),
            ('MTH201', 'Thursday', '11:00:00', '13:00:00', 'LT2', 'Prof. Johnson', 'Calculus II'),
            ('PHY101', 'Monday', '14:00:00', '16:00:00', 'Lab3', 'Dr. Lee', 'Physics I')");
        echo "Sample timetable data added.<br>";
    }
    
    // Create an admin user if none exists
    $stmt = $conn->query("SELECT COUNT(*) FROM users_db WHERE role = 'admin'");
    $admin_count = $stmt->fetchColumn();
    
    if ($admin_count == 0) {
        // Create a default admin user (email: admin@campus.edu, password: admin123)
        $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
        $conn->exec("INSERT INTO `users_db` (`name`, `email`, `password`, `role`) 
                    VALUES ('System Admin', 'admin@campus.edu', '$admin_password', 'admin')");
        echo "Default admin user created with email: admin@campus.edu and password: admin123<br>";
    }
    
    echo "<br><strong>Database initialization completed successfully!</strong><br>";
    echo "<a href='index.php'>Go to homepage</a>";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

$conn = null; // Close connection
?> 