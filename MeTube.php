<?php
	session_start();
	include_once 'connection.php';

	if(isset($_POST['search'])){
		$_SESSION['keyword'] = $_POST['key'];
		header('location: search_results.php' );
	}

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
			<form method="POST" action="">
				<input type="text" name='key' placeholder="Keyword Search">
				<input type="submit" value='Search' name='search'>
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
								//If the user is logged in, echo the user's options. If not, give the option to log in
								if (isset($_SESSION['userID'])){
									echo "<li><a href='./profile/user_profile.php'>Your Profile</a></li>
										<li><a href = 'signout.php'>Sign Out</a></li>";

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
					//Initialize counter
					$i = 0;
					//Query to grab recent videos
					$query = mysqli_query($conn, "SELECT * FROM media WHERE type = 'video' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");
					$results = mysqli_num_rows($query);
					//Check if there are any videos
					if ($results > 0){
						//Output the most recent 5
						do{
							$row = mysqli_fetch_assoc($query);
							if (isset($row['loc'])){
								$location = "profile/".$row['loc'];
								$name = $row['title'];
								echo "<span style= 'display: inline-block;'>
										<video src='".$location."' controls width='200px'>This video could not be displayed :/</video>
										<br>
										<span class='text'><a href='media_content.php?mediaID='".$row['mediaID']."''>".$name."</a></span>
									</span>";
							}
							$i++;

						}while($row && $i < 5);
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
					//Initialize counter
					$i = 0;
					//Query to grab recent audio
					$query = mysqli_query($conn, "SELECT * FROM media WHERE type = 'audio' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");
					$results = mysqli_num_rows($query);
					//Check if there are any videos
					if ($results > 0){
						do{
							//Output the most recent 5
							$row = mysqli_fetch_assoc($query);
							if (isset($row['loc'])){
								$location = "profile/".$row['loc'];
								$name = $row['title'];
								echo "<span style= 'display: inline-block;'>
										<audio src='".$location."' controls type='audio/mpeg'>This audio could not be displayed :/</audio>
										<br>
										<span class='text'><a href='media_content.php?mediaID='".$row['mediaID']."''>".$name."</a></span>
									</span>";
							}
							$i++;
						}while($row && $i < 5);
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
					//Initialize counter
					$i = 0;
					//Query to grab recent images
					$query = mysqli_query($conn, "SELECT * FROM media WHERE type = 'image' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");
					$results = mysqli_num_rows($query);
					//Check if there are any videos
					if ($results > 0){
						do{
							//Output the most recent 5
							$row = mysqli_fetch_assoc($query);
							if (isset($row['loc'])){
								$location = "profile/".$row['loc'];
								$name = $row['title'];
								echo "<span style= 'display: inline-block;'>
										<img src='".$location."' width='200' alt='This image could not be displayed :/'/>
										<br>
										<span class='text'><a href='media_content.php?mediaID=".$row['mediaID']."'>".$name."</a></span>
									</span>";
							}
							$i++;
						}while($row && $i < 5);
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
