<?php

$servername = "mysql1.cs.clemson.edu";
$username = "metube__user";
$password = "p@ssword1";
$dbname = "metube__460";

// Create connection
$conn = mysqli_connect($servername, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
