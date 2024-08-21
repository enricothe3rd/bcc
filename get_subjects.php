<?php
// require 'db_connection1.php';

// if (isset($_GET['class_id'])) {
//     $class_id = (int)$_GET['class_id'];

//     $stmt = $pdo->prepare("SELECT id, code, subject_title, units, room, day_time FROM subjects WHERE class_id = ?");
//     if (!$stmt->execute([$class_id])) {
//         echo json_encode(['error' => 'Query execution failed']);
//         exit;
//     }

//     $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     if (empty($subjects)) {
//         echo json_encode(['error' => 'No subjects found']);
//         exit;
//     }

//     header('Content-Type: application/json');
//     echo json_encode($subjects);
//     exit;
// } else {
//     echo json_encode(['error' => 'class_id not set']);
// }
?>
