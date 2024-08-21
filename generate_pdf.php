<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering
ob_start();

// Include TCPDF and database connection
require 'vendor/autoload.php';
require_once 'db_connection1.php';

// Fetch data from the database
try {
    $stmt = $pdo->query('
        SELECT e.*, c.course_name 
        FROM enrollment e
        JOIN courses c ON e.course_id = c.id
    ');
    $enrollments = $stmt->fetchAll();
} catch (PDOException $e) {
    // Log the error and redirect to an error page
    error_log("Database error: " . $e->getMessage());
    ob_end_clean();
    header('Location: error_page.php');
    exit;
}

// Function to capitalize the first letter of each word
function capitalizeWords($string) {
    return ucwords(strtolower($string));
}
class MYPDF extends TCPDF {
    // Page header
    public function Header() {
        // Override header to remove text
    }

    // Page footer
    public function Footer() {
        // Override footer to remove text
    }
}

// Create new PDF document using the customized class
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Enrollment Data');
$pdf->SetSubject('Enrollment Details');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Set default header data
$pdf->SetHeaderData('', 0, '', '');

// Set header and footer fonts
$pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
$pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Set font
$pdf->SetFont('helvetica', '', 10);

// Add a page
$pdf->AddPage();

// Set content for PDF with styling
$html = '<style> 
        .course { color: #ff0000; } /* Change this to your desired color */
        span { font-weight: 700;}
        .small { font-size: 11px; } /* Define a class for smaller text */
</style>  
<h3>Registration Form</h3>';

foreach ($enrollments as $row) {
    $html .= '<strong>Student No: </strong> <u class="small">'.capitalizeWords($row['student_id']).'</u>
    
<p class="label"><span>Student:</span> <u class="small">'.capitalizeWords($row['lastname']). " ".capitalizeWords($row['firstname']).$row['suffix']. " ".capitalizeWords($row['middlename']). " " . '</u>

<span>   </span>
<span>Year:</span> <u class="small">'.capitalizeWords($row['year']). " " . ' </u> 

<span>   </span>
<span>Course:</span> <u class="course small">'.capitalizeWords($row['course_name']).'</u>

<span>   </span>
<span>Major:</span> <u class="course small">'.capitalizeWords($row['course_name']).'</u>

<span>   </span>
<span>Sex:</span> <u class="small">'.capitalizeWords($row['sex']).'</u></p>

<span>   </span> <span><span>   </span><span>   </span>
<sup>(LAST NAME, GIVEN NAME, MIDDLE NAME)</sup>

<p><span>Date of Birth:</span> <u class="small">'.capitalizeWords($row['dob']).'</u>

<span> </span>
<span>Present Address:</span> <u class="small">'.capitalizeWords($row['address']).'</u>

<span>   </span>
<span>Email Address:</span> <u class="small">'.capitalizeWords($row['email']).'</u></p>
<p style="text-align:right;"><span>Contact No:</span> <u class="small">'.capitalizeWords($row['contact_no']).'</u></p>

<p><strong>Status:</strong> <span class="small">'.capitalizeWords($row['status']).'</span></p>
<hr>';
}



// Set content for PDF with styling
$html = '<style> 
        .course { color: #ff0000; } /* Change this to your desired color */
        span { font-weight: 700;}
        .small { font-size: 11px; } /* Define a class for smaller text */
</style>  
<h3>Registration Form</h3>';

foreach ($enrollments as $row) {
    $html .= '<strong>Student No: </strong> <u class="small">'.capitalizeWords($row['student_id']).'</u>
    
<p class="label"><span>Student:</span> <u class="small">'.capitalizeWords($row['lastname']). " ".capitalizeWords($row['firstname']).$row['suffix']. " ".capitalizeWords($row['middlename']). " " . '</u>

<span>   </span>
<span>Year:</span> <u class="small">'.capitalizeWords($row['year']). " " . ' </u> 

<span>   </span>
<span>Course:</span> <u class="course small">'.capitalizeWords($row['course_name']).'</u>

<span>   </span>
<span>Major:</span> <u class="course small">'.capitalizeWords($row['course_name']).'</u>

<span>   </span>
<span>Sex:</span> <u class="small">'.capitalizeWords($row['sex']).'</u></p>

<span>   </span> <span><span>   </span><span>   </span>
<sup>(LAST NAME, GIVEN NAME, MIDDLE NAME)</sup>

<p><span>Date of Birth:</span> <u class="small">'.capitalizeWords($row['dob']).'</u>

<span> </span>
<span>Present Address:</span> <u class="small">'.capitalizeWords($row['address']).'</u>

<span>   </span>
<span>Email Address:</span> <u class="small">'.capitalizeWords($row['email']).'</u></p>
<p style="text-align:right;"><span>Contact No:</span> <u class="small">'.capitalizeWords($row['contact_no']).'</u></p>

<p><strong>Status:</strong> <span class="small">'.capitalizeWords($row['status']).'</span></p>
<hr>';
}

// Output the PDF
$pdf->writeHTML($html, true, false, true, false, '');
ob_end_clean(); // Clean the buffer before sending the PDF
$pdf->Output('enrollment_data.pdf', 'I');
exit;
