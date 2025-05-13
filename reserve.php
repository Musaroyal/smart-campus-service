<?php
include 'config.php';

$room_id = $_GET['room_id'] ?? null;

if (!$room_id) {
    echo "Invalid room ID.";
    exit;
}

// Check room availability
$stmt = $conn->prepare("SELECT location FROM rooms WHERE = ?");
$stmt->bind_param("i", $location);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Room not found.";
    exit;
}

$room = $result->fetch_assoc();

if (!$room['is_available']) {
    echo "Sorry, this room is not available for booking.";
    exit;
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $_POST['student_name'];
    $student_id = $_POST['student_id'];
    $email = $_POST['email'];
    $booking_date = date("Y-m-d H:i:s");

    // Insert booking
    $stmt = $conn->prepare("INSERT INTO bookings (room_id, student_name, student_id, email, booking_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $room_id, $student_name, $student_id, $email, $booking_date);

    if ($stmt->execute()) {
        // Mark room as unavailable
        $update = $conn->prepare("UPDATE rooms SET is_available = 0 WHERE id = ?");
        $update->bind_param("i", $room_id);
        $update->execute();

        echo "Room successfully booked!";
    } else {
        echo "Error booking the room.";
    }
} else {
?>
    <h2>Reserve Room <?= htmlspecialchars($room['room_number']) ?></h2>
    <form method="post">
        <label>Name:</label><br>
        <input type="text" name="student_name" required><br><br>
        
        <label>Student ID:</label><br>
        <input type="text" name="student_id" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <input type="submit" value="Reserve Room">
    </form>
<?php
}
?>
