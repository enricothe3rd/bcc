<?php
require 'db_connection1.php';

if (isset($_GET['id'])) {
    $subject_id = $_GET['id'];

    // Fetch the subject details
    $stmt = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
    $stmt->execute([$subject_id]);
    $subject = $stmt->fetch();

    if (!$subject) {
        echo "Subject not found!";
        exit;
    }

    // Fetch the available classes
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subject</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .input-focus:focus {
            border-color: #4f46e5; /* Tailwind indigo-600 */
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.3); /* Tailwind indigo-600 shadow */
        }
    </style>
</head>
<body class="bg-gray-200 flex items-center justify-center min-h-screen p-6">
    <!-- <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-4xl"> -->
        <!-- <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit Subject</h1> -->
        <form action="update_subject.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($subject['id']); ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Code field -->
                <div class="mb-4">
                    <label for="code" class="block text-gray-700 mb-2">Code:</label>
                    <input type="text" id="code" name="code" value="<?php echo htmlspecialchars($subject['code']); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Subject Title field -->
                <div class="mb-4">
                    <label for="subject_title" class="block text-gray-700 mb-2">Subject Title:</label>
                    <input type="text" id="subject_title" name="subject_title" value="<?php echo htmlspecialchars($subject['subject_title']); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Units field -->
                <div class="mb-4">
                    <label for="units" class="block text-gray-700 mb-2">Units:</label>
                    <input type="text" id="units" name="units" value="<?php echo htmlspecialchars($subject['units']); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Room field -->
                <div class="mb-4">
                    <label for="room" class="block text-gray-700 mb-2">Room:</label>
                    <input type="text" id="room" name="room" value="<?php echo htmlspecialchars($subject['room']); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Day field -->
                <div class="mb-4">
                    <label for="day" class="block text-gray-700 mb-2">Day:</label>
                    <select id="day" name="day" required class="w-full border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:ring-indigo-500 sm:text-sm">
                        <?php 
                        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        foreach ($daysOfWeek as $day) : ?>
                            <option value="<?php echo htmlspecialchars($day); ?>" <?php echo $day == $subject['day'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($day); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Start Time field -->
                <div class="mb-4">
                    <label for="start_time" class="block text-gray-700 mb-2">Start Time:</label>
                    <input type="time" id="start_time" name="start_time" value="<?php echo date('H:i', strtotime($subject['start_time'])); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- End Time field -->
                <div class="mb-4">
                    <label for="end_time" class="block text-gray-700 mb-2">End Time:</label>
                    <input type="time" id="end_time" name="end_time" value="<?php echo date('H:i', strtotime($subject['end_time'])); ?>" required class="w-full border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Class selection dropdown -->
                <div class="mb-4">
                    <label for="class_id" class="block text-gray-700 mb-2">Class:</label>
                    <select id="class_id" name="class_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:ring-indigo-500 sm:text-sm">
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo htmlspecialchars($class['id']); ?>" <?php echo $class['id'] == $subject['class_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($class['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <button type="submit" class="bg-yellow-500 text-white font-bold px-4 py-2 rounded-lg shadow-md hover:bg-yellow-600 transition">Update Subject</button>
                <button type="button" class="bg-gray-300 text-gray-700 font-bold px-4 py-2 rounded-lg shadow-md hover:bg-gray-400 transition" onclick="closeSubjectModal()">Cancel</button>
            </div>
        </form>
</body>
</html>

