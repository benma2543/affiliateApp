<?php
require_once('config.php');
if (file_exists('add3.php')) {require 'add3.php';}else{$add3=0;}
if ($_POST['WP_SECURITYKEY']==$wpluskey) {
	$email = trim($_POST['WP_BUYER_EMAIL']);
	$name = trim($_POST['WP_BUYER_NAME']);
	$event = trim($_POST['WP_ACTION']);
	$pass=getRandomPassword(10);
	$wptx=trim($_POST['WP_SALEID']);
	if ($event=="sale"){
		try {
			$dsn = "mysql:host=" . USER_DB_HOST . ";dbname=" . USER_DB_NAME . ";charset=" . USER_DB_CHARSET;
			$pdo = new PDO($dsn, USER_DB_USER, USER_DB_PASSWORD);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			die();
		}
		$nowtime=time();
		try {
			$insertSql = "
				INSERT INTO users (user_name, user_email, user_password, user_creation, user_lastlogin, user_logins, user_apikey, user_status, user_txid_wplus)
				VALUES (:name, :email, :pass, :nowtime, :nowtime, 0, '', 1, :wptx);
			";
			$stmt = $pdo->prepare($insertSql);
			$stmt->bindParam(':name', $name);
			$stmt->bindParam(':email', $email);
			$stmt->bindParam(':pass', password_hash($pass, PASSWORD_DEFAULT));
			$stmt->bindParam(':nowtime', $nowtime);
			$stmt->bindParam(':wptx', $wptx);
			$stmt->execute();
			
			$loginlink=getURL();
			$loginlink.="login.php";
			
			$message="Hi\r\n\r\nYour new account has been set up at ".$sitename." - Here are your login details : \r\n\r\nPassword: ".$pass."\r\n\r\nYou can use your email (".$email.") and this password to login at the following URL: ".$loginlink."\r\n\r\nMany Thanks.";
			$fromemail="noreply@".$_SERVER['SERVER_NAME'];
			$headers = array(
				'From' => $fromemail,
				'Reply-To' => $fromemail,
				'X-Mailer' => 'PHP/' . phpversion()
			);		 
			mail($email,"New account set up at ".$sitename,$message,$headers);	
			file_put_contents("TEMPMail.txt","MESSAGE: ".$message."\r\n\r\n From : ".$fromemail."\r\n\r\n TO: ".$email);
			if ($add3==1){
				$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
				$currentURL = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				$script2URL = dirname($currentURL)."/adminsendlead.php";
				$curldata = array(
					'name' => $name,
					'email' => $email,
				);
				$postFields = http_build_query($curldata);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $script2URL);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
				$response = curl_exec($ch);
				curl_close($ch);
			}			
			
		} catch (PDOException $e) {
			die();
		}
		$pdo = null;
	} else if ($event == "refund" || $event=="subscr_cancelled" || $event=="subscr_suspended") {
		try {
			$dsn = "mysql:host=" . USER_DB_HOST . ";dbname=" . USER_DB_NAME . ";charset=" . USER_DB_CHARSET;
			$pdo = new PDO($dsn, USER_DB_USER, USER_DB_PASSWORD);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			die();
		}
		try {
			$updateSql = "
				UPDATE users
				SET user_status = 0
				WHERE user_txid_wplus = :wptx;
			";
			$stmt = $pdo->prepare($updateSql);
			$stmt->bindParam(':wptx', $wptx);
			$stmt->execute();

		} catch (PDOException $e) {
			die();
		}
		$pdo = null;		
	}
}

function getRandomPassword($chars)
{
	$data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	return substr(str_shuffle($data), 0, $chars);
}

function getURL()
{
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $requestUri = $_SERVER['REQUEST_URI'];
    $path = rtrim(dirname($requestUri), '/') . '/';

    return $protocol . '://' . $host . $path;
}

?>