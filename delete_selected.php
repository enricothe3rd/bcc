<?php
require 'db_connection1.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo->beginTransaction();

        // Deleting selected classes
        if (!empty($_POST['classes'])) {
            $classIds = $_POST['classes'];
            foreach ($classIds as $classId) {
                // Delete all subjects related to the class first
                $stmt = $pdo->prepare("DELETE FROM subjects WHERE class_id = ?");
                $stmt->execute([$classId]);

                // Then delete the class
                $stmt = $pdo->prepare("DELETE FROM classes WHERE id = ?");
                $stmt->execute([$classId]);
            }
        }

        // Deleting selected subjects
        if (!empty($_POST['subjects'])) {
            $subjectIds = $_POST['subjects'];
            foreach ($subjectIds as $subjectId) {
                $stmt = $pdo->prepare("DELETE FROM subjects WHERE id = ?");
                $stmt->execute([$subjectId]);
            }
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Failed: " . $e->getMessage();
    }
}

header('Location: subject1.php');
?>
