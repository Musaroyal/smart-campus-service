# Smart Campus Services Portal

A comprehensive web-based system for managing campus services including room bookings, timetables, maintenance requests, and more.

## Features

- **User Authentication**: Role-based system for students, lecturers, and administrators
- **Study Room Booking**: Book study spaces on campus with approval workflow
- **Timetable Viewer**: View class schedules with daily and weekly views
- **Maintenance Request System**: Report and track maintenance issues across campus
- **Notification System**: Stay informed with important announcements and updates

## Setup Instructions

### Prerequisites

- XAMPP (or similar AMP stack) with PHP 7.4+ and MySQL
- Web browser

### Installation Steps

1. **Clone/Download the Project**
   - Clone or download this repository to your XAMPP htdocs folder:
   - Path: `/opt/lampp/htdocs/SFG-Lecturer/smart-campus-service/`

2. **Start XAMPP Services**
   - Start Apache and MySQL services from the XAMPP control panel

3. **Database Setup**
   - Navigate to the project in your browser:
   ```
   http://localhost/SFG-Lecturer/smart-campus-service/db_init.php
   ```
   - This will automatically create the database and required tables

4. **Access the Application**
   - Open the following URL in your browser:
   ```
   http://localhost/SFG-Lecturer/smart-campus-service/
   ```

5. **Default Login Credentials**
   
   *Admin:*
   - Email: admin@campus.edu
   - Password: admin123

   To create a student account, use the Sign Up page.

## Project Structure

- `index.php` - Landing page
- `login.php` - User login
- `signup.php` - New user registration
- `student.php` - Student dashboard
- `lecturer-dashborad.php` - Lecturer dashboard
- `study_room.php` - Study room booking
- `timetable.php` - Timetable view
- `maintenance.php` - Maintenance request system
- `config.php` - Database connection settings
- `db_init.php` - Database initialization script

## Database Tables

- `users_db` - User accounts and roles
- `rooms` - Available study rooms and facilities
- `room_bookings` - Study room reservation records
- `timetable` - Course schedules
- `maintenance_requests` - Campus maintenance issues
- `notifications` - System notifications and announcements

## Technologies Used

- PHP 7.4+
- MySQL
- HTML5
- CSS3
- Bootstrap 5
- JavaScript

## License

[MIT License](LICENSE) 