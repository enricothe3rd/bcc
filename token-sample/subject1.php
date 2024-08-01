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
    <style>
        @media print {
            .no-print {
                display: none;
            }
            .print-container {
                width: 100% !important;
                margin: 0 !important;
                overflow: visible !important;
            }
            .button-container {
                display: none;
            }
            .print-actions {
                display: none;
            }
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .print-button {
            background-color: #3b82f6;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        .print-button:hover {
            background-color: #2563eb;
        }

        .search-bar {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
        }

        .highlight {
            background-color: yellow;
            font-weight: bold;
        }
    </style>
</head>
<body class="font-sans h-full">


 <!-- ========================================================================================================== -->

    <!-- START OF ADD CLASS -->
<div class="flex flex-col items-center">
    <h2 class="text-2xl font-semibold text-custom-red ml-6 lg:ml-24">Classes and Subjects</h2>
    <div class="mt-4">
        <button id="openModalButton" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 lg:m-11">Add Class</button>

        <!-- Modal -->
        <div id="myModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen px-4 lg:px-10">
                <div class="bg-custom-red rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg"> <!-- Adjust width for responsiveness -->
                    <div class="px-6 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-custom-white">Add Class</h3>
                        <div class="mt-2">
                            <form action="create_class.php" method="post">
                                <label for="name" class="block text-sm font-medium text-custom-white mt-4">Class Name:</label>
                                <input type="text" id="name" name="name" required class="mt-1 block w-full rounded-md border-2 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                
                                <label for="description" class="block text-sm font-medium text-custom-white mt-4">Description:</label>
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

            openModalButton.addEventListener('click', () => {
                modal.classList.remove('hidden');
            });

            closeModalButton.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        </script>
     
    </div>
</div>
    <!-- END OF ADD CLASS -->

     <!-- ========================================================================================================== -->

    <!-- START OF ADD SUBJECT -->
    <button id="openModalButton1" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 ml-4">Open Modal</button>

<!-- Modal -->
<div id="myModal1" class="fixed inset-0 hidden z-50">
    <div class="modal-backdrop absolute inset-0 bg-gray-900 opacity-50"></div>
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg w-full">
            <div class="px-6 py-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Add Subjects</h3>
                <div class="mt-2">
                    <form action="create_subject.php" method="post">
                        <div class="mb-4">
                            <label for="class_id" class="block text-sm font-medium text-gray-700">Select Class:</label>
                            <select id="class_id" name="class_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <?php
                                // PHP code to fetch class options
                                require 'db_connection1.php'; // Ensure this file sets up the $pdo object
                                $stmt = $pdo->query("SELECT id, name FROM classes");
                                while ($row = $stmt->fetch()) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div id="subjects-container" class="mt-4">
                            <!-- Initial subject input fields -->
                            <div class="mb-4">
                                <label for="code[]" class="block text-sm font-medium text-gray-700">Code:</label>
                                <input type="text" name="code[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="mb-4">
                                <label for="subject_title[]" class="block text-sm font-medium text-gray-700">Subject Title:</label>
                                <input type="text" name="subject_title[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="mb-4">
                                <label for="units[]" class="block text-sm font-medium text-gray-700">Units:</label>
                                <input type="text" name="units[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="mb-4">
                                <label for="room[]" class="block text-sm font-medium text-gray-700">Room:</label>
                                <input type="text" name="room[]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="mb-4">
                                <label for="day[]" class="block text-sm font-medium text-gray-700">Day:</label>
                                <input type="date" name="day[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="mb-4">
                                <label for="hours[]" class="block text-sm font-medium text-gray-700">Hours:</label>
                                <input type="number" name="hours[]" min="0" max="23" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="mb-4">
                                <label for="minutes[]" class="block text-sm font-medium text-gray-700">Minutes:</label>
                                <input type="number" name="minutes[]" min="0" max="59" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        <button type="button" onclick="addSubjectField()" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Add Another Subject</button><br><br>
                        <div class="mt-4 flex justify-end">
                            <button type="button" id="closeModalButton1" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                            <button type="submit" class="bg-yellow-300 text-black font-bold px-4 py-2 rounded ml-10">Add Subjects</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to handle modal visibility
    const openModalButton1 = document.getElementById('openModalButton1');
    const closeModalButton1 = document.getElementById('closeModalButton1');
    const modal1 = document.getElementById('myModal1');

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

    function addSubjectField() {
        const container = document.getElementById('subjects-container');
        const subjectDiv = document.createElement('div');

        subjectDiv.innerHTML = `
            <div class="mb-4">
                <label for="code[]" class="block text-sm font-medium text-gray-700">Code:</label>
                <input type="text" name="code[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="subject_title[]" class="block text-sm font-medium text-gray-700">Subject Title:</label>
                <input type="text" name="subject_title[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="units[]" class="block text-sm font-medium text-gray-700">Units:</label>
                <input type="text" name="units[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="room[]" class="block text-sm font-medium text-gray-700">Room:</label>
                <input type="text" name="room[]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="day[]" class="block text-sm font-medium text-gray-700">Day:</label>
                <input type="date" name="day[]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="hours[]" class="block text-sm font-medium text-gray-700">Hours:</label>
                <input type="number" name="hours[]" min="0" max="23" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="minutes[]" class="block text-sm font-medium text-gray-700">Minutes:</label>
                <input type="number" name="minutes[]" min="0" max="59" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
        `;

        container.appendChild(subjectDiv);
    }
</script>

    <!-- END OF ADD SUBJECT -->


 <!-- ========================================================================================================== -->

    <!--  SEARCH BAR -->
    <div class="search-bar">
        <input id="search-input" type="text" placeholder="Search classes and subjects..." class="w-full p-2 border rounded-md shadow-sm">
    </div>
    <!--  SEARCH BAR -->

    <div id="results">
        <?php
        require 'db_connection1.php';

        $stmt_classes = $pdo->query("SELECT * FROM classes");
        $classes = $stmt_classes->fetchAll(PDO::FETCH_ASSOC);

        foreach ($classes as $class) {
            $classId = htmlspecialchars($class['id']);
            $className = htmlspecialchars($class['name']);
            $classDescription = htmlspecialchars($class['description']);

            echo "<div class='class-container' style='width: 70%; margin: 0 auto; margin-bottom: 20px;'>";
            echo "<div class='class-header' style='margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #ddd;'>";
            echo "<h3 class='text-xl font-semibold text-gray-600 inline' data-class-name='{$className}'>{$className}</h3>";

            echo "<div class='no-print inline'>";
            echo "<a href='edit_class.php?id={$classId}' class='text-blue-500 hover:underline ml-2'>Edit</a> | ";
            echo "<a href='delete_class.php?id={$classId}' class='text-red-500 hover:underline ml-2' onclick='return confirm(\"Are you sure you want to delete this class and all its subjects?\")'>Delete</a>";
            echo "</div>";

            echo "</div>";
            echo "<p class='text-gray-500' style='margin-bottom: 10px;' data-class-description='{$classDescription}'>{$classDescription}</p>";

            $stmt_subjects = $pdo->prepare("SELECT * FROM subjects WHERE class_id = ?");
            $stmt_subjects->execute([$classId]);
            $subjects = $stmt_subjects->fetchAll(PDO::FETCH_ASSOC);

            if ($subjects) {
                echo "<div class='print-container' style='width: 100%; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>";
                echo "<table style='width: 100%; border-collapse: collapse;' class='subject-table'>";
                echo "<thead style='background-color: #f9f9f9;'>";
                echo "<tr>";
                echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Code</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Subject Title</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Units</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Room</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Day & Time</th>";
                echo "<th class='print-actions' style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Actions</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                foreach ($subjects as $subject) {
                    $subjectId = htmlspecialchars($subject['id']);
                    $subjectCode = htmlspecialchars($subject['code']);
                    $subjectTitle = htmlspecialchars($subject['subject_title']);
                    $subjectUnits = htmlspecialchars($subject['units']);
                    $subjectRoom = htmlspecialchars($subject['room']);
                    $subjectDayTime = htmlspecialchars($subject['day_time']);

                    echo "<tr>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$subjectCode}</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$subjectTitle}</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$subjectUnits}</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$subjectRoom}</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$subjectDayTime}</td>";
                    echo "<td class='print-actions' style='border: 1px solid #ddd; padding: 8px;'>";
                    echo "<a href='delete_subject.php?id={$subjectId}' class='text-red-500 hover:underline no-print' onclick='return confirm(\"Are you sure you want to delete this subject?\")'>Delete</a> | ";
                    echo "<a href='edit_subject.php?id={$subjectId}' class='text-blue-500 hover:underline no-print'>Edit</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                

                echo "</tbody>";
                echo "</table>";
                echo "</div>";
            } else {
                echo "<p class='text-gray-500'>No subjects found for this class.</p>";
            }

            echo "</div>";
        }
        ?>
    </div>

    <!-- PRINT BUTTON -->
    <div class="button-container">
        <button onclick="printTable()" class="print-button no-print">Print Form</button>
    </div>
    <!-- /PRINT BUTTON -->

   <script>
    document.getElementById('search-input').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const classContainers = document.querySelectorAll('.class-container');

        classContainers.forEach(container => {
            const className = container.querySelector('.class-header h3').textContent.toLowerCase();
            const subjectRows = container.querySelectorAll('.subject-table tbody tr');

            let classMatch = className.includes(searchTerm);
            let subjectMatch = false;

            subjectRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let rowText = '';
                cells.forEach(cell => {
                    rowText += cell.textContent.toLowerCase() + ' ';
                });

                if (rowText.includes(searchTerm)) {
                    row.style.display = '';
                    highlightRow(row, searchTerm);
                    subjectMatch = true;
                } else {
                    row.style.display = 'none';
                }
            });

            if (classMatch || subjectMatch) {
                container.style.display = '';
            } else {
                container.style.display = 'none';
            }
        });
    });

    function highlightRow(row, searchTerm) {
        const cells = row.querySelectorAll('td');
        cells.forEach(cell => {
            const text = cell.textContent;
            const highlightedText = text.replace(new RegExp(searchTerm, 'gi'), match => `<span class='highlight'>${match}</span>`);
            cell.innerHTML = highlightedText;
        });
    }

    function printTable() {
        window.print();
    }
</script>
</body>
</html>
