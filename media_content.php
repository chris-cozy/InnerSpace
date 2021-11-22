<?php
	session_start();
	include_once 'connection.php';
    $mediaID = $_GET['mediaID'];
    $_SESSION['curmediaID'] = $mediaID;
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
        <title>MeTube<3</title>
    </head>
    <body style="background-color: rgb(42, 44, 44);">
		<header>
			<h1 class="logo"><a href="MeTube.php" class="text">MeTube<3</a></h1>
		</header>
		<hr>
		<main>
			<section>
                <!--Section for media display -->
                <?php
                    $query = mysqli_query($conn, "SELECT * FROM media WHERE mediaID='$mediaID';") or die ("Query error".mysqli_error($conn)."\n");
                    $resultCheck = mysqli_num_rows($query);

					if ($resultCheck > 0){
                        $row = mysqli_fetch_assoc($query);
						if (isset($row['loc'])){
							$location = "profile/".$row['loc'];
							$name = $row['title'];
                            $type = $row['type'];
                            $description = $row['description'];
                            $creatorID = $row['userID'];
                            $creator = "";

                            $query = mysqli_query($conn, "SELECT * FROM user_info WHERE userID='$creatorID';") or die ("Query error".mysqli_error($conn)."\n");
                            $resultCheck = mysqli_num_rows($query);

					        if ($resultCheck > 0){
                                $row = mysqli_fetch_assoc($query);
						        if (isset($row['username'])){
                                    $creator = $row['username'];
                                }
                            }
                            if($type=='video'){
                                echo "<span style= 'display: inline-block;'>
										<video src='".$location."' controls width='700px'>This video could not be displayed :/</video>
										<br>
										<span style= 'display: inline-block;'>
                                            <p>".$name."</p>
                                            <br>
                                            <p>".$description."</p>
                                            <br>
                                            <p>Posted By: </p>
                                            <a href='general_user_page.php?creatorID=".$creatorID."&creatorUser=".$creator."'>".$creator."</a>
                                        </span>
									</span>";
                            }elseif($type=='audio'){
                                echo "<span style= 'display: inline-block;'>
										<audio src='".$location."' controls type='audio/mpeg'>This audio could not be displayed :/</audio>
										<br>
										<span style= 'display: inline-block;'>
                                            <p>".$name."</p>
                                            <br>
                                            <p>".$description."</p>
                                            <br>
                                            <p>Posted By: </p>
                                            <a href='general_user_page.php?creatorID=".$creatorID."&creatorUser=".$creator."'>".$creator."</a>
                                        </span>
									</span>";
                            }elseif($type=='image'){
                                echo "<span style= 'display: inline-block;'>
									    <img src='".$location."' width='700' alt='This image could not be displayed :/'/>
									    <br>
									    <span style= 'display: inline-block;'>
                                            <p>".$name."</p>
                                            <br>
                                            <p>".$description."</p>
                                            <br>
                                            <p>Posted By: </p>
                                            <a href='general_user_page.php?creatorID=".$creatorID."&creatorUser=".$creator."'>".$creator."</a>
                                        </span>
								    </span>";
                            }
                            //Handling downloads
                            if(isset($_POST['dl'])){
                                readfile($location.$name);
                            }
                        }
					}
                ?>
				
			</section>
			<hr>
			<section>
                <?php
                    //check if the user has liked
                    $query = mysqli_query($conn, "SELECT * FROM media_likes WHERE userID = '$uid' AND mediaID = '$mediaID';") or die ("Query error".mysqli_error($conn)."\n");

					$resultCheck = mysqli_num_rows($query);
					if ($resultCheck == 0){
                        //check if the user has disliked
                        $query = mysqli_query($conn, "SELECT * FROM media_dislikes WHERE userID = '$uid' AND mediaID = '$mediaID';") or die ("Query error".mysqli_error($conn)."\n");

					    $resultCheck = mysqli_num_rows($query);
                        if ($resultCheck == 0){
                            //User has neither liked or disliked
                            if(isset($_POST['sub'])){
                                $selection = $_POST['LD'];
                                if($selection == "LIKE"){
                                    //If they liked
                                    $query = "INSERT INTO media_likes (mediaID, userID) VALUES ('$mediaID', '$uid');";

                                }elseif($selection == "DISLIKE"){
                                    //If they disliked
                                    $query = "INSERT INTO media_dislikes (mediaID, userID) VALUES ('$mediaID', '$uid');";
                                }
                            mysqli_query($conn,$query);
                            }
                        }
                    }
                    
                    //---HANDLING FAVORITED---//
                    if(isset($_POST['fav'])){
                        $query = "INSERT INTO media_favorited (userID, mediaID) VALUES ('$uid', '$mediaID');";
                        mysqli_query($conn,$query);
                    }elseif(isset($_POST['unfav'])){
                        $query = "DELETE FROM media_favorited WHERE userID='$uid' AND mediaID='$mediaID';";
                        mysqli_query($conn,$query);
                    }
                ?>
                <span style= 'display: inline-block;'>
                    <form method="POST" action="">
                        <input name="LD" type="radio" id="like" value="LIKE"/>
                        <label for="like">LIKE :)</label><br>
                        <input name="LD" type="radio" id="dislike" value="DISLIKE"/>
                        <label for="dislike">DISLIKE :(</label><br>
                        <?php
                            if(!isset($_POST['sub'])){
                                echo "<input type='submit' value='Submit' name='sub'>";
                            }
                            //check if the user has favorited
                            $query = mysqli_query($conn, "SELECT * FROM media_favorited WHERE userID = '$uid' AND mediaID = '$mediaID';") or die ("Query error".mysqli_error($conn)."\n");
					        $resultCheck = mysqli_num_rows($query);
                            if($resultCheck == 0){
                                echo "<input type='submit' value='Favorite' name='fav'>";
                            }else{
                                echo "<input type='submit' value='Unfavorite' name='unfav'>";
                            }
                        ?>
                        <input type='submit' value='Download' name='dl'>
                    </form>
                </span>
                <span>
                    <p>Likes: 
                    <?php
                        $query = mysqli_query($conn, "SELECT * FROM media_likes WHERE mediaID = '$mediaID';") or die ("Query error".mysqli_error($conn)."\n");
                        $resultCheck = mysqli_num_rows($query);
                        echo $resultCheck;
                    ?>
                    </p>
                    <p>Dislikes: 
                    <?php
                        $query = mysqli_query($conn, "SELECT * FROM media_dislikes WHERE mediaID = '$mediaID';") or die ("Query error".mysqli_error($conn)."\n");
                        $resultCheck = mysqli_num_rows($query);
                        echo $resultCheck;
                    ?>
                    </P>
                </span>
			</section>
			<section>
                <h2 class="text">COMMENTS</h2>
                <span style= 'display: inline-block;'>
                    <?php
                        $i = 0;
                        $query = mysqli_query($conn, "SELECT * FROM media_comments WHERE mediaID = '$mediaID' ORDER BY date_time ASC;") or die ("Query error".mysqli_error($conn)."\n");

                        $resultCheck = mysqli_num_rows($query);
                        if ($resultCheck > 0){
                            do{
                                $row = mysqli_fetch_assoc($query);
                                if (isset($row['comment'])){
                                    $comment = $row['comment'];
                                    $userID = $row['userID'];

                                    $query2 = mysqli_query($conn, "SELECT * FROM user_info WHERE userID = '$userID';") or die ("Query error".mysqli_error($conn)."\n");
                                    $row2 = mysqli_fetch_assoc($query2);
                                    if (isset($row2['username'])){
                                        $username = $row2['username'];
                                    }
                                }
                                echo "<p>".$comment." - ".$username."</p>";
                                $i++;
    
                            }while($row && $i < 20 && $i < $resultCheck);
                        }

                    ?>
                </span>
                <span style= 'display: inline-block;'>
                    <?php
                    

                    ?>
                    <form method="POST" action="comment_grab.php">
                        <input type="text" name="comment" placeholder="Enter your comment"/>
                        <input type='submit' value='Submit' name='sub_com'>
                    </form>
                </span>
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