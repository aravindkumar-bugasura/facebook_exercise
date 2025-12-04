<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "Root@1234";
$database = "facebook_db";
$con = new mysqli($servername, $username, $password, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Main user
$main_user_id = 1;
$sql_main_user = "SELECT * FROM tUser WHERE User_id = $main_user_id";
$result_main_user = $con->query($sql_main_user);
$main_user = $result_main_user->fetch_assoc();

// Fetch friends
$sql_friend = "SELECT DISTINCT u.User_id, u.Name, u.photo_path
               FROM tFriends f
               JOIN tUser u ON f.friend_id = u.User_id
               WHERE f.user_id = $main_user_id";
$result_friend = $con->query($sql_friend);

// Fetch posts (main user + friends)
$sql_post = "SELECT user_id, post, posting_date 
             FROM tWall
             WHERE user_id = $main_user_id
             OR user_id IN (SELECT friend_id FROM tFriends WHERE user_id=$main_user_id)
             ORDER BY posting_date DESC";
$result_post = $con->query($sql_post);

// Insert new post
if (isset($_POST['post_submit'])) {
    $post = trim($_POST['new_post']); 
    $user_id = $main_user_id; 

    if (!empty($post)) {
        $post_safe = $con->real_escape_string($post);
        $sql_insert = "INSERT INTO tWall (user_id, post, posting_date) 
                       VALUES ($user_id, '$post_safe', NOW())";

        if ($con->query($sql_insert)) {
            header("Location: facebook_exer.php"); 
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Facebook page</title>
<link rel="icon" type="image/png" href="./images/fb_icon_144x144.png">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="/temp.css">
</head>
<body>

<!-- Header and Navbar remain unchanged -->
<div class="container-fluid nav-container">
    <div class="row nav-bar">
        <!-- Left Section -->
        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 banner-left">
            <div class="banner-left-img">
                <img src="./images/fb_icon_144x144.png" alt="facebook" class="facebook-img" height="10px">
            </div>
            <div class="banner-left-input col-lg-12">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" placeholder="Search Facebook" class="search-input hidden-xs hidden-sm search-bar">
            </div>
        </div>
        <!-- Middle Section -->
        <div class="col-sm-4 col-md-4 col-lg-4 banner-middle hidden-xs">
            <ul class="navbar nav-tabs">
                <li><img src="./images/svgexport-4.svg" alt="home" class="middle-img"></li>
                <li><img src="./images/svgexport-5.svg" alt="friends" class="middle-img"></li>
                <li><img src="./images/groups.svg" alt="groups" class="middle-img"></li>
            </ul>
        </div>
        <!-- Right Section -->
        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 banner-right">
            <div class="right-content right-find-friends hidden-xs hidden-sm">
                <p class="friendd"><span class="find-friend">Find friends</span></p>
            </div>
            <div class="right-content top-right-icon"><img src="./images/menu.svg" alt="" class="user-img"></div>
            <div class="right-content top-right-icon"><img src="./images/mess.svg" alt="" class="user-img"></div>
            <div class="right-content top-right-icon"><img src="./images/notification.svg" class="user-img" alt=""></div>
            <div class="right-content user-icon" id="userMenu">
                <img src="./images/user.png" alt="user" class="user-img">
            </div>
        </div>
    </div>
</div>

<!-- Center Image -->
<div class="center text-center">
    <div class="center-img">
        <img src="./images/main.png" alt="">
    </div>
</div>

<!-- Profile Row -->
<div class="row profile-row">
    <div class="col-xs-12 col-md-12 col-lg-2 profile-img">
        <div class="profile-image">
            <img src="./images/549396203_10116914317863211_2996843027695932475_n.jpg" alt="">
        </div>
    </div>
    <div class="col-xs-12 col-md-12 col-lg-6 profile-details">
        <h2>Mark Zuckerberg <span><img src="./images/Verified account.svg" alt=""></span></h2>
        <p><a href="#"><strong>120M</strong>followers</a></p>
        <img src="./images/following.png" alt="">
    </div>
    <div class="col-xs-12 col-md-12 col-lg-4 profile-button">
        <div>
            <button id="btnFollow" class="btn-follow"><img src="./images/LJ8KuNpi23A.png" alt="" class="follow-icon">Follow</button>
            <button class="btn-search"><span><img src="./images/svgexport-3.svg" alt=""></span>Search</button>
            <button><img src="./images/svgexport-11.svg" alt="" class="btn-scr"></button>
        </div>
    </div>
</div>


<hr class="line-hr hello">

<!-- Middle Menu -->
<div class="container-fluid full-container">    
    <div class="container middle-heeder">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="fb-menu-row d-flex align-items-center">
                    <a class="menu-link active">Posts</a>
                    <a class="menu-link">About</a>
                    <a class="menu-link">Channels</a>
                    <a class="menu-link">Reels</a>
                    <a class="menu-link">Photos</a>
                    <a class="menu-link">Events</a>
                    <a class="menu-link">More</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid post-full-container">
    <div class="container mt-4 pcontainer">
        <div class="row">
            <!-- LEFT SIDE: Intro + Friends Photos -->
            <div class="col-lg-4">
                <!-- Intro Card -->
                <div class="card shadow-sm border-0 mb-3 fb-card">
                    <div class="card-body fb-card-body">
                        <div class="intro-title">Intro</div>
                        <div class="intro-text">Bringing the world closer together.</div>
                        <ul class="list-unstyled intro-list">
                            <li><i><img src="./images/new/prof7.png" alt=""></i> Profile · Public figure</li>
                            <li><i><img src="./images/new/prof3.png" alt=""></i> Founder and CEO at <b>Meta</b></li>
                            <li><i><img src="./images/new/prof3.png" alt=""></i> Works at <b>Biohub</b></li>
                            <li><i><img src="./images/new/prof2.png" alt=""></i> Studied at <b>Harvard University</b></li>
                            <li><i><img src="./images/new/prof6.png" alt=""></i> Lives in <b>Palo Alto, California</b></li>
                            <li><i><img src="./images/new/prof1.png" alt=""></i> From <b>Dobbs Ferry, New York</b></li>
                            <li><i><img src="./images/new/prof4.png" alt=""></i> Married to <b>Priscilla Chan</b></li>
                            <li><i><img src="./images/new/prof5.png" alt=""></i> Meta Channel · 800k members</li>
                        </ul>
                    </div>
                </div>

                <!-- Friends Photos -->
                <div class="row photos">
                    <div class="photos-top">
                        <h4>Friends</h4>
                        <span>See All Photos</span>
                    </div>
                    <div class="row g-2">
                        <?php
                        if($result_friend->num_rows > 0){
                            $result_friend->data_seek(0);
                            while($friend = $result_friend->fetch_assoc()){
                                $photo_path = !empty($friend['photo_path']) ? $friend['photo_path'] : 'images/default-user.png';
                                echo '<div class="col-4 mb-2">';
                                echo '<img src="'. $photo_path .'" title="'. htmlspecialchars($friend['Name']) .'" data-userid="'. $friend['User_id'] .'" onerror="this.src=\'images/default-user.png\'">';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>No friends photos yet.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE: Posts -->
            <div class="col-lg-8">
                <h4 class="post-heading">Posts</h4>
                <!-- CREATE POST ALWAYS VISIBLE -->
                <div class="create-post">
                <textarea id="newPostText" class="post-textarea" placeholder="Write something..."></textarea><br>
                <button id="postBtn" class="buttom-post">Post</button>
            </div>

    <!-- POSTS WILL BE LOADED HERE -->
    <div id="postsContainer">
        <?php
        if ($result_post->num_rows > 0) {
            while($post = $result_post->fetch_assoc()) {
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
                                <strong><?php echo $user_name; ?></strong><br>
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

<script>
document.querySelectorAll('.photos img').forEach(photo => {
    photo.addEventListener('click', function() {
        const friendId = this.dataset.userid;
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_posts.php?user_id=' + friendId, true);
        xhr.onload = function() {
            if(this.status === 200){
                document.getElementById('postsContainer').innerHTML = this.responseText;
            }
        };
        xhr.send();
    });
});

document.getElementById("postBtn").addEventListener("click", function () {

    let postText = document.getElementById("newPostText").value.trim();

    if(postText === ""){
        alert("Please enter something!");
        return;
    }

    let formData = new FormData();
    formData.append("new_post", postText);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "add_post_ajax.php", true);

    xhr.onload = function () {
        if (this.responseText.trim() === "success") {

            // Clear textarea
            document.getElementById("newPostText").value = "";

            // Refresh posts section
            loadPosts();
        } else {
            alert("Error posting!");
        }
    };

    xhr.send(formData);
});

// Load latest posts without reload
function loadPosts() {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_posts_ajax.php", true);

    xhr.onload = function () {
        document.getElementById("postsContainer").innerHTML = this.responseText;
    };

    xhr.send();
}
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>

<?php $con->close(); ?>