<?php
require_once('../TCPDF-main/tcpdf.php'); // Adjust the path to your TCPDF installation

include('log_action.php');
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Include the database connection file
include('../db/db_connection.php');

// Retrieve search criteria and search value from the query parameters
$search_criteria = $_GET['search_criteria'] ?? '';
$search_value = $_GET['search_value'] ?? '';

// Build the search query based on the search criteria
if (!empty($search_criteria) && !empty($search_value)) {
    switch ($search_criteria) {
        case 'name':
            $search_query = "SELECT * FROM Patient WHERE Name LIKE '%$search_value%'";
            break;
        case 'dob':
            $search_query = "SELECT * FROM Patient WHERE DOB LIKE '%$search_value%'";
            break;
        case 'sex':
            $search_query = "SELECT * FROM Patient WHERE Sex LIKE '%$search_value%'";
            break;
        case 'phone_number':
            $search_query = "SELECT * FROM Patient WHERE PhoneNumber LIKE '%$search_value%'";
            break;
        case 'diagnostic_info':
            $search_query = "SELECT * FROM Patient WHERE DiagnosticInformation LIKE '%$search_value%'";
            break;
        case 'genetic_mutations':
            $search_query = "SELECT * FROM Patient WHERE GeneticMutations LIKE '%$search_value%'";
            break;
        default:
            $search_query = "SELECT * FROM Patient"; // Default: show all patients
            break;
    }
} else {
    $search_query = "SELECT * FROM Patient"; // Default: show all patients
}

$result = $conn->query($search_query);

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('All Patients Report');
$pdf->SetSubject('All Patients Report');
$pdf->SetKeywords('TCPDF, PDF, report, patients');

// add a page
$pdf->AddPage();

// write the HTML content
$html = '
<h1>All Patients Details</h1>
<table border="1" cellpadding="4">
    <tr>
        <th>Patient ID</th>
        <th>Name</th>
        <th>Date of Birth</th>
        <th>Sex</th>
        <th>Phone Number</th>
        <th>Address</th>
        <th>Diagnostic Information</th>
        <th>Genetic Mutations</th>
    </tr>';

while ($row = $result->fetch_assoc()) {
    $html .= '
    <tr>
        <td>' . $row['PatientID'] . '</td>
        <td>' . $row['Name'] . '</td>
        <td>' . $row['DOB'] . '</td>
        <td>' . $row['Sex'] . '</td>
        <td>' . $row['PhoneNumber'] . '</td>
        <td>' . $row['Address'] . '</td>
        <td>' . $row['DiagnosticInformation'] . '</td>
        <td>' . $row['GeneticMutations'] . '</td>
    </tr>';
}

$html .= '
</table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');


// Log the action 
$clinician_id = $_SESSION['clinician_id'];
$action = "Generated all patients PDF";
log_action($conn, $clinician_id, $action, null, "Generated PDF for all patients");

// close and output PDF document
$pdf->Output('all_patients_report.pdf', 'I');

// Close the database connection
$conn->close();
?>