<?php
session_start();
require 'config.php';

// Redirect if already logged in
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users_db WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        // Set session variables
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["role"] = $user["role"];
        
        if ($user["role"] === "student") {
            $_SESSION["student_no"] = $user["student_no"];
            $_SESSION["course_name"] = $user["course_name"];
            $_SESSION["course_code"] = $user["course_code"];
            header("Location: student.php");
        } elseif ($user["role"] === "lecturer") {
            header("Location: lecturer-dashborad.php");
        } elseif ($user["role"] === "admin") {
            header("Location: admin.php");
        } else {
            header("Location: student.php"); // Default to student page
        }
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            background: #f0f4f8;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .signup-link {
            text-align: center;
            margin-top: 15px;
        }

        .signup-link a {
            text-decoration: none;
            color: #007bff;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
    <div class="signup-link">
        Don't have an account? <a href="signup.php">Sign Up</a>
    </div>
</div>
</body>
</html>
