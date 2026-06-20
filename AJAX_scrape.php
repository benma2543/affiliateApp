<?php
header('Content-Type: text/plain; charset=utf-8');
require_once("config.php");
require_once("user/protect.php");
require_once("user/user-lib.php");
$scrapeURL=$_POST["url"];
$ch = curl_init($scrapeURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
$html = curl_exec($ch);
curl_close($ch);

$dom = new DOMDocument();
libxml_use_internal_errors(true); 

$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);


// Extract the content inside the <body> tag
$body = $dom->getElementsByTagName('body')->item(0);
$cleanBody = new DOMDocument();
foreach ($body->childNodes as $child){
    $cleanBody->appendChild($cleanBody->importNode($child, true));
}

// Remove script and style elements from the body
foreach (['script', 'style', 'head', 'title', 'meta', 'link'] as $removeTag) {
    $elements = $cleanBody->getElementsByTagName($removeTag);
    for ($i = $elements->length; --$i >= 0; ) {
        $element = $elements->item($i);
        $element->parentNode->removeChild($element);
    }
}


// Define a list of classes and IDs typically used to hide elements
$hiddenClasses = [
    'hidden', 'hide', 'invisible', 'display-none', 'd-none', 
    'visually-hidden', 'sr-only', 'collapse', 
    'elementor-hidden-phone', 'elementor-hidden-desktop', 
    'tve-hidden', 'tve-hide', 'tve-no-display', 'tve-display-none', 'tve-mobile-hide',
    'hide-mobile', 'hide-desktop'
    
];
$hiddenIds = [

    'hidden-id', 'modal-id', 'popup-id','hidden','hide'
    
];

// Function to remove elements by class
function removeElementsByClass($domDocument, $class) {
    $elements = $domDocument->getElementsByTagName('*');
    for ($i = $elements->length; --$i >= 0; ) {
        $element = $elements->item($i);
        if (in_array($class, explode(' ', $element->getAttribute('class')))) {
            $element->parentNode->removeChild($element);
        }
    }
}

// Remove elements with hidden classes
foreach ($hiddenClasses as $class) {
    removeElementsByClass($cleanBody, $class);
}

// Remove elements with hidden IDs
foreach ($hiddenIds as $id) {
    $element = $cleanBody->getElementById($id);
    if ($element) {
        $element->parentNode->removeChild($element);
    }
}


// Extract text
$text = $cleanBody->textContent;

// Normalize CR LF (Carriage Return and Line Feed) combinations to a single newline
$text = preg_replace('/\r\n|\r/', "\n", $text);
// Replace multiple consecutive newlines with a single newline
$text = preg_replace('/\n{2,}/', "\n", $text);

// Remove leading and trailing spaces around newlines
$text = preg_replace('/\s*\n\s*/', "\n", $text);

// Define phrases to remove (case-insensitive)
$phrasesToRemove = [
    'Login', 'Reset Your Access', 'Support Desk', 'Earning Disclaimer', 'Privacy Policy',
    'Refund Policy', 'Terms Of Service', 'Copyright ©', 'Copyright', 'Contest & Prizes',
    'Contest Based On: Total Revenue', 'Request Links', 'SalesPage Preview:', 'Support Desk',
    'If You Have Any Question Or Need Anything ?', 'Contact Us', 'Connect on Facebook', 
    'Privacy Overview', 'Contact Us', 'PLR License and Use', 'Terms of Service | Privacy Policy',
    'Affiliate Terms & Conditions', 'Earnings Disclaimer', 'Privacy Policy | Terms of Service | Contact Us | Earnings Disclaimer',
    'Support', 'Legal Pages and Disclaimers', 'Proceed to Checkout', 'LIMITED TIME OFFER'
];

foreach ($phrasesToRemove as $phrase) {
    $text = str_ireplace($phrase, '', $text);
}

// Remove lines that start with "win" and "share" (case-insensitive)
$text = preg_replace('/^win.*$/mi', '', $text);
$text = preg_replace('/^share.*$/mi', '', $text);

// Trim leading and trailing newlines and whitespaces
$text = trim($text);


// Output filtered text

echo ($text);
exit();


?>