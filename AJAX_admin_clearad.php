<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("config.php");
if (!isset($_SESSION["adminuser".$l1]) || !isset($_POST)){
	header("location:adminlogin.php");
}
if(isset($_POST['adnum'])) {
    $adnum = $_POST['adnum'];
     if(file_exists("image_ads/".$adnum.".png")) {
        unlink("image_ads/".$adnum.".png");
    }
}
?>
