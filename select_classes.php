<?php
// select_classes.php
include 'db_connection1.php';

// Fetch classes for the dropdown
try {
    $stmt = $pdo->query("SELECT id, name FROM classes");
    $classes = $stmt->fetchAll();
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Handle AJAX request to fetch subjects
if (isset($_GET['class_id'])) {
    $classId = $_GET['class_id'];

    try {
        // Updated query
        $stmt = $pdo->prepare("SELECT id, code, subject_title, units, room, day, start_time, end_time FROM subjects WHERE class_id = ?");
        $stmt->execute([$classId]);
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Add AM/PM information
        foreach ($subjects as &$subject) {
            $subject['start_time_am_pm'] = date('g:i A', strtotime($subject['start_time']));
            $subject['end_time_am_pm'] = date('g:i A', strtotime($subject['end_time']));
            $subject['day_time'] = $subject['day'] . ' ' . $subject['start_time_am_pm'] . '-' . $subject['end_time_am_pm'];
        }

        echo json_encode($subjects);
    } catch (\PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Classes</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        async function loadSubjects(classId) {
            if (!classId) {
                document.getElementById('subjects').innerHTML = '';
                return;
            }

            try {
                const response = await fetch(`select_classes.php?class_id=${classId}`);
                const subjects = await response.json();

                if (subjects.error) {
                    console.error(subjects.error);
                    document.getElementById('subjects').innerHTML = `<p class="text-red-500">Error: ${subjects.error}</p>`;
                    return;
                }

                let subjectsHtml = `<h2 class="text-xl font-bold mt-4">Subjects</h2><table class="min-w-full bg-white border"><thead><tr><th class="py-2 px-4 border">ID</th><th class="py-2 px-4 border">Code</th><th class="py-2 px-4 border">Title</th><th class="py-2 px-4 border">Units</th><th class="py-2 px-4 border">Room</th><th class="py-2 px-4 border">Day & Time</th></tr></thead><tbody>`;
                
                subjects.forEach(subject => {
                    subjectsHtml += `<tr>
                        <td class="py-2 px-4 border">${subject.id}</td>
                        <td class="py-2 px-4 border">${subject.code}</td>
                        <td class="py-2 px-4 border">${subject.subject_title}</td>
                        <td class="py-2 px-4 border">${subject.units}</td>
                        <td class="py-2 px-4 border">${subject.room}</td>
                        <td class="py-2 px-4 border">${subject.day_time}</td>
                    </tr>`;
                });

                subjectsHtml += `</tbody></table>`;
                document.getElementById('subjects').innerHTML = subjectsHtml;
            } catch (error) {
                console.error('Fetch error:', error);
                document.getElementById('subjects').innerHTML = `<p class="text-red-500">Fetch error: ${error.message}</p>`;
            }
        }
    </script>
</head>
<body class="bg-gray-100 p-6">
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Select a Class</h1>
        <form>
            <div class="mb-4">
                <label for="class" class="block text-gray-700 font-bold mb-2">Class:</label>
                <select id="class" name="class" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" onchange="loadSubjects(this.value)">
                    <option value="" disabled selected>Select a class</option>
                    <?php foreach ($classes as $class): ?>
                    <option value="<?php echo htmlspecialchars($class['id']); ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
        <div id="subjects" class="mt-4"></div>
    </div>
</body>
</html>
