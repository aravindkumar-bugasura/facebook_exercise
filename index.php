<?php
	session_start();
	// ==========================
	// Database connection
	// ==========================
	include 'db_connection.php';
	// ==========================
	// Check if user is logged in
	// ==========================
	if (!isset($_SESSION['user_id'])) {
		header("Location: login.php");
		exit;
	}
	// ==========================
	// Profile user (viewed profile)
	// ==========================
	$profile_user_id = isset($_GET['user_id'])
		? (int) $_GET['user_id']
		: $_SESSION['user_id'];
	// ==========================
	// Function to show "time ago"
	// ==========================
	function timeAgo($datetime) {
		$time = strtotime($datetime);
		$diff = time() - $time;
		if ($diff < 60) {
			return "Just now";
		} elseif ($diff < 3600) {
			return floor($diff / 60) . "m";
		} elseif ($diff < 86400) {
			return floor($diff / 3600) . "h";
		} elseif ($diff < 345600) {
			return floor($diff / 86400) . "d";
		} else {
			return date("d M \\a\\t H:i ", $time);
		}
	}
	// ==========================
	// Fetch main user data
	// ==========================
	$main_user_id = $_SESSION['user_id'];
	$sql_main_user = "SELECT * FROM tUser WHERE user_id = $main_user_id";
	$result_main_user = $con->query($sql_main_user);
	$main_user = $result_main_user->fetch_assoc();
	// ==========================
	// Fetch friends of main user
	// ==========================
	$sql_friend = "
		SELECT DISTINCT u.user_id, u.name, u.photo_path, u.cover_photo
		FROM tFriends f
		LEFT OUTER JOIN tUser u ON f.friend_id = u.user_id
		WHERE f.user_id = $main_user_id;
	";
	$result_friend = $con->query($sql_friend);
	// ==========================
	// Handle Edit Profile Submission
	// ==========================
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
		$user_id = $_SESSION['user_id'];
		$name    = $con->real_escape_string($_POST['name']);
		$email   = $con->real_escape_string($_POST['email']);
		$phone   = $con->real_escape_string($_POST['phone']);
		$address = $con->real_escape_string($_POST['address']);
		$sql_update = "
			UPDATE tUser 
			SET name = '$name', 
				email_id = '$email', 
				phone = '$phone', 
				address = '$address'
			WHERE user_id = $user_id
		";
		if ($con->query($sql_update)) {
			$_SESSION['update_success'] = "Profile updated successfully!";
			header("Location: index.php");
			exit;
		} else {
			$_SESSION['update_error'] = "Error updating profile: " . $con->error;
		}
	}
	// ==========================
	// Fetch posts (main user + friends)
	// ==========================
	if ($profile_user_id == $main_user_id) {
		// MAIN USER FEED (Mark + friends)
		$sql_post = "
			SELECT DISTINCT w.user_id, w.post, w.posting_date
			FROM tWall w LEFT JOIN tFriends f ON w.user_id = f.friend_id
			WHERE w.user_id = $main_user_id OR f.user_id = $main_user_id
			ORDER BY w.posting_date DESC;
		";
	} else {
		// FRIEND PROFILE (only Vikram)
		$sql_post = "
			SELECT user_id, post, posting_date
			FROM tWall
			WHERE user_id = $profile_user_id
			ORDER BY posting_date DESC
		";
	}
	$result_post = $con->query($sql_post);
	// ==========================
	// Insert new post if submitted
	// ==========================
	if (isset($_POST['post_submit'])) {
		$post = trim($_POST['new_post']);
		$user_id = $main_user_id;
		if (!empty($post)) {
			$post_safe = $con->real_escape_string($post);
			$sql_insert = "
				INSERT INTO tWall (user_id, post, posting_date)
				VALUES ($user_id, '$post_safe', NOW())
			";
			if ($con->query($sql_insert)) {
				header("Location: check.php");
				exit();
			}
		}
	}
	// ==========================
	// Fetch profile user data (for header)
	// ==========================
	$sql_profile_user = "SELECT name, photo_path FROM tUser WHERE user_id = $profile_user_id";
	$result_profile_user = $con->query($sql_profile_user);
	$profile_user = $result_profile_user->fetch_assoc();
	// Fallback image
	$profile_photo = !empty($profile_user['photo_path'])
		? $profile_user['photo_path']
		: 'images/default-user.png';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Facebook page</title>
		<link rel="icon" type="image/png" href="./images/fb_icon_144x144.png">
		<link rel="stylesheet" href="asserts/css/bootstrap.min.css">
		<link rel="stylesheet" href="asserts/css/index.css">
	</head>
	<body data-user-id="<?php echo $_SESSION['user_id']; ?>">
	<!-- ==========================
		Header and Navbar
	========================== -->
		<?php include 'includes/header.php'; ?>
	<!-- ==========================
		Center Cover Image
	========================== -->
		<?php include 'includes/profile_section.php'; ?>
	<!-- ==========================
		Posts Section
	========================== -->
		<?php include 'includes/post_section.php'; ?>		
	
	<!-- ==========================
		Footer Section
	========================== -->
		<?php include 'includes/footer.php';?>
	</body>
</html>