<!-- conversation.php -->

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

// Check if the conversation ID is provided in the URL
if (!isset($_GET['conversation_id'])) {
    header('Location: conversations.php'); // Redirect to conversations page if no conversation ID provided
    exit;
}

$conversation_id = $_GET['conversation_id'];

try {
    // Prepare a select statement to retrieve the conversation details
    $stmt = $pdo->prepare("SELECT user1_id, user2_id FROM conversations WHERE conversation_id = :conversation_id");
    $stmt->bindParam(':conversation_id', $conversation_id, PDO::PARAM_INT);

    // Execute the prepared statement
    $stmt->execute();

    // Fetch the conversation details as an associative array
    $conversation = $stmt->fetch();
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching conversation: " . $e->getMessage());
}

// Check if the conversation exists and involves the current user
if (!$conversation || ($conversation['user1_id'] !== $current_user_id && $conversation['user2_id'] !== $current_user_id)) {
    header('Location: conversations.php'); // Redirect to conversations page if conversation doesn't exist or doesn't involve the user
    exit;
}

// Get the other user ID in the conversation
$other_user_id = ($conversation['user1_id'] === $current_user_id) ? $conversation['user2_id'] : $conversation['user1_id'];

try {
    // Prepare a select statement to retrieve the messages in the conversation
    $stmt = $pdo->prepare("SELECT message_id, sender_id, content, created_at FROM messages WHERE conversation_id = :conversation_id ORDER BY created_at ASC");
    $stmt->bindParam(':conversation_id', $conversation_id, PDO::PARAM_INT);

    // Execute the prepared statement
    $stmt->execute();

    // Fetch all messages in the conversation as an associative array
    $messages = $stmt->fetchAll();
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching messages: " . $e->getMessage());
}

// Process sending new messages
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the message content is not empty
    if (isset($_POST['message_content']) && !empty(trim($_POST['message_content']))) {
        $message_content = trim($_POST['message_content']);

        try {
            // Prepare an insert statement to add the new message to the conversation
            $stmt = $pdo->prepare("INSERT INTO messages (conversation_id, sender_id, content, created_at) VALUES (:conversation_id, :sender_id, :content, NOW())");
            $stmt->bindParam(':conversation_id', $conversation_id, PDO::PARAM_INT);
            $stmt->bindParam(':sender_id', $current_user_id, PDO::PARAM_INT);
            $stmt->bindParam(':content', $message_content, PDO::PARAM_STR);

            // Execute the prepared statement
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle any database errors
            die("Error sending message: " . $e->getMessage());
        }

        // Redirect to the same page to avoid resubmission of the form
        header("Location: conversation.php?conversation_id=$conversation_id");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Conversation with <?php echo $other_user['username']; ?></title>
</head>

<body>
    <h2>Conversation with <?php echo $other_user['username']; ?></h2>
    <div>
        <?php foreach ($messages as $message) : ?>
            <p>
                <?php echo ($message['sender_id'] === $current_user_id) ? 'You' : $other_user['username']; ?>: <?php echo $message['content']; ?>
            </p>
        <?php endforeach; ?>
    </div>

    <!-- Message Form -->
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?conversation_id=' . $conversation_id); ?>" method="post">
        <label>Send a Message:</label>
        <textarea name="message_content" required></textarea>
        <input type="submit" name="send_message" value="Send Message">
    </form>
</body>

</html>