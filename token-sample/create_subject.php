<?php
// create_subject.php

require 'db_connection1.php'; // Ensure this file sets up the $pdo object

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_id = $_POST['class_id'];
    $codes = $_POST['code'];
    $names = $_POST['subject_title'];
    $units = $_POST['units'];
    $rooms = $_POST['room'];
    $days = $_POST['day'];
    $hours = $_POST['hours'];
    $minutes = $_POST['minutes'];

    $stmt = $pdo->prepare("INSERT INTO subjects (class_id, code, subject_title, units, room, day_time) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($codes as $key => $code) {
        $name = $names[$key];
        $unit = $units[$key];
        $room = $rooms[$key];
        $day_time = $days[$key] . ' ' . str_pad($hours[$key], 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes[$key], 2, '0', STR_PAD_LEFT) . ':00';

        if (!$stmt->execute([$class_id, $code, $name, $unit, $room, $day_time])) {
            echo "Error: Could not add subject.";
            exit;
        }
    }

    header("Location: teacher_dashboard.php");
    exit();
}
?>
