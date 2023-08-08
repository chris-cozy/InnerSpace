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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Connectify</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="explore.php">Explore</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="post.php">Post</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="conversations.php">Messages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="card mb-3">
            <div class="card-body">
                <a href="user_profile.php?user_id=<?php echo $post['user_id']; ?>" class="link-offset-2 link-underline link-underline-opacity-0">@<?php echo $post['username']; ?></a>
                <p class="card-text"><?php echo $post['content']; ?></p>
                <?php if ($post['content_type'] === 'photo') : ?>
                    <img src="<?php echo $post['media_path']; ?>" alt="Post Image" class="img-thumbnail" width="200">
                <?php elseif ($post['content_type'] === 'video') : ?>
                    <video src="<?php echo $post['media_path']; ?>" controls class="img-thumbnail" width="200"></video>
                <?php endif; ?>

            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-4">
                    <p class="card-text"><?php echo $like_count; ?> Likes</p>
                </div>
                <div class="col-md-4">
                    <p class="card-text"><?php echo $dislike_count; ?> Dislikes</p>
                </div>

            </div>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?post_id=' . $post_id); ?>" method="post" class="mt-3">
                <input type="submit" name="like" value="Like" class="btn btn-primary">
                <input type="submit" name="dislike" value="Dislike" class="btn btn-danger">
            </form>
        </div>

        <hr>
        <!-- Comment Form -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?post_id=' . $post_id); ?>" method="post">
            <div class="mb-3">
                <textarea class="form-control" id="comment_content" name="comment_content" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Comment</button>
        </form>
        <div class="container mt-4">
            <ul class="list-group mb-3">
                <?php foreach ($comments as $comment) : ?>
                    <li class="list-group-item">
                        <p class="mb-0">@<?php echo $comment['username']; ?> | <?php echo $comment['content']; ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>
    <!-- Add Bootstrap JS (Popper.js and Bootstrap's JavaScript) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>