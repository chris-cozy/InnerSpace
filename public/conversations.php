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
</head>

<body>
    <h2>Conversations</h2>
    <ul>
        <?php foreach ($conversations as $conversation) : ?>
            <?php
            // Determine the other user ID in the conversation
            $other_user_id = ($conversation['user1_id'] === $current_user_id) ? $conversation['user2_id'] : $conversation['user1_id'];
            // Fetch the other user's username
            $stmt = $pdo->prepare("SELECT username FROM users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $other_user_id, PDO::PARAM_INT);
            $stmt->execute();
            $other_user = $stmt->fetch();
            ?>
            <li><a href="conversation.php?conversation_id=<?php echo $conversation['conversation_id']; ?>"><?php echo $other_user['username']; ?></a></li>
        <?php endforeach; ?>
    </ul>
</body>

</html>