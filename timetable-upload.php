<?php
session_start();
require 'config.php';

// Check if user is logged in and is admin or lecturer
if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] !== "admin" && $_SESSION["role"] !== "lecturer")) {
    header("Location: login.php");
    exit;
}

// Handle upload actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] === 'add_timetable_entry') {
        $course_code = $_POST['course_code'];
        $day_of_week = $_POST['day_of_week'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $room = $_POST['room'];
        $lecturer = $_POST['lecturer'];
        $subject = $_POST['subject'];
        
        // Validation
        if (strtotime($end_time) <= strtotime($start_time)) {
            $error = "End time must be after start time";
        } else {
            $sql = "INSERT INTO timetable (course_code, day_of_week, start_time, end_time, room, lecturer, subject) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            if ($stmt->execute([$course_code, $day_of_week, $start_time, $end_time, $room, $lecturer, $subject])) {
                $success = "Timetable entry added successfully!";
            } else {
                $error = "An error occurred while adding the timetable entry.";
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete_entry' && isset($_POST['entry_id'])) {
        $entry_id = $_POST['entry_id'];
        
        $sql = "DELETE FROM timetable WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute([$entry_id])) {
            $success = "Timetable entry deleted successfully!";
        } else {
            $error = "An error occurred while deleting the timetable entry.";
        }
    } elseif (isset($_FILES['timetable_pdf'])) {
        $upload_dir = "uploads/";
        $type = $_POST['pdf_type'];
        
        // Make sure the uploads directory exists
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Define target file name based on type
        if ($type === 'class') {
            $target_file = $upload_dir . "classTimetable.pdf";
        } elseif ($type === 'exam') {
            $target_file = $upload_dir . "examTimetable.pdf";
        } elseif ($type === 'test') {
            $target_file = $upload_dir . "semesterTest.pdf";
        } else {
            $error = "Invalid PDF type selected.";
        }
        
        if (empty($error)) {
            // Always set file_type before checks
            $file_type = strtolower(pathinfo($_FILES["timetable_pdf"]["name"], PATHINFO_EXTENSION));
            // Check for upload errors
            if ($_FILES["timetable_pdf"]["error"] !== UPLOAD_ERR_OK) {
                $error = "Upload error code: " . $_FILES["timetable_pdf"]["error"];
            } elseif ($file_type != "pdf") {
                $error = "Only PDF files are allowed.";
            } else {
                // Upload the file
                if (!move_uploaded_file($_FILES["timetable_pdf"]["tmp_name"], $target_file)) {
                    $error = "Failed to move uploaded file. Check directory permissions for the uploads folder.";
                } else {
                    $success = "Timetable PDF uploaded successfully!";
                }
            }
        }
    }
}

// Fetch all timetable entries
$sql = "SELECT * FROM timetable ORDER BY course_code, day_of_week, start_time";
$stmt = $conn->prepare($sql);
$stmt->execute();
$timetable_entries = $stmt->fetchAll();

// Get list of unique course codes
$course_codes = [];
foreach ($timetable_entries as $entry) {
    if (!in_array($entry['course_code'], $course_codes)) {
        $course_codes[] = $entry['course_code'];
    }
}
sort($course_codes);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable Management</title>
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
                        <a class="nav-link" href="approve-bookings.php">Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="timetable-upload.php">Timetables</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="logout.php" class="btn btn-outline-light">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="mb-4">Timetable Management</h1>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Add Timetable Entry</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            <input type="hidden" name="action" value="add_timetable_entry">
                            
                            <div class="mb-3">
                                <label for="course_code" class="form-label">Course Code</label>
                                <input type="text" class="form-control" id="course_code" name="course_code" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="day_of_week" class="form-label">Day</label>
                                <select class="form-select" id="day_of_week" name="day_of_week" required>
                                    <option value="">Select Day</option>
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_time" class="form-label">Start Time</label>
                                    <input type="time" class="form-control" id="start_time" name="start_time" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="end_time" class="form-label">End Time</label>
                                    <input type="time" class="form-control" id="end_time" name="end_time" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="room" class="form-label">Room</label>
                                <input type="text" class="form-control" id="room" name="room" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="lecturer" class="form-label">Lecturer</label>
                                <input type="text" class="form-control" id="lecturer" name="lecturer" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Add Timetable Entry</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Upload Timetable PDF</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="pdf_type" class="form-label">Timetable Type</label>
                                <select class="form-select" id="pdf_type" name="pdf_type" required>
                                    <option value="">Select Type</option>
                                    <option value="class">Class Timetable</option>
                                    <option value="test">Semester Test Timetable</option>
                                    <option value="exam">Exam Timetable</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="timetable_pdf" class="form-label">PDF File</label>
                                <input class="form-control" type="file" id="timetable_pdf" name="timetable_pdf" accept=".pdf" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Upload PDF</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Available PDF Timetables</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <?php
                            $pdf_files = [
                                ['name' => 'Class Timetable', 'file' => 'uploads/classTimetable.pdf'],
                                ['name' => 'Semester Test Timetable', 'file' => 'uploads/semesterTest.pdf'],
                                ['name' => 'Exam Timetable', 'file' => 'uploads/examTimetable.pdf']
                            ];
                            
                            foreach ($pdf_files as $pdf) {
                                $exists = file_exists($pdf['file']);
                                $icon_class = $exists ? 'text-success' : 'text-muted';
                                $link = $exists ? "href='{$pdf['file']}' target='_blank'" : "";
                                
                                echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                                echo "<span><i class='fas fa-file-pdf me-2 {$icon_class}'></i> {$pdf['name']}</span>";
                                
                                if ($exists) {
                                    echo "<a {$link} class='btn btn-sm btn-outline-primary'>View</a>";
                                } else {
                                    echo "<span class='badge bg-secondary'>Not Uploaded</span>";
                                }
                                
                                echo "</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Current Timetable Entries</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="filter_course" class="form-label">Filter by Course Code</label>
                    <select class="form-select" id="filter_course">
                        <option value="">All Courses</option>
                        <?php foreach ($course_codes as $code): ?>
                            <option value="<?php echo $code; ?>"><?php echo $code; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="timetable_table">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Room</th>
                                <th>Lecturer</th>
                                <th>Subject</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($timetable_entries as $entry): ?>
                                <tr data-course="<?php echo htmlspecialchars($entry['course_code']); ?>">
                                    <td><?php echo htmlspecialchars($entry['course_code']); ?></td>
                                    <td><?php echo htmlspecialchars($entry['day_of_week']); ?></td>
                                    <td>
                                        <?php 
                                            echo date('h:i A', strtotime($entry['start_time'])) . ' - ' . 
                                                 date('h:i A', strtotime($entry['end_time']));
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($entry['room']); ?></td>
                                    <td><?php echo htmlspecialchars($entry['lecturer']); ?></td>
                                    <td><?php echo htmlspecialchars($entry['subject']); ?></td>
                                    <td>
                                        <form method="post" action="" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                                            <input type="hidden" name="action" value="delete_entry">
                                            <input type="hidden" name="entry_id" value="<?php echo $entry['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (empty($timetable_entries)): ?>
                    <p class="text-center text-muted py-3">No timetable entries found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Course filter functionality
        document.getElementById('filter_course').addEventListener('change', function() {
            const selectedCourse = this.value;
            const tableRows = document.querySelectorAll('#timetable_table tbody tr');
            
            tableRows.forEach(row => {
                const courseName = row.getAttribute('data-course');
                if (selectedCourse === '' || courseName === selectedCourse) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
