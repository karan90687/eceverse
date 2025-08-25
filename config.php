<?php
$host = "localhost";
$user = "root";       // Default XAMPP username
$pass = "";           // Default XAMPP password (empty)
$db   = "eceverse_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>