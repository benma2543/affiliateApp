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
$currentFullPath = realpath(__FILE__);
$httpdocsPos = strpos($currentFullPath, '/httpdocs/');
$basePath = substr($currentFullPath, 0, $httpdocsPos + strlen('/httpdocs'));
function getHost()
{
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $requestUri = $_SERVER['REQUEST_URI'];
    $path = rtrim(dirname($requestUri), '/') . '/';
	
    return $host;
}
$thishost=getHost();
$uploadto=0;
if ($thishost=="yoursassapp.com" || $thishost=="exclusivesoftwarelab.online" || $thishost="exclusivesoftwarelab.store" || $thishost="premiumsaasapp.com" || $thishost="exclusivesaaslab.com" || $thishost="exclusivesaaslabs.com"){
	$uploadto=1;
}

if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileError = $file['error'];
    $fileType = $file['type'];
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    $fileId = $_POST['fileId'];
    
    // Retrieve htmlLocation from the POST data
    $htmlLocation = $_POST['htmlLocation'];
	
	if ($uploadto==1){$htmlLocation=0;}
    
    if ($fileActualExt == "html") {
        if ($fileError === 0) {
            if ($htmlLocation==0){$fileDestination = 'index.html';}else{$fileDestination = $basePath.'/index.html';}
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                sendResponse('success', 'File has been uploaded successfully!');
            } else {
                sendResponse('error', 'There was an error moving the uploaded file.');
            }
        } else {
            sendResponse('error', 'There was an error uploading the file.');
        }
    } else {
        sendResponse('error', 'Sorry, only HTML files are allowed.');
    }
} else {
    sendResponse('error', 'No file was uploaded.');
}
?>
