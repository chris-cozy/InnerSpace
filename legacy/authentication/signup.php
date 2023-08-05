<?php
include '../includes/session_manager.php';
include '../includes/connection.php';

SessionManager::startSession();

function createUser($conn, $username, $password, $fname, $lname, $birthday)
{
  // Prepare the SQL statement with parameter binding
  $query = "SELECT * FROM user_info WHERE username = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username);
  $stmt->execute();

  // Fetch the result
  $result = $stmt->get_result();

  if ($result->num_rows !== 0) {
    echo "<p class='text'>Username $username is already taken<p>";
  } else {
    // Hash the password using password_hash()
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the INSERT SQL statement with parameter binding
    $insertQuery = "INSERT INTO user_info(username, password, first_name, last_name, birthday) VALUES (?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("ssssss", $username, $hashedPassword, $fname, $lname, $birthday);

    if ($insertStmt->execute()) {
      // Get the newly inserted user ID
      $uid = $insertStmt->insert_id;

      // Insert a record in the account_info table
      $sql2 = "INSERT INTO account_info(userID) VALUES (?)";
      $insertStmt2 = $conn->prepare($sql2);
      $insertStmt2->bind_param("i", $uid);

      if ($insertStmt2->execute()) {
        $_SESSION['userID'] = $uid;
        header("Location: MediaVerse.php", true, 302);
        exit;
      }
    }
  }
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $birthday = $_POST['birthday'];

  createUser($conn, $username, $password, $fname, $lname, $birthday);
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>MediaVerse Sign Up</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
  <h2 class="text">SIGN UP</h2>
  <form action="" method="post" class='signup-form'>
    <label for="username">Please Select a Username: </label><br>
    <input type="text" id="username" name="username" required><br>
    <label for="password">Please Select a Password: </label><br>
    <input type="password" id="password" name="password" required><br>

    <br>

    <label for="fname"> First Name: </label>
    <input type="text" id="fname" name="fname" required><br>

    <label for="lname"> Last Name: </label>
    <input type="text" id="lname" name="lname" required><br>

    <br>

    <label for="birthday"> Birthday: </label>
    <input type="date" id="birthday" name="birthday" required>

    <br>

    <input type="submit" value="submit" name="submit">
    <input type="reset">

  </form>
</body>

</html>