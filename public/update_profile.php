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
    $bio = $user['bio'];
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching user data: " . $e->getMessage());
}

// Initialize variables to hold form input and error messages


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Process the form data when the form is submitted 
    if (isset($_FILES['pfp']) && $_FILES['pfp']['error'] === UPLOAD_ERR_OK) {
        $pfp = $_FILES['pfp'];
        $pfp_name = $pfp['name'];
        $pfp_tmp_name = $pfp['tmp_name'];

        // Move the uploaded media file to a folder on the server
        $pfp_dir = 'uploads/profiles/';
        $pfp_path = $pfp_dir . $pfp_name;
        move_uploaded_file($pfp_tmp_name, $pfp_path);

        // Save the post with media path in the database
        try {
            $stmt = $pdo->prepare("UPDATE users SET profile_pic = :profile_pic WHERE user_id = :user_id");
            $stmt->bindParam(':profile_pic', $pfp_path, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);


            if ($stmt->execute()) {
                echo 'Success.';
            } else {
                echo 'Something went wrong. Please try again later.';
            }
        } catch (PDOException $e) {
            // Handle any database errors
            die("Error updating profile image: " . $e->getMessage());
        }
    }

    if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
        $banner = $_FILES['banner'];
        $banner_name = $banner['name'];
        $banner_tmp_name = $banner['tmp_name'];

        // Move the uploaded media file to a folder on the server
        $banner_dir = 'uploads/profiles/';
        $banner_path = $banner_dir . $banner_name;
        move_uploaded_file($banner_tmp_name, $banner_path);

        // Save the post with media path in the database
        try {
            $stmt = $pdo->prepare("UPDATE users SET banner_pic = :banner_pic WHERE user_id = :user_id");
            $stmt->bindParam(':banner_pic', $banner_path, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);


            if ($stmt->execute()) {
                echo 'Success.';
            } else {
                echo 'Something went wrong. Please try again later.';
            }
        } catch (PDOException $e) {
            // Handle any database errors
            die("Error updating profile image: " . $e->getMessage());
        }
    }

    if (!empty(trim($_POST['bio']))) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET bio = :bio WHERE user_id = :user_id");
            $stmt->bindParam(':bio', $_POST['bio'], PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            // Execute the prepared statement
            if ($stmt->execute()) {
                echo 'Success.';
            } else {
                echo 'Something went wrong. Please try again later.';
            }
        } catch (PDOException $e) {
            // Handle any database errors
            die("Error updating bio: " . $e->getMessage());
        }
    }
    header('Location: profile.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Profile</title>
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
                            <a class="nav-link " href="home.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="explore.php">Explore</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="post.php">Post</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="conversations.php">Messages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                        <!-- Add more links as needed -->
                    </ul>
                </div>
            </div>
            <div class="col-md-8">
                <h2 class="mb-4 mt-4">Update Profile</h2>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio:</label>
                        <textarea class="form-control" id="bio" name="bio" rows="4"><?php echo $user['bio']; ?></textarea>
                        <br>
                    </div>

                    <!-- Allow users to update their profile picture -->
                    <div class="mb-3">
                        <label for="pfp" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" id="pfp" name="pfp">
                        <small class="form-text text-muted">(jpeg, png, gif)</small>
                    </div>
                    <div class="mb-3">
                        <label for="banner" class="form-label">Banner</label>
                        <input type="file" class="form-control" id="banner" name="banner">
                        <small class="form-text text-muted">(jpeg, png, gif)</small>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="profile.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Bootstrap JS (Popper.js and Bootstrap's JavaScript) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>

</html>