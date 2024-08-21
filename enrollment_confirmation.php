<?php
session_start();

// Check if the enrollment form was submitted
if (!isset($_SESSION['enrollment_submitted']) || $_SESSION['enrollment_submitted'] !== true) {
    // If not submitted, redirect to the enrollment form
    header('Location: enrollment_form.php');
    exit;
}

// Clear the session variable
unset($_SESSION['enrollment_submitted']);

// Retrieve the enrollment ID from the URL parameter
$enrollment_id = isset($_GET['id']) ? $_GET['id'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Confirmation</title>
</head>
<body>
    <h2>Enrollment Confirmation</h2>
    <?php if ($enrollment_id): ?>
        <p>Your enrollment has been successfully submitted.</p>
        <p>Enrollment ID: <?php echo htmlspecialchars($enrollment_id); ?></p>
    <?php else: ?>
        <p>Invalid enrollment ID.</p>
    <?php endif; ?>
</body>
</html>
