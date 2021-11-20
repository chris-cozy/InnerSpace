<?php
	session_start();
	include_once 'connection.php';
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
        <title>MeTube<3</title>
    </head>
    <body style="background-color: rgb(42, 44, 44);">
		<header>
			<h1 class="logo"><a href="MeTube.php" class="text">MeTube<3</a></h1>
			<form>
				<input type="text" value="Keyword Search"/>
			</form>
		</header>
		<hr>
		<main>
			<section>
				<div class="navbar">
					<nav>
						<ul class="text">
							<li><a href="MeTube.php">Home</a></li>
							<?php
								if (isset($_SESSION['userID'])){
									echo "<li><a href='subscriptions.html'>Subscriptions</a></li>
									<li><a href='./profile/user_profile.php'>Your Profile</a></li>";
									echo "<li><a href = 'signout.php'>Sign Out</a></li>";

								}else{
									echo "<li><a href='login.php'>Login</a></li>";
								}
							?>
						</ul>
					</nav>
				</div>
			</section>
			<hr>
			<section>
				<h2 class="text">Recommended Videos</h2>
				<!--Display recent uploaded videos-->
				<div>
					<?php
					$i = 0;
					$extensions_arr = array("mp4","avi","3gp","mov","mpeg");
					$fetchVideos = mysqli_query($conn, "SELECT * FROM media ORDER BY mediaID DESC");
					while($row = mysqli_fetch_assoc($fetchVideos) && $i < 4){
						$type = $row['type'];
						if(in_array($type,$extensions_arr)){
							$location = $row['location'];
							$name = $row['title'];
							echo "<span style= 'display: inline-block;'>
									<video src='".$location."' controls width='200px'>This video could not be displayed :/</video>
									<br>
									<span>".$name."</span>
								</span>";
							$i++;
						}
					}
					?>
				</div>
				<br>
				<hr>
				<br>
			</section>
			<section>
				<h2 class="text">Recommended Audio</h2>
				<div>
					<?php
					$i = 0;
					$extensions_arr = array("mp3");
					$fetchVideos = mysqli_query($conn, "SELECT * FROM media ORDER BY mediaID DESC");
					while($row = mysqli_fetch_assoc($fetchVideos) && $i < 4){
						$type = $row['type'];
						if(in_array($type,$extensions_arr)){
							$location = $row['location'];
							$name = $row['title'];
							echo "<span style= 'display: inline-block;'>
									<audio src='".$location."' controls type='audio/mpeg'>This audio could not be displayed :/</audio>
									<br>
									<span>".$name."</span>
								</span>";
							$i++;
						}
					}
					?>
				</div>
				<br>
				<hr>
				<br>
			</section>
			<section>
				<h2 class="text">Recommended Images</h2>
				<div>
					<?php
					$i = 0;
					$extensions_arr = array("jpg","png");
					$fetchVideos = mysqli_query($conn, "SELECT * FROM media ORDER BY mediaID DESC");
					while($row = mysqli_fetch_assoc($fetchVideos) && $i < 4){
						$type = $row['type'];
						if(in_array($type,$extensions_arr)){
							$location = $row['location'];
							$name = $row['title'];
							echo "<span style= 'display: inline-block;'>
									<img src='".$location."' width='200' alt='This image could not be displayed :/'/>
									<br>
									<span>".$name."</span>
								</span>";
							$i++;
						}
					}
					?>
				</div>
			</section>
			<hr>
		</main>
		<footer>
			<section>
				<div class="navbar">
					<nav>
						<ul class="text" style= "margin: 0px 140px;">
							<li>About</li>
							<li>Contact Us</li>
							<li>FAQ</li>
						</ul>
					</nav>
				</div>

			</section>
		</footer>
    </body>
</html>
