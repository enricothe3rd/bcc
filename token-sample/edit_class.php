<?php
require 'db_connection1.php'; // Requires the database connection script

if (isset($_GET['id'])) {
    $class_id = $_GET['id']; // Retrieve class_id from the query string

    // Prepare SQL statement to select a class by its ID
    $stmt = $pdo->prepare("SELECT * FROM classes WHERE id = ?");
    $stmt->execute([$class_id]);
    $class = $stmt->fetch(); // Fetch the class record as an associative array

    // Check if the class record exists
    if (!$class) {
        echo "Class not found!";
        exit;
    }
} else {
    echo "No class ID provided!";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Class</title>
</head>
<body>
    <h2>Edit Class</h2>
    <form action="update_class.php" method="post">
        <input type="hidden" name="id" value="<?php echo $class['id']; ?>">
        <label for="name">Class Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $class['name']; ?>" required><br><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description"><?php echo $class['description']; ?></textarea><br><br>
        <input type="submit" value="Update Class">
    </form>
</body>
</html>
