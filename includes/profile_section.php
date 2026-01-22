<div class="container-fluid center text-center">
	<div class="container bg-image" id="cover_photo"></div>
</div>
<!-- Profile Row -->
<div class="container newprofile">
	<div class="full-new-profile">
		<div class="pro-procontent">
			<div class="profile-img">
				<div class="profile-image">
					<img id="main_profile_img" src="<?php echo $profile_photo; ?>" alt="profile">
				</div>
			</div>
			<div class="profile-details">
				<h2 id="main_profile_name">
					<?php echo htmlspecialchars($profile_user['name']); ?>
					<span class="tick-span">
						<img src="./images/Verified account.svg" class="tick-img" alt="verfied">
					</span>
				</h2>
				<p>
					<a href="#"><strong>121M</strong><span>followers</span></a>
				</p>
				<div class="followers-images">
					<img src="./images/foll1.jpg" class="followers" alt="follower1">
					<img src="./images/foll2.jpg" class="followers" alt="follower2">
					<img src="./images/foll3.jpg" class="followers" alt="follower3">
					<img src="./images/foll4.jpg" class="followers" alt="follower4">
					<img src="./images/foll4.jpg" class="followers" alt="follower5">
					<img src="./images/foll5.jpg" class="followers" alt="follower6">
					<img src="./images/foll6.jpg" class="followers" alt="follower7">
					<img src="./images/foll7.jpg" class="followers" alt="follower8">
					<img src="./images/foll8.jpg" class="followers" alt="follower9">
				</div>
			</div>
		</div>
		<div class="profile-button">
			<div class="profile-button-all">
				<button id="btn_follow" class="btn-follow three-btn">
					<img src="./images/LJ8KuNpi23A.png" alt="follow" class="follow-icon">
					<span class="follow-button">Follow</span>
				</button>
				<button id="btn_search" class="btn-search three-btn">
					<img src="./images/svgexport-3.svg" alt="search" class="search-icons">
					<span class="search-button">Search</span>
				</button>
				<button class="btn-drop three-btn">
					<img src="./images/svgexport-11.svg" alt="more" class="btn-scr">
				</button>
			</div>
		</div>
	</div>
</div>
<!-- ==========================
	Middle Menu
========================== -->
<div class="container-fluid full-container">
	<div class="container middle-heeder">
		<div class="card middle-nav-div shadow-sm border-0">
			<div class="card-body middle-nav-container p-0">
				<div class="fb-menu-row d-flex align-items-center">
					<a class="menu-link active" id="tab_posts">Posts</a>
					<a class="menu-link" id="tab_about">About</a>
					<a class="menu-link hidden-xs" id="tab_channels">Channels</a>
					<a class="menu-link hidden-xs" id="tab_reels">Reels</a>
					<a class="menu-link hidden-xs" id="tab_friends">Friends</a>
					<a class="menu-link hidden-xs" id="tab_events">Events</a>
					<a class="menu-link" id="tab_more">More <img src="./images/dropdown_menu.svg" alt="more"></a>
				</div>
				<div class="three-dot">
					<img src="./images/3dot-1.svg" alt="more-icon">
				</div>
			</div>
		</div>
	</div>
</div>
<!-- ==========================
	friends Section
========================== -->
<div id="friends_container">
	<div class="container friends-inner">
		<!-- Friends Header -->
		<div class="friends-header clearfix">
			<h4 class="pull-left friend-heading">Friends</h4>
			<div class="pull-right friends-search-box">
				<div class="friend-search-div">
					<img src="./images/search-img.svg" alt="search" class="friend-search-icon">
					<input type="text" class="friend-search" placeholder="Search">
				</div>
			</div>
		</div>
		<!-- Friends List -->
		<div id="friends_list">
			<!-- AJAX friends load here -->
		</div>
		<div id="no_friends">
			<p class="no_found">No friends found.</p>
		</div>
	</div>
</div>