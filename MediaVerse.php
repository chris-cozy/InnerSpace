<?php
include_once 'connection.php';
include 'includes/session_manager.php';
include 'includes/media_manager.php';

SessionManager::startSession();

if (isset($_POST['search'])) {
	$_SESSION['keyword'] = $_POST['key'];
	header('location: search_results.php');
}

// MediaManager instance to handle media operations
$mediaManager = new MediaManager($conn);

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>MediaVerse</title>
	<link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
	<?php include 'includes/header.php' ?>
	<hr>
	<main>
		<section>
			<div class="navbar">
				<nav>
					<ul class="text">
						<?php
						//If the user is logged in, echo the user's options. If not, give the option to log in
						if (isset($_SESSION['userID'])) {
							echo "<li><a href='./profile/user_profile.php'>Your Profile</a></li>
										<li><a href = 'signout.php'>Sign Out</a></li>";
						} else {
							echo "<li><a href='authentication/login.php'>Login</a></li>";
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
				$mediaManager->getRecentMedia('video', 5);
				?>
			</div>
			<br>
			<hr>
		</section>
		<section>
			<h2 class="text">Recommended Audio</h2>
			<div>
				<?php
				$mediaManager->getRecentMedia('audio', 5);
				?>
			</div>
			<br>
			<hr>
		</section>
		<section>
			<h2 class="text">Recommended Images</h2>
			<div>
				<?php
				$mediaManager->getRecentMedia('image', 5);
				?>
			</div>
		</section>
		<hr>
	</main>
	<?php include 'includes/footer.php' ?>
</body>

</html>