<?php
session_start();
require_once("config.php");
unset($_SESSION["adminuser".$l1]);
header("Location: adminlogin.php");
?>