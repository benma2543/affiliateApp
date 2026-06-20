<?php
session_start();
require_once("config.php");
require_once("user/user-lib.php");
if (!isset($_SESSION["adminuser".$l1])){
	header("location:adminlogin.php");
}

$userid=$_POST['id'];
$password=$_POST['newpass'];
$USR->setpassword($userid,$password);
?>