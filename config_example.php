<?php
$host = "YOUR_DATABASE_HOST";
$dbname = "YOUR_DATABASE_NAME";
$dbuser = "YOUR_DATABASE_USERNAME";
$dbpass = "YOUR_DATABASE_PASSWORD";

$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");