<?php
session_start();
require_once("config.php");
require_once("user/user-lib.php");
if (!isset($_SESSION["adminuser".$l1])){
	header("location:adminlogin.php");
}

$credits=$_POST['credits'];

try {
 
    $pdo = new PDO("mysql:host=" . USER_DB_HOST . ";dbname=" . USER_DB_NAME . ";charset=" . USER_DB_CHARSET, USER_DB_USER, USER_DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("UPDATE users SET user_credits = user_credits + :credits");
    $stmt->bindParam(':credits', $credits, PDO::PARAM_INT);
    $stmt->execute();

    echo $credits;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>