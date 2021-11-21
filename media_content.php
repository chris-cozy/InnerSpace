<?php
	session_start();
	include_once 'connection.php';
    $mediaID = $_GET['mediaID'];
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
                            if($type=='video'){
                                echo "<span style= 'display: inline-block;'>
										<video src='".$location."' controls width='700px'>This video could not be displayed :/</video>
										<br>
										<span>".$name."</span>
									</span>";
                            }elseif($type=='audio'){
                                echo "<span style= 'display: inline-block;'>
										<audio src='".$location."' controls type='audio/mpeg'>This audio could not be displayed :/</audio>
										<br>
										<span>".$name."</span>
									</span>";
                            }elseif($type=='image'){
                                echo "<span style= 'display: inline-block;'>
									    <img src='".$location."' width='700' alt='This image could not be displayed :/'/>
									    <br>
									    <span>".$name."</span>
								    </span>";
                            }
                        }
					}
                ?>
				
			</section>
			<hr>
			<section>
                <form>
                    
                </form>
			</section>
			<section>
                <h2 class="text">COMMENTS</h2>
                <span>
                    <p>Comment go here :P</p>
                </span>
                <span>
                    <P>Comment button go here :P</p>
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