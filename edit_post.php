<?php
require "config.php";
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$postId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
$userId = $_SESSION["user_id"];

if (!$postId) {
    die("Invalid blog post.");
}

$findPost = $conn->prepare(
    "SELECT id, title, content
     FROM blog_posts
     WHERE id = ? AND user_id = ?"
);
$findPost->bind_param("ii", $postId, $userId);
$findPost->execute();

$post = $findPost->get_result()->fetch_assoc();

if (!$post) {
    http_response_code(403);
    die("You are not allowed to edit this blog post.");
}

$errors = [];
$title = $post["title"];
$content = $post["content"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    if ($title === "" || $content === "") {
        $errors[] = "Title and content are required.";
    }

    if (empty($errors)) {

    date_default_timezone_set("Asia/Colombo");
    $updatedAt = date("Y-m-d H:i:s");

    $update = $conn->prepare(
        "UPDATE blog_posts
         SET title = ?, content = ?, updated_at = ?
         WHERE id = ? AND user_id = ?"
    );

    $update->bind_param("sssii", $title, $content, $updatedAt, $postId, $userId);

    if ($update->execute()) {
        header("Location: view_post.php?id=" . $postId . "&message=updated");
        exit;
    } else {
        $errors[] = "The post could not be updated.";
    }
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <script src="app.js" defer></script>
    <title>Edit Blog Post | My Blog App</title>
</head>
<body>
    <h1>Edit Blog Post</h1>

    <?php foreach ($errors as $error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endforeach; ?>

    <form method="POST" action="edit_post.php?id=<?php echo $postId; ?>">
        <label for="title">Title</label><br>
        <input
            type="text"
            id="title"
            name="title"
            value="<?php echo htmlspecialchars($title); ?>"
            required
        ><br><br>

        <label for="content">Content</label><br>
        <textarea
            id="content"
            name="content"
            rows="10"
            cols="60"
            required
        ><?php echo htmlspecialchars($content); ?></textarea><br><br>

        <button type="submit">Save Changes</button>
    </form>

    <p><a href="view_post.php?id=<?php echo $postId; ?>">Cancel</a></p>
</body>
</html>