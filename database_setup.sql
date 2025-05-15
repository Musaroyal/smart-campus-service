-- Database setup for Smart Campus System

-- Users table
CREATE TABLE IF NOT EXISTS `users_db` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `student_no` VARCHAR(20) NULL,
  `course_name` VARCHAR(100) NULL,
  `course_code` VARCHAR(50) NULL,
  `role` ENUM('student', 'lecturer', 'admin') NOT NULL DEFAULT 'student',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Rooms table
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `room_number` VARCHAR(20) NOT NULL,
  `building` VARCHAR(100) NOT NULL,
  `capacity` INT NOT NULL,
  `features` TEXT NULL,
  `is_available` BOOLEAN DEFAULT TRUE
);

-- Room Bookings table
CREATE TABLE IF NOT EXISTS `room_bookings` (
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
);

-- Timetable table
CREATE TABLE IF NOT EXISTS `timetable` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `course_code` VARCHAR(50) NOT NULL,
  `day_of_week` ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `room` VARCHAR(50) NOT NULL,
  `lecturer` VARCHAR(100) NULL,
  `subject` VARCHAR(100) NOT NULL
);

-- Maintenance requests table
CREATE TABLE IF NOT EXISTS `maintenance_requests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `issue_type` VARCHAR(100) NOT NULL,
  `location` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `status` ENUM('reported', 'in-progress', 'resolved') DEFAULT 'reported',
  `reported_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `resolved_at` TIMESTAMP NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users_db`(`id`) ON DELETE CASCADE
);

-- Notifications table
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users_db`(`id`) ON DELETE CASCADE
);

-- Sample data for rooms
INSERT INTO `rooms` (`room_number`, `building`, `capacity`, `features`, `is_available`) VALUES
('R101', 'Main Library', 4, 'Whiteboard, Power outlets', TRUE),
('R102', 'Main Library', 8, 'Whiteboard, Smart board, Power outlets', TRUE),
('R201', 'Engineering Building', 6, 'Projector, Whiteboard, Power outlets', TRUE),
('R202', 'Engineering Building', 12, 'Projector, Computers, Power outlets', TRUE),
('R301', 'Science Building', 4, 'Whiteboard, Power outlets', TRUE);

-- Sample timetable data
INSERT INTO `timetable` (`course_code`, `day_of_week`, `start_time`, `end_time`, `room`, `lecturer`, `subject`) VALUES
('CS101', 'Monday', '09:00:00', '11:00:00', 'LT1', 'Dr. Smith', 'Introduction to Programming'),
('CS101', 'Wednesday', '09:00:00', '11:00:00', 'LT1', 'Dr. Smith', 'Introduction to Programming'),
('MTH201', 'Tuesday', '11:00:00', '13:00:00', 'LT2', 'Prof. Johnson', 'Calculus II'),
('MTH201', 'Thursday', '11:00:00', '13:00:00', 'LT2', 'Prof. Johnson', 'Calculus II'),
('PHY101', 'Monday', '14:00:00', '16:00:00', 'Lab3', 'Dr. Lee', 'Physics I'); 