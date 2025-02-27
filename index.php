
<!DOCTYPE html>
<html>
<head>
    <title>Recipe Management</title>
    <link rel="stylesheet" href='style/index.css'>
</head>
<?php
// Include the configuration file
require "php/config.php";
if(!isset($_SESSION)){
    session_start();
}
if(isset($_SESSION['logIN']) && $_SESSION['logIN'] == 'FAILED'){
    echo "<script>alert('Invalid username or password.')</script>";
    $_SESSION['logIN'] = '';
}
?>
<body>
    <header>
        <h1>Cooking Recipe Management</h1>
        <?php 
        echo "<nav>";

        #checks whether the user is already LOGGED IN
        #if LOGGED IN then displays LOGOUT/ADDRECIPE
        if(isset($_SESSION['username']) && $_SESSION['username'] != ''){
            echo "<a href='php/logout.php'>Logout</a> |";
            echo "<a href='html/addRecipe.html'>Add Recipe</a>";
        } else {

            #if not LOGGED IN then redirects to the login and register page
            echo "<a href='html/login.html'>Login</a> |";
            echo "<a href='html/register.html'>Register</a>";
        }
        echo "</nav>";
        ?>
    </header>

    <div class="container">
            <?php
                $sql = "SELECT * FROM CATEGORY";
#connects with the database and RETRIVES ALL THE CATEGORIES
                $result = mysqli_query($connection, $sql);

#if rows are found then displays the categories PICTURE/IMAGE as a CLICKABLE BUTTON
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        #the form is sent to isloggedin.php which HANDLES THE USER AUTHENTICATION AND redirects to the appropriate CATEGORY PAGE
                        echo "<form method='post' action='php/isLogged.php'>";
                        echo "<input type='hidden' name='categoryName' value='". $row['categoryName'] ."'>";
                        echo "<button class='recipe' type='submit'>";
                    
                        echo "<img src='assets/" . $row['categoryName'] . ".jpg' />";
                        echo "<h2 class='recipe-title'>" . $row["categoryName"] . "</h2>";
                        echo "</button>";
                        echo "</form>";
                    }
                } else {
                    echo "<button class='recipe' >";
                    echo "<h2 class='recipe-title'>No categories found.</h2>";
                    echo "</button>";
                }
            ?>
    </div>

    <footer>
        <p>&copy; 2024 Recipe Management. All rights reserved.</p>
    </footer>
</body>
</html>