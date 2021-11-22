<?php
	session_start();
	include_once 'connection.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>MeTube<3</title>
		<link rel="stylesheet" href="styles.css">
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
				<h2 class="text">Searched Videos</h2>
				<!--Display recent uploaded videos-->
				<div>
					<?php
                    $key = $_SESSION['keyword'];
					$i = 0;
					//WATCH PREPARED STATEMENTS VIDEO
					$extensions_arr = array("mp4","avi","3gp","mov","mpeg");
					$fetchVideos = mysqli_query($conn, "SELECT * FROM media WHERE type = 'video' AND keyword = '$key' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");

					$resultCheck = mysqli_num_rows($fetchVideos);
					if ($resultCheck > 0){
						do{
							$row = mysqli_fetch_assoc($fetchVideos);
							if (isset($row['loc'])){
								$location = "profile/".$row['loc'];
								$name = $row['title'];
								echo "<span style= 'display: inline-block;'>
										<video src='".$location."' controls width='200px'>This video could not be displayed :/</video>
										<br>
										<span><a href='media_content.php?mediaID='".$row['mediaID']."''>".$name."</a></span>
									</span>";
							}
							$i++;

						}while($row && $i < 4 && $i < $resultCheck);
					}
					?>
				</div>
				<br>
				<hr>
				<br>
			</section>
			<section>
				<h2 class="text">Searched Audio</h2>
				<div>
					<?php
                    $key = $_SESSION['keyword'];
					$i = 0;
					$extensions_arr = array("mp3");
					$fetchVideos = mysqli_query($conn, "SELECT * FROM media WHERE type = 'audio' AND keyword = '$key' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");

					$resultCheck = mysqli_num_rows($fetchVideos);
					if ($resultCheck > 0){
						do{
							$row = mysqli_fetch_assoc($fetchVideos);
							if (isset($row['loc'])){
								$location = "profile/".$row['loc'];
								$name = $row['title'];
								echo "<span style= 'display: inline-block;'>
										<audio src='".$location."' controls type='audio/mpeg'>This audio could not be displayed :/</audio>
										<br>
										<span><a href='media_content.php?mediaID='".$row['mediaID']."''>".$name."</a></span>
									</span>";
							}
							$i++;
						}while($row && $i < 4 && $i < $resultCheck);
					}
					?>
				</div>
				<br>
				<hr>
				<br>
			</section>
			<section>
				<h2 class="text">Searched Images</h2>
				<div>
					<?php
                    $key = $_SESSION['keyword'];
					$i = 0;
					$extensions_arr = array("jpg","png");
					$fetchVideos = mysqli_query($conn, "SELECT * FROM media WHERE type = 'image' AND keyword = '$key' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");

					$resultCheck = mysqli_num_rows($fetchVideos);

					if ($resultCheck > 0){
						do{
							$row = mysqli_fetch_assoc($fetchVideos);
							if (isset($row['loc'])){
								$location = "profile/".$row['loc'];
								$name = $row['title'];
								echo "<span style= 'display: inline-block;'>
										<img src='".$location."' width='200' alt='This image could not be displayed :/'/>
										<br>
										<span><a href='media_content.php?mediaID=".$row['mediaID']."'>".$name."</a></span>
									</span>";
							}
							$i++;

						}while($row && $i < 4 && $i < $resultCheck);
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
