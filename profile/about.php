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
            <h2 class="text"><a href="../MeTube.html" class="text">MeTube<3</a></h2>
			<?php
				$uid = $_SESSION['userID'];

			?>
			<h3 class="text">About: <?php 
					$uid = $_SESSION['userID'];
					getName($uid, $conn);
				?></h3>
        </header>
        <main>
            <section>
				<div class="navbar">
					<nav>
						<ul class="text">
							<li><a href="user_profile.php">Media</a></li>
							<li><a href="playlists.php" >Playlists</a></li>
							<li><a href="friends.php" >Friends</a></li>
                            <li><a href="about.php" ><b>About</b></a></li>
							<li><a href="updateprofile.php">Update Profile</a></li>
              				<li><a href="upload.php">Upload</a></li>
                            <li><a href="messages.php" >Messages</a></li>
						</ul>
					</nav>
				</div>
			</section>
            <hr>
            <section>
				<span style= 'display: inline-block;'>
					<?php
						$uid = $_SESSION['userID'];
						//Enter code to display user info
						$fetchUserInfo = mysqli_query($conn, "SELECT * FROM user_info WHERE userID=('$uid')");
						while($row = mysqli_fetch_assoc($fetchUserInfo)){
							$userID = $row['userID'];
							$gender = $row['gender'];
							$firstN = $row['first_name'];
							$lastN = $row['last_name'];
							$birthday = $row['birthday'];
							$date_created = $row['date_created'];
							echo "<ul class='text'>
							<li>User ID: ".$userID."</li>
							<li>Gender: ".$gender."</li>
							<li>Name: ".$firstN." ".$lastN."</li>
                            <li>Birthday: ".$birthday."</li>
                            <li>Day Account Created: ".$date_created."</li>
						</ul>";
						}
					?>
				</span>
				<span style= 'display: inline-block;'>
					<?php
						$uid = $_SESSION['userID'];
						//Enter code to display account info
						$query = mysqli_query($conn, "SELECT * FROM account_info WHERE userID=('$uid')");
						while($row = mysqli_fetch_assoc($query)){
							$about = $row['about_info'];
							$subscriberCount = $row['subscriber_count'];
							echo "<ul class='text'>
							<li>Subscriber Count: ".$subscriberCount."</li>
							<li>About: ".$about."</li>
							</ul>";
						}
					?>
				</span>
            </section>
        </main>
    </body>
</html>
