<?php
	$servername = "localhost";
	$username = "root";
	$password = "Root@123";
	$database = "facebook_db";
	$con = new mysqli($servername, $username, $password, $database);
	if ($con->connect_error) {
		die("Connection failed: " . $con->connect_error);
	}
	$wall_user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 1;

	$sql_post = "SELECT post, posting_date FROM tWall WHERE user_id=$wall_user_id ORDER BY posting_date DESC";
	$result_post = $con->query($sql_post);
	if($result_post->num_rows > 0){
		while($post = $result_post->fetch_assoc()){
			echo '<div class="post">';
			echo '<strong>'.$post['posting_date'].'</strong><br>';
			echo htmlspecialchars($post['post']);
			echo '</div>';
		}
	}else{
		echo "No posts yet.";
	}
	$con->close();
?>
