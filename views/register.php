<?php
// Display errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include('../db/db_connection.php');

// Test the database connection
if ($conn->connect_error) {
    //die("Database connection failed: " . $conn->connect_error);
} else {
    //echo "Database connected successfully.<br>";
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //echo "Form submitted aa.<br>";

    // Sanitize and capture form inputs
    $name = htmlspecialchars($_POST['name']);
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirmPassword']);
    $specialty = htmlspecialchars($_POST['specialty']);  // Capture the specialty input

    //echo "Name: " . $name . "<br>";
    //echo "Username: " . $username . "<br>";
    //echo "Specialty: " . $specialty . "<br>";

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<p style='color: white;font-size: 1em;'>Passwords do not match.</p>";
    } else {
        // Check for duplicate Name or Username
        $duplicate_query = "SELECT * FROM Clinician WHERE Name = ? OR Username = ?";
        $stmt = $conn->prepare($duplicate_query);
        $stmt->bind_param("ss", $name, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<p style='color: red;font-size: 1em;'>Name or Username already exists. Please choose different information.</p>";
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the SQL query to insert a new clinician
            $query = "INSERT INTO Clinician (Name, Username, Password, Specialty) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);

            if ($stmt === false) {
                die("MySQL prepare error: " . $conn->error);
            }

            // Bind the parameters to the query
            $stmt->bind_param("ssss", $name, $username, $hashed_password, $specialty);  // Bind specialty as well

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
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../assets/css/register.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <title>Register</title>
    <script>
        // Validate Name
        function validateName(input) {
            const value = input.value;
            const errorField = document.getElementById(input.id + 'Error');
            const namePattern = /^[a-zA-Z\s'-]+$/;

            if (value.length === 0) {
                errorField.textContent = "";
                return true;
            } else if (!value.match(namePattern)) {
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

            if (password.length === 0) {
                errorField.textContent = "";
                return true;
            } else if (!password.match(passwordPattern)) {
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

            if (confirmPassword.length === 0) {
                errorField.textContent = "";
                return true;
            } else if (password !== confirmPassword) {
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

            if (specialty.length === 0) {
                errorField.textContent = "";
                return true;
            } else if (!specialty.match(specialtyPattern)) {
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
            const usernameValid = validateName(document.getElementById('username'));
            const passwordValid = validatePassword();
            const confirmPasswordValid = validateConfirmPassword();
            const specialtyValid = validateSpecialty();

            if (!nameValid || !usernameValid || !passwordValid || !confirmPasswordValid || !specialtyValid) {
                alert("Please correct the errors in the form before submitting.");
                return false;
            }
            return true;
        }
    </script>
</head>


<body>
    <div class="main">
        <form action="register.php" method="post" onsubmit="return validateForm();">
            <div class="registerBox">
                <div class="login-header">
                    <h1 aria-hidden="true" style="color: white;">Register
                        <img src="../includes/Logo.png" style="width: 62px; height: 50px; margin-top: 10px;">
                    </h1>
                </div>
                <div class="form-row">
                    <p>Your Name:</p>
                    <input type="text" id="name" name="name" oninput="validateName(this)" required />
                </div>
                <div id="nameError" class="form-error"></div>

                <div class="form-row">
                    <p>Username:</p>
                    <input type="text" id="username" name="username" oninput="validateName(this)" required />
                </div>
                <div id="usernameError" class="form-error"></div>

                <div class="form-row">
                    <p>Password:</p>
                    <input type="password" id="password" name="password" oninput="validatePassword(this)" required />
                </div>
                <div id="passwordError" class="form-error"></div>

                <div class="form-row">
                    <p>Confirm Password:</p>
                    <input type="password" id="confirmPassword" name="confirmPassword"
                        oninput="validateConfirmPassword()" required />
                </div>
                <div id="confirmPasswordError" class="form-error"></div>

                <div class="form-row">
                    <p>Specialty:</p>
                    <input type="text" id="specialty" name="specialty" oninput="validateSpecialty(this)" required />
                </div>
                <div id="specialtyError" class="form-error"></div>

                <button type="submit">Register</button>
                <a href="login.php" style="text-decoration: none; font-size: 12px;">
                    <button type="button">Already have an account?</button>
                </a>
            </div>
        </form>
    </div>
</body>

</html>