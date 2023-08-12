<?php
// This page will handle the creation of new posts.
// Allow users to create and post content (text, photo, video).
// Save the posts to the database and display them on the user's profile or home page.

require_once '../includes/db_connection.php';

// Check if the user is logged in. Redirect to login page if not.
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Initialize variables to hold form input and error messages
$content = '';
$content_err = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form data when the form is submitted

    // Validate content
    if (empty(trim($_POST['content']))) {
        $content_err = 'Please enter content.';
    } else {
        $content = trim($_POST['content']);
    }

    // Check for any input errors before creating the post
    if (empty($content_err)) {
        // Check if an image or video file was uploaded
        if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
            $media = $_FILES['media'];
            $media_name = $media['name'];
            $media_tmp_name = $media['tmp_name'];
            $media_type = $media['type'];

            // Move the uploaded media file to a folder on the server
            $media_dir = 'uploads/posts/';
            $media_path = $media_dir . $media_name;
            move_uploaded_file($media_tmp_name, $media_path);

            // Save the post with media path in the database
            try {
                $stmt = $pdo->prepare("INSERT INTO posts (user_id, content_type, content, media_path) VALUES (:user_id, :content_type, :content, :media_path)");
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindValue(':content_type', $_FILES['media']['type'] === 'video/mp4' ? 'video' : 'photo', PDO::PARAM_STR);
                $stmt->bindParam(':content', $content, PDO::PARAM_STR);
                $stmt->bindParam(':media_path', $media_path, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    // Post creation successful, redirect to the user's profile page
                    header('Location: profile.php');
                    exit;
                } else {
                    echo 'Something went wrong. Please try again later.';
                }
            } catch (PDOException $e) {
                // Handle any database errors
                die("Error creating post: " . $e->getMessage());
            }
        } else {
            // Save the post without media path in the database (text-only post)
            try {
                $stmt = $pdo->prepare("INSERT INTO posts (user_id, content_type, content) VALUES (:user_id, 'text', :content)");
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':content', $content, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    // Post creation successful, redirect to the user's profile page
                    header('Location: profile.php');
                    exit;
                } else {
                    echo 'Something went wrong. Please try again later.';
                }
            } catch (PDOException $e) {
                // Handle any database errors
                die("Error creating post: " . $e->getMessage());
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Post</title>
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
                        <li class="nav-item active">
                            <a class="nav-link active" href="post.php"><i class="bi bi-plus-square"></i> Post</a>
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
                <h2 class="mb-4 mt-4">Create Post</h2>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="content" class="form-label">Share your message...</label>
                        <textarea class="form-control" id="content" name="content" rows="4"><?php echo $content; ?></textarea>
                        <span class="text-danger"><?php echo $content_err; ?></span><br>
                    </div>

                    <div class="mb-3">

                        <input type="file" class="form-control" id="media" name="media">
                        <small class="form-text text-muted">(jpeg, png, gif, mp4)</small>
                    </div>


                    <button type="submit" class="btn btn-primary">Post</button>
                    <button type="reset" class="btn btn-primary">Cancel</button>
                </form>
            </div>

        </div>

    </div>
    <!-- Add Bootstrap JS (Popper.js and Bootstrap's JavaScript) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>