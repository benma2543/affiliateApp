<?php
header('Access-Control-Allow-Origin: same-origin');
session_start();
require_once("user/protect.php");
require_once("user/user-lib.php");

if (isset($_POST['apikey']) && trim($_POST['apikey'])!=""){
	
	$apikey=$_POST['apikey'];
	if (checkKey($apikey)==true){
		$USR->saveapi($apikey,$_SESSION["user"]["user_id"]);
		echo("saved");
	} else {
		echo("invalid");
	}
	
} else {
	echo("nokey");
}
exit();

function checkKey($key){

	$url = "https://api.openai.com/v1/models";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Content-Type: application/json",
		"Authorization: Bearer ".$key
	));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($ch);
	$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	if($http_status == 200) {
		curl_close($ch);	
		return true;
	} else {
		curl_close($ch);	
		return false;
	}

	
	
}

?>