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
			<i data-visualcompletion="css-img" id="post_like_button"></i> Like
		</button>
		<button class="action-btn">
			<i id="post_comments_button"></i> Comment
		</button>
		<button class="action-btn">
			<i id="post_share_button"></i> Share
		</button>
	</div>
</div>