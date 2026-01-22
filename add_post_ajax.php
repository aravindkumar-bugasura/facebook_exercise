<?php
	// ==========================
	// Database Connection
	// ==========================
	include 'db_connection.php';
	// ==========================
	// Main User ID (for demo / session)
	// ==========================
	$main_user_id = 1;
	// ==========================
	// Handle new post submission
	// ==========================
	if (isset($_POST['new_post'])) {
		$post = trim($_POST['new_post']); 
		if (!empty($post)) {
			// Escape special characters to prevent SQL errors
			$post_safe = $con->real_escape_string($post);
			// Insert post into database
			$sql = "
			INSERT INTO tWall (user_id, post, posting_date)
			VALUES ($main_user_id, '$post_safe', NOW())
			";
			if ($con->query($sql)) {
				echo "success"; 
			} else {
				echo "error";  
			}
		}
	}
	// ==========================
	// Close database connection
	// ==========================
	$con->close();
?>
