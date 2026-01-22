<?php
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
					data-id="'.$row['user_id'].'"
					data-name="'.htmlspecialchars($row['name']).'"
					data-photo="'.$photo.'">
					<div class="friend-left">
						<img src="'.$photo.'" class="friend-img">
					</div>
					<div class="friend-middle">
						<div class="friend-name">'.htmlspecialchars($row['name']).'</div>
					</div>
				</div>
			</div>
			';
		}
		echo '</div>';
	} else {
		echo '<p class="text-muted">No friends found.</p>';
	}
?>