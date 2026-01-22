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
						$sql_user_post = "SELECT name, photo_path FROM tUser WHERE user_id = $user_id_post";
						$res_user_post = $con->query($sql_user_post);
						$user_post = $res_user_post->fetch_assoc();
						$profile_photo = !empty($user_post['photo_path']) ? $user_post['photo_path'] : 'images/default-user.png';
						$user_name = htmlspecialchars($user_post['name']);
						?>
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
<?php include 'includes/about_section.php';?>