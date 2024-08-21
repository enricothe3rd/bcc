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
    $start_times = $_POST['start_time'];
    $end_times = $_POST['end_time'];

    $stmt = $pdo->prepare("INSERT INTO subjects (class_id, code, subject_title, units, room, day, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($codes as $key => $code) {
        $name = $names[$key];
        $unit = $units[$key];
        $room = $rooms[$key];
        $day = $days[$key];

        // Convert AM/PM format to 24-hour format for database storage
        $start_time = date('H:i:s', strtotime($start_times[$key])); // Convert '5:00 PM' to '17:00:00'
        $end_time = date('H:i:s', strtotime($end_times[$key]));     // Convert '6:00 PM' to '18:00:00'

        if (!$stmt->execute([$class_id, $code, $name, $unit, $room, $day, $start_time, $end_time])) {
            echo "Error: Could not add subject.";
            exit;
        }
    }

    header("Location: registrar_dashboard.php");
    exit();
}
?>
