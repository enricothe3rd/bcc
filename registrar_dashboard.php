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
        header("Location: registrar_dashboard.php"); // Refresh the page
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
            header("Location: registrar_dashboard.php"); // Refresh the page
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
            header("Location: registrar_dashboard.php"); // Refresh the page
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
        header("Location: registrar_dashboard.php"); // Refresh the page
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes and Subjects</title>
    <link href="./output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
 <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
 <link rel="stylesheet" href="styles.css">


 <style>
 
 </style>
</head>
<body class="font-sans h-full">

<div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Registrar Management</h1>

        <!-- Buttons to open modals -->
        <div class="mb-6">
            <button type="button" class="customRed textWhite px-6 py-3 rounded-lg shadow-lg customRedHover transition" onclick="openCourseModal()">Add Course</button>
            <button type="button" class="customRed textWhite px-6 py-3 rounded-lg shadow-lg customRedHover transition" onclick="openStatusModal()">Add Status</button>
            <button type="button" class="customRed textWhite px-6 py-3 rounded-lg shadow-lg customRedHover transition" onclick="openSexModal()">Add Sex Option</button>
            <button id="openModalButton" class="customRed textWhite px-6 py-3 rounded-lg shadow-lg customRedHover transition">Add Class</button>
            <button id="openModalButton1" class="customRed textWhite px-6 py-3 rounded-lg shadow-lg customRedHover transition">Add Subject</button>
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
        <form action="registrar_dashboard.php" method="post">
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
        <form action="registrar_dashboard.php" method="post">
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
        <form action="registrar_dashboard.php" method="post">
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
        <form action="registrar_dashboard.php" method="post">
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

 <!-- ========================================================================================================== -->

    <!-- START OF ADD CLASS -->
    <div class="flex flex-col items-center">
    <h2 class="text-2xl font-semibold text-custom-red ml-6 lg:ml-24">Classes and Subjects</h2>
    <div class="mt-4">
        <!-- BUTTON FOR ADD CLASS -->
        <!-- <button id="openModalButton" class="bg-custom-red text-white px-4 py-2 rounded mt-4 lg:m-11">Add Class</button> -->
        <!-- BUTTON FOR ADD SUBJECT -->
        <!-- <button id="openModalButton1" class="bg-custom-red text-white px-4 py-2 rounded mt-4 ml-4">Add Subject</button> -->

        <!-- MODAL FOR ADD CLASS -->
        <div id="myModal" class="fixed z-10 inset-0 hidden">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50" id="modalBackdrop"></div>
            <div class="flex items-center justify-center min-h-screen px-4 lg:px-10">
                <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg"> <!-- Adjust width for responsiveness -->
                    <div class="px-6 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-custom-black">Add Class</h3>
                        <div class="mt-2">
                            <form action="create_class.php" method="post">
                                <label for="name" class="block text-sm font-medium text-custom-black mt-4">Class Name:</label>
                                <input type="text" id="name" name="name" required class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                
                                <label for="description" class="block text-sm font-medium text-custom-black mt-4">Description:</label>
                                <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                
                                <div class="mt-4 flex justify-end">
                                    <button type="button" id="closeModalButton" class="bg-gray-500 text-custom-white px-4 py-2 rounded mr-2">Cancel</button>
                                    <button type="submit" class="bg-yellow-300 text-custom-black font-bold px-4 py-2 rounded ml-10">Add Class</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const openModalButton = document.getElementById('openModalButton');
            const closeModalButton = document.getElementById('closeModalButton');
            const modal = document.getElementById('myModal');
            const modalBackdrop = document.getElementById('modalBackdrop');

            openModalButton.addEventListener('click', () => {
                modal.classList.remove('hidden');
            });

            closeModalButton.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            modalBackdrop.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        </script>
    </div>
</div>

    <!-- END OF ADD CLASS -->

     <!-- ========================================================================================================== -->

<!-- START OF ADD SUBJECT -->
<!-- <button id="openModalButton1" class="bg-custom-red text-white px-4 py-2 rounded mt-4 ml-4">Add Subject</button> -->

