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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200 flex items-center justify-center min-h-screen p-6">
    <!-- <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-4xl">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit Class</h1> -->
        <form action="update_class.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($class['id']); ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Class Name -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 mb-2">Class Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($class['name']); ?>" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:ring-indigo-500 sm:text-sm">
                </div>

                <!-- Description -->
                <div class="mb-4 col-span-2">
                    <label for="description" class="block text-gray-700 mb-2">Description:</label>
                    <textarea id="description" name="description"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:ring-indigo-500 sm:text-sm"><?php echo htmlspecialchars($class['description']); ?></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <button type="submit" class="bg-yellow-500 text-white font-bold px-4 py-2 rounded-lg shadow-md hover:bg-yellow-600 transition">Update Class</button>
                <button type="button" class="bg-gray-300 text-gray-700 font-bold px-4 py-2 rounded-lg shadow-md hover:bg-gray-400 transition" onclick="closeClassModal()">Cancel</button>
            </div>
        </form>
    <!-- </div> -->
</body>

</html>

