<?php
// Main dashaboard view where clinicians can view aptent summaries and access different sections
session_start();
include '../db/db_connection.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <div id="patient-list">
        <!-- PHP code to list patients goes here -->
    </div>
    <script src="../assets/js/scripts.js"></script>
</body>

</html>