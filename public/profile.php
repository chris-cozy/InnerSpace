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
    $stmt->bindParam(':user_id', $current_user_id, PDO::PARAM_INT);

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
    <title>Profile</title>
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
        <!-- User Profile Info -->
        <div class="hstack">
            <div class="col-md-3">
                <img src="<?php echo $user['profile_pic']; ?>" class="rounded-circle" alt="Profile Picture" width="100" height="100">
            </div>
            <div class="col-md-4">
                <h2><?php echo $user['username']; ?></h2>
                <h7><?php echo $user['bio']; ?></p>

            </div>
            <div class="col-md-3">
                <div>
                    <div class="col-md-6">
                        <p><?php echo $follower_count; ?> Followers</p>
                    </div>
                    <div class="col-md-6">
                        <p><?php echo $following_count; ?> Following</p>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <a href="update_profile.php" class="btn btn-primary">Edit Profile</a>
            </div>

        </div>


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
        <!--User's Posts Here-->
        <?php foreach ($current_page_posts as $post) : ?>
            <div class="card mb-3">
                <div class="card-body">
                    <a href="user_profile.php?user_id=<?php echo $post['user_id']; ?>" class="link-offset-2 link-underline link-underline-opacity-0">@<?php echo $user['username']; ?></a>
                    <p class="card-text"><?php echo $post['content']; ?></p>

                    <?php if ($post['content_type'] === 'photo') : ?>
                        <img src="<?php echo $post['media_path']; ?>" alt="Post Image" class="img-thumbnail" width="200">
                    <?php elseif ($post['content_type'] === 'video') : ?>
                        <video src="<?php echo $post['media_path']; ?>" controls class="img-thumbnail" width="200"></video>
                    <?php endif; ?>
                    <!-- Add link to post details page -->
                    <a href="post_details.php?post_id=<?php echo $post['post_id']; ?>" class="link-offset-2 link-underline link-underline-opacity-0 link-opacity-50">View Details</a>
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