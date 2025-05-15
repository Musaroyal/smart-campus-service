<?php
session_start();

// If the user is already logged in, redirect to the appropriate page
if (isset($_SESSION["user_id"])) {
    if ($_SESSION["role"] === "lecturer") {
        header("Location: lecturer-dashborad.php");
    } elseif ($_SESSION["role"] === "admin") {
        header("Location: admin.php");
    } else {
        header("Location: student.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Campus Services Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('images/campus.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        
        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 30px;
            font-weight: 300;
        }
        
        .feature-card {
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            font-size: 40px;
            margin-bottom: 20px;
            color: #0d6efd;
        }
        
        .btn-primary {
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
        }
        
        .footer {
            background-color: #343a40;
            color: white;
            padding: 50px 0 20px;
        }
        
        .footer h5 {
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .footer ul {
            list-style: none;
            padding-left: 0;
        }
        
        .footer ul li {
            margin-bottom: 10px;
        }
        
        .footer ul li a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
        }
        
        .footer ul li a:hover {
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">📚 Smart Campus</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light ms-2 px-3" href="signup.php">Sign Up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Welcome to Smart Campus Services Portal</h1>
            <p class="hero-subtitle">Streamlining campus services for students, lecturers, and administrators</p>
            <div class="d-flex justify-content-center">
                <a href="signup.php" class="btn btn-primary me-3">Get Started</a>
                <a href="login.php" class="btn btn-outline-light">Login</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Key Features</h2>
                <p class="text-muted">Discover what makes our platform essential for campus life</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h4>Timetable Management</h4>
                            <p class="text-muted">Access your class schedule, exam timetables, and semester tests all in one place.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-door-open"></i>
                            </div>
                            <h4>Room Booking</h4>
                            <p class="text-muted">Easily book study rooms, labs, and other campus facilities online.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h4>Maintenance Reporting</h4>
                            <p class="text-muted">Report campus maintenance issues directly from the platform.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <h4>Notifications</h4>
                            <p class="text-muted">Stay informed with real-time updates and announcements.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <h4>Secure Access</h4>
                            <p class="text-muted">Role-based access for students, lecturers, and administrators.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4>Analytics Dashboard</h4>
                            <p class="text-muted">Comprehensive analytics for administrators to monitor usage.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5 bg-light" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="images/campus-life.jpg" alt="Campus Life" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">About Smart Campus</h2>
                    <p class="mb-4">Smart Campus Services Portal is designed to streamline and automate key campus services, enhancing the experience for students, lecturers, and administrators. Our platform provides a centralized solution for managing campus resources and services.</p>
                    <p>By digitizing essential campus functions, we aim to reduce administrative overhead, improve communication, and provide a more efficient campus experience for everyone.</p>
                    <div class="mt-4">
                        <a href="signup.php" class="btn btn-primary">Join Today</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5>Smart Campus</h5>
                    <p>Transforming campus management through innovative digital solutions.</p>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5>Links</h5>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#about">About</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5>Resources</h5>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Support</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h5>Contact</h5>
                    <ul>
                        <li><i class="fas fa-envelope me-2"></i> info@smartcampus.edu</li>
                        <li><i class="fas fa-phone me-2"></i> +1 234 567 8900</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Campus Drive, University City</li>
                    </ul>
                </div>
            </div>
            <hr class="mt-4 mb-3 opacity-25">
            <div class="row">
                <div class="col text-center">
                    <p class="small opacity-75">© 2023 Smart Campus Services Portal. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
