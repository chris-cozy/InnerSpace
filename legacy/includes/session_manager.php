<?php
class SessionManager
{
    public static function startSession()
    {
        session_start();
    }

    public static function destroySession()
    {
        session_destroy();
    }

    // Implement methods for user authentication, session checks, etc. if needed
}
