<?php
include '../includes/session_manager.php';
include '../includes/connection.php';
include '../includes/media_manager.php';
include '../includes/user_auth.php';

SessionManager::startSession();

if (isset($_POST['submitinfo'])) {
  $about_info = $_POST['aboutinfo'];
  $userID = $_SESSION['userID'];
  $query = "UPDATE account_info SET about_info = ? WHERE userID = ?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "si", $about_info, $userID);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  header("Location: update_profile.php");
  exit();
}

if (isset($_POST['submitpass'])) {
  $newpass = $_POST['update_pass'];
  $hashed_pass = password_hash($newpass, PASSWORD_DEFAULT);
  $userID = $_SESSION['userID'];
  $query = "UPDATE user_info SET password = ? WHERE userID = ?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "si", $hashed_pass, $userID);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  header("Location: update_profile.php");
  exit();
}

?>
<!DOCTYPE html>
<html>

<head>
  <title> Update Information</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
  <header>
    <h2 class="text"><a href="../MediaVerse.php" class="text">MediaVerse</a>
    </h2>
    <h3 class="text">
      <?php
      $uid = $_SESSION['userID'];
      getName($uid, $conn);
      ?>
    </h3>
  </header>
  <main>
    <section>
      <div class="navbar">
        <nav>
          <ul class="text">
            <li><a href="user_profile.php">Media</a></li>
            <li><a href="./playlists/playlists.php">Playlists</a></li>
            <li><a href="friends.php">Friends</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="updateprofile.php"><b>Update Profile</b></a></li>
            <li><a href="upload.php">Upload</a></li>
            <li><a href="./messages/messages.php">Messages</a></li>
          </ul>
        </nav>
      </div>
    </section>
    <section>
      <hr>
      <h2 class='text'> Update Profile Information </h2>
      <br>
      <form action="" method="post" class='text'>
        <p>
          <label for="aboutinfo">Update About Information (No apostrophes) </label><br>
          <input type="text" id="aboutinfo" name="aboutinfo"><br>

          <input type="submit" value="Send" name="submitinfo">
          <input type="reset">

        <p>
      </form>
      <form>
        <p>
          <label for="update_pass">Update Password (No apostrophes)</label><br>
          <input type="text" id="update_pass" name="update_pass"><br>

          <input type="submit" value="Send" name="submitpass">
          <input type="reset">
        </p>
      </form>
    </section>
  </main>
</body>

</html>