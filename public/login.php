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
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Optional: Add Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.25.0/font/bootstrap-icons.css">


</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center">User Login</h2>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" name="username" id="username" class="form-control" value="<?php echo $username; ?>">
                                <span class="text-danger"><?php echo $username_err; ?></span>
                            </div>

                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" name="password" id="password" class="form-control">
                                <span class="text-danger"><?php echo $password_err; ?></span>
                            </div>
                            <div class="container mt-3">
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </div>
                            </div>

                        </form>
                        <div class="container mt-3">
                            <p class="text-center">Don't have an account? <a href="register.php">Register here</a>.</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS (Popper.js and Bootstrap's JavaScript) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>