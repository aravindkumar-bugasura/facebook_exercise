<?php
	session_start();
	include 'db_connection.php';

	if (isset($_GET['user_id'])) {

		$user_id = intval($_GET['user_id']);

		$sql = "
			SELECT u.User_id, u.Name, u.photo_path
			FROM tFriends f LEFT OUTER JOIN tUser u 
			ON f.friend_id = u.User_id
			WHERE f.user_id = $user_id;
		";
		$result = $con->query($sql);
		if ($result->num_rows > 0) {
			echo '<div class="row">';
			while ($row = $result->fetch_assoc()) {

				$photo = !empty($row['photo_path'])
					? $row['photo_path']
					: 'images/default-user.png';
				$name = htmlspecialchars($row['Name']);
				echo '
				<div class="col-xs-12 col-sm-6">
					<div class="friend-row"
						data-id="'.$row['User_id'].'"
						data-name="'.htmlspecialchars($row['Name']).'"
						data-photo="'.$photo.'">
						<div class="friend-left">
							<img src="'.$photo.'" class="friend-img">
						</div>
						<div class="friend-middle">
							<div class="friend-name">'.htmlspecialchars($row['Name']).'</div>
						</div>
					</div>
				</div>
				';
			}
			echo '</div>';
		} else {
			echo '<p class="text-muted">No friends found.</p>';
		}
	}
	$con->close();
?>
