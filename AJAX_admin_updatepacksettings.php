<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("config.php");
if (!isset($_SESSION["adminuser".$l1]) || !isset($_POST)){
	header("location:adminlogin.php");
}

$wplus_pack_description_1=trim(urldecode($_POST["wplus_pack_description_1"]));
$wplus_pack_credits_1=trim(urldecode($_POST["wplus_pack_credits_1"]));
$wplus_pack_cart_url_1=trim(urldecode($_POST["wplus_pack_cart_url_1"]));
$wplus_pack_product_id_1=trim(urldecode($_POST["wplus_pack_product_id_1"]));

$wplus_pack_description_2=trim(urldecode($_POST["wplus_pack_description_2"]));
$wplus_pack_credits_2=trim(urldecode($_POST["wplus_pack_credits_2"]));
$wplus_pack_cart_url_2=trim(urldecode($_POST["wplus_pack_cart_url_2"]));
$wplus_pack_product_id_2=trim(urldecode($_POST["wplus_pack_product_id_2"]));

$wplus_pack_description_3=trim(urldecode($_POST["wplus_pack_description_3"]));
$wplus_pack_credits_3=trim(urldecode($_POST["wplus_pack_credits_3"]));
$wplus_pack_cart_url_3=trim(urldecode($_POST["wplus_pack_cart_url_3"]));
$wplus_pack_product_id_3=trim(urldecode($_POST["wplus_pack_product_id_3"]));

$wplus_pack_description_4=trim(urldecode($_POST["wplus_pack_description_4"]));
$wplus_pack_credits_4=trim(urldecode($_POST["wplus_pack_credits_4"]));
$wplus_pack_cart_url_4=trim(urldecode($_POST["wplus_pack_cart_url_4"]));
$wplus_pack_product_id_4=trim(urldecode($_POST["wplus_pack_product_id_4"]));

$wplus_pack_description_5=trim(urldecode($_POST["wplus_pack_description_5"]));
$wplus_pack_credits_5=trim(urldecode($_POST["wplus_pack_credits_5"]));
$wplus_pack_cart_url_5=trim(urldecode($_POST["wplus_pack_cart_url_5"]));
$wplus_pack_product_id_5=trim(urldecode($_POST["wplus_pack_product_id_5"]));

$wplus_pack_description_6=trim(urldecode($_POST["wplus_pack_description_6"]));
$wplus_pack_credits_6=trim(urldecode($_POST["wplus_pack_credits_6"]));
$wplus_pack_cart_url_6=trim(urldecode($_POST["wplus_pack_cart_url_6"]));
$wplus_pack_product_id_6=trim(urldecode($_POST["wplus_pack_product_id_6"]));

$wplus_pack_description_7=trim(urldecode($_POST["wplus_pack_description_7"]));
$wplus_pack_credits_7=trim(urldecode($_POST["wplus_pack_credits_7"]));
$wplus_pack_cart_url_7=trim(urldecode($_POST["wplus_pack_cart_url_7"]));
$wplus_pack_product_id_7=trim(urldecode($_POST["wplus_pack_product_id_7"]));

$wplus_pack_description_8=trim(urldecode($_POST["wplus_pack_description_8"]));
$wplus_pack_credits_8=trim(urldecode($_POST["wplus_pack_credits_8"]));
$wplus_pack_cart_url_8=trim(urldecode($_POST["wplus_pack_cart_url_8"]));
$wplus_pack_product_id_8=trim(urldecode($_POST["wplus_pack_product_id_8"]));

$wplus_pack_description_9=trim(urldecode($_POST["wplus_pack_description_9"]));
$wplus_pack_credits_9=trim(urldecode($_POST["wplus_pack_credits_9"]));
$wplus_pack_cart_url_9=trim(urldecode($_POST["wplus_pack_cart_url_9"]));
$wplus_pack_product_id_9=trim(urldecode($_POST["wplus_pack_product_id_9"]));

$wplus_pack_description_10=trim(urldecode($_POST["wplus_pack_description_10"]));
$wplus_pack_credits_10=trim(urldecode($_POST["wplus_pack_credits_10"]));
$wplus_pack_cart_url_10=trim(urldecode($_POST["wplus_pack_cart_url_10"]));
$wplus_pack_product_id_10=trim(urldecode($_POST["wplus_pack_product_id_10"]));


$status=1;

if ($status>0) {

	


$configout=<<<EOT
<?php

\$wplus_pack_description_1='$wplus_pack_description_1';
\$wplus_pack_credits_1='$wplus_pack_credits_1';
\$wplus_pack_cart_url_1='$wplus_pack_cart_url_1';
\$wplus_pack_product_id_1='$wplus_pack_product_id_1';

\$wplus_pack_description_2='$wplus_pack_description_2';
\$wplus_pack_credits_2='$wplus_pack_credits_2';
\$wplus_pack_cart_url_2='$wplus_pack_cart_url_2';
\$wplus_pack_product_id_2='$wplus_pack_product_id_2';

\$wplus_pack_description_3='$wplus_pack_description_3';
\$wplus_pack_credits_3='$wplus_pack_credits_3';
\$wplus_pack_cart_url_3='$wplus_pack_cart_url_3';
\$wplus_pack_product_id_3='$wplus_pack_product_id_3';

\$wplus_pack_description_4='$wplus_pack_description_4';
\$wplus_pack_credits_4='$wplus_pack_credits_4';
\$wplus_pack_cart_url_4='$wplus_pack_cart_url_4';
\$wplus_pack_product_id_4='$wplus_pack_product_id_4';

\$wplus_pack_description_5='$wplus_pack_description_5';
\$wplus_pack_credits_5='$wplus_pack_credits_5';
\$wplus_pack_cart_url_5='$wplus_pack_cart_url_5';
\$wplus_pack_product_id_5='$wplus_pack_product_id_5';

\$wplus_pack_description_6='$wplus_pack_description_6';
\$wplus_pack_credits_6='$wplus_pack_credits_6';
\$wplus_pack_cart_url_6='$wplus_pack_cart_url_6';
\$wplus_pack_product_id_6='$wplus_pack_product_id_6';

\$wplus_pack_description_7='$wplus_pack_description_7';
\$wplus_pack_credits_7='$wplus_pack_credits_7';
\$wplus_pack_cart_url_7='$wplus_pack_cart_url_7';
\$wplus_pack_product_id_7='$wplus_pack_product_id_7';

\$wplus_pack_description_8='$wplus_pack_description_8';
\$wplus_pack_credits_8='$wplus_pack_credits_8';
\$wplus_pack_cart_url_8='$wplus_pack_cart_url_8';
\$wplus_pack_product_id_8='$wplus_pack_product_id_8';

\$wplus_pack_description_9='$wplus_pack_description_9';
\$wplus_pack_credits_9='$wplus_pack_credits_9';
\$wplus_pack_cart_url_9='$wplus_pack_cart_url_9';
\$wplus_pack_product_id_9='$wplus_pack_product_id_9';

\$wplus_pack_description_10='$wplus_pack_description_10';
\$wplus_pack_credits_10='$wplus_pack_credits_10';
\$wplus_pack_cart_url_10='$wplus_pack_cart_url_10';
\$wplus_pack_product_id_10='$wplus_pack_product_id_10';

?>
EOT;

	
	file_put_contents("configpacks.php",$configout);
	
}

// Status 0: admin user or pass not entered - Status 1: field changed and saved - Status 2: admin user or password changed so logout

echo "OK";
exit();


?>
