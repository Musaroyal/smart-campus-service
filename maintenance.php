<?php
session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo "<div class='alert alert-danger'>Please log in to report maintenance issues.</div>";
    exit;
}

$user_id = $_SESSION["user_id"];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_report'])) {
    $issue_type = $_POST['issue_type'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    
    $sql = "INSERT INTO maintenance_requests (user_id, issue_type, location, description) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([$user_id, $issue_type, $location, $description])) {
        $success = "Your maintenance request has been submitted successfully.";
    } else {
        $error = "An error occurred while submitting your request. Please try again.";
    }
}

// Fetch user's previous maintenance requests
$sql = "SELECT * FROM maintenance_requests WHERE user_id = ? ORDER BY reported_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$requests = $stmt->fetchAll();
?>

<div class="container-fluid py-3">
    <h2 class="mb-4">🔧 Maintenance Request</h2>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Report an Issue</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="issue_type" class="form-label">Issue Type</label>
                            <select class="form-select" id="issue_type" name="issue_type" required>
                                <option value="">Select an issue type</option>
                                <option value="Plumbing">Plumbing</option>
                                <option value="Electrical">Electrical</option>
                                <option value="Furniture">Furniture</option>
                                <option value="Air Conditioning">Air Conditioning</option>
                                <option value="Network/Internet">Network/Internet</option>
                                <option value="Cleaning">Cleaning</option>
                                <option value="Safety Hazard">Safety Hazard</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" 
                                   placeholder="Building and room number" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4"
                                      placeholder="Please describe the issue in detail" required></textarea>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" name="submit_report" class="btn btn-primary">Submit Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Your Previous Reports</h5>
                </div>
                <div class="card-body">
                    <?php if (count($requests) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Issue Type</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Reported On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($requests as $request): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($request['issue_type']); ?></td>
                                            <td><?php echo htmlspecialchars($request['location']); ?></td>
                                            <td>
                                                <?php 
                                                    $status = $request['status'];
                                                    $status_class = '';
                                                    
                                                    if ($status === 'reported') {
                                                        $status_class = 'bg-warning text-dark';
                                                    } elseif ($status === 'in-progress') {
                                                        $status_class = 'bg-info text-dark';
                                                    } elseif ($status === 'resolved') {
                                                        $status_class = 'bg-success text-white';
                                                    }
                                                ?>
                                                <span class="badge <?php echo $status_class; ?>">
                                                    <?php echo ucfirst($status); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($request['reported_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">You haven't submitted any maintenance requests yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div> 