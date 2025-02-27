<?php

// MySQL database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "recipe";

// Create a connection to the MySQL database
$connection =  new mysqli($host, $username, $password, $database);

// Check if the connection was successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set the character set for the connection
mysqli_set_charset($connection, "utf8");

// Other MySQL configuration settings...

?>
