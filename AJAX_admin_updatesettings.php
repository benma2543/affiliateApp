<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("config.php");
if (!isset($_SESSION["adminuser".$l1]) || !isset($_POST)){
	header("location:adminlogin.php");
}

if (!isset($opt)){$new_opt="";}else{$new_opt=$opt;}
if (!isset($ainame)){$new_ainame="gpt-3.5-turbo";}else{$new_ainame=trim(urldecode($_POST["ainame"]));}
$new_termslink=trim(urldecode($_POST["termslink"]));
$new_supportlink=trim(urldecode($_POST["supportlink"]));
$new_supportemail=trim(urldecode($_POST["supportemail"]));
$new_logoutredirect=trim(urldecode($_POST["logoutredirect"]));
$new_adminuser=trim(urldecode($_POST["adminuser"]));
$new_adminpassword=trim(urldecode($_POST["adminpassword"]));
$new_masterkeymode=trim(urldecode($_POST["masterkeymode"]));
$new_masterapikey=trim(urldecode($_POST["masterapikey"]));
$new_aiengine=trim(urldecode($_POST["aiengine"]));
$new_wpluskey=trim(urldecode($_POST["wpluskey"]));
$new_jvzkey=trim(urldecode($_POST["jvzkey"]));
$new_initial_credits=trim(urldecode($_POST["initial_credits"]));
$new_toggleCredits=trim(urldecode($_POST["toggleCredits"]));
$new_showreg=trim(urldecode($_POST["showreg"]));
$new_menucolor=trim(urldecode($_POST["menucolor"]));


file_put_contents("add2.php",'<?php $add2='.$new_toggleCredits.'; ?>');

$menucolors=["bg-gradient-primary","bg-gradient-secondary","bg-gradient-success","bg-gradient-info","bg-gradient-warning","bg-gradient-danger","bg-gradient-dark","bg-gradient-light"];
$menucolor=$menucolors[$new_menucolor];
file_put_contents("menucolor.php","<?php \$menucolor='$menucolor'; \$menucolorselect='$new_menucolor'; ?>");

if ($new_initial_credits==""){$new_initial_credits="0";}
$status=1;

if ($new_adminuser=="" || $new_adminpassword=="") { 
	$status=0; 
} else {
	if (($new_adminuser!=$adminuser) || ($new_adminpassword!=$adminpassword)){
		if ($status!=0){
			$status=2;
		}
	}
}


if ($status>0) {
	// write config file
	$user_db_host=USER_DB_HOST;
	$user_db_name=USER_DB_NAME;
	$user_db_user=USER_DB_USER;
	$user_db_password=USER_DB_PASSWORD;
	


$configout=<<<EOT
<?php
\$version='1.0';
\$sitename='$sitename';
\$termslink='$new_termslink';
\$supportlink='$new_supportlink';
\$supportemail='$new_supportemail';
\$l1='$l1';
\$l2='$l2';
\$logo='img/logo.png';
\$masterkeymode=$new_masterkeymode;
\$masterapikey='$new_masterapikey';
\$logoutredirect='$new_logoutredirect';
\$adminuser='$new_adminuser';
\$adminpassword='$new_adminpassword';
\$aiengine='$new_aiengine';
\$wpluskey='$new_wpluskey';
\$jvzkey='$new_jvzkey';
\$showreg='$new_showreg';
\$initial_credits='$new_initial_credits';
\$supportlinkcode='';
if (\$supportlink!=''){\$supportlinkcode.='Support Link : Click <a href="'.\$supportlink.'" target="_BLANK">HERE</a>';}
if (\$supportlink!='' && \$supportemail!=''){\$supportlinkcode.=' - ';}
if (\$supportemail!=''){\$supportlinkcode.='Support Email : <a href="mailto:'.\$supportemail.'">'.\$supportemail.'</a>';}
\$termscode='';
if(\$termslink!=''){\$termscode='- For terms click <a href="'.\$termslink.'" target="_BLANK">HERE</a>';}
\$y=date("Y");

\$footer=<<<EOTFOOTER
<div style="color:#777; font:size:14px; display:block; float:left;">(C)\$y \$sitename \$termscode</div><div style="color:#777; float:right">\$supportlinkcode</div>
EOTFOOTER;

\$ainame='$new_ainame';
\$opt='opt0.php';

define('USER_DB_HOST', '$user_db_host');
define('USER_DB_NAME', '$user_db_name');
define('USER_DB_CHARSET', 'utf8mb4');
define('USER_DB_USER', '$user_db_user');
define('USER_DB_PASSWORD', '$user_db_password');
?>
EOT;

	
	file_put_contents("config.php",$configout);
	
	try {
		$pdo = new PDO("mysql:host=" . USER_DB_HOST . ";dbname=" . USER_DB_NAME . ";charset=" . USER_DB_CHARSET, USER_DB_USER, USER_DB_PASSWORD);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = "ALTER TABLE users MODIFY user_credits INT DEFAULT ?";
		$stmt = $pdo->prepare($query);
		$stmt->execute([$new_initial_credits]);
	} catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	$pdo = null;	
	
}

// Status 0: admin user or pass not entered - Status 1: field changed and saved - Status 2: admin user or password changed so logout

if ($status==1){
	echo("OK");
	exit();
} else if ($status==0) {
	echo("ERROR_ADMINCREDS");
	exit();
} else if ($status==2) {
	echo("ADMINCHANGE");
}



?>
