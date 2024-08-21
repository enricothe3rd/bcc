<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate PDF</title>
</head>
<body>
    <button id="generatePdfBtn">Generate PDF</button>

    <script>
        document.getElementById('generatePdfBtn').addEventListener('click', function () {
            window.location.href = 'generate_pdf.php';
        });
    </script>
</body>
</html>
