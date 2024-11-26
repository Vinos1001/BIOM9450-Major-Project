<?php
require_once('../TCPDF-main/tcpdf.php'); // Adjust the path to your TCPDF installation

// Include the log_action function
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

// Get the PatientID from the URL
$patient_id = $_GET['patient_id'];

// Fetch patient details
$patient_query = "SELECT * FROM Patient WHERE PatientID = '$patient_id'";
$patient_result = $conn->query($patient_query);
$patient = $patient_result->fetch_assoc();

// Fetch phenotypes
$phenotype_query = "SELECT * FROM Phenotypes WHERE PatientID = '$patient_id'";
$phenotype_result = $conn->query($phenotype_query);

// Fetch mutations
$mutation_query = "SELECT * FROM Mutations WHERE PatientID = '$patient_id'";
$mutation_result = $conn->query($mutation_query);

// Fetch diagnostics
$diagnostic_query = "SELECT * FROM Diagnostics WHERE PatientID = '$patient_id'";
$diagnostic_result = $conn->query($diagnostic_query);

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Patient Report');
$pdf->SetSubject('Patient Report');
$pdf->SetKeywords('TCPDF, PDF, report, patient');

// Add a page
$pdf->AddPage();

// Write the HTML content
$html = '
<h1>Patient Details</h1>
<table border="1" cellpadding="4">
    <tr>
        <th>Patient ID</th>
        <td>' . $patient['PatientID'] . '</td>
    </tr>
    <tr>
        <th>Name</th>
        <td>' . $patient['Name'] . '</td>
    </tr>
    <tr>
        <th>Date of Birth</th>
        <td>' . $patient['DOB'] . '</td>
    </tr>
    <tr>
        <th>Sex</th>
        <td>' . $patient['Sex'] . '</td>
    </tr>
    <tr>
        <th>Phone Number</th>
        <td>' . $patient['PhoneNumber'] . '</td>
    </tr>
    <tr>
        <th>Address</th>
        <td>' . $patient['Address'] . '</td>
    </tr>
    <tr>
        <th>Diagnostic Information</th>
        <td>' . $patient['DiagnosticInformation'] . '</td>
    </tr>
    <tr>
        <th>Genetic Mutations</th>
        <td>' . $patient['GeneticMutations'] . '</td>
    </tr>
</table>

<h2>Phenotypes</h2>
<table border="1" cellpadding="4">
    <tr>
        <th>Phenotype ID</th>
        <th>Description</th>
        <th>Date Recorded</th>
    </tr>';

while ($row = $phenotype_result->fetch_assoc()) {
    $html .= '
    <tr>
        <td>' . $row['PhenotypeID'] . '</td>
        <td>' . $row['Description'] . '</td>
        <td>' . $row['DateRecorded'] . '</td>
    </tr>';
}

$html .= '
</table>

<h2>Mutations</h2>
<table border="1" cellpadding="4">
    <tr>
        <th>Mutation ID</th>
        <th>Gene Involved</th>
        <th>Mutation Type</th>
        <th>Impact on Health</th>
    </tr>';

while ($row = $mutation_result->fetch_assoc()) {
    $html .= '
    <tr>
        <td>' . $row['MutationID'] . '</td>
        <td>' . $row['GeneInvolved'] . '</td>
        <td>' . $row['MutationType'] . '</td>
        <td>' . $row['ImpactOnHealth'] . '</td>
    </tr>';
}

$html .= '
</table>

<h2>Diagnostics</h2>
<table border="1" cellpadding="4">
    <tr>
        <th>Diagnosis ID</th>
        <th>Diagnosis Type</th>
        <th>Date of Diagnosis</th>
    </tr>';

while ($row = $diagnostic_result->fetch_assoc()) {
    $html .= '
    <tr>
        <td>' . $row['DiagnosisID'] . '</td>
        <td>' . $row['DiagnosisType'] . '</td>
        <td>' . $row['DateOfDiagnosis'] . '</td>
    </tr>';
}

$html .= '
</table>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Log the action
$clinician_id = $_SESSION['clinician_id'];
$action = "Generated patient PDF";
log_action($conn, $clinician_id, $action, $patient_id, "Generated PDF for patient ID $patient_id");

// Close and output PDF document
$pdf->Output('patient_report.pdf', 'I');

// Close the database connection
$conn->close();
?>