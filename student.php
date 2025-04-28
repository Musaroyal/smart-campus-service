<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Student Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
  

  body {
  margin: 0;
  padding: 0;
  overflow-x: hidden; /* Prevent horizontal overflow */
  display: flex;
  flex-direction: row; /* Ensure horizontal layout with sidebar and content */
  height: 100vh; /* Make body take full viewport height */
}

    /* Sidebar */
    #sidebar {
      height: 100vh;
      width: 350px;
      position: fixed;
      top: 0;
      left: 0;
      background-color: #f0f0f0; 
      background: url('images/sidenavBackground.avif') no-repeat center center;
      padding-top: 80px;
      transition: all 0.3s;
      overflow-y: auto;
      box-shadow: 2px 0 5px rgba(0,0,0,0.1);
      position: relative; 
      z-index: 1; 
      color : white
    }

  #sidebar::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* black overlay, 50% opacity */
  z-index: -1; /* place under sidebar content */
}

#sidebar a {
  color: white !important; /* Make the text white */
  text-align: left;         /* Align text to the left */
  padding-left: 20px;       /* Optional: add a little left padding */
}

#sidebar a:hover {
  background-color:  #007bff(255, 255, 255, 0.2); /* Optional: nice hover effect */
}


.center-link {
  display: block; /* Ensures it takes up the full width of its container */
  width: 100%; /* Makes the link fill the available space */
  text-align: center; /* Centers the content inside */
}

    /* When sidebar is hidden */
    #sidebar.hidden {
      margin-left: -350px;
    }

    /* Content area */
    #content {
  margin-left: 350px; /* Sidebar width */
  width: calc(100% - 350px); /* Adjust content width to the remaining space */
  padding: 20px;
  padding-top: 100px;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  transition: all 0.3s;
  flex-grow: 1;
}
    /* When sidebar is hidden, stretch content */
    #content.full-width {
      margin-left: 0; 
       width: 100%;
    }

    /* Top Navbar */
    .navbar {
      z-index: 1000;
    }

    /* Hover Effect */
    .custom-hover:hover {
      background-color: #007bff;
      color: white;
      border-color: #007bff;
    }

    @media (max-width: 768px) {
      #sidebar {
        width: 100%;
        height: auto;
        position: relative;

        margin-left: 0;
  
      }
      #sidebar.hidden {
        margin-left: 0;
      }
      #content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
  <div class="text-center mb-4">
    <img src="images/profile2.jpg" class="rounded-circle mb-2" alt="Student Avatar" width="90" height="90">
    <h5>Thato Aphane</h5>
    <small>Student No: 219599340</small><br>
    <small>Course: BSc Computer Science</small><br>
    <div class="d-flex justify-content-center">

      <a onclick="loadPage('profile')" href="#" class="btn btn-outline-secondary w-auto d-block mb-2">👤 Edit Profile</a>
    </div>
    
  </div>


  <hr>
  <a onclick="loadPage('dashboard.php')" href="#" class="btn btn-outline-secondary w-auto d-block mb-2">📊  dashboard</a>
  <a href="javascript:void(0);" onclick="loadPage('timetable')" class="btn btn-outline-secondary w-auto d-block mb-2">📅 Timetable</a>
  <a onclick="loadPage('studyrooms')" href="#" class="btn btn-outline-secondary w-auto d-block mb-2">📖 Study Rooms</a>
  <a onclick="loadPage('messages')" href="#" class="btn btn-outline-secondary w-auto d-block mb-2">💬 Messages</a>
  <!-- <a onclick="loadPage('messages')" href="#" class="btn btn-outline-secondary w-auto d-block mb-2">💬 Notifications</a> -->
</div>

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
  <div class="container-fluid">
    <button id="toggle-btn" class="btn btn-outline-primary me-2">☰</button>
    <a class="navbar-brand fw-bold text-primary" href="#">📚 Smart Campus</a>
    <div class="d-flex ms-auto">
      <!-- <a href="dashboard.php" class="btn btn-outline-primary me-2">Dashboard</a> -->
      <a href="logout.php" class="btn btn-primary">Logout</a>
    </div>
  </div>
</nav>

<!-- Main Content -->
<div id="content">
  <h2>Welcome to your Dashboard</h2>
  <!-- <p>This is your landing page. Your timetable will be displayed here.</p> -->
</div>

<script>
  const toggleBtn = document.getElementById('toggle-btn');
  const sidebar = document.getElementById('sidebar');
  const content = document.getElementById('content');

  // Toggle sidebar visibility and content width
  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('hidden');
    content.classList.toggle('full-width');
  });

  // Load content for different pages
  function loadPage(page) {
    let contentDiv = document.getElementById('content');

    if (page === 'timetable') {
      fetch('timetable.php')
        .then(response => response.text())
        .then(data => {
          contentDiv.innerHTML = data;  // Insert content from timetable.php
        })
        .catch(error => console.error('Error loading timetable:', error));
    } else if (page === 'studyrooms') {
      contentDiv.innerHTML = `
        <h2>📖 Book Study Rooms</h2>
        <p>Choose a study room and make a reservation below.</p>
        <button class="btn btn-primary">Book Now</button>
      `;
    } else if (page === 'profile') {
      fetch('profile.php')
        .then(response => response.text())
        .then(data => {
          contentDiv.innerHTML = data;  // Insert profile.php content
        })
        .catch(error => console.error('Error loading profile:', error));
    } else if (page === 'messages') {
      contentDiv.innerHTML = `
        <h2>💬 Messages</h2>
        <p>You have no new messages.</p>
      `;
    }
  }
</script>


</body>
</html>
