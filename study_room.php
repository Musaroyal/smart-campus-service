
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Study Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Available Study Rooms</h1>

    <!-- Available Rooms List -->
    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['room_name']; ?></h5>
                        <p class="card-text">Capacity: <?php echo $row['capacity']; ?> people</p>
                        <form action="study_room.php" method="POST">
                            <input type="hidden" name="room_id" value="<?php echo $row['id']; ?>">
                            <div class="form-group">
                                <label for="booking_date">Booking Date:</label>
                                <input type="date" class="form-control" name="booking_date" required>
                            </div>
                            <div class="form-group">
                                <label for="time_slot">Time Slot:</label>
                                <input type="text" class="form-control" name="time_slot" placeholder="e.g., 09:00-10:00" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Book Room</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
