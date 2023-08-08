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
</head>

<body>
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
        <h2 class="mb-4">Create Post</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="content" class="form-label">Content:</label>
                <textarea class="form-control" id="content" name="content" rows="4"><?php echo $content; ?></textarea>
                <span class="text-danger"><?php echo $content_err; ?></span><br>
            </div>

            <div class="mb-3">
                <label for="media" class="form-label">Upload:</label>
                <input type="file" class="form-control" id="media" name="media">
                <small class="form-text text-muted">(jpeg, png, gif, mp4)</small>
            </div>


            <button type="submit" class="btn btn-primary">Post</button>
            <button type="reset" class="btn btn-primary">Cancel</button>
        </form>
    </div>
    <!-- Add Bootstrap JS (Popper.js and Bootstrap's JavaScript) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>