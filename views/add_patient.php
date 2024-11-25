<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file
include('../db/db_connection.php');

// Initialize variables for error/success messages
$error = '';
$success = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture and sanitize form inputs
    $name = htmlspecialchars($_POST['name']);
    $dob = htmlspecialchars($_POST['dob']); // Ensure proper date format
    $sex = htmlspecialchars($_POST['sex']);
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $address = htmlspecialchars($_POST['address']);
    $diagnostics_info = htmlspecialchars($_POST['diagnostics_info']);
    $genetic_mutations = htmlspecialchars($_POST['genetic_mutations']);

    // Validate inputs (e.g., ensure no empty fields)
    if (empty($name) || empty($dob) || empty($sex) || empty($phone_number) || empty($address) || empty($diagnostics_info) || empty($genetic_mutations)) {
        $error = "All fields are required.";
    } else {
        // Insert data into the database
        $query = "INSERT INTO patient (Name, DOB, Sex, PhoneNumber, Address, DiagnosticInformation, GeneticMutations) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("sssssss", $name, $dob, $sex, $phone_number, $address, $diagnostics_info, $genetic_mutations);
            if ($stmt->execute()) {
                $success = "Patient added successfully!";
            } else {
                $error = "Error adding patient: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error = "Database query error: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Patient</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body>
    <?php include("../includes/header.php"); ?>
    <h1 style="text-align: center;">Add Patient</h1>

    <!-- Display error or success messages -->
    <div class="container">
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <!-- Patient form -->
    <section> 
    <form action="add_patient.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="dob">Date of Birth (YYYY-MM-DD):</label>
        <input type="date" id="dob" name="dob" required>

        <label for="sex">Sex:</label>
        <select id="sex" name="sex" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>

        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" required>

        <label for="address">Address:</label>
        <textarea id="address" name="address" required></textarea>

        <label for="diagnostics_info">Diagnostics Info:</label>
        <textarea id="diagnostics_info" name="diagnostics_info" required></textarea>

        <label for="genetic_mutations">Genetic Mutations:</label>
        <textarea id="genetic_mutations" name="genetic_mutations" required></textarea>

        <button type="submit">Add Patient</button>
    </form>
    </section> 
</body>

</html>