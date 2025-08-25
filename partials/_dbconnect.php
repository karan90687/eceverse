<?php
$servername = "localhost";
$username = "root";       // your MySQL username
$password = "";           // your MySQL password
$database = "eceverse_db"; // your database name

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
