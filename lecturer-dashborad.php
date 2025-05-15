<?php
session_start();
require 'config.php';

// Check if user is logged in and is a lecturer
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "lecturer") {
    header("Location: login.php");
    exit;
}

// Get user details from session
$name = $_SESSION["name"] ?? "Lecturer";
$email = $_SESSION["email"] ?? "";

// Get count of pending room bookings
$sql = "SELECT COUNT(*) FROM room_bookings WHERE status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$pending_bookings = $stmt->fetchColumn();

// Get count of total room bookings
$sql = "SELECT COUNT(*) FROM room_bookings";
$stmt = $conn->prepare($sql);
$stmt->execute();
$total_bookings = $stmt->fetchColumn();

// Get count of timetable entries
$sql = "SELECT COUNT(*) FROM timetable";
$stmt = $conn->prepare($sql);
$stmt->execute();
$timetable_entries = $stmt->fetchColumn();

// Fetch recent pending bookings
$sql = "SELECT rb.*, r.room_number, r.building, u.name as student_name 
        FROM room_bookings rb 
        JOIN rooms r ON rb.room_id = r.id 
        JOIN users_db u ON rb.user_id = u.id 
        WHERE rb.status = 'pending' 
        ORDER BY rb.booking_date ASC 
        LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->execute();
$recent_bookings = $stmt->fetchAll();

// Fetch all PDF timetables
$pdf_files = [
    ['name' => 'Class Timetable', 'file' => 'uploads/classTimetable.pdf'],
    ['name' => 'Semester Test Timetable', 'file' => 'uploads/semesterTest.pdf'],
    ['name' => 'Exam Timetable', 'file' => 'uploads/examTimetable.pdf']
];

// Check which PDFs exist
foreach ($pdf_files as &$pdf) {
    $pdf['exists'] = file_exists($pdf['file']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Dashboard | Smart Campus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .dashboard-header {
            background-color: #343a40;
            color: white;
            padding: 2rem 0;
        }
        
        .stat-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            color: white;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(45deg, #007bff, #00bcd4);
        }
        
        .bg-gradient-success {
            background: linear-gradient(45deg, #28a745, #84c991);
        }
        
        .bg-gradient-warning {
            background: linear-gradient(45deg, #ffc107, #ffdb7d);
        }
        
        .bg-gradient-info {
            background: linear-gradient(45deg, #17a2b8, #90caf9);
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #343a40;
            color: white;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 0.8rem 1rem;
            border-radius: 5px;
            margin-bottom: 0.5rem;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: white;
            background-color: #007bff;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Smart Campus</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="lecturer-dashborad.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="approve-bookings.php">Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="timetable-upload.php">Timetables</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="text-light me-3"><?php echo htmlspecialchars($name); ?></span>
                    <a href="logout.php" class="btn btn-outline-light">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container">
            <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
            <p class="lead">Manage classes, timetables, and student bookings from your dashboard.</p>
        </div>
    </div>

    <div class="container py-5">
        <!-- Stats Cards -->
        <div class="row mb-5">
            <div class="col-md-3 mb-4">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-gradient-primary me-3">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Pending Bookings</h6>
                            <h3 class="mb-0"><?php echo $pending_bookings; ?></h3>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <a href="approve-bookings.php" class="btn btn-sm btn-primary w-100">View All</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-gradient-success me-3">
                            <i class="fas fa-bookmark"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Bookings</h6>
                            <h3 class="mb-0"><?php echo $total_bookings; ?></h3>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <a href="approve-bookings.php" class="btn btn-sm btn-success w-100">View Details</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-gradient-warning me-3">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Timetable Entries</h6>
                            <h3 class="mb-0"><?php echo $timetable_entries; ?></h3>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <a href="timetable-upload.php" class="btn btn-sm btn-warning w-100">Manage Timetables</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon bg-gradient-info me-3">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Announcements</h6>
                            <h3 class="mb-0"><i class="fas fa-plus"></i></h3>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <button class="btn btn-sm btn-info w-100" data-bs-toggle="modal" data-bs-target="#announcementModal">
                            Post Announcement
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Recent Booking Requests -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Recent Booking Requests</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (count($recent_bookings) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Student</th>
                                            <th>Room</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_bookings as $booking): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($booking['student_name']); ?></td>
                                                <td><?php echo htmlspecialchars($booking['room_number']); ?></td>
                                                <td><?php echo date('M j, Y', strtotime($booking['booking_date'])); ?></td>
                                                <td>
                                                    <?php 
                                                        echo date('g:i A', strtotime($booking['start_time'])) . ' - ' . 
                                                             date('g:i A', strtotime($booking['end_time']));
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="approve-bookings.php" class="btn btn-sm btn-primary">Review</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3">No pending booking requests</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="approve-bookings.php" class="btn btn-outline-primary btn-sm">View All Requests</a>
                    </div>
                </div>
            </div>
            
            <!-- Timetable PDFs -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Timetable PDFs</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($pdf_files as $pdf): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-pdf <?php echo $pdf['exists'] ? 'text-danger' : 'text-muted'; ?> me-2"></i>
                                        <?php echo $pdf['name']; ?>
                                    </div>
                                    <?php if ($pdf['exists']): ?>
                                        <a href="<?php echo $pdf['file']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    <?php else: ?>
                                        <a href="timetable-upload.php" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-upload"></i> Upload
                                        </a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="timetable-upload.php" class="btn btn-outline-primary btn-sm w-100">Manage Timetables</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Quick Actions -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 mb-3 mb-md-0">
                                <a href="approve-bookings.php" class="text-decoration-none">
                                    <div class="p-4 rounded bg-light">
                                        <i class="fas fa-calendar-check text-primary mb-3" style="font-size: 2rem;"></i>
                                        <h6>Approve Bookings</h6>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="col-md-3 mb-3 mb-md-0">
                                <a href="timetable-upload.php" class="text-decoration-none">
                                    <div class="p-4 rounded bg-light">
                                        <i class="fas fa-upload text-success mb-3" style="font-size: 2rem;"></i>
                                        <h6>Upload Timetable</h6>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="col-md-3 mb-3 mb-md-0">
                                <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#announcementModal">
                                    <div class="p-4 rounded bg-light">
                                        <i class="fas fa-bullhorn text-warning mb-3" style="font-size: 2rem;"></i>
                                        <h6>Post Announcement</h6>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="col-md-3">
                                <a href="timetable.php" class="text-decoration-none">
                                    <div class="p-4 rounded bg-light">
                                        <i class="fas fa-table text-info mb-3" style="font-size: 2rem;"></i>
                                        <h6>View Timetable</h6>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcement Modal -->
    <div class="modal fade" id="announcementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Post Announcement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="messages.php" method="post">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="target_role" class="form-label">Send To</label>
                            <select class="form-select" id="target_role" name="target_role">
                                <option value="all">All Users</option>
                                <option value="student" selected>Students Only</option>
                                <option value="lecturer">Lecturers Only</option>
                                <option value="admin">Admins Only</option>
                            </select>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" name="post_announcement" class="btn btn-primary">Send Announcement</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
