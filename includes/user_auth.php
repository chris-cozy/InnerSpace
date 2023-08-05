<?php
class UserAuth
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getUserData($userID)
    {
        $query = mysqli_prepare($this->conn, "SELECT username FROM user_info WHERE userID = ?");
        mysqli_stmt_bind_param($query, "i", $userID);
        mysqli_stmt_execute($query);
        $result = mysqli_stmt_get_result($query);
        $userData = mysqli_fetch_assoc($result);
        mysqli_stmt_close($query);
        return $userData;
    }
}
