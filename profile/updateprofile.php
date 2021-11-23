<?php
  session_start();
  include 'connection.php'; 
?>
<!DOCTYPE html>
<html>
<head>
  <title> Update Information</title>
  <link rel="stylesheet" href="../styles.css">
</head>

<body>
<h2 class='text'> Update Profile Information </h2>
<br>
  <form action = "" method = "post" class='text'>
    <p>
    <label for="aboutinfo">Update About Information </label><br>
    <input type="text" id = "aboutinfo" name = "aboutinfo"><br>

    <input type="submit" value="Send" name="submitinfo">
    <input type="reset">
    
    <p>
    <label for="update_pass">Update Password</label><br>
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
