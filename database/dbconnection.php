<?php
// Database credentials
$servername = "localhost";   // Usually localhost
$username = "root";          // XAMPP default
$password = "";              // XAMPP default is empty
$dbname = "wanderly_db";     // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set charset to utf8
$conn->set_charset("utf8");
?>
