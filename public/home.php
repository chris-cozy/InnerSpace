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
    $stmt = $pdo->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.user_id WHERE posts.user_id IN (SELECT following_id FROM follows WHERE follower_id = :user_id) ORDER BY posts.created_at DESC");
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
    <title>Home</title>
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
        <h2 class="mb-4">Home</h2>
        <hr>


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
        <?php foreach ($current_page_posts as $post) :
        ?>
            <div class="card mb-3">
                <div class="card-body">
                    <p class="card-text"><?php echo $post['content']; ?></p>
                    <p class="card-text">Posted by: <?php echo $post['username']; ?></p>
                    <?php if ($post['content_type'] === 'image') : ?>
                        <img src="<?php echo $post['media_path']; ?>" alt="Post Image" class="img-thumbnail" width="200">
                    <?php elseif ($post['content_type'] === 'video') : ?>
                        <video src="<?php echo $post['media_path']; ?>" controls class="img-thumbnail" width="200"></video>
                    <?php endif; ?>
                    <!-- Add link to post details page -->
                    <a href="post_details.php?post_id=<?php echo $post['post_id']; ?>" class="btn btn-primary btn-sm">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Pagination links -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                    <?php if ($i === $current_page) : ?>
                        <li class="page-item active" aria-current="page">
                            <span class="page-link"><?php echo $i; ?><span class="sr-only">(current)</span></span>
                        </li>
                    <?php else : ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php endif; ?>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
    <!-- Add Bootstrap JS (Popper.js and Bootstrap's JavaScript) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>