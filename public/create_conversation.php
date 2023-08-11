<?php
require_once '../includes/db_connection.php';

// Check if the user is logged in. Redirect to login page if not.
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the receiver's user ID from the query parameter
$receiver_id = isset($_GET['receiver_id']) ? $_GET['receiver_id'] : null;

// Check if the user already has a conversation with the receiver
$user_id = $_SESSION['user_id'];

// Check if a conversation already exists between the users
$stmt = $pdo->prepare("SELECT conversation_id FROM conversations WHERE (user1_id = :user1_id AND user2_id = :user2_id) OR (user1_id = :user2_id AND user2_id = :user1_id)");
$stmt->bindParam(':user1_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':user2_id', $receiver_id, PDO::PARAM_INT);
$stmt->execute();

$existing_conversation = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing_conversation) {
    // Redirect to the existing conversation's page
    header("Location: conversation.php?conversation_id={$existing_conversation['conversation_id']}");
    exit;
}

// If no existing conversation, process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message_content = $_POST['message_content'];

    // Create a new conversation
    $stmt = $pdo->prepare("INSERT INTO conversations (user1_id, user2_id) VALUES (:user1_id, :user2_id)");
    $stmt->bindParam(':user1_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':user2_id', $receiver_id, PDO::PARAM_INT);
    $stmt->execute();

    // Get the newly created conversation ID
    $conversation_id = $pdo->lastInsertId();

    // Insert the message into the messages table
    $stmt = $pdo->prepare("INSERT INTO messages (conversation_id, sender_id, content) VALUES (:conversation_id, :user_id, :content)");
    $stmt->bindParam(':conversation_id', $conversation_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':content', $message_content, PDO::PARAM_STR);
    $stmt->execute();

    // Redirect to the newly created conversation's page
    header("Location: conversation.php?conversation_id=$conversation_id");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Conversation</title>
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
                <h2 class="mb-4 mt-4">Create Message</h2>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?receiver_id=$receiver_id"); ?>" method="post">
                    <div class="mb-3">
                        <label for="message_content" class="form-label">Share your message...</label>
                        <textarea class="form-control" id="message_content" name="message_content" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Send</button>
                    <a href="user_profile.php?user_id=<?php echo $receiver_id; ?>" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>