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

$newcatname = trim($_POST["newcatname"]);
$newcatname = preg_replace('/[^A-Za-z0-9\s\-!\[\]{}()*?#&+_\-=]/', '', $newcatname);

if ($newcatname!=""){
	$currentDateTime = date('Y-m-d H:i:s');
	$stmt = $db->prepare("INSERT INTO categories (cat_userid, cat_name, cat_created) VALUES (:userid, :catname, :created)");
	$stmt->bindParam(':userid', $userid);
	$stmt->bindParam(':catname', $newcatname);
	$stmt->bindParam(':created', $currentDateTime);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		$catId = $db->lastInsertId();
		$response = array(
			'cat_id' => $catId,
			'cat_name' => $newcatname,
			'cat_created' => $currentDateTime
		);
		echo json_encode($response);
	} else {
		echo "ERROR";
	}
}





?>
