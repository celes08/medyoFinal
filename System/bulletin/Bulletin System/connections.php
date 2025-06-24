<?php
// connections.php

// Database connection parameters
$db_host = "localhost"; // The database host (usually 'localhost' for XAMPP)
$db_port = 3306;        // MySQL port (default is 3306)
$db_user = "root";      // Your MySQL username (default for XAMPP is 'root')
$db_pass = "";          // Your MySQL password (default for 'root' in XAMPP is an empty string '')
$db_name = "cvsu_bulletin_system_db"; // *** IMPORTANT: Replace with the exact name of YOUR DATABASE ***

// Attempt to establish a connection to the MySQL database
// The mysqli_connect function takes five main arguments:
// 1. Hostname
// 2. Username
// 3. Password
// 4. Database Name
// 5. Port (optional)
$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);

// Check if the connection failed
if (!$con) {
    // If connection fails, stop the script and display a detailed error message
    // mysqli_connect_error() provides the specific reason for the connection failure
    die("Failed to connect to database: " . mysqli_connect_error() . 
        "<br><br>Please ensure that:<br>" .
        "1. XAMPP is running<br>" .
        "2. MySQL service is started in XAMPP Control Panel<br>" .
        "3. The database '" . $db_name . "' exists<br>" .
        "4. The credentials are correct");
}

// Set the character set for the connection to UTF-8 (highly recommended)
// This helps prevent issues with special characters and international text.
$con->set_charset("utf8mb4");

// At this point, if the script hasn't died, the connection is successful.
// The database connection object is now stored in the variable $con.
// Other PHP files (like index.php, signup.php, login.php) can now include this file
// and use the $con variable to interact with the database.
?>