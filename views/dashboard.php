<?php
include("include_menu.php");

$name = $_POST["name"];
$password = $_POST["password"];
echo "Welcome: $name, your password is: $password";
?>