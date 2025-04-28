<?php
// profile.php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>

body {
  margin: 0;
  padding: 0;
  overflow-x: hidden; 
  display: flex;
  flex-direction: row; 
  height: 100vh; 
}

</style>

<body>
<div class="container mt-5">
<img src="images/profile2.jpg" class="rounded-circle mb-2" alt="Student Avatar" width="90" height="90">
<!-- Profile Picture -->
<!-- <div class="form-group">
                    <label for="profilePic">Profile Picture</label>
                    <input type="file" class="form-control-file" id="profilePic">
</div> -->

 <!-- Profile Picture Section (Optional) -->
 <!-- <div class="col-md-6">
            <img src="profile.jpg" class="img-fluid" alt="Profile Picture">
            <p class="mt-3">Upload a new profile picture here.</p>
        </div> -->

    <div class="row">
        <!-- Profile Section -->
        <div class="col-md-6">
    
            <form>
                <!-- Full Name -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="firstName">First Name</label>
                        <input type="text" class="form-control" id="firstName" placeholder="First Name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="lastName">Last Name</label>
                        <input type="text" class="form-control" id="lastName" placeholder="Last Name">
                    </div>
                </div>

                <div class="form-row">
                <!-- Student ID -->
                <div class="form-group">
                    <label for="studentId">Student No</label>
                    <input type="text" class="form-control" id="studentNo" placeholder="Student No">
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email">Student Email</label>
                    <input type="studentEmail" class="form-control" id="studentEmail" placeholder="Student Email">
                </div>
            </div>

             <div class="form-row">  
            <!-- Phone Number -->
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" class="form-control" id="phone" placeholder="Phone Number">
                </div>

                <!-- Department/Faculty -->
                <div class="form-group">
                    <label for="department">Department/Faculty</label>
                    <input type="text" class="form-control" id="department" placeholder="Department">
                </div>
             </div>
                <!-- Role -->
                <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-control" id="role">
                        <option value="Student" selected>Student</option>
                        <option value="Lecturer">Lecturer</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <!-- Update Button -->
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>

       
    </div>
</div>

<!-- Include Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
