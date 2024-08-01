<?php
require 'db_connection1.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("UPDATE classes SET name = ?, description = ? WHERE id = ?");
    if ($stmt->execute([$name, $description, $id])) {
        header("Location: teacher_dashboard.php");
    } else {
        echo "Error: Could not update class.";
    }
}
?>
