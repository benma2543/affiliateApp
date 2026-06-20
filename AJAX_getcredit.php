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

$creditid = trim($_POST["id"]);

try {
    $query = "SELECT user_credits FROM users WHERE user_id = :userid";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':userid', $creditid, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo $result['user_credits'];
		exit();
    } else {
        echo "ERROR";
    }

} catch(PDOException $e) {
    echo "Error getting credits left";
}




?>
