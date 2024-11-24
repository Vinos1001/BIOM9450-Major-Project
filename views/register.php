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
    $name = htmlspecialchars($_POST['name']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);
    $specialty = htmlspecialchars($_POST['specialty']);  // Capture the specialty input

    echo "Name: " . $name . "<br>";
    echo "Specialty: " . $specialty . "<br>";

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<p style='color: red;'>Passwords do not match.</p>";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query to insert a new clinician
        $query = "INSERT INTO Clinician (Username, PasswordHash, Specialty) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die("MySQL prepare error: " . $conn->error);
        }

        // Bind the parameters to the query
        $stmt->bind_param("sss", $name, $hashed_password, $specialty);  // Bind specialty as well

        // Execute the query
        if ($stmt->execute()) {
            echo "<p style='color: green;'>Registration successful. Redirecting to login page...</p>";
            header("Location: login.php"); // Redirect to the login page
            exit();
        } else {
            echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <script>
        // Validate Name
        function validateName(input) {
            const value = input.value;
            const errorField = document.getElementById(input.id + 'Error');
            const namePattern = /^[a-zA-Z\s'-]+$/;

            if (!value.match(namePattern)) {
                errorField.textContent = "Name can only contain letters, spaces, apostrophes, and hyphens.";
                return false;
            } else {
                errorField.textContent = "";
                return true;
            }
        }

        // Validate Password
        function validatePassword() {
            const password = document.getElementById('password').value;
            const errorField = document.getElementById('passwordError');
            const passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

            if (!password.match(passwordPattern)) {
                errorField.textContent = "Password must be at least 8 characters long and include uppercase, lowercase, and numbers.";
                return false;
            } else {
                errorField.textContent = "";
                return true;
            }
        }

        // Validate Confirm Password
        function validateConfirmPassword() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const errorField = document.getElementById('confirmPasswordError');

            if (password !== confirmPassword) {
                errorField.textContent = "Passwords do not match.";
                return false;
            } else {
                errorField.textContent = "";
                return true;
            }
        }

        // Validate Specialty
        function validateSpecialty() {
            const specialty = document.getElementById('specialty').value;
            const errorField = document.getElementById('specialtyError');
            const specialtyPattern = /^[a-zA-Z\s]+$/; // Allows letters and spaces only

            if (!specialty.match(specialtyPattern)) {
                errorField.textContent = "Specialty can only contain letters and spaces.";
                return false;
            } else {
                errorField.textContent = "";
                return true;
            }
        }

        // Validate the entire form
        function validateForm() {
            const nameValid = validateName(document.getElementById('name'));
            const passwordValid = validatePassword();
            const confirmPasswordValid = validateConfirmPassword();
            const specialtyValid = validateSpecialty();

            if (!nameValid || !passwordValid || !confirmPasswordValid || !specialtyValid) {
                alert("Please correct the errors in the form before submitting.");
                return false;
            }
            return true;
        }
    </script>
</head>
<!-- Navigation Bar -->
<nav>
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>

</nav>
<br>

<head>
    <b>Register</b>
</head>

<body>
    <form action="register.php" method="post" onsubmit="return validateForm();">
        <table>
            <tr>
                <td>Name:</td>
                <td>
                    <input type="text" id="name" name="name" oninput="validateName(this)" required />
                    <span id="nameError" style="color: red;"></span>
                </td>
            </tr>
            <tr>
                <td>Password:</td>
                <td>
                    <input type="password" id="password" name="password" oninput="validatePassword()" required />
                    <span id="passwordError" style="color: red;"></span>
                </td>
            </tr>
            <tr>
                <td>Confirm Password:</td>
                <td>
                    <input type="password" id="confirmPassword" name="confirm_password"
                        oninput="validateConfirmPassword()" required />
                    <span id="confirmPasswordError" style="color: red;"></span>
                </td>
            </tr>
            <tr>
                <td>Specialty:</td>
                <td>
                    <input type="text" id="specialty" name="specialty" oninput="validateSpecialty()" required />
                    <span id="specialtyError" style="color: red;"></span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="Register" />
                </td>
            </tr>
        </table>
    </form>
</body>

</html>