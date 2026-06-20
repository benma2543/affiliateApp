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
if ($id !== "") {

    $db->beginTransaction();

    try {
        $deleteSql = "DELETE FROM categories WHERE cat_userid = :user_id AND cat_id = :id";
        $deleteStatement = $db->prepare($deleteSql);
        $deleteStatement->bindParam(':user_id', $userid, PDO::PARAM_INT);
        $deleteStatement->bindParam(':id', $id, PDO::PARAM_INT);
        $deleteStatement->execute();
        $affectedRows = $deleteStatement->rowCount();

        if ($affectedRows > 0) {
            $updateSql = "UPDATE projects SET category = 0 WHERE category = :id AND user_id = :user_id";
            $updateStatement = $db->prepare($updateSql);
            $updateStatement->bindParam(':id', $id, PDO::PARAM_INT);
            $updateStatement->bindParam(':user_id', $userid, PDO::PARAM_INT);
            $updateStatement->execute();
            $updateAffectedRows = $updateStatement->rowCount();

            $db->commit();
            echo $id;
        } else {
            echo "ERROR";
        }
    } catch (PDOException $e) {
        $db->rollBack();
        echo "ERROR";
    }
}


?>
