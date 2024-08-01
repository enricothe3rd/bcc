<?php
require 'db_connection1.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO classes (name, description) VALUES (?, ?)");
    if ($stmt->execute([$name, $description])) {
        header("Location: teacher_dashboard.php");
        exit();
    } else {
        echo "Error: Could not add class.";
    }
}
?>
