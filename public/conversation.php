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

try {
    // Prepare a select statement to retrieve the user's profile information
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $other_user_id, PDO::PARAM_INT);

    // Execute the prepared statement
    $stmt->execute();

    // Fetch the user's profile as an associative array
    $other_user = $stmt->fetch();
} catch (PDOException $e) {
    // Handle any database errors
    die("Error fetching user data: " . $e->getMessage());
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
    <!-- Add Bootstrap CSS -->
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
                        <li class="nav-item">
                            <a class="nav-link active" href="conversations.php"><i class="bi bi-chat-left"></i> Messages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a>
                        </li>
                        <!-- Add more links as needed -->
                    </ul>
                </div>
            </div>
            <div class="col-md-8">
                <div class="hstack">
                    <img src="<?php echo $other_user['profile_pic']; ?>" class="rounded-circle messages-pfp" alt="Profile Picture" width="50" height="50">
                    <h2 class="mb-4 mt-4"><?php echo $other_user['username']; ?></h2>
                </div>

                <div class="mb-3">
                    <?php foreach ($messages as $message) : ?>
                        <p class="<?php echo ($message['sender_id'] === $current_user_id) ? 'text-end' : 'text-start'; ?>">
                            <?php echo ($message['sender_id'] === $current_user_id) ? 'You' : $other_user['username']; ?>: <?php echo $message['content']; ?>
                        </p>
                    <?php endforeach; ?>
                </div>

                <!-- Message Form -->
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?conversation_id=' . $conversation_id); ?>" method="post">
                    <div class="mb-3">

                        <textarea class="form-control" id="message_content" name="message_content" rows="1" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Bootstrap JS (Popper.js and Bootstrap's JavaScript) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>