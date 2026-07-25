<?php
require "config.php";
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $statement = $conn->prepare(
        "SELECT id, username, password FROM users WHERE email = ?"
    );
    $statement->bind_param("s", $email);
    $statement->execute();

    $result = $statement->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];

        header("Location: index.php");
        exit;
    } else {
        $error = "Incorrect email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In | My Blog App</title>
</head>
<body>
    <h1>Log In</h1>

    <?php if ($error !== ""): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Log In</button>
    </form>

    <p>New user? <a href="register.php">Create an account</a></p>
</body>
</html>