<?php
$dbServername= "mysql1.cs.clemson.edu via TCP/IP";
$dbUsername = "metube__user@www1.cs.clemson.edu";
$dbPassword = "p@ssword1";
$dbName= "metube__460";

$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
