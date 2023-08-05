<?php
// This page will handle user login functionality
// It should have a login form to collect username/email and password.
// After successful login, redirect users to their profile or home page.

require_once '../includes/db_connection.php';

// Initialize variables to hold form input and error messages
$username = $password = '';
$username_err = $password_err = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form data when the form is submitted

    // Validate username
    if (empty(trim($_POST['username']))) {
        $username_err = 'Please enter a username.';
    } else {
        $username = trim($_POST['username']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter a password.';
    } else {
        $password = trim($_POST['password']);
    }

    // Check for any input errors before attempting to log in
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement to retrieve user data based on the provided username
        $stmt = $pdo->prepare("SELECT user_id, username, password FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);

        // Execute the prepared statement
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            // Fetch user data as an associative array
            $user = $stmt->fetch();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password is correct, start a new session
                session_start();

                // Store user data in the session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to the user's profile or home page
                header('Location: profile.php');
                exit;
            } else {
                $password_err = 'Invalid password.';
            }
        } else {
            $username_err = 'Username not found.';
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Login</title>
</head>

<body>
    <h2>User Login</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <label>Username:</label>
        <input type="text" name="username" value="<?php echo $username; ?>">
        <span><?php echo $username_err; ?></span><br>

        <label>Password:</label>
        <input type="password" name="password">
        <span><?php echo $password_err; ?></span><br>

        <input type="submit" value="Login">
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </form>
</body>

</html>