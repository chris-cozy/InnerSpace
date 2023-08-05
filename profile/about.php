<?php
include '../includes/session_manager.php';
include '../includes/connection.php';
include '../includes/media_manager.php';
include '../includes/user_auth.php';

SessionManager::startSession();

// Get user ID from the session
$uid = $_SESSION['userID'];

// Function to display user info
function displayUserInfo($row)
{
	$userID = $row['userID'];
	$firstN = $row['first_name'];
	$lastN = $row['last_name'];
	$birthday = $row['birthday'];
	$date_created = $row['date_created'];

	echo "<ul class='text'>
        <li>User ID: $userID</li>
        <li>Name: $firstN $lastN</li>
        <li>Birthday: $birthday</li>
        <li>Day Account Created: $date_created</li>
    </ul>";
}

// Function to display account info
function displayAccountInfo($row)
{
	$about = $row['about_info'];
	$subscriberCount = $row['subscriber_count'];

	echo "<ul class='text'>
        <li>Subscriber Count: $subscriberCount</li>
        <li>About: $about</li>
    </ul>";
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>MediaVerse: Profile</title>
	<link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
	<header>
		<h2 class="text"><a href="../MediaVerse.php" class="text">MediaVerse</a>
		</h2>
		<h3 class="text">About: <h3 class="text">About: <?php getName($uid, $conn); ?></h3>
	</header>
	<main>
		<section>
			<div class="navbar">
				<nav>
					<ul class="text">
						<li><a href="user_profile.php">Media</a></li>
						<li><a href="./playlists/playlists.php">Playlists</a></li>
						<li><a href="friends.php">Friends</a></li>
						<li><a href="about.php"><b>About</b></a></li>
						<li><a href="updateprofile.php">Update Profile</a></li>
						<li><a href="upload.php">Upload</a></li>
						<li><a href="./messages/messages.php">Messages</a></li>
					</ul>
				</nav>
			</div>
		</section>
		<hr>
		<section>
			<div class="user-info">
				<?php
				// Retrieve user information
				$query = mysqli_query($conn, "SELECT * FROM user_info WHERE userID = '$uid' LIMIT 1");
				if ($row = mysqli_fetch_assoc($query)) {
					displayUserInfo($row);
				}
				?>
			</div>
			<div class="account-info">
				<?php
				// Retrieve account information
				$query = mysqli_query($conn, "SELECT * FROM account_info WHERE userID = '$uid' LIMIT 1");
				if ($row = mysqli_fetch_assoc($query)) {
					displayAccountInfo($row);
				}
				?>
			</div>
		</section>
	</main>
</body>

</html>