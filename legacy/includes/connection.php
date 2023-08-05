<?php
$dbServername= "";
$dbUsername = "";
$dbPassword = "";
$dbName= "";

$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
