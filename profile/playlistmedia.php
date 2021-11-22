<?php
session_start();
include 'connection.php';
include 'functions.php';
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Metube: Profile</title>
    <link rel="stylesheet" href="../styles.css">
  </head>
  <body style="background-color: rgb(42, 44, 44);">
    <header>
      <h2 class="text"><a href="MeTube.php" class="text">MeTube<3</a></h2>
      <h3 class="text">*User's name here*</h3>
    </header>
    <main>
      <section>
        <div class="navbar">
          <nav>
            <ul class="text">
              <li><a href="user_profile.php" >Media</a></li>
              <li><a href="playlists.php" ><b>Playlists</b></a></li>
              <li><a href="friends.php" >Friends</a></li>
              <li><a href="about.php" >About</a></li>
              <li><a href="messages.php" >Messages</a></li>
            </ul>
          </nav>
        </div>
      </section>
      <hr>
      <section>
        <div class="text">
          <h2> Playlists</h2>
          <?php
            $i = 0;
            if(isset($_GET['pid'])){
              $pid = $_GET['pid'];
              $query = "SELECT * from media_playlists  WHERE playlistID = '$pid'";
              $result = mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
              $numrows = mysqli_num_rows($result);
              if($numrows == 0){
                echo "This playlist is empty";
              }
              else{

                do{
                  $row = mysqli_fetch_assoc($result);
                  $mediaID = $row['mediaID'];

                  $query = "SELECT * from media WHERE mediaID = '$mediaID'";
                  $result2 = mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
                  $row2=mysqli_fetch_assoc($result2);
                  $numrows2 = mysqli_num_rows($result2);
                  if (isset($row2['loc'])){
                    $location = "profile/".$row2['loc'];
                    $name = $row2['title'];
                    $type = $row2['type'];
                      if($type=='video'){
                        echo "<span style= 'display: inline-block;'>
                        <video src='".$location."' controls width='700px'>This video could not be displayed :/</video>
                        <br>
                        <span><a href='../media_content.php?mediaID='".$mediaID."''>".$name."</a></span>
                        </span>";
                      }
                      elseif($type=='audio'){
                        echo "<span style= 'display: inline-block;'>
                        <audio src='".$location."' controls type='audio/mpeg'>This audio could not be displayed :/</audio>
                        <br>
                        <span><a href='../media_content.php?mediaID='".$mediaID."''>".$name."</a></span>
                        </span>";
                      }
                      elseif($type=='image'){
                        echo "<span style= 'display: inline-block;'>
                        <img src='".$location."' width='700' alt='This image could not be displayed :/'/>
                        <br>
                        <span><a href='../media_content.php?mediaID='".$mediaID."''>".$name."</a></span>
                        </span>";
                      }
                  }
                  $i++;
                }while($row2 && $i < $numrows2);
              }
            }
          ?>
          <form>
            <for action="" method="post">
            <button name="delete" type="submit">Delete Playlist</button>
          </form>

          <?php
            if(isset($_POST['delete'])){
              $query = "DELETE FROM media_playlists where playlistID ='$pid';";
              $result = mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
              $query = "DELETE FROM user_playlists where playlistID ='$pid';";
              $result = mysqli_query($conn,$query) or die ("Query error".mysqli_error($conn)."\n");
              header("Location:playlistmedia.php");
            }
          ?>
        </div>
      </section>
    </main>
  </body>
</html>
