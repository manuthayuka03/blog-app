<?php
require "config.php";
session_start();

$postId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$postId) {
    die("Invalid blog post.");
}

$statement = $conn->prepare(
    "SELECT blog_posts.id, blog_posts.user_id, blog_posts.title,
            blog_posts.content, blog_posts.created_at,
            blog_posts.updated_at, users.username
     FROM blog_posts
     INNER JOIN users ON blog_posts.user_id = users.id
     WHERE blog_posts.id = ?"
);

$statement->bind_param("i", $postId);
$statement->execute();

$post = $statement->get_result()->fetch_assoc();

if (!$post) {
    http_response_code(404);
    die("Blog post not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <script src="app.js" defer></script>
    <title><?php echo htmlspecialchars($post["title"]); ?> | My Blog App</title>
</head>
<body>
    <p><a href="index.php">← Back to all posts</a></p>

    <article>
        <h1><?php echo htmlspecialchars($post["title"]); ?></h1>

        <p>
            By <strong><?php echo htmlspecialchars($post["username"]); ?></strong>
            on <?php echo htmlspecialchars($post["created_at"]); ?>
        </p>

        <hr>

        <p>
            <?php echo nl2br(htmlspecialchars($post["content"])); ?>
        </p>

        <?php if (
    isset($_SESSION["user_id"]) &&
    (int) $_SESSION["user_id"] === (int) $post["user_id"]
): ?>
    <hr>

    <p>
        <a href="edit_post.php?id=<?php echo $post["id"]; ?>">
            Edit this post
        </a>
    </p>

    <form id="delete-form" method="POST" action="delete_post.php">
    <input type="hidden" name="id" value="<?php echo $post["id"]; ?>">
    <button type="submit">Delete this post</button>
</form>
<?php endif; ?>
    </article>
</body>
</html>