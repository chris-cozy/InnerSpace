<?php

function getName($uid)
{
	$query = mysqli_query($conn, "SELECT * FROM user_info WHERE userID='$uid';") or die("Query error" . mysqli_error($conn) . "\n");
	$results = mysqli_num_rows($query);

	if ($results > 0) {
		$row = mysqli_fetch_assoc($query);
		if (isset($row['username'])) {
			$username = $row['username'];
		}
	}
	echo $username;
}
