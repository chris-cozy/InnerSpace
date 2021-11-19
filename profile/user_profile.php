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
            <h2 class="text"><a href="MeTube.html" class="text">MeTube<3</a></h2>
            <h3 class="text">*User's name here*</h3>
        </header>
        <main>
            <section>
				<div class="navbar">
					<nav>
						<ul class="text">
							<li><a href="user_profile.html" ><b>Media</b></a></li>
							<li><a href="./playlists.html" >Playlists</a></li>
							<li><a href="./friends.html" >Friends</a></li>
                            <li><a href="./about.html" >About</a></li>
                            <li><a href="user_profile.html" >Messages</a></li>
							<li><a href="updateprofile.php">Update Profile</a></li>
							<li><a href="upload.php">Upload</a></li>
						</ul>
					</nav>
				</div>
			</section>
            <hr>
			<!--Display recent uploaded medias-->
            <section>
				<h2 class="text">Your Videos</h2>
				<div>
					<?php
					$i = 0;
					$uid = $_SESSION['userID'];
					//WATCH PREPARED STATEMENTS VIDEO
					$extensions_arr = array("mp4","avi","3gp","mov","mpeg");
					$fetchVideos = mysqli_query($conn, "SELECT * FROM media WHERE userID=('$uid') ORDER BY mediaID DESC");

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
				<h2 class="text">Your Audio</h2>
				<div>
					<?php
					$uid = $_SESSION['userID'];
					$i = 0;
					$extensions_arr = array("mp3");
					$fetchVideos = mysqli_query($conn, "SELECT * FROM media WHERE userID=('$uid') ORDER BY mediaID DESC");
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
				<h2 class="text">Your Images</h2>
				<div>
					<?php
					$uid = $_SESSION['userID'];
					$i = 0;
					$extensions_arr = array("jpg","png");
					$fetchVideos = mysqli_query($conn, "SELECT * FROM media WHERE userID=('$uid') ORDER BY mediaID DESC");
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
        </main>
    </body>
</html>