<?php
	session_start();
	include_once 'connection.php';

	if(isset($_POST['search'])){
		$_SESSION['keyword'] = $_POST['key'];
		header('location: search_results.php' );
	}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>MeTube<3</title>
		<link rel="stylesheet" href="styles.css">
    </head>
    <body>
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
								if (isset($_SESSION['userID'])){
									echo "<li><a href='./profile/user_profile.php'>Your Profile</a></li>";
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
						$type = "video";
						$loop = 5;
						$query = mysqli_query($conn, "SELECT * FROM media WHERE type = 'video' AND keyword = '$key' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");

						mediaLoop($query, $type, $loop);
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
						$type = "audio";
						$loop = 5;
						$query = mysqli_query($conn, "SELECT * FROM media WHERE type = 'audio' AND keyword = '$key' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");

						mediaLoop($query, $type, $loop);
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
						$type = "image";
						$loop = 5;
						$query = mysqli_query($conn, "SELECT * FROM media WHERE type = 'image' AND keyword = '$key' ORDER BY mediaID DESC;") or die ("Query error".mysqli_error($conn)."\n");

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
