<?php
	session_start();
	include 'db_connection.php';
	if (isset($_GET['user_id'])) {
		$user_id = intval($_GET['user_id']);
		$sql = "
		SELECT u.user_id, u.name, u.photo_path
		FROM tFriends f 
		LEFT OUTER JOIN tUser u ON f.friend_id = u.user_id
		WHERE f.user_id = $user_id;
		";
		$result = $con->query($sql);
		include './includes/friends_section.php';
	}
	$con->close();
?>
