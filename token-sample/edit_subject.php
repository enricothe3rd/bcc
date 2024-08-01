<?php
require 'db_connection1.php';

if (isset($_GET['id'])) {
    $subject_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
    $stmt->execute([$subject_id]);
    $subject = $stmt->fetch();

    if (!$subject) {
        echo "Subject not found!";
        exit;
    }

    $stmt = $pdo->query("SELECT id, name FROM classes");
    $classes = $stmt->fetchAll();
} else {
    echo "No subject ID provided!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Subject</title>
</head>
<body>
    <h2>Edit Subject</h2>
    <form action="update_subject.php" method="post">
        <input type="hidden" name="id" value="<?php echo $subject['id']; ?>">
        
        <!-- Add code field -->
        <label for="code">Code:</label><br>
        <input type="text" id="code" name="code" value="<?php echo $subject['code']; ?>" required><br><br>
        
        <!-- Add subject title field -->
        <label for="subject_title">Subject Title:</label><br>
        <input type="text" id="subject_title" name="subject_title" value="<?php echo $subject['subject_title']; ?>" required><br><br>
        
        <!-- Add units field -->
        <label for="units">Units:</label><br>
        <input type="text" id="units" name="units" value="<?php echo $subject['units']; ?>" required><br><br>
        
        <!-- Add room field -->
        <label for="room">Room:</label><br>
        <input type="text" id="room" name="room" value="<?php echo $subject['room']; ?>"><br><br>
        
        <!-- Add day & time field -->
        <label for="day_time">Day & Time:</label><br>
        <input type="text" id="day_time" name="day_time" value="<?php echo $subject['day_time']; ?>"><br><br>
        

        
        <!-- Class selection dropdown -->
        <label for="class_id">Class:</label><br>
        <select id="class_id" name="class_id" required>
            <?php foreach ($classes as $class): ?>
                <option value="<?php echo $class['id']; ?>" <?php echo $class['id'] == $subject['class_id'] ? 'selected' : ''; ?>>
                    <?php echo $class['name']; ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        
        <!-- Submit button -->
        <input type="submit" value="Update Subject">
    </form>
</body>
</html>
