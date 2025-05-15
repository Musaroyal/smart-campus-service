<?php
// Include database connection from config.php
require 'config.php';

// Fetch available rooms
$sql = "SELECT * FROM rooms WHERE is_available = TRUE";
$stmt = $conn->prepare($sql);
$stmt->execute();
$rooms = $stmt->fetchAll();

// Handle booking submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_room'])) {
    if (!isset($_SESSION['user_id'])) {
        $error = "Please log in to book a room";
    } else {
        $user_id = $_SESSION['user_id'];
        $room_id = $_POST['room_id'];
        $booking_date = $_POST['date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $purpose = $_POST['purpose'];
        $group_size = $_POST['group_size'];
        
        // Check if room is already booked for that time
        $check_sql = "SELECT * FROM room_bookings 
                      WHERE room_id = ? AND booking_date = ? 
                      AND ((start_time <= ? AND end_time >= ?) 
                      OR (start_time <= ? AND end_time >= ?) 
                      OR (start_time >= ? AND end_time <= ?))
                      AND status != 'rejected'";
                      
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->execute([$room_id, $booking_date, $start_time, $start_time, $end_time, $end_time, $start_time, $end_time]);
        
        if ($check_stmt->rowCount() > 0) {
            $error = "This room is already booked for the selected time slot";
        } else {
            // Insert booking
            $insert_sql = "INSERT INTO room_bookings (user_id, room_id, booking_date, start_time, end_time, purpose, group_size) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            
            if ($insert_stmt->execute([$user_id, $room_id, $booking_date, $start_time, $end_time, $purpose, $group_size])) {
                $success = "Your booking has been submitted and is pending approval";
            } else {
                $error = "An error occurred while submitting your booking";
            }
        }
    }
}
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
    
    .room-card {
      border-radius: 10px;
      transition: transform 0.3s;
      margin-bottom: 20px;
    }
    
    .room-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form class="row g-3 align-items-end" method="get">
      <div class="col-md-3">
        <label class="form-label">Campus or Building</label>
        <select class="form-select" name="building">
          <option value="">All Buildings</option>
          <option value="Main Library">Main Library</option>
          <option value="Engineering Building">Engineering Building</option>
          <option value="Science Building">Science Building</option>
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label">Select Date</label>
        <input type="date" class="form-control" name="date" min="<?php echo date('Y-m-d'); ?>" required>
      </div>

      <div class="col-md-2">
        <label class="form-label">Start Time</label>
        <input type="time" class="form-control" name="start_time" required>
      </div>

      <div class="col-md-2">
        <label class="form-label">End Time</label>
        <input type="time" class="form-control" name="end_time" required>
      </div>

      <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-search"></i> Find Rooms
        </button>
      </div>
    </form>
  </div>

  <div class="container mt-5">
    <h4>Available Rooms</h4>
    
    <div class="row" id="room-results">
      <?php 
      if (count($rooms) > 0):
        foreach ($rooms as $room): 
      ?>
        <div class="col-lg-4 col-md-6">
          <div class="card room-card">
            <div class="card-body">
              <h5 class="card-title"><?php echo $room['room_number']; ?></h5>
              <h6 class="card-subtitle mb-2 text-muted"><?php echo $room['building']; ?></h6>
              <p class="card-text">
                <i class="fas fa-users me-2"></i> Capacity: <?php echo $room['capacity']; ?> people<br>
                <i class="fas fa-list-ul me-2"></i> Features: <?php echo $room['features']; ?>
              </p>
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal<?php echo $room['id']; ?>">
                Book Now
              </button>
            </div>
          </div>
          
          <!-- Booking Modal -->
          <div class="modal fade" id="bookingModal<?php echo $room['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Book Room <?php echo $room['room_number']; ?></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form method="post" action="">
                    <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                    
                    <div class="mb-3">
                      <label class="form-label">Date</label>
                      <input type="date" class="form-control" name="date" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                      <label class="form-label">Start Time</label>
                      <input type="time" class="form-control" name="start_time" required>
                    </div>
                    
                    <div class="mb-3">
                      <label class="form-label">End Time</label>
                      <input type="time" class="form-control" name="end_time" required>
                    </div>
                    
                    <div class="mb-3">
                      <label class="form-label">Purpose</label>
                      <input type="text" class="form-control" name="purpose" placeholder="e.g. Group Study, Project Meeting" required>
                    </div>
                    
                    <div class="mb-3">
                      <label class="form-label">Group Size</label>
                      <input type="number" class="form-control" name="group_size" min="1" max="<?php echo $room['capacity']; ?>" value="1" required>
                    </div>
                    
                    <div class="d-grid">
                      <button type="submit" name="book_room" class="btn btn-primary">Submit Booking</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php 
        endforeach;
      else:
      ?>
        <div class="col-12">
          <div class="alert alert-info">No rooms available. Please try different search criteria.</div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



