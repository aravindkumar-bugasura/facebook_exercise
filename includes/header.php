<div class="container-fluid nav-container">
	<div class="row nav-bar nav-width">
		<!-- Left Section -->
		<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 banner-left">
			<div class="banner-left-img">
				<img src="./images/fb_icon_144x144.png" alt="facebook" class="facebook-img" height="10px">
			</div>
			<div class="banner-left-input col-lg-12">
				<div class="search-box">
					<img src="./images/search-img.svg" alt="search">
					<input type="text" placeholder="Search Facebook" class="search-input hidden-xs hidden-sm search-bar">
				</div>
			</div>
		</div>
		<!-- Middle Section -->
		<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 banner-middle hidden-xs">
			<ul class="navbar nav-tabs">
				<li><img src="./images/svgexport-4.svg" alt="home" class="middle-img"></li>
				<li><img src="./images/svgexport-5.svg" alt="friends" class="middle-img"></li>
				<li><img src="./images/groups.svg" alt="groups" class="middle-img"></li>
			</ul>
		</div>
		<!-- Right Section -->
		<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 banner-right">
			<div class="header-right ">
				<div class="right-content right-find-friends hidden-xs hidden-sm">
					<span class="find-friend">Find friends</span>
				</div>
				<div class="right-content top-right-icon" id="menu">
					<img src="./images/menu.svg" alt="menu" class="user-img">
				</div>
				<div class="right-content top-right-icon" id="message">
					<img src="./images/mess.svg" alt="message" class="user-img">
				</div>
				<div class="right-content top-right-icon" id="notifications">
					<img src="./images/notification.svg" class="user-img" alt="notification">
				</div>
				<div class="right-content user-icon dropdown">
					<img src="./images/mark.jpg" alt="user" class="user-img" id="user_img_mark">
					<img src="./images/dropdown_pro.svg" alt="drop_down" id="user_drop_mark">
					<div class="dropdown-menu fb-dropdown" id="profile_dropdown">
						<!-- Profile -->
						<div class="fb-profile-box">
							<div class="fb-profile-row">
								<div class="fb-avatar">
									<img src="./images/mark.jpg" alt="profile" class="fb-avatar-img">
								</div>
								<span class="fb-name" id="edit_profile_btn">
									<?php echo isset($main_user['name']) ? htmlspecialchars($main_user['name']) : 'User'; ?>
								</span>
							</div>
						</div>
						<!-- See all profiles -->
						<div class="fb-all-profiles">
							<i class="fa-solid fa-users"></i>
							<span>See all profiles</span>
						</div>
						<div class="fb-divider"></div>
						<!-- Menu items -->
						<div class="fb-item">
							<i><img src="./images/pro-setting.svg" alt="settings"></i>
							<span>Settings & privacy</span>
							<i class="fa-solid fa-chevron-right right-arrow"></i>
						</div>
						<div class="fb-item">
							<i><img src="./images/prof-qn.svg" alt="help"></i>
							<span>Help & support</span>
							<i class="fa-solid fa-chevron-right right-arrow"></i>
						</div>
						<div class="fb-item">
							<i><img src="./images/prof-dis.svg" alt="display"></i>
							<span>Display & accessibility</span>
							<i class="fa-solid fa-chevron-right right-arrow"></i>
						</div>
						<div class="fb-item">
							<i><img src="./images/prof-msg.svg" alt="give feedback"></i>
							<span>Give feedback</span>
							<small>CTRL B</small>
						</div>
						<div class="fb-item">
							<i><img src="./images/log1.png" alt="logout"></i>
							<a href="logout.php" class="logout-link">Log out</a>
						</div>
						<div class="fb-footer">
							Privacy · Terms · Advertising · Cookies · More
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- ==========================
		Edit Profile Modal
		========================== -->
		<div class="edit-modal" id="edit_profile_modal">
			<form class="edit-form" action="" method="POST" enctype="multipart/form-data">
				<h3 class="edit-title">Edit Profile</h3>
				<label class="edit-label">Name</label>
				<input class="edit-input" type="text" name="name"
					value="<?php echo htmlspecialchars($main_user['name']); ?>" required>
				<label class="edit-label">Email</label>
				<input class="edit-input" type="email" name="email"
					value="<?php echo htmlspecialchars($main_user['email_id']); ?>" required>
				<label class="edit-label">Phone</label>
				<input class="edit-input" type="phone" name="phone"
					value="<?php echo htmlspecialchars($main_user['phone']); ?>" required>
				<label class="edit-label">Address</label>
				<input class="edit-input" type="text" name="address"
					value="<?php echo htmlspecialchars($main_user['address']); ?>" required>
				<button class="save-btn" type="submit">Save Changes</button>
				<!-- Close button below -->
				<button type="button" class="close-btn" onclick="closeBox()">Close</button>
			</form>
		</div>
	</div>
</div>