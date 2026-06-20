<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("config.php");
if (!isset($_SESSION["adminuser".$l1]) || !isset($_POST)){
	header("location:adminlogin.php");
}

for ($i = 1; $i <= 5; $i++) {
    $varName = "imglink" . $i;
    $$varName = trim(urldecode($_POST[$varName]));
}

for ($i = 1; $i <= 5; $i++) {
    $varName = "menulink" . $i;
    $$varName = trim(urldecode($_POST[$varName]));
}

for ($i = 1; $i <= 5; $i++) {
    $varName = "menutext" . $i;
    $$varName = trim(urldecode($_POST[$varName]));
}


$configout=<<<EOT
<?php

\$imglink1='$imglink1';
\$imglink2='$imglink2';
\$imglink3='$imglink3';
\$imglink4='$imglink4';
\$imglink5='$imglink5';

?>
EOT;

	
file_put_contents("configimgads.php",$configout);
	
$configout=<<<EOT
<?php

\$menulink1='$menulink1';
\$menulink2='$menulink2';
\$menulink3='$menulink3';
\$menulink4='$menulink4';
\$menulink5='$menulink5';

\$menutext1='$menutext1';
\$menutext2='$menutext2';
\$menutext3='$menutext3';
\$menutext4='$menutext4';
\$menutext5='$menutext5';

?>
EOT;

	
file_put_contents("configmenuads.php",$configout);


echo "OK";
exit();


?>
