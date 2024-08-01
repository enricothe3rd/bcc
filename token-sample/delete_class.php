<?php
// delete_class.php

require 'db_connection1.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // First, delete all subjects associated with this class
    $sqlSubjects = "DELETE FROM subjects WHERE class_id = ?";
    $stmtSubjects = $pdo->prepare($sqlSubjects);
    $stmtSubjects->execute([$id]);

    // Then delete the class itself
    $sqlClass = "DELETE FROM classes WHERE id = ?";
    $stmtClass = $pdo->prepare($sqlClass);

    if ($stmtClass->execute([$id])) {
        echo "<script>alert('Class deleted successfully');</script>";
        echo "<meta http-equiv='refresh' content='0;url=teacher_dashboard.php'>";
    } else {
        echo "Error deleting class";
    }
}
?>
