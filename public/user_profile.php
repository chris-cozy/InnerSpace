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
    <title><?php echo $user['username']; ?> Profile</title>
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

                    <img src="<?php echo $user['banner_pic']; ?>" class="mt-3 mb-3 banner" alt="Profile Picture" width="900" height="300">
                    <div class="hstack">
                        <img src="<?php echo $user['profile_pic']; ?>" class="rounded-circle pfp ml-3" alt="Profile Picture" width="150" height="auto">



                        <div class="vstack">

                            <div class="hstack">
                                <h2 class="col-6"><?php echo $user['username']; ?></h2>
                                <?php if ($user['user_id'] !== $current_user_id) : // Show follow/unfollow button only for other users' profiles 
                                ?>
                                    <?php if ($is_following) : ?>
                                        <div class="col-2">
                                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?user_id=' . $user['user_id']); ?>" method="post">
                                                <input type="submit" name="unfollow" value="Unfollow" class="btn btn-danger user-profile-buttons">
                                            </form>
                                        </div>

                                    <?php else : ?>
                                        <div class="col-2">
                                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?user_id=' . $user['user_id']); ?>" method="post">
                                                <input type="submit" name="follow" value="Follow" class="btn btn-primary">
                                            </form>
                                        </div>

                                    <?php endif; ?>
                                <?php endif; ?>

                                <a href="create_conversation.php?receiver_id=<?php echo $user_id; ?>" class="btn btn-primary">Message</a>
                            </div>
                            <h7 class="mb-2 mt-2"><?php echo $user['bio']; ?></h7>
                            <div class="row">
                                <div class="col-2 mb-2 mt-2">

                                    <p><?php echo $follower_count; ?> Followers</p>


                                </div>
                                <div class="col-2 mb-2 mt-2">

                                    <p><?php echo $following_count; ?> Following</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <hr>
                    <h7 class="profile-posts">Posts</h7>
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
                    <?php foreach ($posts as $post) : ?>
                        <div class="card">
                            <div class=" card-body mb-2 mt-2">
                                <a href="user_profile.php?user_id=<?php echo $post['user_id']; ?>" class="link-offset-2 link-underline link-underline-opacity-0 white">@<?php echo $user['username']; ?></a>
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
                                        <span class="page-link"><?php echo $i; ?><span class="sr-only">(current)</span></span>
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


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>