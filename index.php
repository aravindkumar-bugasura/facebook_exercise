<?php
	session_start();
	// ==========================
	// Database connection
	// ==========================
	$servername = "localhost";
	$username   = "root";
	$password   = "Root@123";
	$database   = "facebook_db";
	$con = new mysqli($servername, $username, $password, $database);
	if ($con->connect_error) {
		die("Connection failed: " . $con->connect_error);
	}
	// ==========================
	// Check if user is logged in
	// ==========================
	if (!isset($_SESSION['user_id'])) {
		header("Location: login.php");
		exit;
	}
	// ==========================
	// Profile user (viewed profile)
	// ==========================
	$profile_user_id = isset($_GET['user_id'])
		? (int) $_GET['user_id']
		: $_SESSION['user_id'];
	// ==========================
	// Function to show "time ago"
	// ==========================
	function timeAgo($datetime) {
		$time = strtotime($datetime);
		$diff = time() - $time;
		if ($diff < 60) {
			return "Just now";
		} elseif ($diff < 3600) {
			return floor($diff / 60) . "m";
		} elseif ($diff < 86400) {
			return floor($diff / 3600) . "h";
		} elseif ($diff < 345600) {
			return floor($diff / 86400) . "d";
		} else {
			return date("d M \\a\\t H:i ", $time);
		}
	}
	// ==========================
	// Fetch main user data
	// ==========================
	$main_user_id = $_SESSION['user_id'];
	$sql_main_user = "SELECT * FROM tUser WHERE User_id = $main_user_id";
	$result_main_user = $con->query($sql_main_user);
	$main_user = $result_main_user->fetch_assoc();
	// ==========================
	// Fetch friends of main user
	// ==========================
	$sql_friend = "
		SELECT DISTINCT u.User_id, u.Name, u.photo_path, u.cover_photo
		FROM tFriends f
		LEFT OUTER JOIN tUser u ON f.friend_id = u.User_id
		WHERE f.user_id = $main_user_id;
	";
	$result_friend = $con->query($sql_friend);
	// ==========================
	// Handle Edit Profile Submission
	// ==========================
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
		$user_id = $_SESSION['user_id'];
		$name    = $con->real_escape_string($_POST['name']);
		$email   = $con->real_escape_string($_POST['email']);
		$phone   = $con->real_escape_string($_POST['phone']);
		$address = $con->real_escape_string($_POST['address']);
		$sql_update = "
			UPDATE tUser 
			SET name = '$name', 
				email_id = '$email', 
				phone = '$phone', 
				address = '$address'
			WHERE User_id = $user_id
		";
		if ($con->query($sql_update)) {
			$_SESSION['update_success'] = "Profile updated successfully!";
			header("Location: index.php");
			exit;
		} else {
			$_SESSION['update_error'] = "Error updating profile: " . $con->error;
		}
	}
	// ==========================
	// Fetch posts (main user + friends)
	// ==========================
	if ($profile_user_id == $main_user_id) {
		// MAIN USER FEED (Mark + friends)
		$sql_post = "
			SELECT DISTINCT w.user_id, w.post, w.posting_date
			FROM tWall w LEFT JOIN tFriends f ON w.user_id = f.friend_id
			WHERE w.user_id = $main_user_id OR f.user_id = $main_user_id
			ORDER BY w.posting_date DESC;
		";
	} else {
		// FRIEND PROFILE (only Vikram)
		$sql_post = "
			SELECT user_id, post, posting_date
			FROM tWall
			WHERE user_id = $profile_user_id
			ORDER BY posting_date DESC
		";
	}
	$result_post = $con->query($sql_post);
	// ==========================
	// Insert new post if submitted
	// ==========================
	if (isset($_POST['post_submit'])) {
		$post = trim($_POST['new_post']);
		$user_id = $main_user_id;
		if (!empty($post)) {
			$post_safe = $con->real_escape_string($post);
			$sql_insert = "
				INSERT INTO tWall (user_id, post, posting_date)
				VALUES ($user_id, '$post_safe', NOW())
			";
			if ($con->query($sql_insert)) {
				header("Location: check.php");
				exit();
			}
		}
	}
	// ==========================
	// Fetch profile user data (for header)
	// ==========================
	$sql_profile_user = "SELECT Name, photo_path FROM tUser WHERE User_id = $profile_user_id";
	$result_profile_user = $con->query($sql_profile_user);
	$profile_user = $result_profile_user->fetch_assoc();
	// Fallback image
	$profile_photo = !empty($profile_user['photo_path'])
		? $profile_user['photo_path']
		: 'images/default-user.png';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Facebook page</title>
		<link rel="icon" type="image/png" href="./images/fb_icon_144x144.png">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="/index.css">
	</head>
	<body>
	<!-- ==========================
		Header and Navbar
	========================== -->
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
						<div class="right-content top-right-icon" id="menu"><img src="./images/menu.svg" alt="menu" class="user-img"></div>
						<div class="right-content top-right-icon" id="message"><img src="./images/mess.svg" alt="message" class="user-img"></div>
						<div class="right-content top-right-icon" id="notifications"><img src="./images/notification.svg" class="user-img" alt="notification"></div>
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
			</div>
		</div>
	<!-- ==========================
		Center Cover Image
	========================== -->
		<div class="container-fluid center text-center">
			<div class="container bg-image" id="cover_photo"></div>
		</div>
	<!-- ==========================
		Profile Row
	========================== -->
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
							<?php echo htmlspecialchars($profile_user['Name']); ?>
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
	<!-- ==========================
		Posts Section
	========================== -->
		<div class="container-fluid post-full-container" id="posts_section">
			<div class="container post-midle-container">
				<div class="row post-intro ">
					<!-- LEFT SIDE -->
					<div class="col-lg-5 post-left-side">
						<!-- Intro Card -->
						<div class="card shadow-sm border-0 mb-3 fb-card">
							<div class="card-body fb-card-body">
								<div class="intro-title">Intro</div>
								<div class="intro-text">
									<span>Bringing the world closer together.</span>
								</div>
								<ul class="list-unstyled intro-list">
									<li>
										<i>
											<img class="new-profile" src="./images/new/prof7.png" alt="profile">
										</i> <b>Profile</b>
										· Public figure
									</li>
									<li>
										<i>
											<img src="./images/new/prof3.png" alt="meta">
										</i> 
										Founder and CEO at <b>Meta</b>
									</li>
									<li>
										<i>
											<img src="./images/new/prof3.png" alt="work">
										</i> Works at <b>Biohub</b>
									</li>
									<li>
										<i>
											<img src="./images/new/prof2.png" alt="study">
										</i> Studied at <b>Harvard University</b>
									</li>
									<li>
										<i>
											<img src="./images/new/prof6.png" alt="places">
										</i> Lives in <b>Palo Alto,California</b>
									</li>
									<li>
										<i>
											<img src="./images/new/prof1.png" alt="native">
										</i> From <b>Dobbs Ferry, New York</b>
									</li>
									<li>
										<i>
											<img src="./images/new/prof4.png" alt="married">
										</i> Married to <b>Priscilla Chan</b>
									</li>
									<li>
										<i>
											<img src="./images/new/prof5.png" alt="channel">
										</i> 
										<a href="#">Meta Channel</a>
										<div class="post-bio">Channel · 807k members</div>
									</li>
								</ul>
							</div>
						</div>
						<!-- Photos Section -->
						<div class="photos">
							<div class="photos-top">
								<h4>Photos</h4>
								<a href="#" class="see-all">See All Photos</a>
							</div>
							<div class="photos-grid">
								<img src="./images/photos-img9.jpg" alt="photo1" class="photo-first">
								<img src="./images/photos-img8.jpg" alt="photo2" class="photo-second">
								<img src="./images/photos-img7.jpg" alt="photo3" class="photo-third">
								<img src="./images/photos-img6.jpg" alt="photo4" class="photo-fourth">
								<img src="./images/photos-img5.jpg" alt="photo5" class="photo-fifth">
								<img src="./images/photos-img4.jpg" alt="photo6" class="photo-sixth">
								<img src="./images/photos-img3.jpg" alt="photo7" class="photo-seventh">
								<img src="./images/photos-img2.jpg" alt="photo8" class="photo-eighth">
								<img src="./images/photos-img1.jpg" alt="photo9" class="photo-ninth">
							</div>
						</div>
						<div class="advertisement">
							<ul class="ad-list">
								<li>Privacy .</li>
								<li>Terms .</li>
								<li>Advertising .</li>
								<li>Ad choices <img src="./images/privacy.png" alt="ad choices" class="privacy"> .</li>
								<li>Cookies .</li>
								<li>More</li>
							</ul>
						</div>
					</div>
					<!-- RIGHT SIDE -->
					<div class="col-lg-7 post-right-side">
						<!-- Posts Header -->
						<div class="post-section">
							<div class="post-heading-div">
								<div class="post-heading-inner">
									<h4 class="post-heading">Posts</h4>
								</div>
								<div class="post-filter">
									<div class="post-filter-icon">
										<div>
											<img src="./images/filter.svg" alt="filters" class="filter-img"> 
											<span class="filter-icon">Filters</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- Create Post -->
						<div class="create-post" id="create_post">
							<input id="new_post_text" class="post-textarea" placeholder="Write something..."><br>
							<button id="post_btn" class="buttom-post">Post</button>
							<span id="error_msg"></span>
						</div>
						<!-- Posts -->
						<div id="posts_container">
							<?php
							if ($result_post->num_rows > 0) {
							while ($post = $result_post->fetch_assoc()) {
								$user_id_post = $post['user_id'];
								$sql_user_post = "SELECT Name, photo_path FROM tUser WHERE User_id = $user_id_post";
								$res_user_post = $con->query($sql_user_post);
								$user_post = $res_user_post->fetch_assoc();
								$profile_photo = !empty($user_post['photo_path']) ? $user_post['photo_path'] : 'images/default-user.png';
								$user_name = htmlspecialchars($user_post['Name']);
								?>
							<div class="post">
								<div class="post-header">
									<div class="post-user">
										<img src="<?php echo $profile_photo; ?>" class="post-user-img">
										<div class="user-info">
											<strong>
												<?php echo $user_name; ?>
											</strong><br>
											<small class="date-format">
												<?php echo timeAgo($post['posting_date']); ?> . <img
													src="./images/Shared with Public.svg" alt="public">
											</small>
										</div>
									</div>
									<div class="post-more">
										<img src="./images/more.svg" class="post-more-img">
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
										<span>69.2k comments 7k shares</span>
									</div>
								</div>
								<div class="post-actions">
									<button class="action-btn">
										<i class="action-like">Like</i>
									</button>
									<button class="action-btn">
										<i class="action-comment">Comment</i>	
									</button>
									<button class="action-btn">
										<i class="action-share">Share</i>
									</button>
								</div>
							</div>
							<?php
							}
						} else {
							echo "<p>No posts yet.</p>";
						}
						?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- ==========================
		About Section
	========================== -->
		<div id="about_container">
			<div class="container about-wrapper">
				<div class="row">
					<!-- LEFT SIDE MENU -->
					<div class="col-lg-4 about-left-side">
						<div class="card about-left">
							<ul class="about-menu">
								<h4 class="about-heading">About</h4>
								<li class="active">Overview</li>
								<li>Work and education</li>
								<li>Places lived</li>
								<li>Contact and basic info</li>
								<li>Privacy and legal info</li>
								<li>Profile transparency</li>
								<li>Family and relationships</li>
								<li>Life events</li>
							</ul>
						</div>
					</div>
					<!-- RIGHT SIDE CONTENT -->
					<div class="col-lg-8">
						<div class="card about-right">
							<ul class="about-details">
								<li>
									<img src="./images/about_img1.png" alt="about" class="about-icon">
									<span>Works at <b>Meta</b> and <b>Biohub</b></span>
								</li>
								<li>
									<img src="./images/about_img2.png" alt="study" class="about-icon">
									<span>Studied Computer Science and Psychology at <b>Harvard University</b></span>
									<div class="small-text"><br>Attended from 2002 to 2004</div>
								</li>
								<li>
									<img src="./images/about_img3.png" alt="lives" class="about-icon">
									<span>Lives in <b>Palo Alto, California</b></span>
								</li>
								<li>
									<img src="./images/about_img4.png" alt="from" class="about-icon">
									<span>From <b>Dobbs Ferry, New York</b></span>
								</li>
							</ul>
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
	<!-- ==========================
		JavaScript Section
	========================== -->
		<script>
			const LOGGED_IN_USER_ID = "<?php echo $_SESSION['user_id']; ?>";
			/* ==========================
			Dropdown Toggle
			==========================*/
			const userImg = document.getElementById('user_img_mark');
			const dropdown = document.getElementById('profile_dropdown');
			userImg.addEventListener('click', function (e) {
				dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
			});
			// Close dropdown if clicked outside
			window.addEventListener('click', function (e) {
				if (!userImg.contains(e.target) && !dropdown.contains(e.target)) {
					dropdown.style.display = 'none';
				}
			});
			/* ==========================
			Edit Profile Modal
			==========================*/
			const editBtn = document.getElementById("edit_profile_btn");
			const modal = document.getElementById("edit_profile_modal");
			editBtn.addEventListener("click", () => {
				modal.style.display = "flex";
			});
			function closeBox() {
				document.getElementById("edit_profile_modal").style.display = "none";
			}
			/* ==========================
			Add new post via AJAX
			========================== */
			document.getElementById("post_btn").addEventListener("click", function () {
				let postText = document.getElementById("new_post_text").value.trim();
				if (postText === "") {
					const errorMsg = document.getElementById("error_msg");
					errorMsg.innerText = "Post cannot be empty!";
					setTimeout(() => {
						errorMsg.innerText = "";
					}, 1000);
					return;
				}
				let formData = new FormData();
				formData.append("new_post", postText);
				let xhr = new XMLHttpRequest();
				xhr.open("POST", "add_post_ajax.php", true);
				xhr.onload = function () {
					if (this.responseText.trim() === "success") {
						document.getElementById("new_post_text").value = "";
						loadPosts();
					} else {
						alert("Error posting!");
					}
				};
				xhr.send(formData);
			});
			// Load latest posts without page reload
			function loadPosts(profileUserId = LOGGED_IN_USER_ID) {
				let xhr = new XMLHttpRequest();
				xhr.open("GET","fetch_posts_ajax.php?user_id=" + profileUserId,true);
				xhr.onload = function () {
					document.getElementById("posts_container").innerHTML = this.responseText;
				};
				xhr.send();
			}
			const tabFriends = document.getElementById("tab_friends");
			const tabPosts = document.getElementById("tab_posts");
			const tabAbout = document.getElementById("tab_about");
			const postsSection = document.getElementById("posts_section");
			const friendsContainer = document.getElementById("friends_container");
			const aboutContainer = document.getElementById("about_container");
			tabFriends.addEventListener("click", function () {
				postsSection.style.display = "none";
				aboutContainer.style.display = "none";
				friendsContainer.style.display = "block";
				document.querySelectorAll('.menu-link').forEach(tab => tab.classList.remove('active'));
				this.classList.add('active');
				let xhr = new XMLHttpRequest();
				const urlParams = new URLSearchParams(window.location.search);
				urlParams.set("tab", "friends");
				history.pushState({}, "", "index.php?" + urlParams.toString());
				const profileUserId = urlParams.get("user_id") || "<?php echo $_SESSION['user_id']; ?>";
				xhr.open("GET","fetch_friend_list.php?user_id=" + profileUserId,true);
				xhr.onload = function () {
					document.getElementById('friends_list').innerHTML = xhr.responseText;
				};
				xhr.send();
			});
			tabPosts.addEventListener("click", function () {
				friendsContainer.style.display = "none";
				aboutContainer.style.display = "none";
				postsSection.style.display = "block";
				document.querySelectorAll('.menu-link')
					.forEach(tab => tab.classList.remove('active'));
				this.classList.add('active');

				const params = new URLSearchParams(window.location.search);
				params.set("tab", "posts");
				history.pushState({}, "", "index.php?" + params.toString());
				const profileUserId = params.get("user_id") || LOGGED_IN_USER_ID;
				toggleCreatePostBox(profileUserId);
				loadPosts(profileUserId);
			});
			tabAbout.addEventListener("click", function () {
				postsSection.style.display = "none";
				friendsContainer.style.display = "none";
				aboutContainer.style.display = "block";
				document.querySelectorAll('.menu-link')
					.forEach(tab => tab.classList.remove('active'));
				this.classList.add('active');
				const urlParams = new URLSearchParams(window.location.search);
				urlParams.set("tab", "about");
				history.pushState({}, "", "index.php?" + urlParams.toString());
			});
			/* ==========================
			Friend Search Filter
			========================== */
			document.addEventListener('keyup', function (e) {
				if (!e.target.classList.contains('friend-search')) return;
				const val = e.target.value.toLowerCase().trim();
				const friends = document.querySelectorAll('.friend-row');
				const noFriendsElement = document.getElementById('no_friends');
				let found = false;
				friends.forEach(row => {
					const match = row.innerText.toLowerCase().includes(val);
					row.style.display = match ? 'block' : 'none';
					if (match) found = true;
				});
				// Show "No friends found" base no after search there is 0 child visible
				if (!found) {
					noFriendsElement.style.display = 'block';
				} else {
					noFriendsElement.style.display = 'none';
				}
			});

			/* ==========================
			Load Friend's Posts on Click
			==========================*/
			document.getElementById("friends_list").addEventListener("click", function (e) {
				const friendRow = e.target.closest(".friend-row");
				if (!friendRow) return;
				const friendId = friendRow.dataset.id;
				const friendName = friendRow.dataset.name;
				const friendPhoto = friendRow.dataset.photo;
				window.location.href = "index.php?user_id=" + friendId + "&tab=posts";
			});
			function toggleCreatePostBox(profileUserId) {
				const box = document.getElementById("create_post");
				if (!box) return;

				if (profileUserId == LOGGED_IN_USER_ID) {
					box.style.display = "block";
				} else {
					box.style.display = "none";    
				}
			}
			window.addEventListener("load", function () {
				const params = new URLSearchParams(window.location.search);
				const profileUserId = params.get("user_id") || LOGGED_IN_USER_ID;
				const urlParams = new URLSearchParams(window.location.search);
				const userId = urlParams.get("user_id");
				if (userId) {
					loadFriendPosts(userId);
				}
				toggleCreatePostBox(profileUserId);
				const tab = params.get("tab") || "posts";
				if (tab === "posts") tabPosts.click();
				else if (tab === "friends") tabFriends.click();
				else if (tab === "about") tabAbout.click();
			});
			/* -----------------------------
			Load Friend's Posts Function
			----------------------------- */
			function loadFriendPosts(friendId) {
				let xhr = new XMLHttpRequest();
				xhr.open("GET", "fetch_posts_ajax.php?user_id=" + friendId, true);
				xhr.onload = function () {
					document.getElementById("posts_container").innerHTML = this.responseText;
				};
				xhr.send();
			}
		</script>
	</body>
</html>