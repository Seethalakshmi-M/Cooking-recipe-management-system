<?php
require "config.php";

if(!isset($_SESSION)){
    session_start();
}

// Get the user input from the login form
$username = $_POST['username'];
$password = $_POST['password'];

// Query the database for the user info
$query = "SELECT isAdmin FROM LOGIN WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($connection, $query);


// Bind the result of the query to variables
if ($result && mysqli_num_rows($result) > 0) {
    list($isAdmin) = mysqli_fetch_array($result);

    // User authentication successful
    $_SESSION["username"] = $username;
    $_SESSION["isAdmin"] = $isAdmin;
    header("Location: ../");
} else {

    // User authentication failed
    $_SESSION["logIN"] = 'FAILED';
    header("Location: ../");
}

// Close the database connection
mysqli_close($connection);

?>

