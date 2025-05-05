<?php
session_start();


// Include database configuration to handle avatar uploads
require 'config.php';

// Get user info from the database
$stmt = $conn->prepare("SELECT * FROM users_db WHERE id = ?");
$stmt->execute([$_SESSION['name']]);
$user = $stmt->fetch();

// Set the default page (home page) based on GET parameter
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Campus Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .sidebar {
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      background-color: rgb(122, 130, 234);
      color: white;
      padding-top: 20px;
    }
    .sidebar a {
      color: white;
      padding: 10px;
      text-decoration: none;
      display: block;
    }
    .sidebar a:hover {
      background-color:rgb(33, 45, 208);
    }
    .content-area {
      margin-left: 260px;
      padding: 20px;
    }
    .profile-avatar {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="text-center">
      <!-- Display user profile info -->
      <h4 class="text-white"><?php echo $_SESSION['email']; ?></h4>
   

      <!-- Form to upload avatar -->
      
    </div>
    <a href="?page=timetable">Timetable</a>
    <a href="?page=booking">Booking</a>
    <a href="?page=announcements">Announcements</a>
    <a href="logout.php">Logout</a>
  </div>

  <!-- Content Area -->
  <div class="content-area" id="content-area">
    <?php
      // Include different content based on the selected page
      switch ($page) {
          case 'timetable':
              include 'timetable.php';
              break;
          case 'booking':
              include 'booking.php';
              break;
          case 'announcements':
              include 'announcements.php';
              break;
          default:
              echo '<h1>Welcome to the Smart Campus Portal</h1>';
              echo '<p>Click on the sidebar items to view your timetable, booking, and announcements.</p>';
              break;
      }
    ?>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
