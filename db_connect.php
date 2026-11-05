<?php
mysqli_report(MYSQLI_REPORT_OFF);

$host = "localhost";
$username = "root";
$password = "12345678";
$database = "hotel_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
