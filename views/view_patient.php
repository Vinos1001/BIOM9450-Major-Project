<?php

include('log_action.php');
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];

// Include the database connection file
include('../db/db_connection.php');
//echo "Welcome, you are successfully logged in as $username.";

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

// log action 
$clinician_id = $_SESSION['clinician_id'];
$action = "Viewed patient details";
log_action($conn, $clinician_id, $action, $patient_id, "Viewed details of patient ID $patient_id");

?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../assets/css/view_patient.css">
    <title>Patient Details</title>
</head>

<body>
    <?php include("../includes/header.php"); ?>

    <br>
    <br>
    <br>
    <h1>Patient Details</h1>

    <!-- Patient profile image -->
    <img src='../assets/default.jpg' alt="Profile Image" class="profile-image">
    <a href="edit_patient.php?patient_id=<?php echo $patient_id; ?>" style="text-decoration: none;">
        <button type="button">Edit</button>
    </a>
    <a href="generate_patient_pdf.php?patient_id=<?php echo $patient_id; ?>" style="text-decoration: none;">
        <button type="button">Save as PDF</button>
    </a>

    <form>
        <h2>General Information</h2>
        <table>
            <tr>
                <th>Patient ID</th>
                <td><?php echo $patient['PatientID']; ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><?php echo $patient['Name']; ?></td>
            </tr>
            <tr>
                <th>Date of Birth</th>
                <td><?php echo $patient['DOB']; ?></td>
            </tr>
            <tr>
                <th>Sex</th>
                <td><?php echo $patient['Sex']; ?></td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td><?php echo $patient['PhoneNumber']; ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo $patient['Address']; ?></td>
            </tr>
            <tr>
                <th>Diagnostic Information</th>
                <td><?php echo $patient['DiagnosticInformation']; ?></td>
            </tr>
            <tr>
                <th>Genetic Mutations</th>
                <td><?php echo $patient['GeneticMutations']; ?></td>
            </tr>
        </table>

        <h2>Phenotypes</h2>
        <table>
            <tr>
                <th>Phenotype ID</th>
                <th>Description</th>
                <th>Date Recorded</th>
            </tr>
            <?php while ($row = $phenotype_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['PhenotypeID']; ?></td>
                    <td><?php echo $row['Description']; ?></td>
                    <td><?php echo $row['DateRecorded']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>Mutations</h2>
        <table>
            <tr>
                <th>Mutation ID</th>
                <th>Gene Involved</th>
                <th>Mutation Type</th>
                <th>Impact on Health</th>
            </tr>
            <?php while ($row = $mutation_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['MutationID']; ?></td>
                    <td><?php echo $row['GeneInvolved']; ?></td>
                    <td><?php echo $row['MutationType']; ?></td>
                    <td><?php echo $row['ImpactOnHealth']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>Diagnostics</h2>
        <table>
            <tr>
                <th>Diagnosis ID</th>
                <th>Diagnosis Type</th>
                <th>Date of Diagnosis</th>
            </tr>
            <?php while ($row = $diagnostic_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['DiagnosisID']; ?></td>
                    <td><?php echo $row['DiagnosisType']; ?></td>
                    <td><?php echo $row['DateOfDiagnosis']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <!-- Link back to Dashboard -->
    </form>
</body>

<?php include("../includes/footer.php"); ?>

</html>

<?php
// Close the database connection
$conn->close();
?>