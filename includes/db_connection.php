<?php
// Replace the following database credentials with your actual database details
$hostname = 'localhost';
$username = 'your_database_username';
$password = 'your_database_password';
$db_name = 'your_database_name';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$hostname;dbname=$db_name", $username, $password);

    // Set PDO error mode to exception to handle errors gracefully
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Set the default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Optionally, you can set character encoding to handle non-English characters
    $pdo->exec("SET NAMES 'utf8mb4'");

    // If the connection is successful, you can include this file in other PHP scripts to access the database.
} catch (PDOException $e) {
    // If there is an error in the connection, handle it gracefully.
    die("Database connection failed: " . $e->getMessage());
}
