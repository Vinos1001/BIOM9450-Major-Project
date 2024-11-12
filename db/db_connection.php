<?php
// This file allows established connection to database and can be included wherever database access is needed
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "PatientManagementSystem";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>