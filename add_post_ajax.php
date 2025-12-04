<?php
$servername = "localhost";
$username = "root";
$password = "Root@1234";
$database = "facebook_db";

$con = new mysqli($servername, $username, $password, $database);
if ($con->connect_error) die("Connection failed: " . $con->connect_error);

$main_user_id = 1;

if(isset($_POST['new_post'])){
    $post = trim($_POST['new_post']);
    if(!empty($post)){
        $post_safe = $con->real_escape_string($post);
        $sql = "INSERT INTO tWall (user_id, post, posting_date)
                VALUES ($main_user_id, '$post_safe', NOW())";

        if($con->query($sql)){
            echo "success";
        } else {
            echo "error";
        }
    }
}

$con->close();
?>
