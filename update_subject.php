<?php
require 'db_connection1.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $code = $_POST['code'];
    $subject_title = $_POST['subject_title'];
    $units = $_POST['units'];
    $room = $_POST['room'];
    $day = $_POST['day']; // Day of the week
    $start_time = $_POST['start_time']; // 12-hour format (e.g., 5:00 PM)
    $end_time = $_POST['end_time']; // 12-hour format (e.g., 6:00 PM)
    $class_id = $_POST['class_id'];

    // Convert 12-hour format times to 24-hour format for database storage
    $start_time_24 = date('H:i:s', strtotime($start_time));
    $end_time_24 = date('H:i:s', strtotime($end_time));

    // Prepare the SQL statement
    $stmt = $pdo->prepare("UPDATE subjects SET code = ?, subject_title = ?, units = ?, room = ?, day = ?, start_time = ?, end_time = ?, class_id = ? WHERE id = ?");
    
    // Execute the SQL statement
    if ($stmt->execute([$code, $subject_title, $units, $room, $day, $start_time_24, $end_time_24, $class_id, $id])) {
        header("Location: registrar_dashboard.php");
    } else {
        echo "Error: Could not update subject.";
    }
}
?>
