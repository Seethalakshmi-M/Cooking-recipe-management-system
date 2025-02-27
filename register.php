<?php
require "config.php";
// Prepare and execute the insertion query
try {
    $stmt = $connection->prepare("INSERT INTO `LOGIN`(`username`, `password`, `isAdmin`) VALUES (?,?,0)");
    $stmt->bind_param("ss", $_POST['username'], $_POST['password']);
    // Execute the query
    $stmt->execute();

    header("Location: ../html/login.html");
} catch(Exception $e) {
    if ($e->getCode() == 1062) {
        header("Location: ../html/login.html");
    } else {
        header("Location: ../");
    }
}

// Close the database connection
$connection = null;

?>
