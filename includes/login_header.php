<div class="container-fluid main-wrapper">
	<!-- Left Content -->
	<div class="left-content">
		<img src="./images/facebook_text.svg" alt="facebook">
		<p class="fb-text">
			Facebook helps you connect and share<br>with the people in your life.
		</p>
	</div>
	<!-- Right Login Box -->
	<div class="login-input">
		`<div class="login-box">
			<form method="POST">
				<input type="email" name="email" placeholder="Email address or phone number" class="input-placeholder" required>
				<input type="password" name="password" placeholder="Password" class="input-placeholder" required>
				<button type="submit" name="login">
					<div class="login-button">
						<p class="login-text">Log in</p>
					</div>
				</button>
				<?php if ($msg != "") echo "<p class='msg'>$msg</p>"; ?>
				<a href="#" class="forgot">Forgotten password?</a>
				<div class="hr-line"></div>
				<a href="#" class="create-account">Create new account</a>
			</form>
		</div>
		<div class="create-page">
			<p class="create-line">
				<a href="#"><b class="create-post-bold">Create a Page</b></a> for a celebrity, brand or business.
			</p>
		</div>
	</div>
</div>