<!DOCTYPE html>
<html>
<head>
    <title>Recipe Management</title>
    <link rel="stylesheet" href='../style/index.css'>
</head>
<?php
// Include the configuration file
require "config.php";
if(!isset($_SESSION)){
    session_start();
}
?>
<body>
    <header>
    <h1 onclick="window.open('../','_self')">Cooking Recipe Management</h1>
    <?php 
        echo "<nav>";
#if user is logged in then displayed LOGOUT LINK IS DISPLAYED
        if(isset($_SESSION['username']) && $_SESSION['username'] != ''){
            echo "<a href='logout.php'>Logout</a>";
        } else {
#else LOGIN AND REGISTER BUTTONS ARE DISPLAYED
            echo "<a href='../html/login.html'>Login</a> |";
            echo "<a href='../html/register.html'>Register</a>";
        }
        echo "</nav>";
        ?>
    </header>

    <div class="container">
            <?php
#retrieves all the recipes of the CLICKED CATEGORY
                $sql = "SELECT * FROM RECIPE r, CATEGORY c WHERE r.categoryID = c.categoryID AND c.categoryName = '$_SESSION[categoryName]'";

                $result = mysqli_query($connection, $sql);
#if rows are found then RECIPEDETAILS.PHP => dispalys the recipes
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
#form is created to send the RECIPE NAME to the RECIPEDETAILS.PHP to display the recipes.
                        echo "<form method='post' action='recipeDetails.php'>";
                        echo "<input type='hidden' name='recipeName' value='". $row['recipeName'] ."'>";
                        echo "<button class='recipe' type='submit'>";
                #image as a button
                        echo "<img src='../assets/" . $row['recipeName'] . ".jpg' />";
                        echo "<h2 class='recipe-title'>" . $row["recipeName"] . "</h2>";
                        echo "</button>";
                        echo "</form>";
                    }
                } else {
                    echo "<button class='recipe' onclick=\"window.open('../','_self');\">";
                    echo "<h2 class='recipe-title'>No recipes found.</h2>";
                    echo "</button>";
                }
            ?>
    </div>

    <footer>
        <p>&copy; 2024 Recipe Management. All rights reserved.</p>
    </footer>
</body>
</html>