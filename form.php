<?php
session_start();
require 'session_timeout.php'; // Include session timeout mechanism
require 'db_connection1.php'; // Include the database connection

// Check if user is logged in and their role is 'student'
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    $_SESSION['last_activity'] = time(); // Update last activity time
    header("Location: login.php"); // Redirect to login page
    exit(); // Stop further execution
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Query to check if the user is enrolled and get their email
$sql = "SELECT u.email, e.student_id 
        FROM users u 
        LEFT JOIN enrollment e ON u.id = e.student_id 
        WHERE u.id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$isEnrolled = !empty($result['student_id']);

// User is not enrolled, display email
$email = htmlspecialchars($result['email'] ?? '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $student_no = $_POST['student_no'] ?? '';
    $student_name = $_POST['student_name'] ?? '';
    $year = $_POST['year'] ?? '';
    $course = $_POST['course'] ?? '';
    $major = $_POST['major'] ?? '';
    $sex = $_POST['sex'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $address = $_POST['address'] ?? '';
    $contact_no = $_POST['contact_no'] ?? '';
    $status = $_POST['status'] ?? '';

    // Extract first name, middle name, last name, and suffix from student_name
    $name_parts = explode(',', $student_name);
    $lastname = trim($name_parts[0] ?? '');
    $firstname = trim($name_parts[1] ?? '');
    $middlename = trim($name_parts[2] ?? '');
    $suffix = trim($name_parts[3] ?? ''); // Assuming suffix might be included

    // Insert into enrollment table
    $sql = "INSERT INTO enrollment (student_id, firstname, middlename, lastname, suffix, year, course, major, sex, dob, address, email, contact_no, status, created_at, updated_at) 
            VALUES (:student_id, :firstname, :middlename, :lastname, :suffix, :year, :course, :major, :sex, :dob, :address, :email, :contact_no, :status, NOW(), NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':student_id', $student_no);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':middlename', $middlename);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':suffix', $suffix);
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':course', $course);
    $stmt->bindParam(':major', $major);
    $stmt->bindParam(':sex', $sex);
    $stmt->bindParam(':dob', $dob);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':contact_no', $contact_no);
    $stmt->bindParam(':status', $status);
    $stmt->execute();

    $enrollment_id = $conn->lastInsertId(); // Retrieve last inserted ID

    // Display confirmation message
    echo "Enrollment Confirmation<br>";
    echo "Your enrollment has been successfully submitted.<br>";
    echo "Enrollment ID: $enrollment_id";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <form method="POST">
            <!-- Student No. -->
            <div class="mb-4">
                <label for="student_no" class="block font-medium text-gray-700">Student No.</label>
                <input type="text" id="student_no" name="student_no" class="mt-1 block w-full sm:w-1/2 lg:w-1/4 shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Student Name -->
            <div class="mb-4">
                <label for="student_name" class="block font-medium text-gray-700">Student</label>
                <input type="text" id="student_name" name="student_name" placeholder="LAST NAME, GIVEN NAME, MIDDLE NAME, SUFFIX" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Year -->
            <div class="mb-4">
                <label for="year" class="block font-medium text-gray-700">Year</label>
                <input type="text" id="year" name="year" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Course -->
            <div class="mb-4">
                <label for="course" class="block font-medium text-gray-700">Course</label>
                <input type="text" id="course" name="course" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Major -->
            <div class="mb-4">
                <label for="major" class="block font-medium text-gray-700">Major</label>
                <input type="text" id="major" name="major" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Sex -->
            <div class="mb-4">
                <label for="sex" class="block font-medium text-gray-700">Sex</label>
                <input type="text" id="sex" name="sex" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Date of Birth -->
            <div class="mb-4">
                <label for="dob" class="block font-medium text-gray-700">Date of Birth</label>
                <input type="date" id="dob" name="dob" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Address -->
            <div class="mb-4">
                <label for="address" class="block font-medium text-gray-700">Present Address</label>
                <input type="text" id="address" name="address" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="block font-medium text-gray-700">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Contact No. -->
            <div class="mb-4">
                <label for="contact_no" class="block font-medium text-gray-700">Contact No.</label>
                <input type="text" id="contact_no" name="contact_no" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Status Section -->
            <div class="mb-4">
                <label class="block font-medium text-gray-700">Status</label>
                <div class="grid grid-cols-2 gap-2">
                    <!-- First Row -->
                    <div class="flex items-center space-x-2">
                        <input type="radio" id="new_student" name="status" value="new_student" class="h-4 w-4 text-indigo-600 border-gray-300">
                        <label for="new_student" class="text-sm text-gray-900">New Student</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="radio" id="old_student" name="status" value="old_student" class="h-4 w-4 text-indigo-600 border-gray-300">
                        <label for="old_student" class="text-sm text-gray-900">Old Student</label>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 mt-2">
                    <!-- Second Row -->
                    <div class="flex items-center space-x-2">
                        <input type="radio" id="regular" name="status" value="regular" class="h-4 w-4 text-indigo-600 border-gray-300">
                        <label for="regular" class="text-sm text-gray-900">Regular</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="radio" id="irregular" name="status" value="irregular" class="h-4 w-4 text-indigo-600 border-gray-300">
                        <label for="irregular" class="text-sm text-gray-900">Irregular</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="radio" id="transferee" name="status" value="transferee" class="h-4 w-4 text-indigo-600 border-gray-300">
                        <label for="transferee" class="text-sm text-gray-900">Transferee</label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mb-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>
