<?php
session_start();
include 'connection.php';
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
							<li><a href="user_profile.php" >Media</a></li>
							<li><a href="playlists.php" ><b>Playlists</b></a></li>
							<li><a href="friends.html" >Friends</a></li>
                            <li><a href="about.php" >About</a></li>
                            <li><a href="user_profile.php" >Messages</a></li>
						</ul>
					</nav>
				</div>
			</section>
            <hr>
            <section>
              <div class="text">
                <h2> Playlists</h2>
                <?php
                  if(isset($_GET['pid'])){
                    $pid = $_GET['pid'];
                    $query = "SELECT * from media_playlists  WHERE playlistID = '$pid'";
                    $result = mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
                    $numrows = mysqli_num_rows($result);
                    echo $pid;
                  if($numrows == 0){
                    echo "This playlist is empty";
                  }
                  else{
                while($row = mysqli_fetch_assoc($result)){
                  $mediaID = $row['mediaID'];
                  $query = "SELECT * from media WHERE mediaID = '$mediaID'";
                  $result = mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
                  if (isset($row['loc'])){
                    $location = "profile/".$row['loc'];
                    $name = $row['title'];
                        $type = $row['type'];
                        if($type=='video'){
                          echo "<span style= 'display: inline-block;'>
                          <video src='".$location."' controls width='700px'>This video could not be displayed :/</video>
                          <br>
                          <span>".$name."</span>
                      </span>";
                        }
                        elseif($type=='audio'){
                          echo "<span style= 'display: inline-block;'>
                          <audio src='".$location."' controls type='audio/mpeg'>This audio could not be displayed :/</audio>
                          <br>
                          <span>".$name."</span>
                          </span>";
                        }
                        elseif($type=='image'){
                          echo "<span style= 'display: inline-block;'>
                          <img src='".$location."' width='700' alt='This image could not be displayed :/'/>
                          <br>
                          <span>".$name."</span>
                        </span>";
                        }
                    }
                  }
                }
              }
            ?>
              </div>




            </section>
        </main>
    </body>
</html>
