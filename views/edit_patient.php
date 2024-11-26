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

// Include the log_action function
include('log_action.php');

// Include database connection
include '../db/db_connection.php'; // Adjust the path as needed
echo "Welcome, you are successfully logged in as $username.";

// Get the PatientID from the URL
$patient_id = $_GET['patient_id'];

// Fetch existing patient data
$query = "SELECT * FROM Patient WHERE PatientID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

// Fetch existing phenotypes, mutations, and diagnostics
$phenotypes = [];
$mutations = [];
$diagnostics = [];

$query = "SELECT * FROM Phenotypes WHERE PatientID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$phenotype_result = $stmt->get_result();
while ($row = $phenotype_result->fetch_assoc()) {
    $phenotypes[] = $row;
}

$query = "SELECT * FROM Mutations WHERE PatientID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$mutation_result = $stmt->get_result();
while ($row = $mutation_result->fetch_assoc()) {
    $mutations[] = $row;
}

$query = "SELECT * FROM Diagnostics WHERE PatientID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$diagnostic_result = $stmt->get_result();
while ($row = $diagnostic_result->fetch_assoc()) {
    $diagnostics[] = $row;
}

// Initialize variables to hold error/success messages
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize data from the form
    $name = htmlspecialchars($_POST['name']);
    $dob = htmlspecialchars($_POST['dob']);
    $sex = htmlspecialchars($_POST['sex']);
    $diagnostics_info = htmlspecialchars($_POST['diagnostics_info']);
    $phone_number = htmlspecialchars($_POST['phone_number']) ?: null;
    $address = htmlspecialchars($_POST['address']) ?: null;
    $genetic_mutations = htmlspecialchars($_POST['genetic_mutations']) ?: null;

    // Update patient data
    $query = "UPDATE Patient SET Name=?, DOB=?, Sex=?, PhoneNumber=?, Address=?, DiagnosticInformation=?, GeneticMutations=?
              WHERE PatientID=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssi", $name, $dob, $sex, $phone_number, $address, $diagnostics_info, $genetic_mutations, $patient_id);

    if ($stmt->execute()) {
        // Log the action
        $clinician_id = $_SESSION['clinician_id'];
        $action = "Updated patient";
        log_action($conn, $clinician_id, $action, $patient_id, "Updated patient ID $patient_id");

        // Process phenotypes
        foreach ($_POST['phenotypes'] as $index => $phenotype) {
            $phenotype_id = $phenotype['id'];
            $description = htmlspecialchars($phenotype['description']);
            $date_recorded = htmlspecialchars($phenotype['date_recorded']);
            if ($description && $date_recorded) {
                if ($phenotype_id) {
                    $query = "UPDATE Phenotypes SET Description=?, DateRecorded=? WHERE PhenotypeID=?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ssi", $description, $date_recorded, $phenotype_id);
                } else {
                    $query = "INSERT INTO Phenotypes (Description, PatientID, DateRecorded) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("sis", $description, $patient_id, $date_recorded);
                }
                $stmt->execute();
            }
        }

        // Process mutations
        foreach ($_POST['mutations'] as $index => $mutation) {
            $mutation_id = $mutation['id'];
            $gene_involved = htmlspecialchars($mutation['gene']);
            $mutation_type = htmlspecialchars($mutation['type']);
            $impact_on_health = htmlspecialchars($mutation['impact']);
            if ($gene_involved && $mutation_type && $impact_on_health) {
                if ($mutation_id) {
                    $query = "UPDATE Mutations SET GeneInvolved=?, MutationType=?, ImpactOnHealth=? WHERE MutationID=?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("sssi", $gene_involved, $mutation_type, $impact_on_health, $mutation_id);
                } else {
                    $query = "INSERT INTO Mutations (GeneInvolved, MutationType, ImpactOnHealth, PatientID) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("sssi", $gene_involved, $mutation_type, $impact_on_health, $patient_id);
                }
                $stmt->execute();
            }
        }

        // Process diagnostics
        foreach ($_POST['diagnostics'] as $index => $diagnostic) {
            $diagnostic_id = $diagnostic['id'];
            $diagnosis_type = htmlspecialchars($diagnostic['type']);
            $date_of_diagnosis = htmlspecialchars($diagnostic['date']);
            if ($diagnosis_type && $date_of_diagnosis) {
                if ($diagnostic_id) {
                    $query = "UPDATE Diagnostics SET DiagnosisType=?, DateOfDiagnosis=? WHERE DiagnosisID=?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ssi", $diagnosis_type, $date_of_diagnosis, $diagnostic_id);
                } else {
                    $query = "INSERT INTO Diagnostics (DiagnosisType, DateOfDiagnosis, PatientID) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ssi", $diagnosis_type, $date_of_diagnosis, $patient_id);
                }
                $stmt->execute();
            }
        }

        $success = "Patient updated successfully!";
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
    <title>Edit Patient</title>
    <script>
        function addPhenotype() {
            var container = document.getElementById('phenotypes');
            var index = container.children.length;
            container.insertAdjacentHTML('beforeend', `
                <div class="phenotype">
                    <label for="phenotypes[${index}][description]">Description:</label>
                    <input type="text" name="phenotypes[${index}][description]" required>
                    <label for="phenotypes[${index}][date_recorded]">Date Recorded:</label>
                    <input type="date" name="phenotypes[${index}][date_recorded]" required>
                    <input type="hidden" name="phenotypes[${index}][id]" value="">
                </div>
            `);
        }

        function addMutation() {
            var container = document.getElementById('mutations');
            var index = container.children.length;
            container.insertAdjacentHTML('beforeend', `
                <div class="mutation">
                    <label for="mutations[${index}][gene]">Gene Involved:</label>
                    <input type="text" name="mutations[${index}][gene]" required>
                    <label for="mutations[${index}][type]">Mutation Type:</label>
                    <input type="text" name="mutations[${index}][type]" required>
                    <label for="mutations[${index}][impact]">Impact on Health:</label>
                    <input type="text" name="mutations[${index}][impact]" required>
                    <input type="hidden" name="mutations[${index}][id]" value="">
                </div>
            `);
        }

        function addDiagnostic() {
            var container = document.getElementById('diagnostics');
            var index = container.children.length;
            container.insertAdjacentHTML('beforeend', `
                <div class="diagnostic">
                    <label for="diagnostics[${index}][type]">Diagnosis Type:</label>
                    <input type="text" name="diagnostics[${index}][type]" required>
                    <label for="diagnostics[${index}][date]">Date of Diagnosis:</label>
                    <input type="date" name="diagnostics[${index}][date]" required>
                    <input type="hidden" name="diagnostics[${index}][id]" value="">
                </div>
            `);
        }
    </script>
