<?php
include '../includes/session_manager.php';
include '../includes/connection.php';
include '../includes/media_manager.php';
include '../includes/user_auth.php';

SessionManager::startSession();
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Friends</title>
	<link rel="stylesheet" href="..assets/css/style.css">
</head>

<body>
	<header>
		<h2 class="text"><a href="../MeTube.php" class="text">MediaVerse</a>
		</h2>
		<h3 class="text"><?php
							$uid = $_SESSION['userID'];
							getName($uid, $conn);
							?></h3>
	</header>
	<main>
		<section>
			<div class="navbar">
				<nav>
					<ul class="text">
						<li><a href="user_profile.php">Media</a></li>
						<li><a href="playlists.php">Playlists</a></li>
						<li><a href="friends.php"><b>Friends</b></a></li>
						<li><a href="about.php">About</a></li>
						<li><a href="updateprofile.php">Update Profile</a></li>
						<li><a href="upload.php">Upload</a></li>
						<li><a href="messages.php">Messages</a></li>
					</ul>
				</nav>
			</div>
		</section>
		<hr>
		<section>
			<?php
			$uid = $_SESSION['userID'];
			$query = "SELECT friendID FROM user_friends WHERE userID = ?";
			$stmt = mysqli_prepare($conn, $query);
			mysqli_stmt_bind_param($stmt, "i", $uid);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);

			$numrows = mysqli_num_rows($result);

			if ($numrows == 0) {
				echo "<p class='text'>You have no friends</p>";
			} else {
				while ($rows = mysqli_fetch_array($result)) {
					$friendID = $rows['friendID'];
					$query = "SELECT username FROM user_info WHERE userID = ?";
					$stmt2 = mysqli_prepare($conn, $query);
					mysqli_stmt_bind_param($stmt2, "i", $friendID);
					mysqli_stmt_execute($stmt2);
					$result2 = mysqli_stmt_get_result($stmt2);
					$row = mysqli_fetch_assoc($result2);
					$friendName = $row['username'];
					echo "<a href='../general_user_page.php?creatorID=" . $friendID . "&creatorUser=" . $friendName . "' class='text'>" . $friendName . "</a><br>";
				}
			}

			// Close the prepared statements
			mysqli_stmt_close($stmt);
			mysqli_stmt_close($stmt2);
			?>
		</section>
	</main>
</body>

</html>