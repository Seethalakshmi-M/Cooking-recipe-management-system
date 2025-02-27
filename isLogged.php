<?php
require "config.php";

if(!isset($_SESSION)){
    session_start();
}

$_SESSION['categoryName'] = $_POST['categoryName'];

if ($_SESSION['username']!='') {
    header("Location: listRecipe.php");
} else {
    header("Location: ../html/login.html");
}
?>