<?php
require "config.php";
session_start();

$sql = "
    SELECT blog_posts.id, blog_posts.title, blog_posts.content,
           blog_posts.created_at, users.username
    FROM blog_posts
    INNER JOIN users ON blog_posts.user_id = users.id
    ORDER BY blog_posts.created_at DESC
";

$posts = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Blog App</title>
</head>
<body>
    <h1>My Blog Application</h1>

    <?php if (isset($_GET["message"]) && $_GET["message"] === "created"): ?>
        <p style="color: green;">Your blog post was published.</p>
    <?php endif; ?>

    <?php if (isset($_SESSION["user_id"])): ?>
        <p>
            You are logged in as
            <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>.
        </p>

        <p>
            <a href="create_post.php">Write a new blog post</a> |
            <a href="logout.php">Log out</a>
        </p>
    <?php else: ?>
        <p>
            <a href="register.php">Register</a> |
            <a href="login.php">Log in</a>
        </p>
    <?php endif; ?>

    <hr>

    <h2>Latest Blog Posts</h2>

    <?php if ($posts->num_rows === 0): ?>
        <p>No blog posts have been published yet.</p>
    <?php else: ?>
        <?php while ($post = $posts->fetch_assoc()): ?>
            <article>
                <h3>
    <a href="view_post.php?id=<?php echo $post["id"]; ?>">
        <?php echo htmlspecialchars($post["title"]); ?>
    </a>
</h3>

                <p>
                    By <?php echo htmlspecialchars($post["username"]); ?>
                    on <?php echo htmlspecialchars($post["created_at"]); ?>
                </p>

                <p>
                    <?php echo nl2br(htmlspecialchars($post["content"])); ?>
                </p>

                <hr>
            </article>
        <?php endwhile; ?>
    <?php endif; ?>
</body>
</html>