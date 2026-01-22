<?php
	session_start();
	include 'db_connection.php';
	$msg = "";
	if (isset($_SESSION['error_msg'])) {
		$msg = $_SESSION['error_msg'];
		unset($_SESSION['error_msg']); 
	}
	/* If already logged in, redirect to index */
	if (isset($_SESSION['user_id'])) {
		header("Location: index.php");
		exit;
	}
	if (isset($_POST['login'])) {
		$email = $con->real_escape_string($_POST['email']);
		$password = $con->real_escape_string($_POST['password']);
		$sql = "SELECT * FROM tUser WHERE email_id='$email' AND Password='$password'";
		$result = $con->query($sql);
		if ($result->num_rows == 1) {
			$user = $result->fetch_assoc();
			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['user_name'] = $user['Name'];
			header("Location: index.php");
			exit();
		} 
		else {
			$_SESSION['error_msg'] = "Invalid email or password!";
			header("Location: login.php");
			exit();
		}
	}
	$con->close();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Facebook – Log in</title>
		<link rel="stylesheet" href="asserts/css/bootstrap.min.css">
		<link rel="stylesheet" href="asserts/css/login.css">
	</head>
	<body>
		<?php include "includes/login_header.php";?>
		<?php include "includes/login_footer.php";?>
	</body>
</html>

