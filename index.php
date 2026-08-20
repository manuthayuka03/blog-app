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

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style.css">

    <title>My Blog | Share Your Ideas</title>
</head>

<body>

    <!-- Header -->
    <header class="site-header">

        <div class="brand">
            <h1>My Blog</h1>
            <p>Share your thoughts and ideas</p>
        </div>

        <nav class="main-nav">

            <?php if (isset($_SESSION["user_id"])): ?>

                <span class="welcome">
                    Hi,
                    <strong>
                        <?php echo htmlspecialchars($_SESSION["username"]); ?>
                    </strong>
                </span>

                <a href="create_post.php" class="nav-button">
                    Write a Post
                </a>

                <a href="logout.php">
                    Log out
                </a>

            <?php else: ?>

                <a href="register.php">
                    Register
                </a>

                <a href="login.php" class="nav-button">
                    Log in
                </a>

            <?php endif; ?>

        </nav>

    </header>


    <!-- Messages -->

    <?php if (isset($_GET["message"]) && $_GET["message"] === "created"): ?>

        <div class="success-message">
            Your blog post was published successfully.
        </div>

    <?php elseif (isset($_GET["message"]) && $_GET["message"] === "updated"): ?>

        <div class="success-message">
            Your blog post was updated successfully.
        </div>

    <?php elseif (isset($_GET["message"]) && $_GET["message"] === "deleted"): ?>

        <div class="success-message">
            Your blog post was deleted successfully.
        </div>

    <?php endif; ?>


    <!-- Blog Posts Section -->

    <main>

        <div class="section-heading">

            <h2>Latest Blog Posts</h2>

            <p>
                Discover stories, ideas and knowledge from our community.
            </p>

        </div>


        <?php if ($posts->num_rows === 0): ?>

            <div class="empty-state">

                <h3>No blog posts yet</h3>

                <p>
                    Be the first person to share something with the community.
                </p>

                <?php if (isset($_SESSION["user_id"])): ?>

                    <a href="create_post.php" class="nav-button">
                        Create Your First Post
                    </a>

                <?php else: ?>

                    <a href="login.php" class="nav-button">
                        Log in to Write
                    </a>

                <?php endif; ?>

            </div>

        <?php else: ?>

            <?php while ($post = $posts->fetch_assoc()): ?>

                <article class="blog-card">

                    <!-- Post Title -->

                    <h3>
                        <a href="view_post.php?id=<?php echo $post["id"]; ?>">
                            <?php echo htmlspecialchars($post["title"]); ?>
                        </a>
                    </h3>


                    <!-- Author and Date -->

                    <?php
                        $date = new DateTime($post["created_at"]);
                    ?>

                    <p class="post-meta">

                        By
                        <strong>
                            <?php echo htmlspecialchars($post["username"]); ?>
                        </strong>

                        <span class="dot">•</span>

                        <?php echo $date->format("F j, Y"); ?>

                    </p>


                    <!-- Post Content -->

                    <p class="post-content">

                        <?php
                        echo nl2br(htmlspecialchars($post["content"]));
                        ?>

                    </p>


                    <!-- Read More -->

                    <a
                        href="view_post.php?id=<?php echo $post["id"]; ?>"
                        class="read-more"
                    >
                        Read more →
                    </a>

                </article>

            <?php endwhile; ?>

        <?php endif; ?>

    </main>


    <!-- Footer -->

    <footer>

        <p>
            © <?php echo date("Y"); ?> My Blog. All rights reserved.
        </p>

    </footer>

</body>
</html>