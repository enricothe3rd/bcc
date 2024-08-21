<?php
session_start();
require 'session_timeout.php'; // Include session timeout mechanism
require 'db_connection1.php'; // Include your database connection file

// Check if user is logged in and their role is 'registrar'
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'registrar') {
    header("Location: index.php"); // Redirect to login page
    exit();
}

// Handle course addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_course'])) {
    $course_name = filter_input(INPUT_POST, 'course_name', FILTER_SANITIZE_STRING);

    try {
        $stmt = $pdo->prepare("INSERT INTO courses (course_name) VALUES (:course_name)");
        $stmt->execute([':course_name' => $course_name]);
        header("Location: registrar_validation.php"); // Refresh the page
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
}

// Handle status addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_status'])) {
    $status_name = filter_input(INPUT_POST, 'status_name', FILTER_SANITIZE_STRING);

    if ($status_name) {
        try {
            $stmt = $pdo->prepare("INSERT INTO status_options (status_name) VALUES (:status_name)");
            $stmt->execute([':status_name' => $status_name]);
            header("Location: registrar_validation.php"); // Refresh the page
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    } else {
        echo "Status name cannot be empty.";
    }
}

// Handle sex option addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_sex'])) {
    $sex_name = filter_input(INPUT_POST, 'sex_name', FILTER_SANITIZE_STRING);

    if ($sex_name) {
        try {
            $stmt = $pdo->prepare("INSERT INTO sex_options (sex_name) VALUES (:sex_name)");
            $stmt->execute([':sex_name' => $sex_name]);
            header("Location: registrar_validation.php"); // Refresh the page
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    } else {
        echo "Sex name cannot be empty.";
    }
}

