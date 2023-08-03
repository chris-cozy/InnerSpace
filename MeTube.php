<?php
	session_start();
	include_once 'connection.php';
	include 'functions.php';

	if(isset($_POST['search'])){
		$_SESSION['keyword'] = $_POST['key'];
		header('location: search_results.php' );
	}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>MeTube <3</title>
		<link rel="stylesheet" href="styles.css">
    </head>
    <body>
		<header>
			<img class="logo-image" src="image/mootube.png" alt="logo unavailable"/>
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
						$type = "video";
						$loop = 5;
						//Query to grab recent videos
						$query = mysqli_query($conn, "SELECT * FROM media WHERE type = 'video' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");
						mediaLoop($query, $type, $loop);
					?>
				</div>
				<br>
				<hr>
			</section>
			<section>
				<h2 class="text">Recommended Audio</h2>
				<div>
					<?php
						$type = "audio";
						$loop = 5;
						//Query to grab recent audio
						$query = mysqli_query($conn, "SELECT * FROM media WHERE type = 'audio' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");
						mediaLoop($query, $type, $loop);
					?>
				</div>
				<br>
				<hr>
			</section>
			<section>
				<h2 class="text">Recommended Images</h2>
				<div>
					<?php
						$type = "image";
						$loop = 5;
						//Query to grab recent images
						$query = mysqli_query($conn, "SELECT * FROM media WHERE type = 'image' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");
						mediaLoop($query, $type, $loop);
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