<!-- MODAL FOR ADD SUBJECT -->
<div id="myModal1" class="fixed inset-0 hidden z-50 overflow-auto">
    <div class="modal-backdrop absolute inset-0 bg-gray-900 opacity-50"></div>
    <div class="flex items-center justify-center min-h-screen p-6">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-xl w-full">
            <div class="px-6 py-5" style="max-height: 80vh; overflow-y: auto;">
                <h3 class="text-2xl font-semibold text-gray-900 mb-6">Add Subjects</h3>
                <form id="add-subject-form" action="create_subject.php" method="post">
                    <div class="mb-4">
                        <label for="class_id" class="block text-sm font-medium text-gray-700">Select Class:</label>
                        <select id="class_id" name="class_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2">
                            <?php
                            require 'db_connection1.php'; // Ensure this file sets up the $pdo object
                            $stmt = $pdo->query("SELECT id, name FROM classes");
                            while ($row = $stmt->fetch()) {
                                echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div id="subjects-container" class="mt-4">
                        <!-- Initial subject input fields -->
                        <div class="subject-group mb-4 border border-gray-300 p-4 rounded-md relative">
                            <button type="button" class="remove-subject absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded">X</button>
                            <div class="mb-4">
                                <label for="code[]" class="block text-sm font-medium text-gray-700">Code:</label>
                                <input type="text" name="code[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2">
                            </div>
                            <div class="mb-4">
                                <label for="subject_title[]" class="block text-sm font-medium text-gray-700">Subject Title:</label>
                                <input type="text" name="subject_title[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2">
                            </div>
                            <div class="mb-4">
                                <label for="units[]" class="block text-sm font-medium text-gray-700">Units:</label>
                                <select name="units[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2">
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="room[]" class="block text-sm font-medium text-gray-700">Room:</label>
                                <input type="text" name="room[]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2">
                            </div>
                            <div class="mb-4">
                                <label for="day[]" class="block text-sm font-medium text-gray-700">Day:</label>
                                <select name="day[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2">
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="start_time[]" class="block text-sm font-medium text-gray-700">Start Time:</label>
                                <input type="time" name="start_time[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2">
                            </div>
                            <div class="mb-4">
                                <label for="end_time[]" class="block text-sm font-medium text-gray-700">End Time:</label>
                                <input type="time" name="end_time[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2">
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addSubjectField()" class="bg-yellow-500 text-white px-4 py-2 rounded mt-4">Add Another Subject</button>
                    <div class="mt-4 flex justify-end space-x-4">
                        <button type="button" id="closeModalButton1" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-yellow-500 text-white font-bold px-4 py-2 rounded">Add Subjects</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>// JavaScript to handle modal visibility
const openModalButton1 = document.getElementById('openModalButton1');
const closeModalButton1 = document.getElementById('closeModalButton1');
const modal1 = document.getElementById('myModal1');
const classSelect = document.getElementById('class_id');
const form = document.getElementById('add-subject-form');
const subjectsContainer = document.getElementById('subjects-container');

openModalButton1.addEventListener('click', () => {
    modal1.classList.remove('hidden');
});

closeModalButton1.addEventListener('click', () => {
    modal1.classList.add('hidden');
});

// Optional: Close modal when clicking outside of it
modal1.addEventListener('click', (event) => {
    if (event.target === modal1) {
        modal1.classList.add('hidden');
    }
});

classSelect.addEventListener('change', resetForm);

function resetForm() {
    // Save the current class selection
    const selectedClassId = classSelect.value;

    // Reset the form fields
    form.reset();

    // Re-select the previously selected class
    classSelect.value = selectedClassId;

    // Remove all additional subject fields, leaving only one set
    const subjectGroups = subjectsContainer.querySelectorAll('.subject-group');
    while (subjectsContainer.children.length > 1) {
        subjectsContainer.removeChild(subjectsContainer.lastChild);
    }

    // Clear the values of the initial subject fields
    subjectGroups.forEach(group => {
        const inputs = group.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.value = '';
        });
    });
}

function addSubjectField() {
    const subjectGroup = document.createElement('div');
    subjectGroup.classList.add('subject-group', 'mb-4', 'border', 'border-gray-300', 'p-4', 'rounded-md', 'relative');

    subjectGroup.innerHTML = `
        <button type="button" class="remove-subject absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded">X</button>
        <div class="mb-4">
            <label for="code[]" class="block text-sm font-medium text-gray-700">Code:</label>
            <input type="text" name="code[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2">
        </div>
        <div class="mb-4">
            <label for="subject_title[]" class="block text-sm font-medium text-gray-700">Subject Title:</label>
            <input type="text" name="subject_title[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2">
        </div>
        <div class="mb-4">
            <label for="units[]" class="block text-sm font-medium text-gray-700">Units:</label>
            <select name="units[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2">
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="room[]" class="block text-sm font-medium text-gray-700">Room:</label>
            <input type="text" name="room[]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2">
        </div>
        <div class="mb-4">
            <label for="day[]" class="block text-sm font-medium text-gray-700">Day:</label>
            <select name="day[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2">
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="start_time[]" class="block text-sm font-medium text-gray-700">Start Time:</label>
            <input type="time" name="start_time[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2">
        </div>
        <div class="mb-4">
            <label for="end_time[]" class="block text-sm font-medium text-gray-700">End Time:</label>
            <input type="time" name="end_time[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2">
        </div>
    `;

    subjectsContainer.appendChild(subjectGroup);

    // Attach event listener to remove button in the new subject group
    const removeButtons = subjectsContainer.querySelectorAll('.remove-subject');
    removeButtons.forEach(button => {
        button.addEventListener('click', () => {
            subjectsContainer.removeChild(button.closest('.subject-group'));
        });
    });
}

