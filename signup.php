<?php session_start(); ?>
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
  <title> Metube Sign Up</title>
</head>

<body  style="background-color: rgb(42, 44, 44);">
  <h2 class="text">SIGN UP</h2>
  <form action="" method="post">
      <label for="username">Please Select a Username: </label><br>
      <input type="text" id = "username" name = "username" required><br>
      <label for="password">Please Select a Password: </label><br>
      <input type="password" id="password" name="password" required><br>

      <h3>Please Select Your Gender:</h3><br>
      <input type="radio" id="female" name="gender" value="Female" required>
      <label for="female">Female</label><br>

      <input type="radio" id="male" name="gender" value="Male" required>
      <label for="male">Male</label><br>

      <input type="radio" id="other" name="gender" value="Other" required>
      <label for="Female">Other</label><br>

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

  <?php

    include 'connection.php';

    if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $birthday = $_POST['birthday'];

    $query = "SELECT * FROM user_info WHERE username = '$username'";
    $result = mysqli_query($conn,$query) or die ("Query error ".mysqli_error($conn)."\n");
    $num_rows = mysqli_num_rows($result);
    if($num_rows != 0){
      echo "Username $username is already taken\n";
    }

    else{
      //setting the session and entering the information then
      //going to homepage

      $sql = "INSERT INTO user_info(username, password, gender, first_name, last_name, birthday)
      VALUES ('$username', '$password', '$gender', '$fname', '$lname', '$birthday')";
      $query = "SELECT userID from user_info where username ='$username'";
      $queryres = mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
      $result = mysqli_fetch_array($queryres);
      $_SESSION['userID'] = $result['userID'];
      $userID = $result['userID'];
      $sql2 = "INSERT INTO account_info(userID) VALUES ('$userID');";

        if($conn->query($sql) == TRUE){
          $conn->query($sql2);
          header('Location:MeTube.php');
          exit;
          echo "entered into database\n";
        }
  }
  }
  ?>
  </html>
