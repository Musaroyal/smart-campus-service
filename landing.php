<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Campus Services</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f8f9fc] font-sans flex flex-col min-h-screen">

  <!-- Navbar -->
  <nav class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
    <div class="text-xl font-bold text-blue-700">Smart Campus Portal</div>
    <div class="space-x-4">
      <a href="login.php" class="bg-transparent border border-blue-600 text-blue-600 px-4 py-2 rounded-full hover:bg-blue-50 transition">Login</a>
      <a href="signup.php" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition">Sign Up</a>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="max-w-6xl mx-auto p-6 space-y-10 flex-grow">

    <!-- Top Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="bg-white p-6 rounded-2xl shadow text-center">
        <div class="text-3xl mb-2">📖</div>
        <h3 class="text-xl font-semibold mb-1">Book Study Rooms</h3>
        <p class="text-gray-600">Easily find and book available rooms.</p>
      </div>
      <div class="bg-white p-6 rounded-2xl shadow text-center">
        <div class="text-3xl mb-2">✏️</div>
        <h3 class="text-xl font-semibold mb-1">Lecturer Tools</h3>
        <p class="text-gray-600">Post timetables and manage sessions.</p>
      </div>
      <div class="bg-white p-6 rounded-2xl shadow text-center">
        <div class="text-3xl mb-2">🛠️</div>
        <h3 class="text-xl font-semibold mb-1">Admin Controls</h3>
        <p class="text-gray-600">Approve bookings and manage campus resources.</p>
      </div>
      <div class="bg-white p-6 rounded-2xl shadow text-center">
        <div class="text-3xl mb-2">🔒</div>
        <h3 class="text-xl font-semibold mb-1">Secure Access</h3>
        <p class="text-gray-600">Role-based login for students, lecturers and admin.</p>
      </div>
    </div>

 <!-- Bottom Section -->
 <!-- <div class="row g-4">
  <div class="col-md-4">
    <a href="student.html" class="text-decoration-none text-dark">
      <div class="card text-center p-4">
        <h5 class="card-title">Students</h5>
        <p class="text-muted">Book study rooms, check schedules</p>
      </div>
    </a>
  </div>
</div>   -->


     <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <a href="student.php" class="text-decoration-none text-dark">
      <div class="bg-white p-6 rounded-2xl shadow text-center">
        <h4 class="text-xl font-semibold mb-2">Students</h4>
        <p class="text-gray-600">Book study rooms, check schedules</p>
      </div>
    </a>


      <div class="bg-white p-6 rounded-2xl shadow text-center">
        <h4 class="text-xl font-semibold mb-2">Lecturers</h4>
        <p class="text-gray-600">Upload class timetables, reserve halls</p>
      </div>
      <div class="bg-white p-6 rounded-2xl shadow text-center">
        <h4 class="text-xl font-semibold mb-2">Admin</h4>
        <p class="text-gray-600">Manage users, approve content, oversee bookings</p>
      </div>
    </div> <!-- Bottom Section -->
  

  </main>

  <!-- Footer -->
  <footer class="bg-white text-center text-gray-500 py-4 shadow-inner">
    © 2025 Smart Campus Portal. All rights reserved.
  </footer>

</body>
</html>
