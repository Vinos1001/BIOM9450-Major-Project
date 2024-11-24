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
    echo "Database connected successfully.<br>";
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Form submitted.<br>";

    // Sanitize and capture form inputs
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    echo "Username: " . $username . "<br>";

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
        echo "<p style='color: red;'>User not found.</p>";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<nav>
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>
</nav>
<br>

<head>
    <b>Login</b>
</head>

<body>
    <form action="login.php" method="post">
        <table>
            <tr>
                <td>Username:</td>
                <td> <input type="text" name="username" required /> </td>
            </tr>
            <tr>
                <td>Password:</td>
                <td> <input type="password" name="password" required /> </td>
            </tr>
            <tr>
                <td colspan="2"> <input type="submit" value="Login" /></td>
            </tr>
        </table>
    </form>
</body>

</html>