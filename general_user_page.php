<?php
	session_start();
	include_once 'connection.php';
    $creatorID = $_GET['creatorID'];
	$creatorUser = $_GET['creatorUser'];
    $_SESSION['creatorID'] = $creatorID;
	$_SESSION['creatorUser'] = $creatorUser;
    $uid = $_SESSION['userID'];
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
            <h3 class="text"><?php echo $_SESSION['creatorUser']; ?></h3>
        </header>
        <main>
            <section>
				<?php
					$creatorID = $_GET['creatorID'];
					$query = mysqli_query($conn, "SELECT * FROM account_info WHERE userID='$creatorID';") or die ("Query error".mysqli_error($conn)."\n");
					$resultCheck = mysqli_num_rows($query);
					if ($resultCheck > 0){
						$row = mysqli_fetch_assoc($query);
						if (isset($row['userID'])){
							$about = $row['about_info'];
							$subs = $row['subscriber_count'];
						}
					}
					echo "<h4 class='text'>ABOUT</h4>
							<br>
							<p>".$about."</p>
							<br>
							<h4 class='text'>Subscribers: ".$subs."</h4>";
				?>
			</section>
            <hr>
			<!--Display recent uploaded medias-->
            <section>
			<h2 class="text">Their Videos</h2>
				<div>
					<?php
					$i = 0;
					$creatorID = $_GET['creatorID'];
					//WATCH PREPARED STATEMENTS VIDEO
					$extensions_arr = array("mp4","avi","3gp","mov","mpeg");
					$fetchVideos = mysqli_query($conn, "SELECT * FROM media WHERE userID ='$creatorID' AND type = 'video' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");

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
										<span><a href='../media_content.php?mediaID='".$row['mediaID']."''>".$name."</a></span>
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
				<h2 class="text">Their Audio</h2>
				<div>
					<?php
					$creatorID = $_GET['creatorID'];
					$i = 0;
					$extensions_arr = array("mp3");
					$fetchVideos = mysqli_query($conn, "SELECT * FROM media WHERE userID ='$creatorID' AND type = 'audio' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");

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
										<span><a href='../media_content.php?mediaID='".$row['mediaID']."''>".$name."</a></span>
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
				<h2 class="text">Their Images</h2>
				<div>
					<?php
					$creatorID = $_GET['creatorID'];
					$i = 0;
					$extensions_arr = array("jpg","png");
					$fetchVideos = mysqli_query($conn, "SELECT * FROM media WHERE userID ='$creatorID' AND type = 'image' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");

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
										<span><a href='../media_content.php?mediaID='".$row['mediaID']."''>".$name."</a></span>
									</span>";
							}
							$i++;

						}while($row && $i < 4 && $i < $resultCheck);
					}
					?>
				</div>
			</section>
        </main>
    </body>
</html>