// Fetch all students' data
try {
    $stmt = $pdo->prepare("SELECT * FROM enrollment");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

// Fetch all courses for the dropdown
try {
    $stmt = $pdo->prepare("SELECT id, course_name FROM courses");
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

// Fetch status options for the dropdown
try {
    $stmt = $pdo->prepare("SELECT status_name FROM status_options");
    $stmt->execute();
    $statusOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

// Fetch sex options for the dropdown
try {
    $stmt = $pdo->prepare("SELECT sex_name FROM sex_options");
    $stmt->execute();
    $sexOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

// Handle student info update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_student'])) {
    $student_id = filter_input(INPUT_POST, 'student_id', FILTER_SANITIZE_NUMBER_INT);
    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
    $middlename = filter_input(INPUT_POST, 'middlename', FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
    $suffix = filter_input(INPUT_POST, 'suffix', FILTER_SANITIZE_STRING);
    $statusofenrollment = filter_input(INPUT_POST, 'statusofenrollment', FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING); // New status field
    $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_STRING);
    $course_id = filter_input(INPUT_POST, 'course_id', FILTER_SANITIZE_NUMBER_INT); // Use course_id instead of course
    $sex = filter_input(INPUT_POST, 'sex', FILTER_SANITIZE_STRING);
    $dob = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $contact_no = filter_input(INPUT_POST, 'contact_no', FILTER_SANITIZE_STRING);

    try {
        $stmt = $pdo->prepare("UPDATE enrollment SET 
        firstname = :firstname,
        middlename = :middlename,
        lastname = :lastname,
        suffix = :suffix,
        statusofenrollment = :statusofenrollment,
        status = :status,
        year = :year,
        course_id = :course_id,
        sex = :sex,
        dob = :dob,
        address = :address,
        email = :email,
        contact_no = :contact_no
        WHERE student_id = :student_id");
    
        $stmt->execute([
            ':firstname' => $firstname,
            ':middlename' => $middlename,
            ':lastname' => $lastname,
            ':suffix' => $suffix,
            ':statusofenrollment' => $statusofenrollment,
            ':status' => $status,
            ':year' => $year,
            ':course_id' => $course_id,
            ':sex' => $sex,
            ':dob' => $dob,
            ':address' => $address,
            ':email' => $email,
            ':contact_no' => $contact_no,
            ':student_id' => $student_id
        ]);
        header("Location: registrar_validation.php"); // Refresh the page
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Registrar Management</h1>

        <!-- Buttons to open modals -->
        <div class="mb-6">
            <button type="button" class="bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-green-700 transition" onclick="openCourseModal()">Add Course</button>
            <button type="button" class="bg-yellow-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-yellow-700 transition" onclick="openStatusModal()">Add Status</button>
            <button type="button" class="bg-purple-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-purple-700 transition" onclick="openSexModal()">Add Sex Option</button>
        </div>

        <!-- Table of students -->
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b">ID</th>
                    <th class="px-6 py-3 border-b">Name</th>
                    <!-- <th class="px-6 py-3 border-b">Course</th> -->
                    <th class="px-6 py-3 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td class="px-6 py-4 border-b"><?= htmlspecialchars($student['student_id']) ?></td>
                        <td class="px-6 py-4 border-b"><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?></td>
                        <!-- <td class="px-6 py-4 border-b"><?= htmlspecialchars($student['course_id']) ?></td> -->
                        <td class="px-6 py-4 border-b">
                            <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition" onclick='openStudentModal(<?= json_encode($student) ?>)'>Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <!-- Modal for adding courses -->
<div id="courseModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Add Course</h2>
        <form action="registrar_validation.php" method="post">
            <input type="hidden" name="add_course" value="1">
            <div class="mb-4">
                <label for="course_name" class="block text-gray-700 mb-2">Course Name</label>
                <input type="text" id="course_name" name="course_name" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>
            <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-green-700 transition">Add Course</button>
            <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow-md hover:bg-gray-400 transition" onclick="closeCourseModal()">Cancel</button>
        </form>
    </div>
</div>

<!-- Modal for adding statuses -->
<div id="statusModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Add Status</h2>
        <form action="registrar_validation.php" method="post">
            <input type="hidden" name="add_status" value="1">
            <div class="mb-4">
                <label for="status_name" class="block text-gray-700 mb-2">Status Name</label>
                <input type="text" id="status_name" name="status_name" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>
            <button type="submit" class="bg-yellow-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-yellow-700 transition">Add Status</button>
            <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow-md hover:bg-gray-400 transition" onclick="closeStatusModal()">Cancel</button>
        </form>
    </div>
</div>

<!-- Modal for adding sex options -->
<div id="sexModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Add Sex Option</h2>
        <form action="registrar_validation.php" method="post">
            <input type="hidden" name="add_sex" value="1">
            <div class="mb-4">
                <label for="sex_name" class="block text-gray-700 mb-2">Sex Name</label>
                <input type="text" id="sex_name" name="sex_name" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>
            <button type="submit" class="bg-purple-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-purple-700 transition">Add Sex Option</button>
            <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow-md hover:bg-gray-400 transition" onclick="closeSexModal()">Cancel</button>
        </form>
    </div>
</div>

<!-- Modal for editing student info -->
<div id="studentModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-4xl">
        <h2 class="text-xl font-semibold mb-4">Edit Student Info</h2>
        <form action="registrar_validation.php" method="post">
            <input type="hidden" name="update_student" value="1">
            <input type="hidden" id="student_id" name="student_id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="firstname" class="block text-gray-700 mb-2">First Name</label>
                    <input type="text" id="firstname" name="firstname" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label for="middlename" class="block text-gray-700 mb-2">Middle Name</label>
                    <input type="text" id="middlename" name="middlename" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="lastname" class="block text-gray-700 mb-2">Last Name</label>
                    <input type="text" id="lastname" name="lastname" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label for="suffix" class="block text-gray-700 mb-2">Suffix</label>
                    <input type="text" id="suffix" name="suffix" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="statusofenrollment" class="block text-gray-700 mb-2">Status of Enrollment</label>
                    <input type="text" id="statusofenrollment" name="statusofenrollment" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="status" class="block text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <?php foreach ($statusOptions as $option): ?>
                            <option value="<?= htmlspecialchars($option['status_name']) ?>"><?= htmlspecialchars($option['status_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="year" class="block text-gray-700 mb-2">Year</label>
                    <input type="text" id="year" name="year" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="course_id" class="block text-gray-700 mb-2">Course</label>
                    <select id="course_id" name="course_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= htmlspecialchars($course['id']) ?>"><?= htmlspecialchars($course['course_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="sex" class="block text-gray-700 mb-2">Sex</label>
                    <select id="sex" name="sex" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <?php foreach ($sexOptions as $option): ?>
                            <option value="<?= htmlspecialchars($option['sex_name']) ?>"><?= htmlspecialchars($option['sex_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="dob" class="block text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" id="dob" name="dob" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="address" class="block text-gray-700 mb-2">Address</label>
                    <input type="text" id="address" name="address" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="contact_no" class="block text-gray-700 mb-2">Contact Number</label>
                    <input type="text" id="contact_no" name="contact_no" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-blue-700 transition">Update Info</button>
            <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow-md hover:bg-gray-400 transition" onclick="closeStudentModal()">Cancel</button>
        </form>
    </div>
</div>

    <script>
        function openCourseModal() {
            document.getElementById('courseModal').classList.remove('hidden');
        }

        function closeCourseModal() {
            document.getElementById('courseModal').classList.add('hidden');
        }

        function openStatusModal() {
            document.getElementById('statusModal').classList.remove('hidden');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }

        function openSexModal() {
            document.getElementById('sexModal').classList.remove('hidden');
        }

        function closeSexModal() {
            document.getElementById('sexModal').classList.add('hidden');
        }

        function openStudentModal(student) {
            document.getElementById('student_id').value = student.student_id;
            document.getElementById('firstname').value = student.firstname;
            document.getElementById('middlename').value = student.middlename;
            document.getElementById('lastname').value = student.lastname;
            document.getElementById('suffix').value = student.suffix;
            document.getElementById('statusofenrollment').value = student.statusofenrollment;
            document.getElementById('status').value = student.status;
            document.getElementById('year').value = student.year;
            document.getElementById('course_id').value = student.course_id;
            document.getElementById('sex').value = student.sex;
            document.getElementById('dob').value = student.dob;
            document.getElementById('address').value = student.address;
            document.getElementById('email').value = student.email;
            document.getElementById('contact_no').value = student.contact_no;
            document.getElementById('studentModal').classList.remove('hidden');
        }

        function closeStudentModal() {
            document.getElementById('studentModal').classList.add('hidden');
        }
    </script>
</body>
</html>
