<?php
	session_start();

	// ==========================
	// Database connection
	// ==========================
	include 'db_connection.php';
	// ==========================
	// Users
	// ==========================
	$main_user_id = $_SESSION['user_id']; // logged-in user

	$profile_user_id = isset($_GET['user_id'])? (int)$_GET['user_id']: $main_user_id;
	// ==========================
	// Time ago function
	// ==========================
	function timeAgo($datetime) {
		$time = strtotime($datetime);
		$diff = time() - $time;
		if ($diff < 60) return "Just now";
		if ($diff < 3600) return floor($diff / 60) . "m";
		if ($diff < 86400) return floor($diff / 3600) . "h";
		if ($diff < 345600) return floor($diff / 86400) . "d";
		return date("d M \\a\\t H:i", $time);
	}

	// ==========================
	// Fetch posts (FEED vs PROFILE)
	// ==========================
	if ($profile_user_id == $main_user_id) {
		// FEED: main user + friends
		$sql_post = "
		SELECT w.user_id, w.post, w.posting_date
		FROM tWall w 
		LEFT JOIN tFriends f ON w.user_id = f.friend_id AND f.user_id = $main_user_id
		WHERE w.user_id = $main_user_id OR f.user_id = $main_user_id
		ORDER BY w.posting_date DESC
		";
	} else {
		// PROFILE: only that user
		$sql_post = "
		SELECT user_id, post, posting_date
		FROM tWall
		WHERE user_id = $profile_user_id
		ORDER BY posting_date DESC
		";
	}
	$result_post = $con->query($sql_post);
	// ==========================
	// Render posts
	// ==========================
	if ($result_post && $result_post->num_rows > 0) {
		while ($post = $result_post->fetch_assoc()) {
			$uid = $post['user_id'];
			$res_user = $con->query("
			SELECT Name, photo_path 
			FROM tUser 
			WHERE User_id = $uid
			");
			$user = $res_user->fetch_assoc();
			$photo = !empty($user['photo_path']) ? $user['photo_path'] : 'images/default-user.png';
			$name  = htmlspecialchars($user['Name']);
			include './includes/post_tab.php';
		}
	} else {
		echo "<p>No posts yet.</p>";
	}
?>