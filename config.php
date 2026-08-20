<?php
$host = "sql202.infinityfree.com";
$dbname = "if0_42490168_blogdb";
$dbuser = "if0_42490168";
$dbpass = "IS2120blogapp";

$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");