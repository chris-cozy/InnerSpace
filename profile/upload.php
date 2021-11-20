<?php
    session_start();
    include_once 'connection.php';
    $uid = $_SESSION['userID'];
    //Allow file uploads
    if(isset($_POST['but_upload'])){
        $maxsize = 50242880; // 50MB
        if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != ''){
            //This creates the target directory that the files will be stored in
            $name = $_FILES['file']['name'];
            $target_dir = "media/";
            $target_file = $target_dir . $_FILES["file"]["name"];

            // Select file type
            $extension = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            // Valid file extensions
            $extensions_arr = array("mp4","avi","3gp","mov","mpeg","mp3","jpg","png");

            // Check extension
            if( in_array($extension,$extensions_arr) ){

               // Check file size
               $size = $_FILES['file']['size'];
               if(($_FILES['file']['size'] >= $maxsize) || ($_FILES["file"]["size"] == 0)) {
                  $_SESSION['message'] = "File too large. File must be less than 5MB.";
               }else{
                  // Uploads the files from their computer into the target directory
                  if(move_uploaded_file($_FILES['file']['tmp_name'],$target_file)){
                    
                    //Determine type
                    $vid = array("mp4","avi","3gp","mov","mpeg");
                    $aud = array("mp3");
                    $img = array("jpg","png");

                    if (in_array($extension,$vid)){
                        $type = "video";
                    }elseif (in_array($extension,$aud)){
                        $type = "audio";
                    }elseif (in_array($extension, $img)){
                        $type = "image";
                    }

                    // Insert record.
                    $desc = $_POST['desc'];
                    $key = $_POST['key'];
                    $query = "INSERT INTO media (userID, title, loc, type, ext, data_size, description, keyword) VALUES('$uid', '$name', '$target_file', '$type', '$extension', '$size', '$desc', '$key')";
     
                    mysqli_query($conn,$query);
                    $_SESSION['message'] = "Upload successfully.";
                  }
               }

            }else{
               $_SESSION['message'] = "Invalid file extension.";
            }
        }else{
            $_SESSION['message'] = "Please select a file.";
        }
        header('location: user_profile.php');
        exit;
     }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>MeTube<3</title>
    </head>
    <body>
        <!-- Upload response -->
        <?php
        if(isset($_SESSION['message'])){
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        }
        echo $_SESSION['userID'];
        ?>
        <form method="POST" action="" enctype='multipart/form-data'>
            <label for="user_file">Select a file:</label>
            <input type="file" name="file">
            <input type="text" name="desc" placeholder="Enter a description">
            <input type="text" name="key" placeholder="Enter a single keyword">
            <input type="submit" value='Upload' name='but_upload'>
        </form>

    </body>
</html>
