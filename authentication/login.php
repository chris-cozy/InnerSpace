<?php
include '../includes/session_manager.php';
include '../includes/connection.php';

SessionManager::startSession();

function loginUser($username, $password, $conn)
{
  // Prepare the SQL statement with parameter binding
  $query = "SELECT * FROM user_info WHERE username = ?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "s", $username);
  mysqli_stmt_execute($stmt);

  // Fetch the result
  $result = mysqli_stmt_get_result($stmt);

  if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // Verify the password using password_verify()
    if (password_verify($password, $user['password'])) {
      $_SESSION['userID'] = $user['userID'];
      header("Location: MediaVerse.php", true, 302);
      exit;
    }
  }
  // If the login fails, display an error message
  echo "Username or password incorrect";
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  loginUser($username, $password, $conn);
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>MediaVerse Login</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
  <p class='text'> Please login with your username and password</p>

  <form action="" method="post" class='text'>
    <label for "username">Username: </label><br>
    <input type="text" id="username" name="username"><br>

    <label for="password">Password: </label><br>
    <input type="password" id="password" name="password"><br>

    <input type="submit" value="Send" name="submit">
    <input type="reset">

    <p class='text'>Don't have an account? Sign up <a href='signup.php' class='text'>here</a></p>

  </form>
</body>

</html>