<?php

// written by Cong Zhang

session_start();
if (empty($_SESSION['email']) ){
    header("Location: login.php");
    die();
}else{
    header("Location: homescreen.php");
    die();
}
?>