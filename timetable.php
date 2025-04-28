<?php
// Sample timetable data
$dailyClasses = [
    'Monday' => [
        ['time' => '9:00 AM - 10:00 AM', 'subject' => 'Math', 'location' => 'Room 101'],
        ['time' => '10:15 AM - 11:15 AM', 'subject' => 'Physics', 'location' => 'Room 102'],
    ],
    'Tuesday' => [
        ['time' => '9:00 AM - 10:00 AM', 'subject' => 'History', 'location' => 'Room 103'],
        ['time' => '10:15 AM - 11:15 AM', 'subject' => 'Biology', 'location' => 'Room 104'],
    ],
    // Add more days as needed...
];

$weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

// Logic to switch between daily and weekly views
$view = isset($_GET['view']) ? $_GET['view'] : 'daily';
$currentDay = isset($_GET['day']) ? $_GET['day'] : 'Monday';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <header class="text-center mb-4">
        <h1>Student Timetable</h1>
        <div class="btn-group" role="group">
            <a href="?view=daily&day=Monday" class="btn btn-primary">Daily View</a>
            <a href="?view=weekly" class="btn btn-secondary">Weekly View</a>
        </div>
    </header>

    <?php if ($view === 'daily') : ?>
        <div class="daily-view">
            <div class="mb-4">
                <h3>Schedule for <?= $currentDay; ?></h3>
            </div>
            <div class="list-group">
                <?php foreach ($dailyClasses[$currentDay] as $class) : ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="class-time"><?= $class['time']; ?></div>
                        <div class="class-info">
                            <h5 class="mb-1"><?= $class['subject']; ?></h5>
                            <p class="mb-0"><?= $class['location']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php elseif ($view === 'weekly') : ?>
        <div class="weekly-view">
            <div class="row">
                <?php foreach ($weekDays as $day) : ?>
                    <div class="col-md-2">
                        <div class="border p-3">
                            <h5 class="text-center"><?= $day; ?></h5>
                            <?php if (isset($dailyClasses[$day])): ?>
                                <?php foreach ($dailyClasses[$day] as $class) : ?>
                                    <div class="mb-3">
                                        <div class="fw-bold"><?= $class['time']; ?></div>
                                        <div><?= $class['subject']; ?></div>
                                        <div class="small text-muted"><?= $class['location']; ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <div>No classes scheduled</div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
