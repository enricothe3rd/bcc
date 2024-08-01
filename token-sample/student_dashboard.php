<?php
session_start();
require 'session_timeout.php'; // Include session timeout mechanism

// Check if user is logged in and their role is 'student'
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    $_SESSION['last_activity'] = time(); // Update last activity time
    header("Location: login.php"); // Redirect to login page
    exit(); // Stop further execution
}

// $sql_get_email_confirmed =  "SELECT email_confirmed FROM users";
// $sql_get_email_confirmed = $conn->query($sql_get_email_confirmed);


// Check if student is enrolled (you would replace this with your actual enrollment check logic)
$isEnrolled = false; // Placeholder, replace with your actual logic to check if the student is enrolled

if (!$isEnrolled) {
    // Redirect to enrollment form if not enrolled
    header("Location: http://localhost/enrollment/token-sample/enrollment_form.php"); // Replace with the actual URL of your enrollment form
    exit(); // Stop further execution
}

// If enrolled, continue displaying the student dashboard
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
</head>
<body>
    <h2>Welcome, Student!</h2>
    <!-- Dashboard content goes here -->
    <p>This is your dashboard where you can view student-specific information.</p>
    <a href="logout.php">Logout</a>
</body>
</html>
