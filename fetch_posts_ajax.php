<?php
	session_start();

	// ==========================
	// Database connection
	// ==========================
	$con = new mysqli("localhost", "root", "Root@123", "facebook_db");
	if ($con->connect_error) {
		die("Connection failed: " . $con->connect_error);
	}

	// ==========================
	// Users
	// ==========================
	$main_user_id = $_SESSION['user_id']; // logged-in user

	$profile_user_id = isset($_GET['user_id'])
		? (int)$_GET['user_id']
		: $main_user_id;

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
    		FROM tWall w LEFT JOIN tFriends f 
			ON w.user_id = f.friend_id AND f.user_id = $main_user_id
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
			$res_user = $con->query("SELECT Name, photo_path FROM tUser WHERE User_id = $uid");
			$user = $res_user->fetch_assoc();
			$photo = !empty($user['photo_path']) ? $user['photo_path'] : 'images/default-user.png';
			$name  = htmlspecialchars($user['Name']);
			?>
			<div class="post">
				<div class="post-header">
					<div class="post-user">
						<img src="<?php echo $photo; ?>" class="post-user-img">
						<div class="user-info">
							<strong><?php echo $name; ?></strong>
							<span class="tick-span">
								<img src="./images/Verified account.svg" class="post-tick-img" alt="verified">
							</span><br>
							<small class="date-format">
								<?php echo timeAgo($post['posting_date']); ?> ·
								<img src="./images/Shared with Public.svg" alt="public">
							</small>
						</div>
					</div>
				</div>
				<div class="post-content">
					<?php echo htmlspecialchars($post['post']); ?>
				</div>
				<div class="reaction-div">
					<div class="reaction-left">
						<img src="./images/like.svg" alt="like" class="like-reaction">
						<img src="./images/love.svg" alt="love" class="love-reaction">
						<span class="reaction-count">370K</span>
					</div>
					<div class="reaction-comments-shares">
						<span>69.2k comments · 7k shares</span>
					</div>
				</div>
				<div class="post-actions">
					<button class="action-btn">
						<i data-visualcompletion="css-img" class="x1b0d499 x1d69dk1" style="background-image: url('https://static.xx.fbcdn.net/rsrc.php/v4/yJ/r/VnWiFrYgCpp.png'); background-position: 0px -833px; background-size: auto; width: 20px; height: 20px; background-repeat: no-repeat; display: inline-block;"></i> Like
					</button>
					<button class="action-btn">
						<i style="background-image: url('https://static.xx.fbcdn.net/rsrc.php/v4/yJ/r/VnWiFrYgCpp.png'); background-position: 0px -791px; background-size: auto; width: 20px; height: 20px; background-repeat: no-repeat; display: inline-block;"></i> Comment
					</button>
					<button class="action-btn">
						<i style="background-image: url('https://static.xx.fbcdn.net/rsrc.php/v4/yJ/r/VnWiFrYgCpp.png'); background-position: 0px -875px; background-size: auto; width: 20px; height: 20px; background-repeat: no-repeat; display: inline-block;"></i> Share
					</button>
				</div>
			</div>
			<?php
		}
	} else {
		echo "<p>No posts yet.</p>";
	}
?>