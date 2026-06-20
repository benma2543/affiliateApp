<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("config.php");
if (!isset($_SESSION["adminuser".$l1]) || !isset($_POST)){
	header("location:adminlogin.php");
}
if(isset($_GET['filepath'])) {
    $filepath = $_GET['filepath'];
    
    if(file_exists($filepath)) {
        echo "exists";
    } else {
        echo "not_exists";
    }
} else {
    echo "error";
}
?>