// Attach event listener to existing remove buttons
const initialRemoveButtons = document.querySelectorAll('.remove-subject');
initialRemoveButtons.forEach(button => {
    button.addEventListener('click', () => {
        subjectsContainer.removeChild(button.closest('.subject-group'));
    });
});
</script>



    <!-- END OF ADD SUBJECT -->


 <!-- ========================================================================================================== -->

    <!--  SEARCH BAR -->
    <div class="flex justify-center">
    <div class="search-bar w-1/2">
        <input id="search-input" type="text" placeholder="Search classes and subjects..." class="w-full p-2 border rounded-md shadow-sm">
    </div>
</div>

  <!-- SEARCH BAR -->
  <div id="results">
    <form id="deleteForm" action="delete_selected.php" method="post">

      
        
        <?php
        require 'db_connection1.php';

        $stmt_classes = $pdo->query("SELECT * FROM classes");
        $classes = $stmt_classes->fetchAll(PDO::FETCH_ASSOC);

        // Initialize totals
        $totalSubjects = 0;
        $totalUnits = 0;

        echo "<div class='class-container' style='width: 70%; margin: 0 auto; margin-bottom: 20px;'>";
        echo "<div class='class-header' style='margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #ddd;'>";
      
        echo "<h3 class='text-xl font-semibold text-gray-600 inline' data-class-name='All Classes'>All Classes</h3>";
        echo "<div class='inline'>";
        echo "<a href='#' class='text-blue-500 hover:underline ml-2' onclick='selectAllClasses()'>Select All Classes</a> | ";
        echo "<a href='#' class='text-blue-500 hover:underline ml-2' onclick='selectAllSubjects()'>Select All Subjects</a>";

        echo '<button id="delete-selected" type="submit" onclick="return confirm(\'Are you sure you want to delete selected classes and subjects?\')" class="text-blue-500 hover:underline ml-2 opacity-50 cursor-not-allowed" disabled>
            Delete Selected Classes and Subjects
        </button>';
   
        
        echo "</div>";
        echo "</div>";

        foreach ($classes as $class) {
            $classId = htmlspecialchars($class['id']);
            $className = htmlspecialchars($class['name']);
            $classDescription = htmlspecialchars($class['description']);

            echo "<div class='class-container' style='margin-bottom: 20px;'>";
            echo "<div class='class-header' style='margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #ddd;'>";
            echo "<input type='checkbox' name='classes[]' value='{$classId}' class='class-checkbox' data-class-id='{$classId}' onclick='toggleClassSubjects({$classId})'>";
            echo "<h3 class='text-xl font-semibold text-gray-600 inline' data-class-name='{$className}'>{$className}</h3>";

            echo "<div class='inline'>";
            echo "<a href='#' onclick='openModal1({$classId})' class='text-blue-500 hover:underline ml-2'>Edit</a> | ";
            echo "<a href='delete_class.php?id={$classId}' class='text-red-500 hover:underline ml-2' onclick='return confirm(\"Are you sure you want to delete this class and all its subjects?\")'>Delete</a>";
            echo "</div>";

            echo "</div>";
            echo "<p class='text-gray-500' style='margin-bottom: 10px;' data-class-description='{$classDescription}'>{$classDescription}</p>";

            $stmt_subjects = $pdo->prepare("SELECT * FROM subjects WHERE class_id = ?");
            $stmt_subjects->execute([$classId]);
            $subjects = $stmt_subjects->fetchAll(PDO::FETCH_ASSOC);

            // Count subjects and sum units
            $classTotalSubjects = 0;
            $classTotalUnits = 0;

            if ($subjects) {
                echo "<div class='print-container' style='width: 100%; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); overflow-x:auto;'>";
                echo "<table style='width: 100%; border-collapse: collapse;' class='subject-table'>";
                echo "<thead style='background-color: #f9f9f9;'>";
                echo "<tr>";
                echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Select</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Code</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Subject Title</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Units</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Room</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Day</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Start Time</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>End Time</th>";
                echo "<th class=' style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Actions</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                foreach ($subjects as $subject) {
                    $subjectId = htmlspecialchars($subject['id']);
                    $subjectCode = htmlspecialchars($subject['code']);
                    $subjectTitle = htmlspecialchars($subject['subject_title']);
                    $subjectUnits = htmlspecialchars($subject['units']);
                    $subjectRoom = htmlspecialchars($subject['room']);
                    $subjectDay = htmlspecialchars($subject['day']);

                    // Convert TIME format to AM/PM format
                    $subjectStartTime = date('g:i A', strtotime($subject['start_time']));
                    $subjectEndTime = date('g:i A', strtotime($subject['end_time']));

                    // Update totals
                    $classTotalSubjects++;
                    $classTotalUnits += $subjectUnits;
                    echo "<tr>";
                    echo "<td style='text-align: center; border: 1px solid #ddd; padding: 8px;'><input type='checkbox' name='subjects[]' value='{$subjectId}' class='subject-checkbox' data-class-id='{$classId}' onclick='updateClassCheckbox({$classId})'></td>"; // Subject checkbox
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$subjectCode}</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$subjectTitle}</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$subjectUnits}</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$subjectRoom}</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$subjectDay}</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$subjectStartTime}</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$subjectEndTime}</td>";
                    echo "<td class='print-actions' style='border: 1px solid #ddd; padding: 8px;'>";
                    echo "<a href='delete_subject.php?id={$subjectId}' class='text-red-500 hover:underline' onclick='return confirm(\"Are you sure you want to delete this subject?\")'>Delete</a> | ";
                    echo "<a href='edit_subject.php?id={$subjectId}' class='text-blue-500 hover:underline ml-2' onclick='openModal({$subjectId}); return false;'>Edit</a>";
                    echo "</td>";
                    echo "</tr>";
                    
                }

                echo "</tbody>";

                // Add footer row with totals
                echo "<tfoot style='background-color: #f9f9f9;'>";
                echo "<tr>";
                echo "<td colspan='4' style='border: 1px solid #ddd; padding: 8px;'></td>";
                echo "<td style='border: 1px solid #ddd; padding: 8px;'><strong>Total Subjects:</strong> <span class='text-normal'>{$classTotalSubjects}</span></td>";
                echo "<td colspan='3' style='border: 1px solid #ddd; padding: 8px;'><strong>Total Units:</strong> <span class='text-normal'>{$classTotalUnits}</span></td>";
                echo "</tr>";  
                echo "</tfoot>";

                echo "</table>";
                echo "</div>";

                // Update overall totals
                $totalSubjects += $classTotalSubjects;
                $totalUnits += $classTotalUnits;
            } else {
                echo "<p class='text-gray-500'>No subjects found for this class.</p>";
            }

            echo "</div>";
        }
        ?>
  
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to class and subject checkboxes
    document.querySelectorAll('.class-checkbox, .subject-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateButtonsState);
    });

    // Add event listener to the "Select All" buttons
    document.getElementById('select-all-classes').addEventListener('click', selectAllClasses);
    document.getElementById('select-all-subjects').addEventListener('click', selectAllSubjects);
    
    // Initial call to set button states based on current checkbox status
    updateButtonsState();
});

