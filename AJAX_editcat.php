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

$catName= trim($_POST["catName"]);
$catName = preg_replace('/[^A-Za-z0-9\s\-!\[\]{}()*?#&+_\-=]/', '', $catName);
$catId=$_POST["catId"];
if ($catName!=""){
	try {
		$stmt = $db->prepare("UPDATE categories SET cat_name = :cat_name WHERE cat_userid = :cat_userid AND cat_id = :cat_id");
		$stmt->bindParam(':cat_name', $catName);
		$stmt->bindParam(':cat_userid', $userid);
		$stmt->bindParam(':cat_id', $catId);
		$stmt->execute();

		echo "OK";
		exit();
		
	} catch(PDOException $e) {
		echo "ERROR";
		exit();
	}
}

?>
