<?php

function getName($uid)
{
	$query = mysqli_query($conn, "SELECT * FROM user_info WHERE userID='$uid';") or die ("Query error".mysqli_error($conn)."\n");
	$results = mysqli_num_rows($query);

	if ($results > 0){
		$row = mysqli_fetch_assoc($query);
		if (isset($row['username'])){
			$username = $row['username'];
		}
	}
	echo $username;
}

function displayMedia($type, $location, $mediaID, $name)
{
	switch($type){
		case "video":
			echo "<span style= 'display: inline-block;'>
					<video src='".$location."' controls width='200px' class='content'>This video could not be displayed :/</video>
					<br>
					<span><a href='media_content.php?mediaID=".$mediaID."' class='text'>".$name."</a></span>
					</span>";
			break;
		case "audio":
			echo "<span style= 'display: inline-block;'>
					<audio src='".$location."' controls type='audio/mpeg' class='content'>This audio could not be displayed :/</audio>
					<br>
					<span><a href='media_content.php?mediaID=".$mediaID."' class='text'>".$name."</a></span>
					</span>";
			break;
		case "image":
			echo "<span style= 'display: inline-block;'>
					<img src='".$location."' width='200' class='content' alt='This image could not be displayed :/'/>
					<br>
					<span><a href='media_content.php?mediaID=".$mediaID."' class='text'>".$name."</a></span>
					</span>";
			break;
		case "in-video":
			echo "<span style= 'display: inline-block;'>
					<video src='".$location."' controls width='200px' class='content' >This video could not be displayed :/</video>
					<br>
					<span><a href='../media_content.php?mediaID=".$mediaID."' class='text'>".$name."</a></span>
					</span>";
			break;
		case "in-audio":
			echo "<span style= 'display: inline-block;'>
					<audio src='".$location."' controls type='audio/mpeg' class='content'>This audio could not be displayed :/</audio>
					<br>
					<span><a href='../media_content.php?mediaID=".$mediaID."' class='text'>".$name."</a></span>
					</span>";
			break;
		case "in-image":
			echo "<span style= 'display: inline-block;'>
					<img src='".$location."' width='200' class='content' alt='This image could not be displayed :/'/>
					<br>
					<span><a href='../media_content.php?mediaID=".$mediaID."' class='text'>".$name."</a></span>
					</span>";
			break;
	}
}

function mediaLoop($query, $type, $loop)
{
	$i = 0;
	$results = mysqli_num_rows($query);
	if ($results > 0){
		do{
			$row = mysqli_fetch_assoc($query);
			if (isset($row['loc'])){
				$location = "profile/".$row['loc'];
				$name = $row['title'];
				$mediaID = $row['mediaID'];
				displayMedia($type, $location, $mediaID, $name);
			}
			$i++;
		}while($row && $i < $loop);
	}
}
