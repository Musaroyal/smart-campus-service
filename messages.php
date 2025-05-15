<?php
// messages.php
// echo '<h2>Your Messages</h2>';
echo '<p>You have no new messages.</p>';

session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo "<div class='alert alert-danger'>Please log in to view messages.</div>";
    exit;
}

$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"];

// Add Back to Dashboard button
$dashboard_url = '#';
if ($role === 'admin') {
    $dashboard_url = 'admin.php';
} elseif ($role === 'lecturer') {
    $dashboard_url = 'lecturer-dashborad.php';
} else {
    $dashboard_url = 'student-dashboard.php'; // Change if your student dashboard has a different filename
}
echo "<div class='mb-3'><a href='$dashboard_url' class='btn btn-secondary'>&larr; Back to Dashboard</a></div>";

// Handle posting new announcement (admin/lecturer only)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_announcement']) && ($role === 'admin' || $role === 'lecturer')) {
    $title = $_POST['title'];
    $message = $_POST['message'];
    $target_role = $_POST['target_role'];
    
    // Get all users with the target role (or all users if target_role is 'all')
    if ($target_role === 'all') {
        $user_sql = "SELECT id FROM users_db";
        $user_stmt = $conn->prepare($user_sql);
        $user_stmt->execute();
    } else {
        $user_sql = "SELECT id FROM users_db WHERE role = ?";
        $user_stmt = $conn->prepare($user_sql);
        $user_stmt->execute([$target_role]);
    }
    
    $target_users = $user_stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Insert notification for each target user
    $success = true;
    $insert_sql = "INSERT INTO notifications (user_id, title, message) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    
    foreach ($target_users as $target_user_id) {
        if (!$insert_stmt->execute([$target_user_id, $title, $message])) {
            $success = false;
            break;
        }
    }
    
    if ($success) {
        $announcement_success = "Announcement has been sent successfully!";
    } else {
        $announcement_error = "An error occurred while sending the announcement.";
    }
}

// Mark notification as read
if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $notification_id = $_GET['mark_read'];
    
    $sql = "UPDATE notifications SET is_read = TRUE WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$notification_id, $user_id]);
    
    // Redirect to remove the query parameter
    header("Location: messages.php");
    exit;
}

// Fetch user's notifications
$sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();

// Count unread notifications
$unread_count = 0;
foreach ($notifications as $notification) {
    if (!$notification['is_read']) {
        $unread_count++;
    }
}
?>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>💬 Messages & Announcements</h2>
        
        <?php if ($role === 'admin' || $role === 'lecturer'): ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newAnnouncementModal">
                <i class="fas fa-plus"></i> New Announcement
            </button>
        <?php endif; ?>
    </div>
    
    <?php if (!empty($announcement_success)): ?>
        <div class="alert alert-success"><?php echo $announcement_success; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($announcement_error)): ?>
        <div class="alert alert-danger"><?php echo $announcement_error; ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-lg-9">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Inbox <?php if ($unread_count > 0): ?><span class="badge bg-light text-primary"><?php echo $unread_count; ?> New</span><?php endif; ?></h5>
                </div>
                <div class="card-body p-0">
                    <?php if (count($notifications) > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($notifications as $notification): ?>
                                <div class="list-group-item <?php echo $notification['is_read'] ? '' : 'bg-light'; ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-1">
                                            <?php if (!$notification['is_read']): ?>
                                                <span class="badge bg-primary me-2">New</span>
                                            <?php endif; ?>
                                            <?php echo htmlspecialchars($notification['title']); ?>
                                        </h5>
                                        <small class="text-muted"><?php echo date('M j, Y g:i A', strtotime($notification['created_at'])); ?></small>
                                    </div>
                                    <p class="mb-1"><?php echo nl2br(htmlspecialchars($notification['message'])); ?></p>
                                    <?php if (!$notification['is_read']): ?>
                                        <div class="text-end mt-2">
                                            <a href="?mark_read=<?php echo $notification['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                Mark as Read
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <img src="images/empty-inbox.svg" alt="Empty Inbox" style="width: 100px; height: 100px; opacity: 0.5;">
                            <p class="text-muted mt-3">Your inbox is empty</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Messages:</span>
                        <span class="fw-bold"><?php echo count($notifications); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Unread Messages:</span>
                        <span class="fw-bold"><?php echo $unread_count; ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Last Message:</span>
                        <span class="fw-bold">
                            <?php 
                            if (count($notifications) > 0) {
                                echo date('M j, Y', strtotime($notifications[0]['created_at']));
                            } else {
                                echo "N/A";
                            }
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <?php if ($role === 'student'): ?>
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Help</h5>
                </div>
                <div class="card-body">
                    <p>This page displays all announcements and notifications from administration and lecturers.</p>
                    <p>Important dates, events, and class announcements will appear here.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($role === 'admin' || $role === 'lecturer'): ?>
<!-- New Announcement Modal -->
<div class="modal fade" id="newAnnouncementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="target_role" class="form-label">Send To</label>
                        <select class="form-select" id="target_role" name="target_role" required>
                            <option value="all">All Users</option>
                            <option value="student">Students Only</option>
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
<?php endif; ?>

<script>
// Add JavaScript to automatically scroll to top when marking a message as read
document.addEventListener('DOMContentLoaded', function() {
    // Check if there's a success message
    if (document.querySelector('.alert-success')) {
        // Scroll to the top of the page
        window.scrollTo(0, 0);
    }
});
</script>
