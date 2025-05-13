<?php
// Connect to database (assuming you're using MySQLi)
$conn = mysqli_connect('localhost', 'root', '', 'smartcampus.db');

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Initialize query
$sql = "SELECT * FROM rooms";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $location = $_POST['location'];
    $other_location = $_POST['other_location'] ?? '';
    $final_location = ($location === 'Other') ? $other_location : $location;

    $date = $_POST['date'];
    $time_slot = $_POST['time_slot'];
    $group_size = $_POST['group_size'];

    // Build query with filters
    $sql = "SELECT * FROM rooms WHERE location = '$final_location' AND date = '$date' AND time_slot = '$time_slot' AND group_size >= $group_size AND booked = 0";
}

// Execute query
$result = mysqli_query($conn, $sql);

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (mysqli_num_rows($result) === 0) {
        $message = "No available rooms found for the selected criteria. The room may be already booked or not available.";
        $messageType = "warning";
    } else {
        $message = "Rooms found! Select one from the available options below.";
        $messageType = "success";
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
<form class="row g-3 align-items-end" method="GET" action="reserve.php">

    <!-- Campus or Building Dropdown -->
    <div class="col-md-3">
      <label class="form-label">Campus or Building</label>
      <select class="form-select" name="location" id="locationDropdown" onchange="toggleOtherField()">
        <optgroup label="Study Rooms">
          <option value="Building 6">Building 6</option>
          <option value="Building 5">Building 5</option>
          <option value="Building 4">Building 4</option>
          <option value="Building 8">Building 8</option>
        </optgroup>
        <optgroup label="Labs">
          <option value="15G01">15G01</option>
          <option value="15G30">15G30</option>
          <option value="16G01">16G01</option>
          <option value="Other">Other</option>
        </optgroup>      
      </select>
    </div>

    <!-- Other Field (Hidden unless "Other" selected) -->
    <div class="col-md-3" id="otherLocationField" style="display: none;">
      <label class="form-label">Specify Other</label>
      <input type="text" class="form-control" name="other_location" placeholder="Specify location">
    </div>

    <!-- Select Date -->
    <div class="col-md-2">
      <label class="form-label">Select Date</label>
      <input type="date" class="form-control" name="date">
    </div>

 <div class="col-md-2">
  <label class="form-label">Time Slot</label>
  <div class="d-flex">
    <input type="time" class="form-control me-1" id="startTime" onchange="updateTimeSlot()" />
    <input type="time" class="form-control" id="endTime" onchange="updateTimeSlot()" />
  </div>
  <input type="hidden" name="time_slot" id="timeSlot" />
</div>

    <!-- Group Size -->
    <div class="col-md-2">
      <label class="form-label">Group Size</label>
      <input type="number" class="form-control" min="1" value="1" name="group_size">
    </div>

    <!-- Submit Button -->
    <div class="col-md-2 d-grid">
      <button type="submit" class="btn btn-primary">
        <i class="fas fa-search"></i>
      </button>
    </div>

  </form>
</div>


<h4>Available Rooms</h4>

<?php if (!empty($message)): ?>
  <div class="alert alert-<?php echo $messageType; ?>"><?php echo $message; ?></div>
<?php endif; ?>


<div class="row" id="room-results">
<?php
// <?php
if (isset($result) && mysqli_num_rows($result) > 0) {
    $rooms = [];

    // Fetch all rows into an array
    while ($row = mysqli_fetch_assoc($result)) {
        $rooms[] = $row;
    }

    // Shuffle the array to randomize the order
    shuffle($rooms);

    // Take only the first 3 rooms
    $rooms = array_slice($rooms, 0, 3);

    // Display the selected rooms
    foreach ($rooms as $row) {
        echo '<div class="col-md-4 mb-3">';
        echo '<div class="card">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . htmlspecialchars($row['location']) . '</h5>';
        echo '<p class="card-text">Date: ' . $row['date'] . '</p>';
        echo '<p class="card-text">Time Slot: ' . $row['time_slot'] . '</p>';
        echo '<p class="card-text">Group Size: ' . $row['group_size'] . '</p>';
        echo '<a href="reserve.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm mt-2">Reserve</a>';

        echo '</div></div></div>';
    }
}
?>

</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>   


  <script>
  function toggleOtherField() {
    var dropdown = document.getElementById("locationDropdown");
    var otherField = document.getElementById("otherLocationField");
    if (dropdown.value === "Other") {
      otherField.style.display = "block";
    } else {
      otherField.style.display = "none";
    }
  }

  function updateTimeSlot() {
    const start = document.getElementById("startTime").value;
    const end = document.getElementById("endTime").value;
    const timeSlot = document.getElementById("timeSlot");
    if (start && end) {
      timeSlot.value = `${start} - ${end}`;
    }
  }


</script>

</body>
</html>



