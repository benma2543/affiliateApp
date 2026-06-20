<?php
session_start();
require_once("config.php");
require_once("user/protect.php");
require_once("user/user-lib.php");

$db = new PDO(
  "mysql:host=".USER_DB_HOST.";dbname=".USER_DB_NAME.";charset=".USER_DB_CHARSET,
  USER_DB_USER, USER_DB_PASSWORD, [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$sql = "UPDATE users 
		SET adnumber = CASE 
					   WHEN adnumber = 5 THEN 1
					   ELSE adnumber + 1
					   END
		WHERE user_id = :userid";

$stmt = $db->prepare($sql);
$stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
$stmt->execute();

unset($_SESSION["user".$l1]);
header("Location: ".$logoutredirect);
?>