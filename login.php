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
		$sql = "SELECT * FROM tUser WHERE Email_id='$email' AND Password='$password'";
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
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="login.css">
	</head>
	<body>
		<div class="container-fluid main-wrapper">
			<!-- Left Content -->
			<div class="left-content">
				<img src="./images/facebook_text.svg" alt="facebook">
				<p class="fb-text">
					Facebook helps you connect and share<br>
					with the people in your life.
				</p>
			</div>
			<!-- Right Login Box -->
			<div class="login-input">
				`<div class="login-box">
					<form method="POST">
						<input type="email" name="email" placeholder="Email address or phone number" class="input-placeholder" required>
						<input type="password" name="password" placeholder="Password" class="input-placeholder" required>
						<button type="submit" name="login">
							<div class="login-button">
								<p class="login-text">Log in</p>
							</div>
						</button>
						<?php if ($msg != "") echo "<p class='msg'>$msg</p>"; ?>
						<a href="#" class="forgot">Forgotten password?</a>
						<div class="hr-line"></div>
						<a href="#" class="create-account">Create new account</a>
					</form>
				</div>
				<div class="create-page">
					<p class="create-line">
						<a href="#"><b class="create-post-bold">Create a Page</b></a> for a celebrity, brand or business.
					</p>
				</div>
			</div>
		</div>
		<div class="container footer">
			<div class="text-center footer-links">
				<!-- Languages -->
				<ul>
					<li>English (UK)</li>
					<li>ಕನ್ನಡ</li>
					<li>اردو</li>
					<li>मराठी</li>
					<li>తెలుగు</li>
					<li>हिन्दी</li>
					<li>தமிழ்</li>
					<li>മലയാളം</li>
					<li>বাংলা</li>
					<li>ગુજરાતી</li>
					<li>ਪੰਜਾਬੀ</li>
					<li class="add-icon">
						<img src="./images/plus-icon1.png" alt="add">
					</li>
				</ul>
				<!-- Footer links row 1 -->
				<ul>
					<li>Sign Up</li>
					<li>Log in</li>
					<li>Messenger</li>
					<li>Facebook Lite</li>
					<li>Video</li>
					<li>Meta Pay</li>
					<li>Meta Store</li>
					<li>Meta Quest</li>
					<li>Ray-Ban Meta</li>
					<li>Meta AI</li>
					<li>Meta AI more content</li>
					<li>Instagram</li>
					<li>Threads</li>
				</ul>
				<!-- Footer links row 2 -->
				<ul>
					<li>Voting Information Centre</li>
					<li>Privacy Policy</li>
					<li>Privacy Centre</li>
					<li>About</li>
					<li>Create ad</li>
					<li>Create Page</li>
					<li>Developers</li>
					<li>Careers</li>
					<li>Cookies</li>
					<li class="ad-choices-link">AdChoices<i class="img ad-choices ad-choices-img"></i>
					</li>
					<li>Terms</li>
					<li>Help</li>
				</ul>
				<!-- Footer links row 3 -->
				<ul>
					<li>Contact uploading and non-users</li>
				</ul>
				<!-- Copyright -->
				<ul>
					<li>Meta © 2026</li>
				</ul>
			</div>
		</div>
	</body>
</html>

