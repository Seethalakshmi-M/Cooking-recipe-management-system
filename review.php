<?php
// Include the configuration file
require "config.php";

if(!isset($_SESSION)) {
    session_start();
}
// Check if the form has been submitted
if(isset($_POST['recipeID']) && isset($_POST['rating']) && isset($_POST['review'])) {
#recipeId, rating, review and username is retrieved using POST
    $recipeId = $_POST['recipeID'];
    $rating = $_POST['rating'];
    $review =  $_POST['review'];
    $username = $_SESSION['username'];

#inserting the review into the REVIEW table
    $stmt = $connection->prepare("INSERT INTO REVIEW (recipeId, username, rating, review) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $recipeId, $username, $rating, $review);

#once the data is inserted into the REVIEW table in the database, directed to listrecipe.php 
    if($stmt->execute()) {
        header("Location: listRecipe.php");
    } else {
        echo "Error submitting review: " . $connection->error;
    }

    $stmt->close();
} else {
    echo "Missing recipeId, rating, or review.";
}

// Close the database connection
$connection->close();
?>