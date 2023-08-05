<?php
include '../includes/session_manager.php';
include '../includes/connection.php';
include '../includes/media_manager.php';
include '../includes/user_auth.php';

SessionManager::startSession();

if (!isset($_SESSION['userID'])) {
	header('Location: ../authentication/login.php');
	exit();
}

$mediaManager = new MediaManager($conn);
$userAuth = new UserAuth(($conn));

$userID = $_SESSION['userID'];
$userData = $userAuth->getUserData($userID);

if (isset($_POST['search'])) {
	$_SESSION['keyword'] = $_POST['key'];
	header('location: search_results.php');
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Profile</title>
	<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
	<header>
		<h2 class="text"><a href="../MediaVerse.php" class="text">MediaVerse</a>
		</h2>
		<h3 class="text">
			<?= $userData['username'] ?>
		</h3>
	</header>
	<main>
		<section>
			<div class="navbar">
				<nav>
					<ul class="text">
						<li><a href="./user_profile.php"><b>Media</b></a></li>
						<li><a href="./playlists/playlists.php">Playlists</a></li>
						<li><a href="./friends.php">Friends</a></li>
						<li><a href="./about.php">About</a></li>
						<li><a href="./updateprofile.php">Update Profile</a></li>
						<li><a href="./upload.php">Upload</a></li>
						<li><a href="./messages/messages.php">Messages</a></li>
					</ul>
				</nav>
			</div>
		</section>
		<hr>
		<?php
		$mediaTypes = array('video', 'audio', 'image');
		foreach ($mediaTypes as $type) {
			$media = $mediaManager->getRecentMediaByType($userID, $type, 5);
			if (!empty($media)) {
				echo "<section>";
				echo "<h2 class='text'>Your " . ucfirst($type) . "</h2>";
				echo "<div>";
				foreach ($media as $item) {
					$location = $item['loc'];
					$name = $item['title'];
					$mediaID = $item['mediaID'];
					$mediaManager->displayMedia($type, $location, $mediaID, $name);
				}
				echo "</div>";
				echo "<br><hr><br>";
				echo "</section>";
			}
		}
		?>
		<section>
			<h2 class='text'>Favorites</h2>
			<?php
			$favorites = $mediaManager->getUserFavorites($userID);
			if (!empty($favorites)) {
				echo "<div>";
				foreach ($favorites as $favorite) {
					$mediaID = $favorite['mediaID'];
					$media = $mediaManager->getMediaByID($mediaID);
					if (!empty($media)) {
						$location = $media['loc'];
						$name = $media['title'];
						$type = $media['type'];

						if ($type == 'video') {
							echo "<span style='display: inline-block;'>
                                        <video src='" . $location . "' controls width='200px' class='content'>This video could not be displayed :/</video>
                                        <br>
                                        <span><a href='../media_content.php?mediaID=" . $mediaID . "' class='text'>" . $name . "</a></span>
                                    </span>";
						} elseif ($type == 'audio') {
							echo "<span style='display: inline-block;'>
                                        <audio src='" . $location . "' controls type='audio/mpeg' class='content'>This audio could not be displayed :/</audio>
                                        <br>
                                        <span><a href='../media_content.php?mediaID=" . $mediaID . "' class='text'>" . $name . "</a></span>
                                    </span>";
						} elseif ($type == 'image') {
							echo "<span style='display: inline-block;'>
                                        <img src='" . $location . "' width='200' class='content' alt='This image could not be displayed :/'/>
                                        <br>
                                        <span><a href='../media_content.php?mediaID=" . $mediaID . "' class='text'>" . $name . "</a></span>
                                    </span>";
						}
					}
				}
				echo "</div>";
			} else {
				echo "<p class='text'>No favorites yet.</p>";
			}
			?>
		</section>
	</main>
</body>

</html>