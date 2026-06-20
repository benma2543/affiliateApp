<?php
function sendResponse($status, $message) {
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("config.php");
if (!isset($_SESSION["adminuser".$l1]) || !isset($_POST)){
	header("location:adminlogin.php");
}

$pagetitle=$_POST['pagetitle'];
$productname=$_POST['productname'];
$productdetails=base64_encode(urldecode($_POST['productdetails']));
$cartinfo=base64_encode(urldecode($_POST['cartinfo']));
$buttoncode=base64_encode(urldecode($_POST['buttoncode']));
$footerhtml=base64_encode(urldecode($_POST['footerhtml']));

$configpageout=<<<EOT
<?php
\$pagetitle="$pagetitle";
\$productname="$productname";
\$productdetails="$productdetails";
\$cartinfo="$cartinfo";
\$buttoncode="$buttoncode";
\$footerhtml="$footerhtml";
?>
EOT;

file_put_contents("configpage.php",$configpageout);
echo "OK";
exit();

?>