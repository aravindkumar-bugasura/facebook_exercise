<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "Root@1234";
$database = "facebook_db";
$con = new mysqli($servername, $username, $password, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
// Get user_id from URL
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 1;
// If form is submitted, update database
if (isset($_POST['save'])) {
    $name = $con->real_escape_string($_POST['name']);
    $email = $con->real_escape_string($_POST['email']);
    $password = $con->real_escape_string($_POST['password']);
    $address = $con->real_escape_string($_POST['address']);
    $phone = $con->real_escape_string($_POST['phone']);
    $sql_update = "
        UPDATE tUser SET
        Name='$name',
        Email_id='$email',
        Password='$password',
        Address='$address',
        Phone='$phone'
        WHERE User_id=$user_id
    ";
    if ($con->query($sql_update) === TRUE) {
        $msg = "Profile updated successfully!";
    } else {
        $msg = "Error: " . $con->error;
    }
}
// Fetch user details to show in form
$sql_user = "SELECT * FROM tUser WHERE User_id=$user_id";
$result_user = $con->query($sql_user);
$user = $result_user->fetch_assoc();
$con->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
<h2>Edit Profile</h2>
<form method="POST" action="" class="form">
    <label>Name</label>
    <input type="text" class="input" name="name" value="<?php echo htmlspecialchars($user['Name']); ?>" required>
    <label>Email</label>
    <input type="email" class="input" name="email" value="<?php echo htmlspecialchars($user['Email_id']); ?>" required>
    <label>Password</label>
    <input type="password" class="input" name="password" value="<?php echo htmlspecialchars($user['Password']); ?>" required>
    <label>Address</label>
    <textarea name="address" class="textarea"><?php echo htmlspecialchars($user['Address']); ?></textarea>
    <label>Phone</label>
    <input type="text" class="input" name="phone" value="<?php echo htmlspecialchars($user['Phone']); ?>">
    <input type="submit" class="input" name="save" value="Save">
    <a href="facebook_exer.php" class="back-btn">← Back to Home</a>
    <?php if (isset($msg)) echo "<p class='msg'>$msg</p>"; ?>
</form>

</body>
</html>
