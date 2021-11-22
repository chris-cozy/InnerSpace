<?php
    session_start();
    include_once 'connection.php';

    $uid = $_SESSION['userID'];
    $mediaID = $_SESSION['curmediaID'];
    echo $mediaID;

    if(isset($_POST['sub_com'])){
        $comment = $_POST['comment'];
        $query = "INSERT INTO media_comments (mediaID, comment, userID) VALUES ('$mediaID', '$comment', '$uid');";
        mysqli_query($conn,$query);
    }

    header("Location: media_content.php?mediaID=$mediaID");
?>
