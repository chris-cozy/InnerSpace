<?php

// Include the database connection file
require_once '../includes/db_connection.php';

// Check if the user is logged in. Redirect to login page if not.
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

try {
    // Prepare a select statement to retrieve user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    // Execute the prepared statement
    $stmt->execute();

    // Fetch user data as an associative array
    $user = $stmt->fetch();
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching user data: " . $e->getMessage());
}

try {
    // Prepare a select statement to retrieve posts from users that the current user follows
    $stmt = $pdo->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.user_id WHERE posts.user_id IN (SELECT following_id FROM followers WHERE follower_id = :user_id) ORDER BY posts.created_at DESC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    // Execute the prepared statement
    $stmt->execute();

    // Fetch posts as an associative array
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching posts: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Home Page</title>
</head>

<body>
    <h2>Home Page</h2>
    <p>Welcome, <?php echo $user['username']; ?>!</p>
    <p>Username: <?php echo $user['username']; ?></p>
    <p>Email: <?php echo $user['email']; ?></p>
    <p>Bio: <?php echo $user['bio']; ?></p>
    <!-- Display user profile picture -->
    <img src="<?php echo $user['profile_pic']; ?>" alt="Profile Picture" width="100" height="100">

    <a href="update_profile.php">Update Profile</a>

    <h3>Posts from users you follow:</h3>
    <?php foreach ($posts as $post) : ?>
        <div>
            <p><?php echo $post['content']; ?></p>
            <p>Posted by: <?php echo $post['username']; ?></p>
            <?php if ($post['content_type'] === 'image') : ?>
                <img src="<?php echo $post['media_path']; ?>" alt="Post Image" width="200">
            <?php elseif ($post['content_type'] === 'video') : ?>
                <video src="<?php echo $post['media_path']; ?>" controls width="200"></video>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</body>

</html>