<?php
require 'db_connection1.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input data
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    if ($id && $name && $description) {
        // Prepare and execute the update statement
        $stmt = $pdo->prepare("UPDATE classes SET name = ?, description = ? WHERE id = ?");
        if ($stmt->execute([$name, $description, $id])) {
            // Redirect to teacher dashboard after successful update
            header("Location: subject1.php");
            exit();
        } else {
            echo "Error: Could not update class.";
        }
    } else {
        echo "Invalid input.";
    }
}
?>
