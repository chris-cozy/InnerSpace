<?php
  session_start();
  include 'connection.php'; 
?>
<!DOCTYPE html>
<style>
	.text{
		color: white;
		font-family: monospace;
		align-items: center;
		text-decoration: none;
	}
	.logo{
		color: white;
		font-family: monospace;
		font-size: 25px;
		cursor: pointer;
	}
	.navbar{
		width: 100%;
		height: 15vh;
		margin: auto;
		display: flex;
		align-items: center;
	}
	.headnav{
		flex: 1;
		padding-left: 100px;
	}
	nav ul li{
		display: inline-block;
		list-style: none;
		margin: 0px 60px;
	}
	nav ul li a{
		text-decoration: none;
		color: rgb(255, 255, 255);
		text-align: center;
	}
	.profile{
		display: inline-block;
		color: white;
		margin: auto;
		align-items: center;
	}
</style>
<html>
<head>
  <title> Update Information</title>
</head>

<body style="background-color: rgb(42, 44, 44);">
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
