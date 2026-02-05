<?php
	// ==========================
	// Database Connection
	// ==========================
		$servername = "localhost";
		$username   = "root";
		$password   = "Root@123";
		$database   = "facebook_db";

		// Create connection
		$con = new mysqli($servername, $username, $password, $database);

		// Check connection
		if ($con->connect_error) {
			die("Connection failed: " . $con->connect_error);
		}

	// ==========================
	// Connection successful
	// ==========================
?>
