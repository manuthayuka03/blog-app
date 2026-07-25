<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Blog App</title>
</head>
<body>
    <h1>My Blog Application</h1>

    <?php if (isset($_SESSION["user_id"])): ?>
        <p>
            You are logged in as
            <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong>.
        </p>

        <p><a href="logout.php">Log out</a></p>
    <?php else: ?>
        <p>You are not logged in.</p>
        <p>
            <a href="register.php">Register</a> |
            <a href="login.php">Log in</a>
        </p>
    <?php endif; ?>
</body>
</html>