</head>

<body>
    <nav>
        <a href="view_patient.php?patient_id=<?php echo $patient_id; ?>">View Patient Details</a>
        &nbsp;
        <a href="dashboard.php">Dashboard</a>
        &nbsp;
        <a href="logout.php">Logout</a>
    </nav>
    <br>

    <h1>Edit Patient</h1>

    <!-- Display error or success messages -->
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <!-- Patient form -->
    <form action="edit_patient.php?patient_id=<?php echo $patient_id; ?>" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($patient['Name']); ?>"
            required><br><br>

        <label for="dob">Date of Birth (YYYY-MM-DD):</label>
        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($patient['DOB']); ?>"
            required><br><br>

        <label for="sex">Sex:</label>
        <select id="sex" name="sex" required>
            <option value="Male" <?php if ($patient['Sex'] == 'Male')
                echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if ($patient['Sex'] == 'Female')
                echo 'selected'; ?>>Female</option>
            <option value="Other" <?php if ($patient['Sex'] == 'Other')
                echo 'selected'; ?>>Other</option>
        </select><br><br>

        <label for="diagnostics_info">Diagnostics Info:</label>
        <textarea id="diagnostics_info" name="diagnostics_info"
            required><?php echo htmlspecialchars($patient['DiagnosticInformation']); ?></textarea><br><br>

        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number"
            value="<?php echo htmlspecialchars($patient['PhoneNumber']); ?>"><br><br>

        <label for="address">Address:</label>
        <textarea id="address" name="address"><?php echo htmlspecialchars($patient['Address']); ?></textarea><br><br>

        <label for="genetic_mutations">Genetic Mutations:</label>
        <textarea id="genetic_mutations"
            name="genetic_mutations"><?php echo htmlspecialchars($patient['GeneticMutations']); ?></textarea><br><br>

        <h3>Phenotypes</h3>
        <div id="phenotypes">
            <?php foreach ($phenotypes as $index => $phenotype): ?>
                <div class="phenotype">
                    <label for="phenotypes[<?php echo $index; ?>][description]">Description:</label>
                    <input type="text" name="phenotypes[<?php echo $index; ?>][description]"
                        value="<?php echo htmlspecialchars($phenotype['Description']); ?>" required>
                    <label for="phenotypes[<?php echo $index; ?>][date_recorded]">Date Recorded:</label>
                    <input type="date" name="phenotypes[<?php echo $index; ?>][date_recorded]"
                        value="<?php echo htmlspecialchars($phenotype['DateRecorded']); ?>" required>
                    <input type="hidden" name="phenotypes[<?php echo $index; ?>][id]"
                        value="<?php echo $phenotype['PhenotypeID']; ?>">
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" onclick="addPhenotype()">Add Phenotype</button><br><br>

        <h3>Mutations</h3>
        <div id="mutations">
            <?php foreach ($mutations as $index => $mutation): ?>
                <div class="mutation">
                    <label for="mutations[<?php echo $index; ?>][gene]">Gene Involved:</label>
                    <input type="text" name="mutations[<?php echo $index; ?>][gene]"
                        value="<?php echo htmlspecialchars($mutation['GeneInvolved']); ?>" required>
                    <label for="mutations[<?php echo $index; ?>][type]">Mutation Type:</label>
                    <input type="text" name="mutations[<?php echo $index; ?>][type]"
                        value="<?php echo htmlspecialchars($mutation['MutationType']); ?>" required>
                    <label for="mutations[<?php echo $index; ?>][impact]">Impact on Health:</label>
                    <input type="text" name="mutations[<?php echo $index; ?>][impact]"
                        value="<?php echo htmlspecialchars($mutation['ImpactOnHealth']); ?>" required>
                    <input type="hidden" name="mutations[<?php echo $index; ?>][id]"
                        value="<?php echo $mutation['MutationID']; ?>">
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" onclick="addMutation()">Add Mutation</button><br><br>

        <h3>Diagnostics</h3>
        <div id="diagnostics">
            <?php foreach ($diagnostics as $index => $diagnostic): ?>
                <div class="diagnostic">
                    <label for="diagnostics[<?php echo $index; ?>][type]">Diagnosis Type:</label>
                    <input type="text" name="diagnostics[<?php echo $index; ?>][type]"
                        value="<?php echo htmlspecialchars($diagnostic['DiagnosisType']); ?>" required>
                    <label for="diagnostics[<?php echo $index; ?>][date]">Date of Diagnosis:</label>
                    <input type="date" name="diagnostics[<?php echo $index; ?>][date]"
                        value="<?php echo htmlspecialchars($diagnostic['DateOfDiagnosis']); ?>" required>
                    <input type="hidden" name="diagnostics[<?php echo $index; ?>][id]"
                        value="<?php echo $diagnostic['DiagnosisID']; ?>">
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" onclick="addDiagnostic()">Add Diagnostic</button><br><br>

        <button type="submit">Submit</button>
    </form>
</body>

</html>