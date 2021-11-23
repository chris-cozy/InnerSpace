<?php
  session_start();
  include 'connection.php'; 
  include 'functions.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <title> Update Information</title>
    <link rel="stylesheet" href="../styles.css">
  </head>

  <body>
    <header>
        <h2 class="text"><a href="../MeTube.php" class="text">MeTube<3</a></h2>
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
              <li><a href="user_profile.php" >Media</a></li>
              <li><a href="playlists.php" >Playlists</a></li>
              <li><a href="friends.php" >Friends</a></li>
              <li><a href="about.php" >About</a></li>
              <li><a href="updateprofile.php"><b>Update Profile</b></a></li>
              <li><a href="upload.php">Upload</a></li>
              <li><a href="messages.php">Messages</a></li>
            </ul>
          </nav>
        </div>
      </section>
      <section>
        <hr>
        <h2 class='text'> Update Profile Information </h2>
          <br>
          <form action = "" method = "post" class='text'>
            <p>
            <label for="aboutinfo">Update About Information (No apostrophes) </label><br>
            <input type="text" id = "aboutinfo" name = "aboutinfo"><br>

            <input type="submit" value="Send" name="submitinfo">
            <input type="reset">
            
            <p>
            <label for="update_pass">Update Password (No apostrophes)</label><br>
            <input type="text" id = "update_pass" name = "update_pass"><br>

            <input type="submit" value="Send" name="submitpass">
            <input type="reset">
          </form>
          <br>
          <?php
            if(isset($_POST['submitinfo'])){
              $about_info = $_POST['aboutinfo'];
              $userID = $_SESSION['userID'];
              $query = "UPDATE account_info SET about_info = '$about_info' WHERE userID = '$userID'";
              $result = mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
            }
            if(isset($_POST['submitpass'])){
              $newpass = $_POST['update_pass'];
              $userID = $_SESSION['userID'];
              $query = "UPDATE user_info SET password = '$newpass' WHERE userID = '$userID'";
              $result = mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
            }
          ?>
        </section>
      </main>
  </body>
</html>