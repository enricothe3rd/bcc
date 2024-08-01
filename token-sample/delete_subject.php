<?php
require 'db_connection1.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the delete statement
    $sql = "DELETE FROM subjects WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Execute the statement
    try {
        $stmt->execute();
        header("Location: teacher_dashboard.php");
        header("Location: subject1.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
