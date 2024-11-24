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

// log action 
$clinician_id = $_SESSION['clinician_id'];
$action = "Viewed patient details";
log_action($conn, $clinician_id, $action, $patient_id, "Viewed details of patient ID $patient_id");

?>

<!DOCTYPE html>
<html>

<head>
    <title>Patient Details</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <!-- Include your menu -->
    <?php include("include_menu.php"); ?>

    <h1>Patient Details</h1>

    <!-- Patient profile image -->
    <img src='../assets/default.jpg' alt="Profile Image" class="profile-image">

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
    <nav>
        <a href="dashboard.php">Back to Dashboard</a>
    </nav>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>