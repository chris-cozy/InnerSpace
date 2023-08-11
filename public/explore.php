<?php
// This page will display content from all users, similar to an explore page.
// Fetch posts from all users from the database and display them.

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
    // Prepare a select statement to retrieve all posts from all users
    $stmt = $pdo->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.user_id ORDER BY posts.created_at DESC");

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
    <title>Explore</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
                            <a class="nav-link active" href="explore.php"><i class="bi bi-search"></i> Explore</a>
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
            <div class="col-md-5">
                <div>
                    <br>
                    <a class="link-underline link-underline-opacity-0" href="home.php">
                        <h2 class="mb-4">Explore</h2>
                    </a>


                    </nav>

                    <br>


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
                    <?php foreach ($posts as $post) :
                    ?>
                        <div class="card">
                            <div class=" card-body mb-2 mt-2">
                                <a href="user_profile.php?user_id=<?php echo $post['user_id']; ?>" class="link link-offset-2 link-underline link-underline-opacity-0 white">@<?php echo $post['username']; ?></a>
                                <p class="card-text mb-2 mt-2"><?php echo $post['content']; ?></p>

                                <?php if ($post['content_type'] === 'photo') : ?>
                                    <img src="<?php echo $post['media_path']; ?>" alt="Post Image" class="img-thumbnail mb-2 mt-2" width="500">
                                <?php elseif ($post['content_type'] === 'video') : ?>
                                    <video src="<?php echo $post['media_path']; ?>" controls class="img-thumbnail mb-2 mt-2" width="500"></video>
                                <?php endif; ?>
                                <!-- Add link to post details page -->
                                <a href="post_details.php?post_id=<?php echo $post['post_id']; ?>" class="link-offset-2 link-underline link-underline-opacity-0 link-opacity-50 mt-4 muted d-block">View Details</a>

                            </div>
                        </div>
                        <hr>
                    <?php endforeach; ?>

                    <!-- Pagination links 
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                <?php if ($i === $current_page) : ?>
                                    <li class="page-item active" aria-current="page">
                                        <span class="page-link"><?php echo $i; ?><span class="sr-only"> </span></span>
                                    </li>
                                <?php else : ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                    -->
                </div>

            </div>

        </div>

    </div>
    <!-- Add Bootstrap JS (Popper.js and Bootstrap's JavaScript) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>