<?php
session_start();
require_once("config.php");
require_once("user/user-lib.php");
if (!isset($_SESSION["adminuser".$l1])){
	header("location:adminlogin.php");
}

$p1 = $_POST['p1'];
$p2 = $_POST['p2'];
$p3 = $_POST['p3'];
$d1 = $_POST['d1'];
$d2 = $_POST['d2'];
$d3 = $_POST['d3'];

updateConfig($p1,$d1);

if ($p2!="filler"){
	updateConfig($p2,$d2);	
}

if ($p2!="filler"){
	updateConfig($p3,$d3);	
}

function updateConfig($varName, $newValue) {
    $filename = "ar_integ_settings.php";
    $content = file_get_contents($filename);

    $mauticVar = '$addon_email_mautic_segment';

    // Check and replace/update the passed variable
    if (strpos($content, '$' . $varName . ' =') !== false) {
        if (is_numeric($newValue)) {
            $replacement = '$' . $varName . ' = ' . $newValue . ';';
        } else {
            $newValue = addcslashes($newValue, '"');  // Escape any double quotes in the string value
            $replacement = '$' . $varName . ' = "' . $newValue . '";';
        }

        // Use a regex to find the specific variable assignment and replace it
        $content = preg_replace(
            '/(\$' . preg_quote($varName) . ' = .*?;)/',
            $replacement,
            $content
        );
    } else {
        if (is_numeric($newValue)) {
            $addition = "\n$" . $varName . " = " . $newValue . ";";
        } else {
            $newValue = addcslashes($newValue, '"');
            $addition = "\n$" . $varName . " = \"" . $newValue . "\";";
        }

        // Insert new variable before the closing tag
        $content = str_replace("?>", $addition . "\n?>", $content);
    }

    // Check for the $addon_email_mautic_segment variable, and add if not present
    if (strpos($content, $mauticVar) === false) {
        $addition = "\n" . $mauticVar . '="";';
        $content = str_replace("?>", $addition . "\n?>", $content);
    }

    file_put_contents($filename, $content);

    return "All Good";
}




?>