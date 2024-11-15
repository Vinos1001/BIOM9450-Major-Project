<?php
// Database configuration
$host = 'localhost';
$db = 'patient_management_system'; // Name of your database
$user = 'root'; // Default XAMPP username
$password = ''; // Default XAMPP password is empty

// Create a connection
$conn = new mysqli($host, $user, $password, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>