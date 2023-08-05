<?php
// update_profile.php

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

// Initialize variables to hold form input and error messages
$bio = '';
$bio_err = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form data when the form is submitted

    // Validate bio
    if (empty(trim($_POST['bio']))) {
        $bio_err = 'Please enter a bio.';
    } else {
        $bio = trim($_POST['bio']);
    }

    // Check for any input errors before updating the user's profile
    if (empty($bio_err)) {
        // Prepare an update statement to update the user's bio
        $stmt = $pdo->prepare("UPDATE users SET bio = :bio WHERE user_id = :user_id");
        $stmt->bindParam(':bio', $bio, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Profile update successful, redirect to the user's profile page
            header('Location: profile.php');
            exit;
        } else {
            echo 'Something went wrong. Please try again later.';
        }
    }
}

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
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Profile</title>
</head>

<body>
    <h2>Update Profile</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <label>Bio:</label>
        <textarea name="bio"><?php echo $user['bio']; ?></textarea>
        <span><?php echo $bio_err; ?></span><br>

        <!-- Allow users to update their profile picture -->
        <label>Profile Picture:</label>
        <input type="file" name="profile_picture">
        <!-- You'll need to handle file upload and save the file path in the database -->

        <input type="submit" value="Update">
        <a href="profile.php">Cancel</a>
    </form>
</body>

</html>