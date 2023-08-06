<?php
require_once '../vendor/autoload.php';

// Specify the path to the .env file
$dotenvFilePath = __DIR__ . '/.env';

// Check if the .env file exists before attempting to load it
if (file_exists($dotenvFilePath)) {
    // Load the .env file
    $envVars = parse_ini_file($dotenvFilePath, false, INI_SCANNER_RAW);

    // Set the environment variables
    foreach ($envVars as $key => $value) {
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
} else {
    die(".env file not found. Make sure it exists in the root directory of your project.");
}
// Replace the following database credentials with your actual database details
$hostname = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$db_name = getenv('DB_NAME');
$db_port = getenv('DB_PORT');
$charset = 'utf8';

try {
    $dsn = "mysql:host={$hostname};port={$db_port};dbname={$db_name};charset={$charset}";
    // Create a new PDO instance
    $pdo = new PDO($dsn, $username, $password);

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
