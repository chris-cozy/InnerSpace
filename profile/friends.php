<?php
  session_start();
  include "connection.php";
  include 'functions.php';
  ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Metube: Profile</title>
		<link rel="stylesheet" href="../styles.css">
    </head>
    <body>
        <header>
            <h2 class="text"><a href="../MeTube.php" class="text">MeTube<3</a></h2>
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
							<li><a href="user_profile.php" >Media</a></li>
							<li><a href="playlists.php" >Playlists</a></li>
							<li><a href="friends.php" ><b>Friends</b></a></li>
                            <li><a href="about.php" >About</a></li>
                            <li><a href="messages.php" >Messages</a></li>
						</ul>
					</nav>
				</div>
			</section>
            <hr>
            <section>
            	<?php
                	//going through the database and getting the friends name
                	$uid = $_SESSION['userID'];
                	$query = "SELECT * from user_friends WHERE userID = '$uid'";
                	$result = mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");

                	$numrows = mysqli_num_rows($result);

                	if($numrows == 0){
                  	echo "<p class='text'>You have no friends</p>";
                	}

                	else{
                		//looping through all the friends for the user
                		while($rows = mysqli_fetch_array($result)){
                  		//using the join command to merge the table and get the information for the friends (username)
                	  		$friendID = $rows['friendID'];
                 	 		$query = "SELECT username from user_info WHERE userID = '$friendID'";
                 	 		$result = mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
                 	 		$row = mysqli_fetch_assoc($result);
				 	 		echo "<p class='text'>".$row['username']."</p><br>";
                		}
              		}
                ?>
            </section>
        </main>
    </body>
</html>
