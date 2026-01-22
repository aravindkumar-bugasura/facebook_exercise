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