function selectAll(masterCheckbox, checkboxClass) {
    var checkboxes = document.querySelectorAll('.' + checkboxClass);
    var allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

    // Toggle checkboxes based on the current state
    var newCheckedState = !allChecked;
    checkboxes.forEach(checkbox => checkbox.checked = newCheckedState);

    // If selecting all classes, also update subjects
    if (checkboxClass === 'class-checkbox') {
        updateAllSubjects(newCheckedState);
    }
    
    // Update button states
    updateButtonsState();
}

function selectAllClasses() {
    var classCheckboxes = document.querySelectorAll('.class-checkbox');
    var allChecked = Array.from(classCheckboxes).every(checkbox => checkbox.checked);

    var masterCheckbox = document.createElement('input');
    masterCheckbox.type = 'checkbox';
    masterCheckbox.checked = !allChecked;
    selectAll(masterCheckbox, 'class-checkbox');
}

function selectAllSubjects() {
    var subjectCheckboxes = document.querySelectorAll('.subject-checkbox');
    var allChecked = Array.from(subjectCheckboxes).every(checkbox => checkbox.checked);

    var masterCheckbox = document.createElement('input');
    masterCheckbox.type = 'checkbox';
    masterCheckbox.checked = !allChecked;
    selectAll(masterCheckbox, 'subject-checkbox');
}

