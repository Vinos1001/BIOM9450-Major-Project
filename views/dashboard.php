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
$name = $_SESSION['username'];

// Display a welcome message
echo "Welcome: $name, you are successfully logged in!";
?>

<!DOCTYPE html>
<html>

<body>
    <!-- Include your menu -->
    <?php include("include_menu.php"); ?>
</body>

</html>