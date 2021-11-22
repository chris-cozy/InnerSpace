<?php
	session_start();
	include_once 'connection.php'
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
        <meta charset="UTF-8">
        <title>Metube: Profile</title>
    </head>
    <body style="background-color: rgb(42, 44, 44);">
        <header>
            <h2 class="text"><a href="MeTube.php" class="text">MeTube<3</a></h2>
            <h3 class="text">*User's name here*</h3>
        </header>
        <main>
            <section>
				<div class="navbar">
					<nav>
						<ul class="text">
							<li><a href="user_profile.php" >Media</a></li>
							<li><a href="playlists.php" >Playlists</a></li>
							<li><a href="friends.php" ><b>Friends</b></a></li>
                            <li><a href="about.php" >About</a></li>
                            <li><a href="messages.php" >Messages</a></li>
						</ul>
					</nav>
				</div>
			</section>
            <hr>
<!-- recieved messages-->
<h2>Inbox</h2>
<?php
	$userID = $_SESSION['userID'];
	$query = "SELECT * FROM messages where receiver = '$userID'";
       	$result =  mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
	$num_rows = mysqli_num_rows($result);

	if($num_rows == 0){
		echo "You currently have no messages in your inbox";
	else{
		do{
			$row = mysqli_fetch_assoc($result);
			$senderID = $rows['senderID'];
			$getsender = "SELECT username FROM users where userID = '$senderID'";
			$senderresult = mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
			$row2 = mysqli_fetch_assoc($senderresult);
			$senderusername = $row2['username'];
			$message = $row['message'];
			echo "From: '.$senderusername.'\n";
			echo $message;
		}while($row);

?>
<!-- sent messages -->
<h2> Outbox</h2>
