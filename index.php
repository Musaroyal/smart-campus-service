<?php
session_start();


if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  $student_no = $_POST["student_no"];
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Smart Campus Services Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
    }
    .tab {
      margin-top: 20px;
    }
    .booking-status {
      text-transform: capitalize;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Smart Campus Services Portal</h2>
      <div class="d-flex align-items-center">
        <img src="<?= $_SESSION['avatar'] ?? 'default-avatar.png' ?>" class="avatar me-2" alt="Avatar">
        <strong><?= $_SESSION['email'] ?? 'Student' ?></strong>
      </div>
    </div>

    <div class="tab mt-4">
      <ul class="nav nav-tabs" id="portalTabs">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#timetable">Timetable</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#bookings">Book Room</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#announcements">Announcements</a></li>
      </ul>

      <div class="tab-content p-3 border bg-white">
        <!-- Timetable Tab -->
        <div id="timetable" class="tab-pane fade show active">
          <h5>Timetable</h5>
          <table class="table table-bordered">
            <thead><tr><th>Time</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th></tr></thead>
            <tbody>
              <?php
              $times = ['8:00 AM', '9:00 AM', '10:00 AM', '11:00 AM'];
              foreach ($times as $time) {
                echo "<tr><td>$time</td>";
                for ($i = 1; $i <= 5; $i++) {
                  // Simulate loading timetable from DB
                  echo "<td>";
                  // Replace with DB data like: SELECT * FROM timetable WHERE day='$i' AND time='$time' AND course_code='$_SESSION[course_code]'
                  echo ($time == '9:00 AM' && $i == 3) ? 'ICT Lecture' : '';
                  echo "</td>";
                }
                echo "</tr>";
              }
              ?>
            </tbody>
          </table>
        </div>

        <!-- Book Room Tab -->
        <div id="bookings" class="tab-pane fade">
          <h5>Book a Room</h5>
          <form method="post" action="book_room.php">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label>Student Number</label>
                <input type="text" class="form-control" name="student_no" value="<?= $_SESSION['student_no'] ?>" readonly>
              </div>
              <div class="col-md-6 mb-3">
                <label>Name</label>
                <input type="text" class="form-control" name="name" value="<?= $_SESSION['name'] ?>" readonly>
              </div>
              <div class="col-md-6 mb-3">
                <label>Course Name</label>
                <input type="text" class="form-control" name="course" value="<?= $_SESSION['course_name'] ?>" readonly>
              </div>
              <div class="col-md-6 mb-3">
                <label>Course Code</label>
                <input type="text" class="form-control" name="course_code" value="<?= $_SESSION['course_code'] ?>" readonly>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </form>

          <h5 class="mt-4">Room Bookings</h5>
          <table class="table table-striped">
            <thead><tr><th>Room</th><th>Date</th><th>Status</th></tr></thead>
            <tbody>
              <?php
              // Replace this with a DB query for real data
              $bookings = [
                ['room' => 'Library Room 1', 'date' => '2025-05-06', 'status' => 'approved'],
                ['room' => 'Lab 2', 'date' => '2025-05-07', 'status' => 'pending'],
              ];
              foreach ($bookings as $booking) {
                echo "<tr>
                        <td>{$booking['room']}</td>
                        <td>{$booking['date']}</td>
                        <td class='booking-status'>{$booking['status']}</td>
                      </tr>";
              }
              ?>
            </tbody>
          </table>
        </div>

        <!-- Announcements Tab -->
        <div id="announcements" class="tab-pane fade">
          <h5>Announcements</h5>
          <ul class="list-group">
            <?php
            // Replace with DB data like: SELECT * FROM announcements ORDER BY date DESC
            $announcements = [
              ['title' => 'Exam Timetable Released', 'author' => 'Admin', 'date' => '2025-05-01'],
              ['title' => 'Lecture Cancelled on Wed', 'author' => 'Lecturer A', 'date' => '2025-04-30']
            ];
            foreach ($announcements as $note) {
              echo "<li class='list-group-item'>
                      <strong>{$note['title']}</strong><br>
                      <small>By {$note['author']} on {$note['date']}</small>
                    </li>";
            }
            ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
