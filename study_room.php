<?php
// Connect to database (assuming you're using MySQLi)
$conn = mysqli_connect('localhost', 'root', '', 'smartcampus.db');

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Fetch available rooms
$sql = "SELECT * FROM rooms";  // Adjust table name if needed
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Study Room Booking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    .hero {
        background: url('images/study_room.jpg') no-repeat center center;
        background-size: cover;
      height: 60vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      text-shadow: 0 2px 4px rgba(0,0,0,0.8);
    }

    .search-box {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      position: relative;
      top: -50px;
      z-index: 1;
    }

    .btn-circle {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0;
    }

    .btn-circle i {
      font-size: 18px;
    }

    .form-label {
      font-weight: 500;
    }
  </style>
</head>
<body>

  <!-- Banner Section -->
  <section class="hero">
    <div class="text-center">
      <h1 class="display-5">Book a Study Room</h1>
      <p class="lead">Quiet. Spacious. Ready when you are.</p>
    </div>
  </section>

  <!-- Search Form -->
  <div class="container search-box">
    <form class="row g-3 align-items-end">
      
      <div class="col-md-3">
        <label class="form-label">Campus or Building</label>
        <input type="text" class="form-control" placeholder="e.g. Main Library" name="location">
      </div>

      <div class="col-md-3">
        <label class="form-label">Select Date</label>
        <input type="date" class="form-control" name="date">
      </div>

      <div class="col-md-2">
        <label class="form-label">Time Slot</label>
        <input type="text" class="form-control" placeholder="10:00 - 12:00" name="time_slot">
      </div>

      <div class="col-md-2">
        <label class="form-label">Group Size</label>
        <input type="number" class="form-control" min="1" value="1" name="group_size">
      </div>

      <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-primary btn-circle">
          <i class="fas fa-search"></i>
        </button>
      </div>

    </form>
  </div>

  <div class="container mt-5">
    <h4>Available Rooms</h4>
    <div class="row" id="room-results">
      <!-- PHP will load available study rooms here -->
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



