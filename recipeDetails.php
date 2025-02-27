<!DOCTYPE html>
<html>
<head>
    <title>Recipe Management</title>
    <link rel="stylesheet" href="../style/index.css">
    <link rel="stylesheet" href="../style/recipeDetails.css">
</head>
<style>
                body {
                    background-color: #2b2b2b;
                    color: #a9b7c6;
                }
                .rating .star {
                    color: #ffc107;
                }
                textarea, button {
                    background-color: #3c3f41;
                    color: #a9b7c6;
                    border: none;
                }
                button {
                    padding: 10px;
                }

            </style>
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
        if(isset($_SESSION['username']) && $_SESSION['username'] != ''){
            echo "<a href='logout.php'>Logout</a>";
        } else {
            echo "<a href='../html/login.html'>Login</a> |";
            echo "<a href='../html/register.html'>Register</a>";
        }
        echo "</nav>";
        ?>
    </header>

    <div class="container">
        <div class="recipe-details">
            <?php
                if(isset($_POST['recipeName'])) { // Check if $_POST['recipeName'] is set
// Escape the input to prevent SQL injection => sanitize the RECIPENAME
                    $recipeName = mysqli_real_escape_string($connection, $_POST['recipeName']); 
        #details of the RECIPE is being retrieved
                    $sql = "SELECT * FROM RECIPE WHERE recipeName = '$recipeName'";
                    $result = mysqli_query($connection, $sql);
                    while($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="recipe-title">
                <h3><?php echo $row['recipeName']; ?></h3>
                <span class="average-rating">Average Rating: <?php echo $row['avgRating']; ?>/5</span>
            </div>
            <div class="recipe-image">
                <img src="../assets/<?php echo $row['recipeName']; ?>.jpg" alt="Recipe Image">
            </div>
            <div class="recipe-ingredients">
                <h4>Ingredients (for <span id='serve'></span>) :</h4>
                <ul>
                    <?php
        #all the rows from the INGREDIENTS table is retrieved
                        $q1 = "SELECT * FROM INGREDIENTS WHERE recipeID = $row[recipeID]";
                        $r1 = mysqli_query($connection, $q1);
                        while($row1 = mysqli_fetch_assoc($r1)) {
                            echo "<li>$row1[ingredientName] - $row1[quantity]</li>";
                        }
                    ?>
                </ul>
            </div>
            <div class="prep-time">
                <h4>Cooking Time: <?php echo $row['prepTime']; ?></h4>
            </div>
            <div class="recipe-instructions">
                <h4>Instructions:</h4>
                <ol>
                <?php
                #rows from the PROCEDURES table is RETRIEVED based on the STEPNUM
                        $q2 = "SELECT * FROM PROCEDURES WHERE recipeID = $row[recipeID] ORDER BY stepNum";
                        $r2 = mysqli_query($connection, $q2);
                        while($row2 = mysqli_fetch_assoc($r2)) {
                            if($row2['stepNum'] == 0){
                # retrieves the PROCEDURE for the recipe, ordered by STEPNUM.
                                echo "<script> document.getElementById('serve').innerHTML = '$row2[stepDetails]'; </script>";
                            }else{
                                echo "<li>$row2[stepDetails]</li>";
                            }
                        }
                    ?>
                </ol>
            </div>

            <form method="post" action="review.php">
                <input type="hidden" name="recipeID" value="<?php echo $row['recipeID']; ?>">
                <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
                <input type="hidden" name="rating" value="">
        <label for="review" style="padding-right:10px;">Your Review:</label>
        <textarea id="review" name="review" rows="2" required></textarea>

        <label for="rating" style="padding-left:10px;">Your Rating:</label>
        <div id="rating" class="rating">
            <span class="star" onclick="rate(1)">&#9733;</span>
            <span class="star" onclick="rate(2)">&#9733;</span>
            <span class="star" onclick="rate(3)">&#9733;</span>
            <span class="star" onclick="rate(4)">&#9733;</span>
            <span class="star" onclick="rate(5)">&#9733;</span>
        </div>

        <button type="submit" style="margin-left:10px;">Submit Review</button>
    </form>
            <?php
                    }
                } else {
                    echo "<p>No recipe selected.</p>"; // Display a message if $_POST['recipeName'] is not set
                }
            ?>
        </div>
    </div>
   
    <script>
    let selectedRating = 0;

    function rate(rating) {
//Updates the selected rating and highlights the corresponding stars
        selectedRating = rating;
        highlightStars();
        document.querySelector('input[name="rating"]').value = rating;
    }

    function highlightStars() {
//Toggles the active class for stars based on the selected rating
        const stars = document.querySelectorAll('.star');
        stars.forEach((star, index) => {
            star.classList.toggle('active', index < selectedRating);
        });
    }
</script>

    <footer>
        &copy; <?php echo date("Y"); ?> Recipe Management. All rights reserved.
    </footer>
</body>
</html>

