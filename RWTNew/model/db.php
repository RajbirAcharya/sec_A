<?php
$host = "localhost";
$user = "root"; // default user in phpMyAdmin
$pass = "";     // set your phpMyAdmin password if any
$dbname = "rwt_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>