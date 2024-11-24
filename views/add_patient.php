<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
include('log_action.php');
// Include database connection
include '../db/db_connection.php'; // Adjust the path as needed


echo "Welcome, you are successfully logged in as $username.";

// Initialize variables to hold error/success messages
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize data from the form
    $name = htmlspecialchars($_POST['name']);
    $dob = htmlspecialchars($_POST['dob']);
    $sex = htmlspecialchars($_POST['sex']);
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $address = htmlspecialchars($_POST['address']);
    $diagnostics_info = htmlspecialchars($_POST['diagnostics_info']);
    $genetic_mutations = htmlspecialchars($_POST['genetic_mutations']);
    $phenotype_description = htmlspecialchars($_POST['phenotype_description']);
    $phenotype_date = htmlspecialchars($_POST['phenotype_date']);
    $mutation_gene = htmlspecialchars($_POST['mutation_gene']);
    $mutation_type = htmlspecialchars($_POST['mutation_type']);
    $impact_on_health = htmlspecialchars($_POST['impact_on_health']);
    $diagnosis_type = htmlspecialchars($_POST['diagnosis_type']);
    $date_of_diagnosis = htmlspecialchars($_POST['date_of_diagnosis']);

    // Insert data into the Patient table
    $query = "INSERT INTO Patient (Name, DOB, Sex, PhoneNumber, Address, DiagnosticInformation, GeneticMutations)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $name, $dob, $sex, $phone_number, $address, $diagnostics_info, $genetic_mutations);

    if ($stmt->execute()) {
        // Get the last inserted PatientID
        $patient_id = mysqli_insert_id($conn);

        // Log the action
        $clinician_id = $_SESSION['clinician_id'];
        $action = "Added new patient";
        log_action($conn, $clinician_id, $action, $patient_id, "Added patient ID $patient_id");

        // Insert data into the Phenotypes table
        $query = "INSERT INTO Phenotypes (Description, PatientID, DateRecorded)
                  VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sis", $phenotype_description, $patient_id, $phenotype_date);
        $stmt->execute();

        // Insert data into the Mutations table
        $query = "INSERT INTO Mutations (GeneInvolved, MutationType, ImpactOnHealth, PatientID)
                  VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $mutation_gene, $mutation_type, $impact_on_health, $patient_id);
        $stmt->execute();

        // Insert data into the Diagnostics table
        $query = "INSERT INTO Diagnostics (DiagnosisType, DateOfDiagnosis, PatientID)
                  VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $diagnosis_type, $date_of_diagnosis, $patient_id);
        $stmt->execute();

        $success = "Patient added successfully!";
    } else {
        $error = "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Patient</title>
</head>

<body>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        &nbsp;
        <a href="logout.php">Logout</a>
    </nav>
    <br>
    <h1>Add Patient</h1>


    <!-- Display error or success messages -->
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <!-- Patient form -->
    <form action="add_patient.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="dob">Date of Birth (YYYY-MM-DD):</label>
        <input type="date" id="dob" name="dob" required><br><br>

        <label for="sex">Sex:</label>
        <select id="sex" name="sex" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br><br>

        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" required><br><br>

        <label for="address">Address:</label>
        <textarea id="address" name="address" required></textarea><br><br>

        <label for="diagnostics_info">Diagnostics Info:</label>
        <textarea id="diagnostics_info" name="diagnostics_info" required></textarea><br><br>

        <label for="genetic_mutations">Genetic Mutations:</label>
        <textarea id="genetic_mutations" name="genetic_mutations"></textarea><br><br>

        <label for="phenotype_description">Phenotype Description:</label>
        <input type="text" id="phenotype_description" name="phenotype_description"><br><br>

        <label for="phenotype_date">Phenotype Date (YYYY-MM-DD):</label>
        <input type="date" id="phenotype_date" name="phenotype_date"><br><br>

        <label for="mutation_gene">Mutation Gene:</label>
        <input type="text" id="mutation_gene" name="mutation_gene"><br><br>

        <label for="mutation_type">Mutation Type:</label>
        <input type="text" id="mutation_type" name="mutation_type"><br><br>

        <label for="impact_on_health">Impact on Health:</label>
        <textarea id="impact_on_health" name="impact_on_health" required></textarea><br><br>

        <label for="diagnosis_type">Diagnosis Type:</label>
        <input type="text" id="diagnosis_type" name="diagnosis_type" required><br><br>

        <label for="date_of_diagnosis">Date of Diagnosis (YYYY-MM-DD):</label>
        <input type="date" id="date_of_diagnosis" name="date_of_diagnosis" required><br><br>

        <button type="submit">Submit</button>
    </form>
</body>

</html>