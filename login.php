<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "Root@1234";
$database = "facebook_db";

// Database connection
$con = new mysqli($servername, $username, $password, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Handle form submission
$msg = "";
if (isset($_POST['login'])) {
    $email = $con->real_escape_string($_POST['email']);
    $password = $con->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM tUser WHERE Email_id='$email' AND Password='$password'";
    $result = $con->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['User_id'];
        $_SESSION['user_name'] = $user['Name'];
        header("Location: facebook_exer.php"); // redirect to home page
        exit();
    } else {
        $msg = "Invalid email or password!";
    }
}

$con->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="login-container">
    <h2>Facebook Login</h2>
    <form method="POST" action="" class="login-form">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="login" value="Login">
        <?php if ($msg != "") echo "<p class='msg'>$msg</p>"; ?>
    </form>
    <p class="signup-link">Don't have an account? <a href="register.php">Sign up</a></p>
</div>
</body>
</html>
