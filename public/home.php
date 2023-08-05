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

    <!-- Add navigation links -->
    <p><a href="home.php">Home</a> | <a href="explore.php">Explore</a></p>

    <h3>Posts from users you follow:</h3>
    <?php
    // Pagination settings
    $posts_per_page = 5; // Change this number to control the number of posts per page
    $total_posts = count($posts);
    $total_pages = ceil($total_posts / $posts_per_page);

    // Get the current page number from the URL
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

    // Calculate the starting index for the current page
    $start_index = ($current_page - 1) * $posts_per_page;

    // Get the posts for the current page
    $current_page_posts = array_slice($posts, $start_index, $posts_per_page);
    ?>
    // Display the posts for the current page
    <?php foreach ($current_page_posts as $post) :
    ?>
        <div>
            <p><?php echo $post['content']; ?></p>
            <p>Posted by: <?php echo $post['username']; ?></p>
            <?php if ($post['content_type'] === 'image') : ?>
                <img src="<?php echo $post['media_path']; ?>" alt="Post Image" width="200">
            <?php elseif ($post['content_type'] === 'video') : ?>
                <video src="<?php echo $post['media_path']; ?>" controls width="200"></video>
            <?php endif; ?>
            <!-- Add link to post details page -->
            <a href="post_details.php?post_id=<?php echo $post['post_id']; ?>">View Details</a>
        </div>
    <?php endforeach; ?>

    <!-- Pagination links -->
    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
        <?php if ($i === $current_page) : ?>
            <strong><?php echo $i; ?></strong>
        <?php else : ?>
            <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>
</body>

</html>