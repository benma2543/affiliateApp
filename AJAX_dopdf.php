<?php
require_once 'libs/dompdf/autoload.inc.php'; 
require_once("config.php");
require_once("user/protect.php");
require_once("user/user-lib.php");
use Dompdf\Dompdf; 
$dompdf = new Dompdf();
$options = new \Dompdf\Options();
$options->set('defaultFont', 'DejaVu Sans');
$options->set('isRemoteEnabled', TRUE);
$dompdf = new \Dompdf\Dompdf($options);
$content = urldecode($_POST['content']);
$dompdf->loadHtml($content); 
$dompdf->setPaper('A4', 'portrait'); 
$dompdf->render(); 
$output = $dompdf->output();
$base64=base64_encode($output);
echo $base64;
exit();
?>

