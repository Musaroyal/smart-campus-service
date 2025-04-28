<?php 
// This could be a session check to ensure the user is logged in
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Campus Portal</title>
  <!-- Link to Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Custom styles */
    .sidebar {
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      background-color: #333;
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
      background-color: #575757;
    }
    .content-area {
      margin-left: 260px;
      padding: 20px;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="text-center">
      <h4 class="text-white">Hello, <?php echo $_SESSION['student_name']; ?></h4>
      <p class="text-white"><?php echo $_SESSION['student_no']; ?> - <?php echo $_SESSION['course']; ?></p>
    </div>
    <a href="#" id="timetable-link">Timetable</a>
    <a href="#" id="profile-link">Profile</a>
    <a href="#" id="messages-link">Messages</a>
    <a href="logout.php">Logout</a>
  </div>

  <!-- Content Area -->
  <div class="content-area" id="content-area">
    <!-- Dynamic content will be loaded here -->
    <h1>Welcome to the Smart Campus Portal</h1>
    <p>Click on the sidebar items to view your timetable, profile, and messages.</p>
  </div>

  <!-- Bootstrap JS and jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // JavaScript to dynamically load content from PHP files
    $(document).ready(function() {
      $('#timetable-link').click(function() {
        $('#content-area').load('timetable.php');
      });

      $('#profile-link').click(function() {
        $('#content-area').load('profile.php');
      });

      $('#messages-link').click(function() {
        $('#content-area').load('messages.php');
      });
    });
  </script>
  
</body>
</html>
