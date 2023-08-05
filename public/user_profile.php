<!-- user_profile.php -->

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
$current_user_id = $_SESSION['user_id'];

// Check if the user ID is provided in the URL
if (!isset($_GET['user_id'])) {
    header('Location: explore.php'); // Redirect to explore page if no user ID provided
    exit;
}

$user_id = $_GET['user_id'];

try {
    // Prepare a select statement to retrieve the user's profile information
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    // Execute the prepared statement
    $stmt->execute();

    // Fetch the user's profile as an associative array
    $user = $stmt->fetch();
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching user data: " . $e->getMessage());
}

// Check if the user exists
if (!$user) {
    header('Location: explore.php'); // Redirect to explore page if user doesn't exist
    exit;
}

// Check if the current user is following the viewed user
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM follows WHERE follower_id = :current_user_id AND following_id = :user_id");
    $stmt->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $is_following = $stmt->fetchColumn() > 0;
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching follow status: " . $e->getMessage());
}

// Get the count of followers for the user
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM follows WHERE following_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $follower_count = $stmt->fetchColumn();
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching follower count: " . $e->getMessage());
}

// Get the count of accounts the user is following
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM follows WHERE follower_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $following_count = $stmt->fetchColumn();
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching following count: " . $e->getMessage());
}

// Process follow/unfollow actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['follow'])) {
        // Process the follow button click
        try {
            $stmt = $pdo->prepare("INSERT INTO follows (follower_id, following_id) VALUES (:follower_id, :following_id)");
            $stmt->bindParam(':follower_id', $current_user_id, PDO::PARAM_INT);
            $stmt->bindParam(':following_id', $user_id, PDO::PARAM_INT);

            // Execute the prepared statement
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle any database errors
            die("Error following the user: " . $e->getMessage());
        }
    } elseif (isset($_POST['unfollow'])) {
        // Process the unfollow button click
        try {
            $stmt = $pdo->prepare("DELETE FROM follows WHERE follower_id = :follower_id AND following_id = :following_id");
            $stmt->bindParam(':follower_id', $current_user_id, PDO::PARAM_INT);
            $stmt->bindParam(':following_id', $user_id, PDO::PARAM_INT);

            // Execute the prepared statement
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle any database errors
            die("Error unfollowing the user: " . $e->getMessage());
        }
    }
    // Redirect to the same page to avoid resubmission of the form
    header("Location: user_profile.php?user_id=$user_id");
    exit;
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
    <title>User Profile</title>
</head>

<body>
    <h2>User Profile</h2>
    <p>Username: <?php echo $user['username']; ?></p>
    <p>Email: <?php echo $user['email']; ?></p>
    <p>Bio: <?php echo $user['bio']; ?></p>
    <!-- Display user profile picture -->
    <img src="<?php echo $user['profile_pic']; ?>" alt="Profile Picture" width="100" height="100">

    <?php if ($user['user_id'] !== $current_user_id) : // Show follow/unfollow button only for other users' profiles 
    ?>
        <?php if ($is_following) : ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?user_id=' . $user['user_id']); ?>" method="post">
                <input type="submit" name="unfollow" value="Unfollow">
            </form>
        <?php else : ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?user_id=' . $user['user_id']); ?>" method="post">
                <input type="submit" name="follow" value="Follow">
            </form>
        <?php endif; ?>
    <?php endif; ?>

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