<?php
// This will be your app's landing page
// You can include a welcome message or redirect users to the login or register page.
require_once __DIR__ . '/vendor/autoload.php';

// Load and parse .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Start the session to manage user authentication
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // User is logged in, redirect to the home page or dashboard
    header('Location: home.php');
    exit;
} else {
    // User is not logged in, redirect to the login or register page
    header('Location: login.php');
    exit;
}
