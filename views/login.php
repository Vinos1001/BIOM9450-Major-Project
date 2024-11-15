<?php
// Display errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include('../db/db_connection.php'); // Adjust the path if needed

// Test the database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
} else {
    echo "Database connected successfully.<br>";
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Form submitted.<br>";
    echo "Name: " . htmlspecialchars($_POST['name']) . "<br>";
    echo "Password: " . htmlspecialchars($_POST['password']) . "<br>";

    // You can now proceed with your database query logic here
}
?>


<!DOCTYPE html>
<html>

<body>


    <form action="dashboard.php" method="post">
        <table>
            <tr>
                <td>Name:</td>
                <td> <input type="text" name="name" /> </td>
            </tr>
            <tr>
                <td>Password:</td>
                <td> <input type="text" name="password" /> </td>
            </tr>
            <tr>
                <td colspan="2"> <input type="submit" value="login" /></td>
            </tr>
        </table>

    </form>
</body>

</html>