<?php
require 'db_connection1.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $class_id = $_POST['class_id'];

    $stmt = $pdo->prepare("UPDATE subjects SET name = ?, description = ?, class_id = ? WHERE id = ?");
    if ($stmt->execute([$name, $description, $class_id, $id])) {
        header("Location: teacher_dashboard.php");
    } else {
        echo "Error: Could not update subject.";
    }
}
?>
