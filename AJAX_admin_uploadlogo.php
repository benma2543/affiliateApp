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

if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileError = $file['error'];
    $fileType = $file['type'];
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
	$fileId = $_POST['fileId'];
	
    if ($fileActualExt == "png") {
        if ($fileError === 0) {
			$finalFileName = substr($fileId, -1);
            $fileDestination = 'img/logo.png';
            if (!file_exists('img')) {
                mkdir('img', 0777, true);
            }
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                sendResponse('success', 'File has been uploaded successfully!');
            } else {
                sendResponse('error', 'There was an error moving the uploaded file.');
            }
        } else {
            sendResponse('error', 'There was an error uploading the file.');
        }
    } else {
        sendResponse('error', 'Sorry, only PNG files are allowed.');
    }
} else {
    sendResponse('error', 'No file was uploaded.');
}
?>
