<!DOCTYPE html>

<html>
<head>
  <title> Update Information</title>
</head>
<?php include 'configure.php'; ?>

<body>
<h2> Update Profile Information </h2>
<br>

  <form action = "" method = "post">
    <p>
      <label for="aboutinfo">Update About Information </label><br>
        <input type="text" id = "aboutinfo" name = "aboutinfo"><br>

<input type="submit" value="Send" name="submitinfo">
<input type="reset">


  <p> Update Password </p>
  <form action = "" method = "post">
    <p>
      <label for="aboutinfo">Update About Information </label><br>
        <input type="text" id = "aboutinfo" name = "aboutinfo"><br>

<input type="submit" value="Send" name="submitpass">
<input type="reset">
  <br>

<?php
  if(isset($_POST['submitinfo'])){
    $about_info = $_POST['aboutinfo'];
    "update accountinfo set aboutInfo = "aboutinfo" where username = $_SESSION['username']";"
  }


 ?>
