<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Retrieve session data
$username = $_SESSION['username'];

// Include the database connection file
include('../db/db_connection.php');

// Display a welcome message
echo "Welcome, you are successfully logged in as $username.";

// Initialize search query
$search_query = '';
$search_value = '';
$search_criteria = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_value'])) {
    $search_value = htmlspecialchars($_POST['search_value']);
    $search_criteria = $_POST['search_criteria'];

    // Build search query based on the search criteria
    if ($search_criteria == 'name') {
        $search_query = "SELECT * FROM Patient WHERE Name LIKE '%$search_value%'";
    } elseif ($search_criteria == 'dob') {
        $search_query = "SELECT * FROM Patient WHERE DOB LIKE '%$search_value%'";
    } elseif ($search_criteria == 'sex') {
        $search_query = "SELECT * FROM Patient WHERE Sex LIKE '%$search_value%'";
    } elseif ($search_criteria == 'phone_number') {
        $search_query = "SELECT * FROM Patient WHERE PhoneNumber LIKE '%$search_value%'";
    } elseif ($search_criteria == 'diagnostic_info') {
        $search_query = "SELECT * FROM Patient WHERE DiagnosticInformation LIKE '%$search_value%'";
    } elseif ($search_criteria == 'genetic_mutations') {
        $search_query = "SELECT * FROM Patient WHERE GeneticMutations LIKE '%$search_value%'";
    }
} else {
    $search_query = "SELECT * FROM Patient"; // Default: show all patients
}

$result = $conn->query($search_query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
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

        input[type="text"],
        select {
            padding: 5px;
        }
    </style>
</head>

<body>

    <nav>
        <a href="add_patient.php">Add Patient</a>
        &nbsp;
        <a href="logout.php">Logout</a>
    </nav>

    <h1>Patient List</h1>

    <form method="POST" action="dashboard.php">
        <select name="search_criteria">
            <option value="name" <?php if ($search_criteria == 'name')
                echo 'selected'; ?>>Name</option>
            <option value="dob" <?php if ($search_criteria == 'dob')
                echo 'selected'; ?>>Date of Birth</option>
            <option value="sex" <?php if ($search_criteria == 'sex')
                echo 'selected'; ?>>Sex</option>
            <option value="phone_number" <?php if ($search_criteria == 'phone_number')
                echo 'selected'; ?>>Phone Number
            </option>
            <option value="diagnostic_info" <?php if ($search_criteria == 'diagnostic_info')
                echo 'selected'; ?>>
                Diagnostic Info</option>
            <option value="genetic_mutations" <?php if ($search_criteria == 'genetic_mutations')
                echo 'selected'; ?>>
                Genetic Mutations</option>
        </select>
        <input type="text" name="search_value" value="<?php echo $search_value; ?>" placeholder="Search..." required />
        <input type="submit" value="Search" />
        <!-- Reset Button -->
        <a href="dashboard.php"><button type="button" class="reset-btn">Reset Filter</button></a>
    </form>

    <table>
        <tr>
            <th>Patient ID</th>
            <th>Name</th>
            <th>Date of Birth</th>
            <th>Sex</th>
            <th>Phone Number</th>
            <th>Address</th>
            <th>Diagnostic Info</th>
            <th>Genetic Mutations</th>
            <th>View Details</th>
        </tr>

        <?php
        // Display patient data
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['PatientID']}</td>
                        <td>{$row['Name']}</td>
                        <td>{$row['DOB']}</td>
                        <td>{$row['Sex']}</td>
                        <td>{$row['PhoneNumber']}</td>
                        <td>{$row['Address']}</td>
                        <td>{$row['DiagnosticInformation']}</td>
                        <td>{$row['GeneticMutations']}</td>
                        <td><a href='view_patient.php?patient_id={$row['PatientID']}'>View Details</a></td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No patients found.</td></tr>";
        }
        ?>

    </table>

    <!-- Add button to generate PDF -->
    <a
        href="generate_all_patients_pdf.php?search_criteria=<?php echo $search_criteria; ?>&search_value=<?php echo $search_value; ?>"><button
            type="button">Save All as PDF</button></a>

</body>

</html>

<?php
// Close the database connection
$conn->close();
?>