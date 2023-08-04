<?php
class MediaManager
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getRecentMedia($type, $limit)
    {
        // Implement media retrieval queries based on type and limit
        // Return an array of media items
        $query = mysqli_query($this->conn, "SELECT * FROM media WHERE type = $type ORDER BY mediaID DESC;") or die("Query error" . mysqli_error($this->conn) . "\n");

        $i = 0;
        $results = mysqli_num_rows($query);
        if ($results > 0) {
            do {
                $row = mysqli_fetch_assoc($query);
                if (isset($row['loc'])) {
                    $location = "profile/" . $row['loc'];
                    $name = $row['title'];
                    $mediaID = $row['mediaID'];
                    displayMedia($type, $location, $mediaID, $name);
                }
                $i++;
            } while ($row && $i < $limit);
        }
    }

    public function displayMedia($type, $location, $mediaID, $name)
    {
        switch ($type) {
            case "video":
                echo "<span style= 'display: inline-block;'>
                        <video src='" . $location . "' controls width='200px' class='content'>This video could not be displayed :/</video>
                        <br>
                        <span><a href='media_content.php?mediaID=" . $mediaID . "' class='text'>" . $name . "</a></span>
                        </span>";
                break;
            case "audio":
                echo "<span style= 'display: inline-block;'>
                        <audio src='" . $location . "' controls type='audio/mpeg' class='content'>This audio could not be displayed :/</audio>
                        <br>
                        <span><a href='media_content.php?mediaID=" . $mediaID . "' class='text'>" . $name . "</a></span>
                        </span>";
                break;
            case "image":
                echo "<span style= 'display: inline-block;'>
                        <img src='" . $location . "' width='200' class='content' alt='This image could not be displayed :/'/>
                        <br>
                        <span><a href='media_content.php?mediaID=" . $mediaID . "' class='text'>" . $name . "</a></span>
                        </span>";
                break;
            case "in-video":
                echo "<span style= 'display: inline-block;'>
                        <video src='" . $location . "' controls width='200px' class='content' >This video could not be displayed :/</video>
                        <br>
                        <span><a href='../media_content.php?mediaID=" . $mediaID . "' class='text'>" . $name . "</a></span>
                        </span>";
                break;
            case "in-audio":
                echo "<span style= 'display: inline-block;'>
                        <audio src='" . $location . "' controls type='audio/mpeg' class='content'>This audio could not be displayed :/</audio>
                        <br>
                        <span><a href='../media_content.php?mediaID=" . $mediaID . "' class='text'>" . $name . "</a></span>
                        </span>";
                break;
            case "in-image":
                echo "<span style= 'display: inline-block;'>
                        <img src='" . $location . "' width='200' class='content' alt='This image could not be displayed :/'/>
                        <br>
                        <span><a href='../media_content.php?mediaID=" . $mediaID . "' class='text'>" . $name . "</a></span>
                        </span>";
                break;
        }
    }

    // Add more methods for media handling, e.g., media uploads, media retrieval by user, etc.
}
