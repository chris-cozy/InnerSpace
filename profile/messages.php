<?php
	session_start();
	include_once 'connection.php';
	include 'functions.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Metube: Profile</title>
		<link rel="stylesheet" href="../styles.css">
    </head>
    <body>
        <header>
            <h2 class="text"><a href="../MeTube.php" class="text">MeTube<3</a></h2>
            <h3 class="text"><?php 
					$uid = $_SESSION['userID'];
					getName($uid, $conn);
				?></h3>
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
                            <li><a href="messages.php" ><b>Messages<b></a></li>
						</ul>
					</nav>
				</div>
			</section>
            <hr>
<!-- recieved messages-->
<div class="text">
<h2>Inbox</h2>
<?php
	$userID = $_SESSION['userID'];
	$query = "SELECT * FROM messages where receiverID = '$userID'";
       	$result =  mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
	$num_rows = mysqli_num_rows($result);

	if($num_rows == 0){
		echo "You currently have no messages in your inbox";
	}
	else{
		do{
			$row = mysqli_fetch_assoc($result);
			if(!empty($row)){
			$senderID = $row['senderID'];
			$getsender = "SELECT username FROM user_info where userID = '$senderID'";
			$senderresult = mysqli_query($conn,$getsender) or die ("Query error".mysqli_error($conn)."\n");
			$row2 = mysqli_fetch_assoc($senderresult);
			$senderusername = $row2['username'];
			$message = $row['message'];
			echo "From: $senderusername\n";
			echo "<br>";
		        echo $message;
			echo "<br>";
			}
		}while($row);
	
	}
?>
<hr>
<!-- messages that have been sent-->
<h2> Outbox </h2>
<?php
	$userID = $_SESSION['userID'];
	$query = "SELECT * FROM messages where senderID = '$userID'";
	$result =  mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
	$num_rows = mysqli_num_rows($result);

	if($num_rows == 0){
		echo "You have not sent any messges";
	}
	else{
		do{
			$row = mysqli_fetch_assoc($result);
			if(!empty($row)){
			$receiverID = $row['receiverID'];
			$getreceiver = "SELECT username from user_info  WHERE userID = '$receiverID'";	
			$receiverresult = mysqli_query($conn,$getreceiver) or die ("Query error".mysqli_error($conn)."\n");
			$row2 = mysqli_fetch_assoc($receiverresult);
			$receiverusername = $row2['username'];
			$message = $row['message'];
			echo "To: $receiverusername";
			echo "<br>";
			echo $message;
			echo "<br>";
			}
		}while($row);
	}
?>
</div>
<hr>
<h2 class='text'> Send a message </h2>
<form action="" method="post" class='text'>
	<label for="username"> To: </label>
	<input type="text" id="username" name="username" required><br>
	
	<br>
	<label for="message"> Message: </label><br>
	<input type="text" id="message" name="message" required><br>

	<input type="submit" value="submit" name="submit"><br>
	<input type="reset"><br>

</form>
</main>
</body>

<?php
	if(isset($_POST['submit'])){
		$sendto = $_POST['username'];
		$message = $_POST['message'];

		$query = "SELECT * from user_info WHERE username = '$sendto'";
	        $result =  mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
		$num_rows = mysqli_num_rows($result);

		if($num_rows == 0){
			echo "no user with that username exists";
		}
		else{
			$row = mysqli_fetch_assoc($result);
			$receiverID = $row['userID'];
			$senderID = $_SESSION['userID'];

			$query = "INSERT INTO messages(senderID, receiverID, message) VALUES ('$senderID','$receiverID','$message')";
		       	$result = mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
			unset($_POST['submit']);
				header("Location:messages.php"); 
			
			

	}
}
?>
</html>			



