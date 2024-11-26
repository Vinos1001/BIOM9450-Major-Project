<?php
// Display errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include('../db/db_connection.php');

// Test the database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
} else {
    //echo "Database connected successfully.<br>";
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //echo "Form submitted.<br>";

    // Sanitize and capture form inputs
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    //echo "Username: " . $username . "<br>";

    // Check if the clinician exists in the database
    $query = "SELECT * FROM Clinician WHERE Username = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("MySQL prepare error: " . $conn->error);
    }

    // Bind the username to the query
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check if the password matches the hashed password
        if (password_verify($password, $row['Password'])) { // Using password_verify for security
            // Start a session and store user information
            session_start();
            $_SESSION['username'] = $row['Username'];
            $_SESSION['clinician_id'] = $row['ClinicianID'];

            echo "Login successful. Redirecting to dashboard...<br>";
            header("Location: dashboard.php"); // Redirect to the dashboard
            exit();
        } else {
            echo "<p style='color: red;'>Incorrect password.</p>";
        }
    } else {
        echo "<p style='color: white; font-size:12px'>User not found.</p>";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="/assets/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>

<body>
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">

        <!-- Login Form (with username and password fields) -->
        <div class="signup">

            <form action="login.php" method="post">

                <br><br><br><br>
                <div class="login-header">
                    <h1 for="chk" aria-hidden="true" style="color: white; font-size: 2.5em; font-weight: bold;">Login</h1>
                    <img src="/includes/Logo.png" style="width: 62px; height: 50px; margin-right: 10px;"> <!-- Logo -->
                </div>
                
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
                <br><br>
                <a href="register.php" style="display: block; padding-left: 112px; color: white; size:2px;">Forgot password?</a>
            </form>
        </div>

        <!-- Register button -->
        <div class="login">
            <a href="register.php" style="text-decoration: none;">
                <button type="button">Register</button>
            </a>
        </div>

    
    </div>
</body>
<body>
    <?php include("../includes/footer.php"); ?>
</body>
</html>
