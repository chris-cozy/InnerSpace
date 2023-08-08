<!-- conversations.php -->

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

try {
    // Prepare a select statement to retrieve all conversations for the current user
    $stmt = $pdo->prepare("SELECT conversation_id, user1_id, user2_id FROM conversations WHERE user1_id = :current_user_id OR user2_id = :current_user_id");
    $stmt->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);

    // Execute the prepared statement
    $stmt->execute();

    // Fetch all conversations as an associative array
    $conversations = $stmt->fetchAll();
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching conversations: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Conversations</title>
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
        <h2 class="mb-4">Messages</h2>
        <hr>
        <ul class="list-group">
            <?php foreach ($conversations as $conversation) : ?>
                <?php
                // Determine the other user ID in the conversation
                $other_user_id = ($conversation['user1_id'] === $current_user_id) ? $conversation['user2_id'] : $conversation['user1_id'];
                // Fetch the other user's username
                $stmt = $pdo->prepare("SELECT username, profile_pic FROM users WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $other_user_id, PDO::PARAM_INT);
                $stmt->execute();
                $other_user = $stmt->fetch();
                ?>
                <li class="list-group-item hstack gap-3">
                    <img src="<?php echo $other_user['profile_pic']; ?>" class="rounded-circle" alt="Profile Picture" width="50" height="50">
                    <a href="conversation.php?conversation_id=<?php echo $conversation['conversation_id']; ?>" class="link-offset-2 link-underline link-underline-opacity-0">
                        <h5><?php echo $other_user['username']; ?></h5>
                    </a>

                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <!-- Add Bootstrap JS (Popper.js and Bootstrap's JavaScript) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>

</html>