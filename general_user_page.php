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
<html>
    <head>
        <meta charset="UTF-8">
        <title>Metube: Profile</title>
		<link rel="stylesheet" href="styles.css">
    </head>
    <body>
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
					//---HANDLING FAVORITED---//
                    if(isset($_POST['fri'])){
                        $query = "INSERT INTO user_friends (userID, friendID) VALUES ('$uid', '$creatorID');";
                        mysqli_query($conn,$query);
						$query = "INSERT INTO user_friends (userID, friendID) VALUES ('$creatorID', '$uid');";
                        mysqli_query($conn,$query);
                    }elseif(isset($_POST['unfri'])){
                        $query = "DELETE FROM user_friends WHERE userID='$uid' AND friendID='$creatorID';";
                        mysqli_query($conn,$query);
						$query = "DELETE FROM user_friends WHERE userID='$creatorID' AND friendID='$uid';";
                        mysqli_query($conn,$query);
                    } 
				?>
				<form method="POST" action="">
					<?php
						$creatorID = $_GET['creatorID'];
                        //check if the user has favorited
                        $query = mysqli_query($conn, "SELECT * FROM user_friends WHERE userID = '$uid' AND friendID = '$creatorID';") or die ("Query error".mysqli_error($conn)."\n");
					    $resultCheck = mysqli_num_rows($query);
                        if($resultCheck == 0){
                            echo "<input type='submit' value='Friend' name='fri'>";
                        }else{
                            echo "<input type='submit' value='Unfriend' name='unfri'>";
                        }
                    ?>    
                    </form>
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
