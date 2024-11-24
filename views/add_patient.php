<?php
// Include database connection
include '../db/db_connection.php'; // Adjust the path as needed

// Initialize variables to hold error/success messages
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect data from the form
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $sex = $_POST['sex'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $diagnostics_info = $_POST['diagnostics_info'];
    $genetic_mutations = $_POST['genetic_mutations'];
    $phenotype_description = $_POST['phenotype_description'];
    $phenotype_date = $_POST['phenotype_date'];
    $mutation_gene = $_POST['mutation_gene'];
    $mutation_type = $_POST['mutation_type'];
    $impact_on_health = $_POST['impact_on_health'];
    $diagnosis_type = $_POST['diagnosis_type'];
    $date_of_diagnosis = $_POST['date_of_diagnosis'];

    // Insert data into the Patient table
    $query = "INSERT INTO patient (Name, DOB, Sex, PhoneNumber, Address, DiagnosticInformation, GeneticMutations)
              VALUES ('$name', '$dob', '$sex', '$phone_number', '$address', '$diagnostics_info', '$genetic_mutations')";

    if (mysqli_query($conn, $query)) {
        // Get the last inserted PatientID
        $patient_id = mysqli_insert_id($conn);

        // Insert data into the Phenotypes table
        $query = "INSERT INTO phenotypes (Description, PatientID, DateRecorded)
                  VALUES ('$phenotype_description', '$patient_id', '$phenotype_date')";
        mysqli_query($conn, $query);

        // Insert data into the Mutations table
        $query = "INSERT INTO mutationvariants (GeneInvolved, MutationType, HealthImpact, PatientID)
                  VALUES ('$mutation_gene', '$mutation_type', '$impact_on_health', '$patient_id')";
        mysqli_query($conn, $query);

        // Insert data into the Diagnostics table
        $query = "INSERT INTO diagnostics (DiagnosisType, DateOfDiagnosis, PatientID)
                  VALUES ('$diagnosis_type', '$date_of_diagnosis', '$patient_id')";
        mysqli_query($conn, $query);

        $success = "Patient added successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Patient</title>
</head>

<nav>

    <a href="dashboard.php">Dashboard</a>
</nav>
<br>

<body>
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
        <textarea id="genetic_mutations" name="genetic_mutations" required></textarea><br><br>

        <label for="phenotype_description">Phenotype Description:</label>
        <input type="text" id="phenotype_description" name="phenotype_description" required><br><br>

        <label for="phenotype_date">Phenotype Date (YYYY-MM-DD):</label>
        <input type="date" id="phenotype_date" name="phenotype_date" required><br><br>

        <label for="mutation_gene">Mutation Gene:</label>
        <input type="text" id="mutation_gene" name="mutation_gene" required><br><br>

        <label for="mutation_type">Mutation Type:</label>
        <input type="text" id="mutation_type" name="mutation_type" required><br><br>

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