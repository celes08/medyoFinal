<?php
// test_connection.php - Simple database connection test

echo "<h2>Database Connection Test</h2>";

// Test 1: Check if MySQL extension is loaded
echo "<h3>1. MySQL Extension Check</h3>";
if (extension_loaded('mysqli')) {
    echo "✅ mysqli extension is loaded<br>";
} else {
    echo "❌ mysqli extension is NOT loaded<br>";
    die("Please enable mysqli extension in php.ini");
}

// Test 2: Try to connect to MySQL server (without specifying database)
echo "<h3>2. MySQL Server Connection Test</h3>";
$test_con = mysqli_connect("localhost", "root", "", "", 3306);
if ($test_con) {
    echo "✅ Successfully connected to MySQL server<br>";
    mysqli_close($test_con);
} else {
    echo "❌ Failed to connect to MySQL server: " . mysqli_connect_error() . "<br>";
    echo "<strong>Solution:</strong> Start MySQL service in XAMPP Control Panel<br>";
}

// Test 3: Check if database exists
echo "<h3>3. Database Existence Check</h3>";
$test_con = mysqli_connect("localhost", "root", "", "", 3306);
if ($test_con) {
    $result = mysqli_query($test_con, "SHOW DATABASES LIKE 'cvsu_bulletin_system_db'");
    if (mysqli_num_rows($result) > 0) {
        echo "✅ Database 'cvsu_bulletin_system_db' exists<br>";
    } else {
        echo "❌ Database 'cvsu_bulletin_system_db' does NOT exist<br>";
        echo "<strong>Solution:</strong> Create the database or import the SQL file<br>";
    }
    mysqli_close($test_con);
}

// Test 4: Try full connection with database
echo "<h3>4. Full Database Connection Test</h3>";
include("connections.php");
if (isset($con) && $con) {
    echo "✅ Successfully connected to database 'cvsu_bulletin_system_db'<br>";
    
    // Test 5: Check if tables exist
    echo "<h3>5. Tables Check</h3>";
    $tables = ['signuptbl', 'posts', 'post_likes', 'post_comments', 'post_bookmarks', 'post_views', 'notifications'];
    foreach ($tables as $table) {
        $result = mysqli_query($con, "SHOW TABLES LIKE '$table'");
        if (mysqli_num_rows($result) > 0) {
            echo "✅ Table '$table' exists<br>";
        } else {
            echo "❌ Table '$table' does NOT exist<br>";
        }
    }
} else {
    echo "❌ Failed to connect to database<br>";
}

echo "<br><hr>";
echo "<h3>Next Steps:</h3>";
echo "1. If MySQL server connection failed: Start MySQL in XAMPP Control Panel<br>";
echo "2. If database doesn't exist: Create it or import the SQL file<br>";
echo "3. If tables don't exist: Import the database schema<br>";
echo "4. If all tests pass: The connection should work in your main application<br>";
?> 