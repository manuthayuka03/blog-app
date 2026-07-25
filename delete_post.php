<?php
require "config.php";
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$postId = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$userId = $_SESSION["user_id"];

if (!$postId) {
    die("Invalid blog post.");
}

$delete = $conn->prepare(
    "DELETE FROM blog_posts WHERE id = ? AND user_id = ?"
);
$delete->bind_param("ii", $postId, $userId);
$delete->execute();

if ($delete->affected_rows === 1) {
    header("Location: index.php?message=deleted");
    exit;
}

http_response_code(403);
die("You are not allowed to delete this blog post.");