<?php
// Define constants for database connection parameters
define('DB_HOST', 'localhost');  // Database host (local server)
define('DB_USER', 'root');       // Database username (default for XAMPP/WAMP)
define('DB_PASS', '');           // Database password (empty for local setup)
define('DB_NAME', 'school_management_system');  // Name of the database

try {
    // Create a new MySQLi connection object
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check if the connection failed and throw an exception
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set the character encoding to UTF-8 for proper handling of special characters
    $conn->set_charset("utf8");
    
    // Define the base URL of the application (used for links and redirects)
    define('BASE_URL', 'http://localhost/school_management_system/');
    
    // Start a secure session if one hasn't already been started
    if (session_status() === PHP_SESSION_NONE) {
        session_start([
            'cookie_lifetime' => 86400,      
            'cookie_secure'   => false,      
            'cookie_httponly' => true,       
            'use_strict_mode' => true        
        ]);
    }
} catch (Exception $e) {
    // If any error occurs, display a custom error message and stop the script
    die("Database connection error: " . $e->getMessage());
}
?>
