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
        /* Print-specific styles */
        @media print {
            .no-print, .print-hide, .print-heading {
                display: none !important; /* Hide specified elements in print view */
            }
            .print-container {
                width: 100% !important; /* Use fixed width if necessary */
                margin: 0 !important; /* Remove any extra margin */
                overflow: visible !important;
            }
        }

        /* Additional styles for button alignment */
        .button-container {
            display: flex;
            justify-content: center; /* Center the button horizontally */
            margin: 20px 0; /* Adjust margin as needed */
        }
        .print-button {
            background-color: #3b82f6; /* Blue color */
            color: #000; /* Text color */
            padding: 0.5rem 1rem; /* Adjust padding */
            border-radius: 0.25rem; /* Rounded corners */
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        .print-button:hover {
            background-color: #2563eb; /* Darker blue on hover */
        }
    </style>
</head>
<body class="font-sans h-full">
    <div class="flex items-center flex-col">
        <h2 class="text-2xl font-semibold text-custom-red ml-24 print-heading">Classes and Subjects</h2>
        <div class="mt-4 print-hide">
            <a href="create_class.php" class="text-custom-black hover:underline mr-4">Add New Class</a>
            <a href="create_subject.php" class="text-blue-500 hover:underline">Add New Subject</a>
        </div>
    </div>

    <br><br>
    <?php
require 'db_connection1.php';

// Fetch and display classes and their subjects
$stmt_classes = $pdo->query("SELECT * FROM classes");

while ($class = $stmt_classes->fetch()) {
    $classId = htmlspecialchars($class['id']);
    $className = htmlspecialchars($class['name']);
    $classDescription = htmlspecialchars($class['description']);

    echo "<div class='class-container' style='width: 70%; margin: 0 auto; margin-bottom: 20px;'>";
    echo "<div class='class-header print-hide' style='margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #ddd;'>";
    echo "<h3 class='text-xl font-semibold text-gray-600 inline'>{$className}</h3>";
    echo "<a href='edit_class.php?id={$classId}' class='text-blue-500 hover:underline ml-2'>Edit</a> | ";
    echo "<a href='delete_class.php?id={$classId}' class='text-red-500 hover:underline ml-2' onclick='return confirm(\"Are you sure you want to delete this class and all its subjects?\")'>Delete</a>";
    echo "</div>";
    echo "<p class='text-gray-500 print-hide' style='margin-bottom: 10px;'>{$classDescription}</p>";

    // Display subjects for the class
    $stmt_subjects = $pdo->prepare("SELECT * FROM subjects WHERE class_id = ?");
    $stmt_subjects->execute([$classId]);

    echo "<div class='print-container' style='width: 100%; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>";
    echo "<table style='width: 100%; border-collapse: collapse;'>";
    echo "<thead style='background-color: #f9f9f9;'>";
    echo "<tr>";
    echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Code</th>";
    echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Subject Title</th>";
    echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Units</th>";
    echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Room</th>";
    echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Day & Time</th>";
    echo "<th style='border: 1px solid #ddd; padding: 8px; text-align: left;'>Actions</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    while ($subject = $stmt_subjects->fetch()) {
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
        echo "<td style='border: 1px solid #ddd; padding: 8px;'>";
        echo "<a href='delete_subject.php?id={$subjectId}' style='color: #e53e3e; text-decoration: none;' onclick='return confirm(\"Are you sure you want to delete this subject?\")'>Delete</a> | ";
        echo "<a href='edit_subject.php?id={$subjectId}' style='color: #3182ce; text-decoration: none;'>Edit</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>"; // End of print-container
    echo "</div>"; // End of class-container
}
?>

    <div class="button-container">
        <button onclick="printTable()" class="print-button no-print">Print Form</button>
    </div>
    
    <script>
    function printTable() {
        // Get the table container element
        const tableContainer = document.querySelector('.print-container');

        // Add the print-specific class to handle overflow and max-width
        if (tableContainer) {
            tableContainer.classList.add('print-container');
        }

        // Trigger the print dialog
        window.print();

        // Wait until the print dialog has closed before restoring the class
        setTimeout(() => {
            if (tableContainer) {
                tableContainer.classList.remove('print-container');
            }
        }, 1000); // Adjust the timeout if needed
    }
    </script>
</body>
</html>



<!-- 
<script>
        const button = document.getElementById('tooltipButton');
        const tooltip = document.getElementById('tooltip-right');

        button.addEventListener('mouseover', () => {
            tooltip.classList.add('tooltip-visible');
        });

        button.addEventListener('mouseout', () => {
            tooltip.classList.remove('tooltip-visible');
        });

        button.addEventListener('focus', () => {
            tooltip.classList.add('tooltip-visible');
        });

        button.addEventListener('blur', () => {
            tooltip.classList.remove('tooltip-visible');
        });
    </script>
    

    
<style>
        /* Tooltip visibility handling */
        .tooltip {
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .tooltip-visible {
            visibility: visible;
            opacity: 1;
        }
    </style>


    <div class="relative inline-block">
        <button id="tooltipButton" type="button" class="text-black bg-black hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-bg-black font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Tooltip right
        </button>
        <div id="tooltip-right" role="tooltip" class="tooltip absolute top-1/2 left-full ml-2 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-lg">
            Tooltip on right
            <div class="tooltip-arrow absolute w-2.5 h-2.5 -translate-x-1/2 rotate-45 bg-gray-900"></div>
        </div> -->


