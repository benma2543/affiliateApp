<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("config.php");
require_once("user/protect.php");
require_once("user/user-lib.php");

$imageprompt = trim($_POST["imageprompt"]);
$imagefilename = trim($_POST["imagefilename"]);
$promptmode = $_POST["promptmode"];
$project_id = $_POST["project_id"];
file_put_contents("TEMPdelimage.txt","FN: ".$imagefilename." - path : ".'image_store/'.$userid.'/'.$imagefilename);
if ($imagefilename!=""){
	@unlink ('image_store/'.$userid.'/'.$imagefilename);
	
}

$imageprompt = preg_replace('/[^A-Za-z0-9\s\-!\[\]{}()*?#&+_\-=]/', '', $imageprompt);


$db = new PDO(
      "mysql:host=".USER_DB_HOST.";dbname=".USER_DB_NAME.";charset=".USER_DB_CHARSET,
      USER_DB_USER, USER_DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

if ($masterkeymode==false) {
	$query = "SELECT user_sdxlkey FROM users WHERE user_id = :userid";
	$statement = $db->prepare($query);
	$statement->bindParam(':userid', $userid);
	$statement->execute();
	$result = $statement->fetch(PDO::FETCH_ASSOC);
	if ($result && !empty($result['user_sdxlkey'])) {
		$api_key = $result['user_sdxlkey'];
	} else {
		echo "NOUSERKEY";
		exit();
	}
} else {
	$api_key = $mastersdxlkey;
}

$curl = curl_init();
$promptsettings="";
switch($promptmode){
	case 0:
		$promptsettings=$imageprompt;
		break;
	case 1:
		$promptsettings=$imageprompt.". fine ultra-detailed realistic + ultra photorealistic + Hasselblad H6D + high definition + 8k + cinematic + color grading + depth of field + photo-realistic + film lighting + rim lighting + intricate + realism + maximalist detail + very realistic";
		break;
	case 2:
		$promptsettings=$imageprompt.". use neon cyberpunk color , heavy strokes, illustration.";
		break;
	case 3:
		$promptsettings=$imageprompt.". use neon cyberpunk colors. fine ultra-detailed realistic + ultra photorealistic + Sony Alpha camera + high definition + 8k photo-realistic + intricate + realism + maximalist detail + very realistic";
		break;	
	case 4:
		$promptsettings=$imageprompt.". expertly drawn + colorful cartoon style.";
		break;
	case 5:
		$promptsettings=$imageprompt.". finely detailed + expertly drawn + colorful illustration style.";
		break;
	case 6:
		$promptsettings=$imageprompt.". monochrome + pencil sketch";
		break;
	case 7:
		$promptsettings=$imageprompt.". soft + light + simple low detail watercolor painting for children.";
		break;	
	case 8:
		$promptsettings="watercolor painting {".$imageprompt."} . vibrant, beautiful, painterly, detailed, textural, artistic";
		break;
	case 9:
		$promptsettings="tilt-shift photo of {".$imageprompt."} . highly detailed, vibrant, selective focus, miniature effect, blurred background, perspective control";
		break;	
	case 10:
		$promptsettings=$imageprompt." in vibrant and enticing retail packaging style . commercial, product-focused, eye-catching, professional, highly detailed photography";
		break;	
	case 11:
		$promptsettings=$imageprompt."in a sci fi style, futuristic, highly detailed, alien, cinematic lighting, offworld";
		break;
	case 12:
		$promptsettings="clay art, claymation, play-doh, craft style of ".$imageprompt;
		break;
	case 13:
		$promptsettings="vibrant and highly detailed graphic novel style illustration of ".$imageprompt;
		break;
	case 14:
		$promptsettings="bold, colorful, ultra vibrant colors, simple comic book style illustration of ".$imageprompt;
		break;
	case 15:
		$promptsettings="ultra detailed, crisp, clean, ink line art drawing of ".$imageprompt;
		break;
	case 16:
		$promptsettings="a professional, high resolution, color graded, appetizing, commerical quality advertisment, food photography style of ".$imageprompt;
		break;
	case 17:
		$promptsettings="loose brushwork, colorful, impressionist painting with light and shade capturing the essence over form of ".$imageprompt;
		break;
	case 18:
		$promptsettings="a street photography, analog film, 35mm film, grainy photograph of ".$imageprompt;
		break;
	case 19:
		$promptsettings="a childrens book style illustration of ".$imageprompt;
		break;
	case 20:
		$promptsettings="an ultra highly detailed technical illustration of ".$imageprompt;
		break;
}


curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.stability.ai/v1/generation/stable-diffusion-xl-1024-v1-0/text-to-image?=',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "text_prompts": [
      {
        "text": "'.$promptsettings.'"
      }
    ],
    "cfg_scale": 7,
    "height": 1024,
    "width": 1024,
    "samples": 1,
    "steps": 30
}',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$api_key,
    'Content-Type: application/json',
    'Accept: image/png'
  ),
));

$response = curl_exec($curl);


curl_close($curl);

if (file_exists("image_temp/full".$userid.".png")){unlink("image_temp/full".$userid.".png");}
if (file_exists("image_temp/full".$userid.".jpg")){unlink("image_temp/full".$userid.".jpg");}

$imagefile="image_temp/full".$userid.".png";
$imagejpg="image_temp/full".$userid.".jpg";

file_put_contents($imagefile,$response);

$sourceImage = imagecreatefrompng($imagefile);
$originalWidth = imagesx($sourceImage);
$originalHeight = imagesy($sourceImage);
$newWidth = floor($originalWidth * 0.5);
$newHeight = floor($originalHeight * 0.5);
$resizedImage = imagecreatetruecolor($newWidth, $newHeight);
imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, 512, 512, $originalWidth, $originalHeight);

imagejpeg($resizedImage, $imagejpg, 90);

imagedestroy($sourceImage);
imagedestroy($resizedImage);

$directoryName = 'image_store/'.$userid;
if (!is_dir($directoryName)) {
    mkdir($directoryName, 0755);
	file_put_contents($directoryName."/index.php",'<?php echo "404"; ?>');
} 
$directoryName.="/";
do {
    $filename = 'image_'.$userid.'_'.uniqid() . '_' . time() . '.jpg';
} while (file_exists($directoryName . $filename));

copy($imagejpg, $directoryName.$filename);
@unlink($imagefile);
@unlink($imagejpg);

$stmt = $db->prepare("UPDATE projects SET imagefile = :imagefile WHERE id = :project_id AND user_id = :user_id");
$stmt->bindParam(':imagefile', $filename, PDO::PARAM_STR);
$stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $userid, PDO::PARAM_INT);
$stmt->execute();

echo ($filename);
exit();


?>
