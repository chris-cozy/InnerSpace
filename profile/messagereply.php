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



	<?php
		$messageID = $_GET['msgID'];
		$query = "SELECT * FROM messages where messageID = '$messageID'";
		$result =  mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
		$row = mysqli_fetch_assoc($result);
		$replyto = $row['senderID'];
		$message = $row['message'];

		echo $message; 

		echo "<br>";

	?>


	<form action="" method="post">
		<input type="text" name="reply" id="reply">
		<input type="submit" name="submit" id="submit">
	</form>

<?php
		if(isset($_POST['submit'])){
			$replymessage=$_POST['reply'];
			$messageID = $_GET['msgID'];
			$query = "UPDATE messages SET reply = '1', reply_message='$replymessage'";
			$result = mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
			$senderID = $_SESSION['userID'];	

			$query = "INSERT INTO messages (message, receiverID, senderID) VALUES ('$replymessage','$replyto','$senderID')";
			$result = mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");

			header("Location:messages.php");
		}
?>

