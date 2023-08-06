<?php
// post_details.php

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

// Check if the post ID is provided in the URL
if (!isset($_GET['post_id'])) {
    header('Location: explore.php'); // Redirect to explore page if no post ID provided
    exit;
}

$post_id = $_GET['post_id'];

try {
    // Prepare a select statement to retrieve the selected post
    $stmt = $pdo->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.user_id WHERE post_id = :post_id");
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);

    // Execute the prepared statement
    $stmt->execute();

    // Fetch the selected post as an associative array
    $post = $stmt->fetch();
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching post data: " . $e->getMessage());
}

// Check if the post exists
if (!$post) {
    header('Location: explore.php'); // Redirect to explore page if post doesn't exist
    exit;
}

try {
    // Prepare a select statement to retrieve the comments for the selected post
    $stmt = $pdo->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.user_id WHERE post_id = :post_id ORDER BY created_at DESC");
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);

    // Execute the prepared statement
    $stmt->execute();

    // Fetch comments as an associative array
    $comments = $stmt->fetchAll();
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching comments: " . $e->getMessage());
}

// Check if the form is submitted (for liking/disliking the post)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['like'])) {
        // Process the like button click
        try {
            $stmt = $pdo->prepare("INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);

            // Execute the prepared statement
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle any database errors
            die("Error liking the post: " . $e->getMessage());
        }
    } elseif (isset($_POST['dislike'])) {
        // Process the dislike button click
        try {
            $stmt = $pdo->prepare("INSERT INTO dislikes (user_id, post_id) VALUES (:user_id, :post_id)");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);

            // Execute the prepared statement
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle any database errors
            die("Error disliking the post: " . $e->getMessage());
        }
    } elseif (isset($_POST['comment'])) {
        // Process the comment form submission
        $comment_content = trim($_POST['comment_content']);

        if (!empty($comment_content)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO comments (user_id, post_id, content) VALUES (:user_id, :post_id, :content)");
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
                $stmt->bindParam(':content', $comment_content, PDO::PARAM_STR);

                // Execute the prepared statement
                $stmt->execute();
            } catch (PDOException $e) {
                // Handle any database errors
                die("Error adding comment: " . $e->getMessage());
            }
        }
    }
}

// Get the like count for the post
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE post_id = :post_id");
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $like_count = $stmt->fetchColumn();
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching like count: " . $e->getMessage());
}

// Get the dislike count for the post
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM dislikes WHERE post_id = :post_id");
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $dislike_count = $stmt->fetchColumn();
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching dislike count: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Post Details</title>
</head>

<body>
    <h2>Post Details</h2>
    <div>
        <p><?php echo $post['content']; ?></p>
        <p>Posted by: <?php echo $post['username']; ?></p>
        <?php if ($post['content_type'] === 'image') : ?>
            <img src="<?php echo $post['media_path']; ?>" alt="Post Image" width="200">
        <?php elseif ($post['content_type'] === 'video') : ?>
            <video src="<?php echo $post['media_path']; ?>" controls width="200"></video>
        <?php endif; ?>
        <p>Like Count: <?php echo $like_count; ?></p>
        <p>Dislike Count: <?php echo $dislike_count; ?></p>
        <a href="profile.php?user_id=<?php echo $post['user_id']; ?>">View Profile</a>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?post_id=' . $post_id); ?>" method="post">
            <input type="submit" name="like" value="Like">
            <input type="submit" name="dislike" value="Dislike">
        </form>
    </div>

    <h3>Comments:</h3>
    <ul>
        <?php foreach ($comments as $comment) : ?>
            <li>
                <p><?php echo $comment['content']; ?></p>
                <p>Commented by: <?php echo $comment['username']; ?></p>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Comment Form -->
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?post_id=' . $post_id); ?>" method="post">
        <label>Add a Comment:</label>
        <textarea name="comment_content" required></textarea>
        <input type="submit" name="comment" value="Post Comment">
    </form>
</body>

</html>