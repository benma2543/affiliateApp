<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("config.php");
require_once("user/protect.php");
require_once("user/user-lib.php");
$add2=0;
if (file_exists("add2.php")){
	require_once("add2.php");
}
@set_time_limit(3000);
@ini_set('max_execution_time', 900);

$db = new PDO(
      "mysql:host=".USER_DB_HOST.";dbname=".USER_DB_NAME.";charset=".USER_DB_CHARSET,
      USER_DB_USER, USER_DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

if ($masterkeymode==false) {
	$query = "SELECT user_apikey FROM users WHERE user_id = :userid";
	$statement = $db->prepare($query);
	$statement->bindParam(':userid', $userid);
	$statement->execute();
	$result = $statement->fetch(PDO::FETCH_ASSOC);
	if ($result && !empty($result['user_apikey'])) {
		$api_key = $result['user_apikey'];
	} else {
		echo "NOUSERKEY";
		exit();
	}
} else {
	$api_key = $masterapikey;
}


// Check for credits system and set key accordingly

if ($add2==1){
	$api_key = $masterapikey;
}

$postfields = array();
foreach ($_POST as $key => $value) {
    $postfields[$key] = $value;
	$postfields[$key]=str_replace("'","",$postfields[$key]);
	$postfields[$key]=addslashes($postfields[$key]);
}

$bonusInsert = "";
$bonusInsert2 = "";
$bonusInsert3 = "";

$bonusnum=1;
$bonuscount=0;
for ($i = 1; $i <= 5; $i++) {

    if (!empty($postfields['bonusname'.$i])) {
        $bonusInsert .= "Bonus Number: ".$bonusnum." -- Bonus Name: [".$postfields['bonusname'.$i] . ", Bonus Description: " . $postfields['bonusdesc'.$i] . "] --  ";
		$bonusnum++;
		$bonuscount++;
    }
}

if ($bonuscount==0){
	$bonusInsert="";
	$bonusInsert2 = "";
	$bonusInsert3 = "";
} else {
	$bonusInsert = trim($bonusInsert);
	$bonusInsert = "I will be giving away ".$bonuscount." bonuses to people who buy this product.  Here are the names and descriptions of the bonuses : [".$bonusInsert."] ";
	$bonusInsert2 = "I will be giving away ".$bonuscount." bonuses to people who buy this product.  Here are the names and descriptions of the bonuses : [".$bonusInsert."] - Please ensure these are also fully listed on the bonus brief.";
	$bonusInsert3 = "I will be giving away ".$bonuscount." bonuses to people who buy this product.  Here are the names and descriptions of the bonuses : [".$bonusInsert."] - Please ensure these are also covered in your output as well.";
}


$toneoptions = array(
	"1" => "Authoritative",
	"2" => "Casual",
	"3" => "Confident",
	"4" => "Conversational",
	"5" => "Educational",
	"6" => "Empathetic",
	"7" => "Encouraging",
	"8" => "Enthusiastic",
	"9" => "Friendly",
	"10" => "Informative",
	"11" => "Lighthearted",
	"12" => "Optimistic",
	"13" => "Persuasive",
	"14" => "Professional",
	"15" => "Straightforward",
	"16" => "Technical"
);
$tone="";
$tonenumber=$postfields["tone"];
if (isset($toneoptions[$tonenumber])) {
	$tone = $toneoptions[$tonenumber];
} 
$langoptions = [
    "1" => "Chinese",
    "2" => "Dutch",
    "3" => "English",
    "4" => "French",
    "5" => "German",
    "6" => "Italian",
    "7" => "Japanese",
    "8" => "Korean",
    "9" => "Portuguese",
    "10" => "Russian",
    "11" => "Spanish",
    "12" => "Vietnamese"
];
$language="";
$langnumber=$postfields["language"];
$product_name=$postfields["product_name"];
if (isset($langoptions[$langnumber])) {
	$language = $langoptions[$langnumber];
}

$panel_input=$postfields["panel_input"];

$target_audience=trim($postfields["audience"]);

$audience_insert="Throughout this task, infuse your writing with the chosen tone: [".$tone."]. Maintain a professional demeanor, eliminating any self-references, apologies, reminders about the instructions, or expressions of gratitude. Refrain from any self-referential remarks. Do not repeat back any of this prompt in your output.";
if($target_audience!==""){
	$audience_insert="Throughout this task, infuse your writing with the chosen tone: [".$tone."] to establish a connection with the target audience of: [".$target_audience."]. Maintain a professional demeanor, eliminating any self-references, apologies, reminders about the instructions, or expressions of gratitude. Refrain from any self-referential remarks. Do not repeat back any of this prompt in your output.";
}
$product_name_insert="";
if (trim($product_name)!=""){$product_name_insert="that is called [".$product_name."]";}

if (isset($postfields["stype"])){
	
	switch ($postfields["stype"]) {
		
		case "1": // Summary
			
	
			$prompt="You are an expert sales copy summarizer.  I want you to write a detailed summary of sales page text that I will provide for you.  I am going to be promoting a product ".$product_name_insert." to my audience as an affiliate so your summary should be focused on the actual product being sold.  Please tailor this to what you think will be the ideal audience.  Pick up on any testimonials or reviews and mention them.  Find any credible proof that the product works as advertised and mention it.  Find any USPs in the copy and highlight them.  Make sure your summary is at least 1000 words - after the summary also write a bullet list of the benefits of the product and the unique features that set it apart from any competitors - all of this should be from an angle of selling it to my audience - Please write in the language [".$language."] --- ".$audience_insert." --- Here is the sales page text : [".$postfields["panel_input"]."]";
			
		
			sendPrompt($prompt,$aiengine,$api_key);
			break;
			
		case "2": // Email Sequence
		
			$prompt="You are an expert email copywriter.  I want you to write a sequence of 5 emails for a product ".$product_name_insert." that I am promoting to my audience as an affiliate - I will provide the sale copy text from the products salespage - Your emails should be focused on the actual product being sold. You MUST write at least 5 emails.  Please make sure you write five emails.  Please tailor this to what you think will be the ideal audience.   Find any credible proof that the product works as advertised and mention it.  Find any USPs in the copy and highlight them. - ".$bonusInsert." - all of this should be from an angle of selling it to my audience --- I will be promoting this over a period of ".$postfields["numdays"]." days. Make sure the last closing email is as long or longer than the first email --- Do not write less than 5 emails --- Please write in the language [".$language."] --- ".$audience_insert." --- Here is the sales page text of the product you are writing the emails for : [".$postfields["panel_input"]."]";
	
			sendPrompt($prompt,$aiengine,$api_key);
			break;
			
		case "3": // Bonus Page Brief
			
			$prompt="You are an expert at creating bonus pages.  I want you to write a bonus page brief for a product ".$product_name_insert." that I am promoting to my audience as an affiliate. Please tailor this to what you think will be the ideal audience.  You should include a full description of the product including the USPs and benefits - ".$bonusInsert2." - all of this should be from an angle of selling it to my audience --- Please ensure this bonus page will help me sell the main product and the bonuses (if any). You should include a section about it being a [Limited Time Offer]. You should include sections about [Testimonials], [Guarantee], [FAQ], [Why choose the product] --- Please write in the language [".$language."] --- ".$audience_insert." --- Here is the sales page text of the product you are writing the brief for : [".$postfields["panel_input"]."]";
			sendPrompt($prompt,$aiengine,$api_key);
			break;
			
		case "4": // FB Posts
			 
			$prompt="You are a Social Media guru.  I want you to write THREE amazing Facebook posts for a product ".$product_name_insert." that I am promoting to my audience as an affiliate - I will provide you with the sales page text of the product. Please tailor this to what you think will be the ideal audience.  ".$bonusInsert." - all of this should be from an angle of selling this product and my bonuses to my audience --- Please ensure the posts will help me sell the main product and the bonuses (if any). --- Please write in the language [".$language."] --- ".$audience_insert." --- Remember : I want three separate Facebook posts please.  Here is the sales page text of the product you are writing the posts for : [".$postfields["panel_input"]."]";
			sendPrompt($prompt,$aiengine,$api_key);
			break;
			
		case "5": // Tik Tok Script
			 
			$prompt="Act as a TikTok video script expert.  I want you to generate an engaging video script for my TikTok account about a product ".$product_name_insert." that I am promoting to my audience as an affiliate - I will provide you with the sales page text of the product.  This script should be designed for maximum engagement and help me sell the product to my audience. ".$bonusInsert3." -  --- Please write in the language [".$language."] --- ".$audience_insert." --- Remember : TikTok videos are typically short, around 15 to 60 seconds so the script will be concise but punchy, focusing on the main benefits of the product.  Here is the sales page text of the product you are writing the script for : [".$postfields["panel_input"]."]";
			sendPrompt($prompt,$aiengine,$api_key);
			break;			

		case "6": // Video Script
			 
			$prompt="Act as a video script expert.  I want you to generate an engaging video script about a product ".$product_name_insert." that I am promoting to my audience as an affiliate - I will provide you with the sales page text of the product.  This script should be designed for maximum engagement and help me sell the product to my audience.  It should be written in a standard script format for a platform like YouTube ".$bonusInsert3." -  --- Please write in the language [".$language."] --- ".$audience_insert." ---  Here is the sales page text of the product you are writing the script for : [".$postfields["panel_input"]."]";
			sendPrompt($prompt,$aiengine,$api_key);
			break;			

		case "7": // Hashtags
			 
			$prompt="Act as a hashtag expert.  I want you to generate 10 hashtags for a product ".$product_name_insert." that I am promoting to my audience as an affiliate - I will provide you with the sales page text of the product.  Please give me 10 Hashtags that will help me rank social media posts and content - Please present your output in a numbered list. ".$bonusInsert3." -  --- Please write in the language [".$language."] --- ".$audience_insert." ---  Here is the sales page text of the product you are writing the hashtags for : [".$postfields["panel_input"]."]";
			sendPrompt($prompt,$aiengine,$api_key);
			break;

		case "8": // Campaign Strategy
			 
			$prompt="You are an expert in creating Campaign Strategy for affiliate marketing campaigns.  I want you to generate a full detailed campaign strategy for a product ".$product_name_insert." that I am promoting to my audience as an affiliate - I will provide you with the sales page text of the product.  ".$bonusInsert3." -  --- Please write in the language [".$language."] --- ".$audience_insert." ---  Here is the sales page text of the product you are writing the hashtags for : [".$postfields["panel_input"]."]";
			sendPrompt($prompt,$aiengine,$api_key);
			break;

		default :
			echo "UNKNOWN MODE";
			break;	
	}
	exit();
} else {
	echo ("NOMODE");
	exit();
}

function sendPrompt($prompt, $aiengine, $api_key) {
    $modelname = "gpt-3.5-turbo-16k"; // Default model name
    $endpoint = "chat/completions";

    if ($aiengine == 1) {
        $modelname = "gpt-4";		
    }


    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.openai.com/v1/' . $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $api_key,
            'Content-Type: application/json'
        ),
    ));

    $postFields = json_encode(array(
        "model" => $modelname,
        "messages" => array(
            array(
                "role" => "user", 
                "content" => $prompt
            )
        ),
        "temperature" => 1,
        "max_tokens" => 4096
    ));


    curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);

    $response = curl_exec($curl);

    if ($response === false) {
        echo "API Error: " . curl_error($curl);
    } else {

        print_r($response);
    }


    curl_close($curl);
}



?>
