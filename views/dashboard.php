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

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <!-- Include the navigation menu -->
    <?php include("../includes/header.php"); ?>
    <div class="container">
        <div class="welcome-box">
            <h1>Welcome to the Dashboard!</h1>
            <p>Hi <strong><?php echo htmlspecialchars($name); ?></strong>, you are successfully logged in!</p>
            <p>Use the navigation menu to explore the features of the system.</p>
        </div>
    </div>
    <?php include("../includes/footer.php"); ?>
</body>
</html>

