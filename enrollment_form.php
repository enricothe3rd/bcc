<?php
session_start();
require 'session_timeout.php';
require 'db_connection1.php';

// Check if user is logged in and their role is 'student'
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    $_SESSION['last_activity'] = time();
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user email and student_id
$sql = "SELECT u.email, u.id AS student_id 
        FROM users u 
        WHERE u.id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    echo "Error: User not found.";
    exit();
}

$email = htmlspecialchars($result['email']);
$student_id = $result['student_id'];

// Fetch courses for dropdown
$course_sql = "SELECT id, course_name FROM courses";
$course_stmt = $pdo->prepare($course_sql);
$course_stmt->execute();
$courses = $course_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch status options for dropdown
$status_sql = "SELECT * FROM status_options";
$status_stmt = $pdo->prepare($status_sql);
$status_stmt->execute();
$status_options = $status_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch sex options for dropdown
$sex_sql = "SELECT * FROM sex_options";
$sex_stmt = $pdo->prepare($sex_sql);
$sex_stmt->execute();
$sex_options = $sex_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
    $middlename = filter_input(INPUT_POST, 'middlename', FILTER_SANITIZE_STRING);
    $suffix = filter_input(INPUT_POST, 'suffix', FILTER_SANITIZE_STRING);
    $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_STRING);
    $course_id = filter_input(INPUT_POST, 'course', FILTER_SANITIZE_NUMBER_INT); // Updated to integer
    $sex = filter_input(INPUT_POST, 'sex', FILTER_SANITIZE_STRING);
    $dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $contact_no = filter_input(INPUT_POST, 'contact_no', FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
    $statusofenrollment = 'verifying'; // Default status for new records

    // Insert data into enrollment table
    $sql = "INSERT INTO enrollment (student_id, lastname, firstname, middlename, suffix, year, course_id, sex, dob, address, contact_no, status, email, statusofenrollment) 
    VALUES (:student_id, :lastname, :firstname, :middlename, :suffix, :year, :course_id, :sex, :dob, :address, :contact_no, :status, :email, :statusofenrollment)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':student_id' => $student_id,
        ':lastname' => $lastname,
        ':firstname' => $firstname,
        ':middlename' => $middlename,
        ':suffix' => $suffix,
        ':year' => $year,
        ':course_id' => $course_id, // Updated to integer
        ':sex' => $sex,
        ':dob' => $dob,
        ':address' => $address,
        ':contact_no' => $contact_no,
        ':status' => $status,
        ':email' => $email,
        ':statusofenrollment' => $statusofenrollment
    ]);

    header("Location: student_dashboard_verifying.php");
    exit();
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
<body class="bg-gray-100">
 <div class="container mx-auto p-8">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Student Registration Form</h2>
        <form method="POST" >
            <!-- Last Name -->
            <div class="mb-4">
                <label for="lastname" class="block text-gray-700 mb-2">Last Name</label>
                <input type="text" id="lastname" name="lastname" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>

            <!-- First Name -->
            <div class="mb-4">
                <label for="firstname" class="block text-gray-700 mb-2">First Name</label>
                <input type="text" id="firstname" name="firstname" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>

            <!-- Middle Name -->
            <div class="mb-4">
                <label for="middlename" class="block text-gray-700 mb-2">Middle Name</label>
                <input type="text" id="middlename" name="middlename" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <!-- Suffix -->
            <div class="mb-4">
                <label for="suffix" class="block text-gray-700 mb-2">Suffix (Optional)</label>
                <select id="suffix" name="suffix" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">--Select--</option>
                    <option value="Jr">Jr. (Junior)</option>
                    <option value="Sr">Sr. (Senior)</option>
                    <option value="II">II</option>
                    <option value="III">III</option>
                    <option value="IV">IV</option>
                    <option value="Esq">Esq. (Esquire)</option>
                </select>
            </div>

            <!-- Year -->
            <div class="mb-4">
                <label for="year" class="block text-gray-700 mb-2">Year</label>
                <input type="text" id="year" name="year" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <!-- Course -->
            <div class="mb-4">
                <label for="course" class="block text-gray-700 mb-2">Course</label>
                <select id="course" name="course" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    <option value="">--Select Course--</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo htmlspecialchars($course['id']); ?>">
                            <?php echo htmlspecialchars($course['course_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Sex -->
            <div class="mb-4">
                <label for="sex" class="block text-gray-700 mb-2">Sex</label>
                <select id="sex" name="sex" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    <option value="">--Select Sex--</option>
                    <?php foreach ($sex_options as $sex_option): ?>
                        <option value="<?php echo htmlspecialchars($sex_option['sex_name']); ?>">
                            <?php echo htmlspecialchars($sex_option['sex_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Date of Birth -->
            <div class="mb-4">
                <label for="dob" class="block text-gray-700 mb-2">Date of Birth</label>
                <input type="date" id="dob" name="dob" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <!-- Address -->
            <div class="mb-4">
                <label for="address" class="block text-gray-700 mb-2">Present Address</label>
                <input type="text" id="address" name="address" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <!-- Contact Number -->
            <div class="mb-4">
                <label for="contact_no" class="block text-gray-700 mb-2">Contact Number</label>
                <input type="text" id="contact_no" name="contact_no" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="status" class="block text-gray-700 mb-2">Status</label>
                <select id="status" name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    <option value="">--Select Status--</option>
                    <?php foreach ($status_options as $status_option): ?>
                        <option value="<?php echo htmlspecialchars($status_option['status_name']); ?>">
                            <?php echo htmlspecialchars($status_option['status_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-blue-700 transition">Submit</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
