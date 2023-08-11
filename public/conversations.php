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
                        <li class="nav-item active">
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
                <h2 class="mb-2 mt-4"><?php echo $user['username']; ?></h2>
                <h5 class="mb-4 mt-2">Messages</h2>
                    <hr>
                    <div class="list-group">
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
                            <a href="conversation.php?conversation_id=<?php echo $conversation['conversation_id']; ?>" class="list-group-item list-group-item-action">
                                <h5 class="white"><?php echo $other_user['username']; ?></h5>
                            </a>
                        <?php endforeach; ?>
                        </ul>
                    </div>
            </div>
        </div>

        <!-- Add Bootstrap JS (Popper.js and Bootstrap's JavaScript) -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>

</html>