<?php
require_once "config.php";
if(!isset($_SESSION)) {
    session_start();
}
#checks whether the recipeTitle is set or not
if(isset($_POST['recipeTitle'])) {
    #extracts the form data
    $recipeName = $_POST['recipeTitle'];
    $recipeCategory = $_POST['category'];
    $recipeIngredients = $_POST['ingredient'];
    $ingredientQuantity = $_POST['quantity'];
    $recipeInstructions =  $_POST['procedure']; 
    $recipePrepTime = $_POST['prepTime'];
    $servings = $_POST['servings'];
    $username = $_SESSION['username']; 

#gives a unique RECIPE ID 
    $recipeID = crc32($recipeName) & 0x7FFFFFFF;
#queries the category table
    $stmt = $connection->prepare("SELECT `categoryID` FROM `CATEGORY` WHERE `categoryName` = ?");
    $stmt->bind_param("s", $recipeCategory);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $categoryID = $row['categoryID'];
    } else {
        echo "Category not found.";
    }
#recipe details are inserted into RECIPE TABLE  
    $stmt = $connection->prepare("INSERT INTO `RECIPE`(`recipeID`, `categoryID`, `recipeName`, `prepTime`) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $recipeID, $categoryID, $recipeName, $recipePrepTime);
    if($stmt->execute()) {

        // Assuming $recipeIngredients and $ingredientQuantity are arrays of equal length
        for($i = 0; $i < count($recipeIngredients); $i++) {
            $ingredient = $recipeIngredients[$i];
            $quantity = $ingredientQuantity[$i];

            $stmt = $connection->prepare("INSERT INTO `INGREDIENTS`(`ingredientName`, `quantity`, `recipeID`) VALUES (?,?,?)");
            $stmt->bind_param("sss", $ingredient, $quantity, $recipeID);
            if(!$stmt->execute()) {
                echo "Error adding ingredient: " . $connection->error;
            }
        }

        // Assuming $recipeInstructions is an array
        for($i = 0; $i < count($recipeInstructions); $i++) {
            $stepDetails = $i == 0 ? $servings . " servings " : $recipeInstructions[$i];
            $stepNum =  $i;
#Loops through the recipeInstructions array and inserts each step into the PROCEDURES table
            $stmt = $connection->prepare("INSERT INTO `PROCEDURES`(`stepNum`, `stepDetails`, `recipeID`) VALUES (?,?,?)");
            $stmt->bind_param("sss", $stepNum, $stepDetails, $recipeID);
            if(!$stmt->execute()) {
                echo "Error adding procedure: " . $connection->error;
            }
        }
        $_SESSION['categoryName'] = $recipeCategory;
#If all insertions are successful, the user is redirected to listRecipe.php
        header("Location: listRecipe.php");
    } else {
        echo "Error adding recipe: " . $connection->error;
    }
    $stmt->close();
} else {
    echo "Missing recipeName, recipeDescription, recipeIngredients, or recipeInstructions.";
}
?>