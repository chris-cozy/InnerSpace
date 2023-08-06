<?php
// This page will display the user's profile and allow them to update their profile information and profile picture.
// Fetch the user's data from the database and display it.
// Allow users to update their information through a form.

// Include the database connection file
require_once '../includes/db_connection.php';

// Check if the user is logged in. Redirect to login page if not.
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the user ID from the session
$current_user_id = $_SESSION['user_id'];

try {
    // Prepare a select statement to retrieve user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $current_user_id, PDO::PARAM_INT);

    // Execute the prepared statement
    $stmt->execute();

    // Fetch user data as an associative array
    $user = $stmt->fetch();
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching user data: " . $e->getMessage());
}
// Get the count of followers for the current user
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM follows WHERE following_id = :user_id");
    $stmt->bindParam(':user_id', $current_user_id, PDO::PARAM_INT);
    $stmt->execute();
    $follower_count = $stmt->fetchColumn();
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching follower count: " . $e->getMessage());
}

// Get the count of accounts the current user is following
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM follows WHERE follower_id = :user_id");
    $stmt->bindParam(':user_id', $current_user_id, PDO::PARAM_INT);
    $stmt->execute();
    $following_count = $stmt->fetchColumn();
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching following count: " . $e->getMessage());
}

try {
    // Prepare a select statement to retrieve user's posts
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    // Execute the prepared statement
    $stmt->execute();

    // Fetch user's posts as an associative array
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching user's posts: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Profile</title>
</head>

<body>
    <h2>My Profile</h2>
    <p>Username: <?php echo $user['username']; ?></p>
    <p>Email: <?php echo $user['email']; ?></p>
    <p>Bio: <?php echo $user['bio']; ?></p>
    <!-- Display user profile picture -->
    <img src="<?php echo $user['profile_pic']; ?>" alt="Profile Picture" width="100" height="100">

    <a href="update_profile.php">Update Profile</a>

    <!-- Add navigation links -->
    <p><a href="home.php">Home</a> | <a href="explore.php">Explore</a></p>

    <p>Followers: <?php echo $follower_count; ?></p>
    <p>Following: <?php echo $following_count; ?></p>

    <h3>Posts:</h3>
    <?php foreach ($posts as $post) : ?>
        <div>
            <p><?php echo $post['content']; ?></p>
            <?php if ($post['content_type'] === 'image') : ?>
                <img src="<?php echo $post['media_path']; ?>" alt="Post Image" width="200">
            <?php elseif ($post['content_type'] === 'video') : ?>
                <video src="<?php echo $post['media_path']; ?>" controls width="200"></video>
            <?php endif; ?>
            <a href="post_details.php?post_id=<?php echo $post['post_id']; ?>">View Details</a>
        </div>
    <?php endforeach; ?>
</body>

</html>