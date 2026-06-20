<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("config.php");
require_once("user/protect.php");
require_once("user/user-lib.php");

$db = new PDO(
      "mysql:host=".USER_DB_HOST.";dbname=".USER_DB_NAME.";charset=".USER_DB_CHARSET,
      USER_DB_USER, USER_DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

$id = intval($_POST["id"]); 
if ($id!=""){
	$sql = "DELETE FROM projects WHERE user_id = :user_id AND id = :id";
	$statement = $db->prepare($sql);
	$statement->bindParam(':user_id', $userid, PDO::PARAM_INT);
	$statement->bindParam(':id', $id, PDO::PARAM_INT);
	$statement->execute();
	$affectedRows = $statement->rowCount();
	if ($affectedRows > 0) {
		echo $id;
	} else {
		echo "ERROR";
	}
}





?>
