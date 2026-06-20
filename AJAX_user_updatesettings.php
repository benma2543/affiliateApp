<?php 
require_once("config.php");
require_once("user/protect.php");
require_once("user/user-lib.php");

$new_user_email = trim($_POST["email"]);
$new_user_api = trim($_POST["api"]);

$status=1;

$db = new PDO(
      "mysql:host=".USER_DB_HOST.";dbname=".USER_DB_NAME.";charset=".USER_DB_CHARSET,
      USER_DB_USER, USER_DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
	
try {
    
    $stmt = $db->prepare("SELECT user_email FROM users WHERE user_id = :userid");
    $stmt->bindParam(':userid', $userid);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        if ($result['user_email'] !== $new_user_email) {
            $status = 2;
        }
    } else {
        echo "ERROR";
        exit();
    }

    $sql = "UPDATE users SET user_email = :user_email, user_apikey = :user_api";
    
    $sql .= " WHERE user_id = :userid";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_email', $new_user_email);
    $stmt->bindParam(':user_api', $new_user_api);
    $stmt->bindParam(':userid', $userid);
    $stmt->execute();

} catch(PDOException $e) {
    echo "ERROR ".$e;
    die();
}

if ($status==1){
	echo("OK");
	exit();
} else if ($status==0) {
	echo("OTHER ERROR");
	exit();
} else if ($status==2) {
	echo("EMAILCHANGE");
}



?>
