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
    } elseif (isset($_POST['comment_content'])) {
        // Process the comment form submission
        $comment_content = trim($_POST['comment_content']);

        if (!empty($comment_content)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO comments (user_id, post_id, content) VALUES (:user_id, :post_id, :content)");
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
                $stmt->bindParam(':content', $comment_content, PDO::PARAM_STR);

                // Execute the prepared statement
                if ($stmt->execute()) {
                    header('Location: post_details.php?post_id=' . $post_id); // Redirect to explore page if no post ID provided
                    exit;
                }
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
    <div class="container-fluid main-body">
        <div class="row align-items-start">
            <div class="col-md-3">
                <div class="sidebar">
                    <h1>InnerSpace</h1>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link " href="home.php"><i class="bi bi-house"></i> Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="explore.php"><i class="bi bi-search"></i> Explore</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php"><i class="bi bi-person"></i> Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="post.php"><i class="bi bi-plus-square"></i> Post</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="conversations.php"><i class="bi bi-chat-left"></i> Messages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a>
                        </li>
                        <!-- Add more links as needed -->
                    </ul>
                </div>
            </div>
            <div class="col-md-8">
                <div class="vstack">
                    <div class="card mb-1">
                        <div class="card-body">
                            <p class="white">
                                <a href="user_profile.php?user_id=<?php echo $post['user_id']; ?>" class="link link-offset-2 link-underline link-underline-opacity-0 white">@<?php echo $post['username']; ?></a>
                            </p>

                            <p class="card-text mb-2 mt-2"><?php echo $post['content']; ?></p>
                            <?php if ($post['content_type'] === 'photo') : ?>
                                <img src="<?php echo $post['media_path']; ?>" alt="Post Image" class="img-thumbnail" width="500">
                            <?php elseif ($post['content_type'] === 'video') : ?>
                                <video src="<?php echo $post['media_path']; ?>" controls class="img-thumbnail" width="500"></video>
                            <?php endif; ?>

                        </div>
                    </div>
                    <div class="card mb-1">
                        <div class="card-body">
                            <p class="card-text"><?php echo $like_count; ?> Likes</p>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?post_id=' . $post_id); ?>" method="post" class="mt-3">
                                <input type="submit" name="like" value="Like" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="card mb-3">
                        <div class="card-body">
                            <!-- Comment Form -->
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?post_id=' . $post_id); ?>" method="post">
                                <div class="mb-3">
                                    <textarea class="form-control" id="comment_content" name="comment_content" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Comment</button>
                            </form>
                            <ul class="list-group mb-3 mt-3">
                                <?php foreach ($comments as $comment) : ?>
                                    <li class="list-group-item">
                                        <p class="mb-0"><a href="user_profile.php?user_id=<?php echo $post['user_id']; ?>" class="link link-offset-2 link-underline link-underline-opacity-0 white">@<?php echo $comment['username']; ?></a></p>
                                        <p><?php echo $comment['content']; ?></p>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Add Bootstrap JS (Popper.js and Bootstrap's JavaScript) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>