<?php
session_start();
//Destroy current session
session_destroy();
//Head back to the home page
header("Location:MeTube.php");
?>
