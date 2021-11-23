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
            <h3 class="text">
				<?php 
					$uid = $_SESSION['userID'];
					getName($uid, $conn);
				?>
			</h3>
        </header>
        <main>
            <section>
				<div class="navbar">
					<nav>
						<ul class="text">
							<li><a href="user_profile.php" ><b>Media</b></a></li>
							<li><a href="./playlists.php" >Playlists</a></li>
							<li><a href="./friends.php" >Friends</a></li>
                            <li><a href="./about.php" >About</a></li>
							<li><a href="updateprofile.php">Update Profile</a></li>
							<li><a href="upload.php">Upload</a></li>
							<li><a href="messages.php">Messages</a></li>
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
					//Initialize media counter
					$i = 0;
					//Set user id variable
					$uid = $_SESSION['userID'];
					$query = mysqli_query($conn, "SELECT * FROM media WHERE userID ='$uid' AND type = 'video' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");
					$results = mysqli_num_rows($query);
					//Check if there are valid rows
					if ($results > 0){
						do{
							//Display media
							$row = mysqli_fetch_assoc($query);
							if (isset($row['loc'])){
								$location = $row['loc'];
								$name = $row['title'];
								echo "<span style= 'display: inline-block;'>
										<video src='".$location."' controls width='200px'>This video could not be displayed :/</video>
										<br>
										<span><a href='../media_content.php?mediaID='".$row['mediaID']."'' class='text'>".$name."</a></span>
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
				<h2 class="text">Your Audio</h2>
				<div>
					<?php
					//Initialize media counter
					$i = 0;
					//Set user id variable
					$uid = $_SESSION['userID'];
					$query = mysqli_query($conn, "SELECT * FROM media WHERE userID ='$uid' AND type = 'audio' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");
					$results = mysqli_num_rows($query);
					//Check if there are valid rows
					if ($results > 0){
						do{
							//Display media
							$row = mysqli_fetch_assoc($query);
							if (isset($row['loc'])){
								$location = $row['loc'];
								$name = $row['title'];
								echo "<span style= 'display: inline-block;'>
										<audio src='".$location."' controls type='audio/mpeg'>This audio could not be displayed :/</audio>
										<br>
										<span><a href='../media_content.php?mediaID='".$row['mediaID']."'' class='text'>".$name."</a></span>
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
				<h2 class="text">Your Images</h2>
				<div>
					<?php
					//Initialize media counter
					$i = 0;
					//Set user id variable
					$uid = $_SESSION['userID'];
					$query = mysqli_query($conn, "SELECT * FROM media WHERE userID ='$uid' AND type = 'image' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");
					$results= mysqli_num_rows($query);
					//Check if there are valid rows
					if ($results > 0){
						do{
							//Display media
							$row = mysqli_fetch_assoc($query);
							if (isset($row['loc'])){
								$location = $row['loc'];
								$name = $row['title'];
								echo "<span style= 'display: inline-block;'>
										<img src='".$location."' width='200' alt='This image could not be displayed :/'/>
										<br>
										<span><a href='../media_content.php?mediaID='".$row['mediaID']."'' class='text'>".$name."</a></span>
									</span>";
							}
							$i++;
						}while($row && $i < 5);
					}
					?>
				</div>
			</section>
			<br>
			<hr>
			<section>
             <h2 class='text'>Favorites</h2>
             <?php
			 //Set user id variable
              $uid = $_SESSION['userID'];
              $query = mysqli_query($conn, "SELECT * FROM media_favorited WHERE userID = '$uid';") or die ("Query error".mysqli_error($conn)."\n");
              $results = mysqli_num_rows($query);
              if ($results > 0){
                do{
                  $row = mysqli_fetch_assoc($query);
                  if (isset($row['mediaID'])){
                    $mediaID = $row['mediaID'];

                    $query = mysqli_query($conn, "SELECT * FROM media WHERE mediaID='$mediaID';") or die ("Query error".mysqli_error($conn)."\n");
                    $results = mysqli_num_rows($query);

                    if ($results > 0){
                      $row = mysqli_fetch_assoc($query);
                      if (isset($row['loc'])){
                        $location = $row['loc'];
                        $name = $row['title'];
                        $type = $row['type'];
                        $description = $row['description'];
                        $creatorID = $row['userID'];
                        $creator = "";

                        $query = mysqli_query($conn, "SELECT * FROM user_info WHERE userID='$creatorID';") or die ("Query error".mysqli_error($conn)."\n");
                        $results = mysqli_num_rows($query);

                        if ($results > 0){
                          $row = mysqli_fetch_assoc($query);
                          if (isset($row['username'])){
                          $creator = $row['username'];
                          }
                        }
                        if($type=='video'){
                          echo "<span style= 'display: inline-block;'>
										        <video src='".$location."' controls width='200px'>This video could not be displayed :/</video>
										        <br>
										        <span><a href='../media_content.php?mediaID='".$mediaID."'' class='text'>".$name."</a></span>
									          </span>";
                        }elseif($type=='audio'){
                          echo "<span style= 'display: inline-block;'>
										        <audio src='".$location."' controls type='audio/mpeg'>This audio could not be displayed :/</audio>
										        <br>
										        <span><a href='../media_content.php?mediaID='".$mediaID."'' class='text'>".$name."</a></span>
								          	</span>";
                        }elseif($type=='image'){
                          echo "<span style= 'display: inline-block;'>
										        <img src='".$location."' width='200' alt='This image could not be displayed :/'/>
										        <br>
										        <span><a href='../media_content.php?mediaID=".$mediaID."' class='text'>".$name."</a></span>
									          </span>";
                        }
                      }
                    }
                  }
                }while($row);
              }
            ?>
            </section>
        </main>
    </body>
</html>
