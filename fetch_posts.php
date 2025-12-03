<?php
$servername = "localhost";
$username = "root";
$password = "Root@1234";
$database = "facebook_db";

$con = new mysqli($servername, $username, $password, $database);
if ($con->connect_error) die("Connection failed: " . $con->connect_error);

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

$sql_post = "SELECT user_id, post, posting_date FROM tWall WHERE user_id = $user_id ORDER BY posting_date DESC";
$result_post = $con->query($sql_post);

if($result_post->num_rows > 0){
    while($post = $result_post->fetch_assoc()) {
        $user_id_post = $post['user_id'];
        $sql_user_post = "SELECT Name, photo_path FROM tUser WHERE User_id = $user_id_post";
        $res_user_post = $con->query($sql_user_post);
        $user_post = $res_user_post->fetch_assoc();
        $profile_photo = !empty($user_post['photo_path']) ? $user_post['photo_path'] : 'images/default-user.png';
        $user_name = htmlspecialchars($user_post['Name']);

        echo '<div class="post">';
        
        // Post header
        echo '<div class="post-header">';
        echo '<div class="post-user">';
        echo '<img src="'. $profile_photo .'" alt="profile" class="post-user-img">';
        echo '<div class="user-info">';
        echo '<strong>'. $user_name .'</strong><br>';
        echo '<small>'. $post['posting_date'] .'</small>';
        echo '</div>'; // user-info
        echo '</div>'; // post-user
        echo '<div class="post-more">';
        echo '<img src="images/more.svg" alt="more" class="post-more-img">';
        echo '</div>'; // post-more
        echo '</div>'; // post-header

        // Post content
        echo '<div class="post-content">'. htmlspecialchars($post['post']) .'</div>';

        // Reaction bar
        echo '<div class="reaction-bar">❤️👍 125K · 47.8K comments · 13K shares</div>';

        echo '</div>'; // post
    }
} else {
    echo "<p>No posts yet.</p>";
}

$con->close();
?>
