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
            <h2 class="text"><a href="../MeTube.html" class="text">MeTube<3</a></h2>
			<?php
				$uid = $_SESSION['userID'];
			?>
			<h3 class="text">About: <?php echo $uid ?></h3>
        </header>
        <main>
            <section>
				<div class="navbar">
					<nav>
						<ul class="text">
							<li><a href="user_profile.html">Media</a></li>
							<li><a href="playlists.html" >Playlists</a></li>
							<li><a href="friends.html" >Friends</a></li>
                            <li><a href="about.html" ><b>About</b></a></li>
                            <li><a href="user_profile.html" >Messages</a></li>
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
							$userID = $row['user_id'];
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
						$fetchAccountInfo = mysqli_query($conn, "SELECT * FROM account_info WHERE userID=('$uid')");
						while($row = mysqli_fetch_assoc($fetchAccountInfo)){
							$about = $row['aboutInfo'];
							$subscriberCount = $row['subscriberCount'];
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