function toggleClassSubjects(classId) {
    var classCheckbox = document.querySelector(`input.class-checkbox[data-class-id='${classId}']`);
    var subjectCheckboxes = document.querySelectorAll(`input.subject-checkbox[data-class-id='${classId}']`);

    subjectCheckboxes.forEach(subjectCheckbox => {
        subjectCheckbox.checked = classCheckbox.checked;
    });

    updateClassCheckbox(classId);
    updateButtonsState();
}

function updateClassCheckbox(classId) {
    var classCheckbox = document.querySelector(`input.class-checkbox[data-class-id='${classId}']`);
    var subjectCheckboxes = document.querySelectorAll(`input.subject-checkbox[data-class-id='${classId}']`);
    var allChecked = Array.from(subjectCheckboxes).every(checkbox => checkbox.checked);

    classCheckbox.checked = allChecked;
    updateButtonsState();
}

function updateAllSubjects(checked) {
    var subjectCheckboxes = document.querySelectorAll('.subject-checkbox');
    subjectCheckboxes.forEach(subjectCheckbox => {
        subjectCheckbox.checked = checked;
    });
    updateButtonsState();
}

function updateButtonsState() {
    var deleteSelectedButton = document.getElementById('delete-selected');

    var anyClassChecked = Array.from(document.querySelectorAll('.class-checkbox')).some(checkbox => checkbox.checked);
    var anySubjectChecked = Array.from(document.querySelectorAll('.subject-checkbox')).some(checkbox => checkbox.checked);

    deleteSelectedButton.disabled = !(anyClassChecked || anySubjectChecked);
}

function updateButtonsState() {
    var deleteSelectedButton = document.getElementById('delete-selected');

    var anyClassChecked = Array.from(document.querySelectorAll('.class-checkbox')).some(checkbox => checkbox.checked);
    var anySubjectChecked = Array.from(document.querySelectorAll('.subject-checkbox')).some(checkbox => checkbox.checked);

    if (anyClassChecked || anySubjectChecked) {
        deleteSelectedButton.disabled = false;
        deleteSelectedButton.classList.remove('opacity-50', 'cursor-not-allowed');
        deleteSelectedButton.classList.add('opacity-100', 'cursor-pointer');
    } else {
        deleteSelectedButton.disabled = true;
        deleteSelectedButton.classList.remove('opacity-100', 'cursor-pointer');
        deleteSelectedButton.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

</script>


<!-- ========================================================================================================== -->
<!-- EDIT SUBJECT MODAL -->
<!-- Modal HTML Structure -->
<div id="editSubjectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full relative">
        <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Edit Subject</h2>
        <div id="modalContent">
            <!-- Content will be injected here -->
        </div>
    </div>
</div>
<script>
        function closeSubjectModal() {
            document.getElementById('editSubjectModal').classList.add('hidden');
        }
</script>

<script>
function openModal(subjectId) {
    // Fetch the modal content via AJAX
    fetch(`edit_subject.php?id=${subjectId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('modalContent').innerHTML = html;
            document.getElementById('editSubjectModal').classList.remove('hidden');
        })
        .catch(error => console.error('Error fetching modal content:', error));
}

document.getElementById('closeModal').addEventListener('click', () => {
    document.getElementById('editSubjectModal').classList.add('hidden');
});
</script>

<!-- EDIT SUBJECT MODAL -->
<!-- ========================================================================================================== -->


<!-- EDIT CLASS MODAL -->
<!-- ========================================================================================================== -->
<!-- Modal HTML Structure -->
<div id="editClassModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full relative">
        <button id="closeModal1" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Edit Class</h2>
        <div id="modalContent1">
            <!-- Content will be injected here -->
        </div>
    </div>
</div>

<script>
function openModal1(classId) {
    // Fetch the modal content via AJAX
    fetch(`edit_class.php?id=${classId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('modalContent1').innerHTML = html;
            document.getElementById('editClassModal').classList.remove('hidden');
        })
        .catch(error => console.error('Error fetching modal content:', error));
}

document.getElementById('closeModal1').addEventListener('click', () => {
    document.getElementById('editClassModal').classList.add('hidden');
});
</script>

<!-- EDIT CLASS MODAL -->
<!-- ========================================================================================================== -->



</body>
</html>
