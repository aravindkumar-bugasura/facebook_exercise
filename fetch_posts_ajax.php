<?php
$servername = "localhost";
$username = "root";
$password = "Root@1234";
$database = "facebook_db";

$con = new mysqli($servername, $username, $password, $database);
if ($con->connect_error) die("Connection failed: " . $con->connect_error);

$main_user_id = 1;

// Fetch posts
$sql_post = "SELECT user_id, post, posting_date 
             FROM tWall
             WHERE user_id = $main_user_id
             OR user_id IN (SELECT friend_id FROM tFriends WHERE user_id=$main_user_id)
             ORDER BY posting_date DESC";

$result_post = $con->query($sql_post);

while($post = $result_post->fetch_assoc()){
    $user_id_post = $post['user_id'];

    $query_user = $con->query("SELECT Name, photo_path FROM tUser WHERE User_id=$user_id_post");
    $user = $query_user->fetch_assoc();
    $photo = !empty($user['photo_path']) ? $user['photo_path'] : 'images/default-user.png';
    ?>

    <div class="post">
        <div class="post-header">
            <div class="post-user">
                <img src="<?php echo $photo; ?>" class="post-user-img">
                <div class="user-info">
                    <strong><?php echo htmlspecialchars($user['Name']); ?></strong><br>
                    <small><?php echo $post['posting_date']; ?></small>
                </div>
            </div>
            <div class="post-more">
                <img src="./images/more.svg" class="post-more-img">
            </div>
        </div>

        <div class="post-content">
            <?php echo htmlspecialchars($post['post']); ?>
        </div>

        <div class="reaction-bar">
            ❤️👍 125K · 47.8K comments · 13K shares
        </div>
    </div>

<?php } ?>
