<?php
session_start();
require 'config.php';

// Check if user is logged in and is admin or lecturer
if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] !== "admin" && $_SESSION["role"] !== "lecturer")) {
    header("Location: login.php");
    exit;
}

// Handle approval/rejection actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && isset($_POST['booking_id'])) {
        $booking_id = $_POST['booking_id'];
        $action = $_POST['action'];
        $status = ($action === 'approve') ? 'approved' : 'rejected';
        
        $sql = "UPDATE room_bookings SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute([$status, $booking_id])) {
            $success = "Booking has been " . $status . " successfully!";
        } else {
            $error = "An error occurred. Please try again.";
        }
    }
}

// Fetch all pending bookings
$sql = "SELECT rb.*, r.room_number, r.building, u.name, u.email, u.student_no 
        FROM room_bookings rb 
        JOIN rooms r ON rb.room_id = r.id 
        JOIN users_db u ON rb.user_id = u.id 
        WHERE rb.status = 'pending' 
        ORDER BY rb.booking_date ASC, rb.start_time ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$bookings = $stmt->fetchAll();

// Fetch approved and rejected bookings
$sql = "SELECT rb.*, r.room_number, r.building, u.name, u.email, u.student_no 
        FROM room_bookings rb 
        JOIN rooms r ON rb.room_id = r.id 
        JOIN users_db u ON rb.user_id = u.id 
        WHERE rb.status != 'pending' 
        ORDER BY rb.booking_date DESC, rb.start_time DESC 
        LIMIT 20";
$stmt = $conn->prepare($sql);
$stmt->execute();
$processed_bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Room Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Smart Campus</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if ($_SESSION["role"] === "admin"): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php">Dashboard</a>
                        </li>
                    <?php elseif ($_SESSION["role"] === "lecturer"): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="lecturer-dashborad.php">Dashboard</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="approve-bookings.php">Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="timetable-upload.php">Timetables</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="logout.php" class="btn btn-outline-light">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="mb-4">Room Booking Management</h1>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="card mb-5">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Pending Approvals</h5>
            </div>
            <div class="card-body">
                <?php if (count($bookings) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Room</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Purpose</th>
                                    <th>Group Size</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td>
                                            <?php echo htmlspecialchars($booking['name']); ?><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($booking['student_no']); ?></small>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($booking['room_number']); ?><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($booking['building']); ?></small>
                                        </td>
                                        <td><?php echo date('M j, Y', strtotime($booking['booking_date'])); ?></td>
                                        <td>
                                            <?php 
                                                echo date('h:i A', strtotime($booking['start_time'])) . ' - ' . 
                                                     date('h:i A', strtotime($booking['end_time']));
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($booking['purpose']); ?></td>
                                        <td><?php echo $booking['group_size']; ?></td>
                                        <td>
                                            <form method="post" action="" class="d-inline">
                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                            <form method="post" action="" class="d-inline ms-1">
                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted py-3">No pending booking requests.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Recent Processing History</h5>
            </div>
            <div class="card-body">
                <?php if (count($processed_bookings) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Room</th>
                                    <th>Date & Time</th>
                                    <th>Purpose</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($processed_bookings as $booking): ?>
                                    <tr>
                                        <td>
                                            <?php echo htmlspecialchars($booking['name']); ?><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($booking['student_no']); ?></small>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($booking['room_number']); ?><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($booking['building']); ?></small>
                                        </td>
                                        <td>
                                            <?php echo date('M j, Y', strtotime($booking['booking_date'])); ?><br>
                                            <small class="text-muted">
                                                <?php 
                                                    echo date('h:i A', strtotime($booking['start_time'])) . ' - ' . 
                                                         date('h:i A', strtotime($booking['end_time']));
                                                ?>
                                            </small>
                                        </td>
                                        <td><?php echo htmlspecialchars($booking['purpose']); ?></td>
                                        <td>
                                            <?php if ($booking['status'] === 'approved'): ?>
                                                <span class="badge bg-success">Approved</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Rejected</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted py-3">No processed bookings yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>