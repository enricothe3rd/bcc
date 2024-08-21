<?php
session_start();

// Check if user is not logged in or if their role is not 'teacher'
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'registrar') {
    // Redirect to login page or display an error message
    header("Location: index.php"); // Redirect to login page
    exit(); // Stop further execution
}

// If the user is logged in as a teacher, continue displaying the dashboard
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>


    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        h3 {
            margin-top: 20px;
            color: #555;
        }
        p {
            color: #777;
        }
        .subject-row {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .subject-details {
            flex: 1;
        }
        .subject-actions {
            flex-shrink: 0;
            margin-left: 10px;
        }
        a {
            color: #007bff;
            text-decoration: none;
            margin-right: 10px;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Welcome, Teacher!</h2>


    <h2>Classes and Subjects</h2>
    <a href="create_class.php">Add New Class</a>
    <a href="create_subject.php">Add New Subject</a><br><br>

    <?php
    require 'db_connection1.php';

    // Display classes and subjects
    $stmt_classes = $pdo->query("SELECT * FROM classes");
    while ($class = $stmt_classes->fetch()) {
        echo "<div>";
        echo "<h3>" . htmlspecialchars($class['name']) . " <a href='edit_class.php?id=" . $class['id'] . "'>Edit</a> | ";
        echo "<a href='delete_class.php?id=" . $class['id'] . "' onclick='return confirm(\"Are you sure you want to delete this class and all its subjects?\")'>Delete</a></h3>";
        echo "<p>" . htmlspecialchars($class['description']) . "</p>";

        // Display subjects for each class
        $stmt_subjects = $pdo->prepare("SELECT * FROM subjects WHERE class_id = ?");
        $stmt_subjects->execute([$class['id']]);
        
        while ($subject = $stmt_subjects->fetch()) {
            echo "<div class='subject-row'>";
            echo "<div class='subject-details'>";
            echo "<p><strong>Code:</strong> " . htmlspecialchars($subject['code']) . " | ";
            echo "<strong>Subject Title:</strong> " . htmlspecialchars($subject['subject_title']) . " | ";
            echo "<strong>Units:</strong> " . htmlspecialchars($subject['units']) . " | ";
            echo "<strong>Room:</strong> " . htmlspecialchars($subject['room']) . " | ";
            echo "<strong>Day & Time:</strong> " . htmlspecialchars($subject['day_time']) . "</p>";
            echo "</div>"; // .subject-details
            echo "<div class='subject-actions'>";
            echo "<a href='edit_subject.php?id=" . $subject['id'] . "'>Edit</a> | ";
            echo "<a href='delete_subject.php?id=" . $subject['id'] . "' onclick='return confirm(\"Are you sure you want to delete this subject?\")'>Delete</a>";
            echo "</div>"; // .subject-actions
            echo "</div>"; // .subject-row
        }
        
        echo "</div>"; // closing class container
    }
    ?>
    <a href="logout.php">Logout</a>
</body>
</html>
