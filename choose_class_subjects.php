<?php
require 'db_connection1.php';

if (isset($_GET['class_id'])) {
    $class_id = (int)$_GET['class_id'];

    $stmt = $pdo->prepare("SELECT id, code, subject_title, units, room, day_time FROM subjects WHERE class_id = ?");
    if (!$stmt->execute([$class_id])) {
        echo json_encode(['error' => 'Query execution failed']);
        exit;
    }

    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($subjects)) {
        echo json_encode(['error' => 'No subjects found']);
        exit;
    }

    header('Content-Type: application/json');
    echo json_encode($subjects);
    exit;
} else {
    echo json_encode(['error' => 'class_id not set']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Choose Class and Subjects</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        form {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            max-width: 600px;
            margin: auto;
        }
        label {
            font-weight: bold;
        }
        select, input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .subject-details {
            display: flex;
            flex-direction: column;
        }
        .subject-detail {
            background-color: #e9e9e9;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
    <script>
        function loadSubjects() {
            var classId = document.getElementById('class_id').value;

            // Fetch subjects via AJAX based on selected classId
            fetch('get_subjects.php?class_id=' + classId)
                .then(response => response.json())
                .then(data => {
                    var subjectContainer = document.getElementById('subject_details');
                    subjectContainer.innerHTML = ''; // Clear existing content

                    if (data.error) {
                        console.error('Error fetching subjects:', data.error);
                        var div = document.createElement('div');
                        div.textContent = 'No subjects found';
                        subjectContainer.appendChild(div);
                        return;
                    }

                    data.forEach(subject => {
                        var div = document.createElement('div');
                        div.className = 'subject-detail';
                        div.innerHTML = `
                            <strong>Code:</strong> ${subject.code} <br>
                            <strong>Title:</strong> ${subject.subject_title} <br>
                            <strong>Units:</strong> ${subject.units} <br>
                            <strong>Room:</strong> ${subject.room} <br>
                            <strong>Day & Time:</strong> ${subject.day_time}
                        `;
                        subjectContainer.appendChild(div);
                    });
                })
                .catch(error => {
                    console.error('Error fetching subjects:', error);
                });
        }
    </script>
</head>
<body>
    <h2>Choose Class and Subjects</h2>
    <form action="process_enrollment.php" method="post">
        <label for="class_id">Select Class:</label><br>
        <select id="class_id" name="class_id" onchange="loadSubjects()" required>
            <?php
            require 'db_connection1.php';

            $stmt = $pdo->query("SELECT id, name FROM classes");
            while ($class = $stmt->fetch()) {
                echo "<option value='" . $class['id'] . "'>" . $class['name'] . "</option>";
            }
            ?>
        </select><br><br>

        <label for="subject_details">Subjects:</label><br>
        <div id="subject_details" class="subject-details">
            <!-- Subject details will be populated dynamically via JavaScript -->
        </div><br><br>

        <input type="submit" value="Enroll">
    </form>
</body>
</html>
