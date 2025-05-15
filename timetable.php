<?php
session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo "<div class='alert alert-danger'>Please log in to view your timetable.</div>";
    exit;
}

// Get course code from session if it's a student
$course_code = isset($_SESSION["course_code"]) ? $_SESSION["course_code"] : null;

// Add Back to Dashboard button
$role = isset($_SESSION["role"]) ? $_SESSION["role"] : null;
$dashboard_url = '#';
if ($role === 'admin') {
    $dashboard_url = 'admin.php';
} elseif ($role === 'lecturer') {
    $dashboard_url = 'lecturer-dashborad.php';
} else {
    $dashboard_url = 'student.php'; // Change if your student dashboard has a different filename
}
echo "<div class='mb-3'><a href='$dashboard_url' class='btn btn-secondary'>&larr; Back to Dashboard</a></div>";

// Logic to switch between daily and weekly views
$view = isset($_GET['view']) ? $_GET['view'] : 'weekly';
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$currentDay = isset($_GET['day']) ? $_GET['day'] : date('l'); // Default to today

// If not in the days array, default to Monday
if (!in_array($currentDay, $days)) {
    $currentDay = 'Monday';
}

// Fetch timetable data
if ($course_code) {
    // For students - filter by their course code
    $sql = "SELECT * FROM timetable WHERE course_code = ? ORDER BY day_of_week, start_time";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$course_code]);
} else {
    // For all users or if no course code is set
    $sql = "SELECT * FROM timetable ORDER BY day_of_week, start_time";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

$timetable_data = $stmt->fetchAll();

// Organize data by day
$timetable_by_day = [];
foreach ($days as $day) {
    $timetable_by_day[$day] = [];
}

foreach ($timetable_data as $class) {
    $day = $class['day_of_week'];
    if (isset($timetable_by_day[$day])) {
        $timetable_by_day[$day][] = $class;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container-fluid py-3">
    <h2 class="mb-4">📅 Class Timetable</h2>
    
    <!-- View Toggle Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="btn-group" role="group">
            <a href="?view=daily&day=<?php echo $currentDay; ?>" class="btn btn-outline-primary <?php echo $view === 'daily' ? 'active' : ''; ?>">
                Daily View
            </a>
            <a href="?view=weekly" class="btn btn-outline-primary <?php echo $view === 'weekly' ? 'active' : ''; ?>">
                Weekly View
            </a>
        </div>
        
        <?php if ($view === 'daily'): ?>
        <div class="btn-group">
            <?php foreach ($days as $day): ?>
            <a href="?view=daily&day=<?php echo $day; ?>" 
               class="btn btn-outline-secondary <?php echo $currentDay === $day ? 'active' : ''; ?>">
                <?php echo substr($day, 0, 3); ?>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <div>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="fas fa-upload"></i> View PDF Timetables
            </a>
        </div>
    </div>
    
    <?php if ($view === 'daily'): ?>
        <!-- Daily View -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><?php echo $currentDay; ?>'s Schedule</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($timetable_by_day[$currentDay])): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Subject</th>
                                    <th>Room</th>
                                    <th>Lecturer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($timetable_by_day[$currentDay] as $class): ?>
                                <tr>
                                    <td>
                                        <?php 
                                        echo date('h:i A', strtotime($class['start_time'])) . ' - ' . 
                                             date('h:i A', strtotime($class['end_time']));
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($class['subject']); ?></td>
                                    <td><?php echo htmlspecialchars($class['room']); ?></td>
                                    <td><?php echo htmlspecialchars($class['lecturer']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center py-3">No classes scheduled for <?php echo $currentDay; ?>.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <!-- Weekly View -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Time</th>
                        <?php foreach ($days as $day): ?>
                            <th><?php echo $day; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Create time slots (8 AM to 6 PM in 1-hour increments)
                    $start_hour = 8;
                    $end_hour = 18;
                    
                    for ($hour = $start_hour; $hour < $end_hour; $hour++) {
                        $time_slot = sprintf('%02d:00:00', $hour);
                        $next_slot = sprintf('%02d:00:00', $hour + 1);
                        $display_time = date('h:i A', strtotime($time_slot)) . ' - ' . date('h:i A', strtotime($next_slot));
                        
                        echo "<tr>";
                        echo "<td class='bg-light'>{$display_time}</td>";
                        
                        foreach ($days as $day) {
                            echo "<td>";
                            $classes_in_slot = array_filter($timetable_by_day[$day], function($class) use ($time_slot, $next_slot) {
                                return ($class['start_time'] <= $time_slot && $class['end_time'] > $time_slot) ||
                                       ($class['start_time'] >= $time_slot && $class['start_time'] < $next_slot);
                            });
                            
                            foreach ($classes_in_slot as $class) {
                                $start = date('h:i A', strtotime($class['start_time']));
                                $end = date('h:i A', strtotime($class['end_time']));
                                
                                echo "<div class='p-2 mb-1 bg-primary text-white rounded'>";
                                echo "<div class='fw-bold'>{$class['subject']}</div>";
                                echo "<div class='small'>{$start} - {$end}</div>";
                                echo "<div class='small'>Room: {$class['room']}</div>";
                                echo "</div>";
                            }
                            
                            echo "</td>";
                        }
                        
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- PDF Timetables Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PDF Timetables</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row text-center mb-4">
                    <!-- Class Timetable Block -->
                    <div class="col-md-4 mb-3">
                        <div onclick="loadPDF('uploads/classTimetable.pdf')" class="card text-white" style="background-color: #0d47a1; height: 120px; cursor: pointer;">
                            <div class="card-body d-flex flex-column justify-content-center">
                                <div class="mb-2" style="font-size: 24px;">📚</div>
                                <h6 class="card-title">Class Timetable</h6>
                            </div>
                        </div>
                    </div>

                    <!-- Semester Test Timetable Block -->
                    <div class="col-md-4 mb-3">
                        <div onclick="loadPDF('uploads/semesterTest.pdf')" class="card text-white" style="background-color: #2196f3; height: 120px; cursor: pointer;">
                            <div class="card-body d-flex flex-column justify-content-center">
                                <div class="mb-2" style="font-size: 24px;">📝</div>
                                <h6 class="card-title">Semester Test Timetable</h6>
                            </div>
                        </div>
                    </div>

                    <!-- Exam Timetable Block -->
                    <div class="col-md-4 mb-3">
                        <div onclick="loadPDF('uploads/examTimetable.pdf')" class="card text-white" style="background-color: #64b5f6; height: 120px; cursor: pointer;">
                            <div class="card-body d-flex flex-column justify-content-center">
                                <div class="mb-2" style="font-size: 24px;">🎓</div>
                                <h6 class="card-title">Exam Timetable</h6>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body p-0">
                        <iframe id="pdfViewer" src="" frameborder="0" width="100%" height="500px"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadPDF(pdfPath) {
    const pdfViewer = document.getElementById('pdfViewer');
    pdfViewer.src = pdfPath;
}
</script>

</body>
</html>
