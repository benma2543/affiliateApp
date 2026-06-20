<?php
@session_set_cookie_params(604800,"/");
session_start();
if (isset($_POST["logout"])) { unset($_SESSION["user".$l1]); }
if (!isset($_SESSION["user".$l1])) {
  header("Location: login.php");
	exit();
}

$userid=$_SESSION["user".$l1]["user_id"];
?>