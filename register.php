<?php
require "config.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($username === "" || $email === "" || $password === "") {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must contain at least 6 characters.";
    }

    if (empty($errors)) {
        $check = $conn->prepare(
            "SELECT id FROM users WHERE username = ? OR email = ?"
        );
        $check->bind_param("ss", $username, $email);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {
            $errors[] = "That username or email is already registered.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare(
                "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
            );
            $insert->bind_param("sss", $username, $email, $hashedPassword);

            if ($insert->execute()) {
                header("Location: login.php");
                exit;
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account | My Blog App</title>
</head>
<body>
    <h1>Create an Account</h1>

    <?php foreach ($errors as $error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endforeach; ?>

    <form method="POST" action="register.php">
        <label for="username">Username</label><br>
        <input
            type="text"
            id="username"
            name="username"
            required
        ><br><br>

        <label for="email">Email</label><br>
        <input
            type="email"
            id="email"
            name="email"
            required
        ><br><br>

        <label for="password">Password</label><br>
        <input
            type="password"
            id="password"
            name="password"
            required
        ><br><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Log in</a></p>
</body>
</html>