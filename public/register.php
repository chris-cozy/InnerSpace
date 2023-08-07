<?php
// This page will handle user registration functionality
// It should have a registration form to collect user details (username, email, password, etc.).
// After successful registration, redirect users to their profile or home page.

require_once '../includes/db_connection.php';

// Initialize variables to hold form input and error messages
$username = $email = $password = $confirm_password = '';
$username_err = $email_err = $password_err = $confirm_password_err = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form data when the form is submitted

    // Validate username
    if (empty(trim($_POST['username']))) {
        $username_err = 'Please enter a username.';
    } else {
        // Prepare a select statement to check if the username is already taken
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
        $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);

        // Execute the prepared statement
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $username_err = 'This username is already taken.';
        } else {
            $username = trim($_POST['username']);
        }
    }

    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_err = 'Please enter an email address.';
    } else {
        // Prepare a select statement to check if the email is already registered
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);

        // Execute the prepared statement
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $email_err = 'This email address is already registered.';
        } else {
            $email = trim($_POST['email']);
        }
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = 'Please enter a password.';
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $password_err = 'Password must have at least 6 characters.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = 'Please confirm the password.';
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if (empty($password_err) && ($password !== $confirm_password)) {
            $confirm_password_err = 'Passwords do not match.';
        }
    }

    // Check for any input errors before inserting data into the database
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        // Prepare an insert statement to create a new user account
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Registration successful, redirect to login page
            header('Location: login.php');
            exit;
        } else {
            echo 'Something went wrong. Please try again later.';
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">User Registration</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>">
                <span class="text-danger"><?php echo $username_err; ?></span><br>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                <span class="text-danger"><?php echo $email_err; ?></span><br>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password">
                <span class="text-danger"><?php echo $password_err; ?></span><br>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                <span class="text-danger"><?php echo $confirm_password_err; ?></span><br>
            </div>


            <input type="submit" class="btn btn-primary" value="Register">

        </form>
        <p class="mt-3">Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>

</html>