<?php
require "config.php";
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$errors = [];
$title = "";
$content = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    if ($title === "" || $content === "") {
        $errors[] = "Title and content are required.";
    }

    if (empty($errors)) {
        $userId = $_SESSION["user_id"];

        $statement = $conn->prepare(
            "INSERT INTO blog_posts (user_id, title, content) VALUES (?, ?, ?)"
        );
        $statement->bind_param("iss", $userId, $title, $content);

        if ($statement->execute()) {
            header("Location: index.php?message=created");
            exit;
        } else {
            $errors[] = "The blog post could not be saved.";
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
    <title>Write a Blog Post | My Blog App</title>
</head>
<body>
    <h1>Write a New Blog Post</h1>

    <?php foreach ($errors as $error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endforeach; ?>

    <form method="POST" action="create_post.php">
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

        <button type="submit">Publish Post</button>
    </form>

    <p><a href="index.php">Back to home</a></p>
</body>
</html>