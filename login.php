<!DOCTYPE html>

<html>
<head>
  <title> Metube Login</title>
</head>

<body>
  <p> Please login with your username and password</p>

  <form action = "" method = "post">
    <p>
      <label for "username">Username: </label><br>
        <input type="text" id = "username" name = "username"><br>

      <label for="password">Password: </label><br>
        <input type="password" id="password" name="password"><br>

<input type="submit" value="Send" name="submit">
<input type="reset">

<p> Don't have an account? Sign up  <a href = "signup.php"> here </a>
</p>
</form>
</body>
</html>

<?php
  include 'connection.php';
  //checks if the user has submitted the information yet
  if(isset($_POST['submit'])){

  $username = $_POST['username'];
  $password = $_POST['password'];

  $query = "SELECT *  from user_info where username='$username' and password = '$password'";
  $result = mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
  $num_rows = mysqli_num_rows($result);

  //if there is a username and password combo then it will take
  //user to the homepage and have their account into
  if($num_rows > 0){
    //if the user exists, set the session to the user then go
    //to homepage as a logged in user
    $query = "SELECT userID from user_info where username ='$username'";
    $result = mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
    $_SESSION['userID'] = $result;
    echo "user exists\n";

  }
  //if not it will give error message
  else{
    echo "Username or password incorrect";
  }
}


?>
