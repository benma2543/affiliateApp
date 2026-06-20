<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require("config.php");
if (!isset($initial_credits)){$initial_credits=0;}
if (!isset($_SESSION["adminuser".$l1])){
	header("location:adminlogin.php");
}
require("add1.php");
if (file_exists("add2.php")){require("add2.php");}else{$add2=0;}
if (file_exists("add3.php")){require("add3.php");}else{$add3=0;}
	if ($add3==1){
	define('ar_integ_token', true); require_once("ar_integ_settings.php");
	if (!isset($mautic_user)){$mautic_user="";}
	if (!isset($mautic_pass)){$mautic_pass="";}
	if (!isset($mautic_url)){$mautic_url="";}		
	}else{
		echo "Addon not installed..."; exit();
	}


function callAPI($method, $url, $data){
   $curl = curl_init();
   switch ($method){
	  case "POST":
		 curl_setopt($curl, CURLOPT_POST, 1);
		 if ($data)
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		 break;
	  case "PUT":
		 curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		 if ($data)
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
		 break;
	  default:
		 if ($data)
			$url = sprintf("%s?%s", $url, http_build_query($data));
   }
   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	  'APIKEY: 111111111111111111111',
	  'Content-Type: application/json',
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}



$thisurl=getURL();
function getURL()
{
	$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
	$host = $_SERVER['HTTP_HOST'];
	$requestUri = $_SERVER['REQUEST_URI'];
	$path = rtrim(dirname($requestUri), '/') . '/';

	return $protocol . '://' . $host . $path;
}



// AR Data
$count=0;

//if (!isset($_SESSION['mautic_segments'])) {

if (1 == 1) {
    unset($_SESSION['mautic_segments']);
    $_SESSION['mautic_segments'] = "";
    if ($mautic_user != "" && $mautic_pass != "" && $mautic_url != "") {
        $endpoint = '/api/segments';
        $token = base64_encode($mautic_user . ":" . $mautic_pass);
        $segments = [];
        $page = 1; // Initialize page number
        $totalFetched = 0; // Keep track of total fetched segments
        

        $start = 0; // Initialize start
        $limit = 30; // Set the limit for each page
        $totalAvailable = PHP_INT_MAX; // Assume a large number initially
        
        while ($start < $totalAvailable) {
            $ch = curl_init();
            $url = $mautic_url . $endpoint . '?limit=' . $limit . '&start=' . $start; // Use start for pagination
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $token]);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
                curl_close($ch);
                exit;
            }
            $responseData = json_decode($response, true);
            if (!empty($responseData['lists'])) {
                foreach ($responseData['lists'] as $segment) {
                    $segments[$segment['id']] = $segment; // Prevent duplicates by using segment ID as key
                }
                $start += $limit; // Move start to the next set of segments
                $totalAvailable = $responseData['total']; // Update total based on API response
            } else {
                break; // Exit loop if no more segments are returned
            }
            curl_close($ch);
        }

        // Loop through segments to build options
        $count = 0;
        foreach ($segments as $segment) {
            $selectme = "";
            if (isset($addon_email_mautic_segment) && $addon_email_mautic_segment == $segment['id']) {
                $selectme = " selected ";
            }
            $_SESSION['mautic_segments'] .= '<option' . $selectme . ' value="' . $segment['id'] . '">' . $segment['name'] . '</option>';
            $count++;
        }
    }
    if ($count == 0) {
        unset($_SESSION['mautic_segments']);
    }
}





if (!isset($_SESSION['mailvio_lists'])) {
	unset($_SESSION['mailvio_lists']);
	$_SESSION['mailvio_lists']="";
	if ($mailvio_token!=""){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://apiv2.mailvio.com/group',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
			'x-access-token: '.$mailvio_token
		  ),
		));
		$response = curl_exec($curl);
		
		curl_close($curl);
		$data = json_decode($response, true);
		$count=0;
		if (isset($data['Groups']) && is_array($data['Groups'])) {
			foreach ($data['Groups'] as $group) {
				if (isset($group['groupName']) && isset($group['id'])) {
					$count++;
					$_SESSION['mailvio_lists'].='<option value="'.$group['id'].'">'.$group['groupName'].'</option>';					
				}
			}
		}
	}
	if (intval($count)==0) { unset($_SESSION['mailvio_lists']); }
}


if (!isset($_SESSION['sendy_lists'])) {

	unset($_SESSION['sendy_lists']);
	$_SESSION['sendy_lists']="";
	if ($sendy_api!="" && $sendy_url!=""){

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $sendy_url . '/api/brands/get-brands.php');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array('api_key' => $sendy_api));
	$brands_result = curl_exec($ch);
	curl_close($ch);
	$brands = json_decode($brands_result, true);
	$count=0;
	
	if ($brands && is_array($brands)) {
		foreach ($brands as $brand) {
			$brand_id = $brand['id'];

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $sendy_url . '/api/lists/get-lists.php');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, array('api_key' => $sendy_api, 'brand_id' => $brand_id));
			$lists_result = curl_exec($ch);
			curl_close($ch);
			
			$lists = json_decode($lists_result, true);
			if ($lists && is_array($lists)) {
				foreach ($lists as $list) {
					$count++;
					$_SESSION['sendy_lists'].='<option value="'.$list['id'].'">'.$list['name'].'</option>';
				}
				
			}
		}
	}
		if (intval($count)==0) { unset($_SESSION['sendy_lists']); }
	}
	
}

if (!isset($_SESSION['mailjet_lists'])) {
	unset($_SESSION['mailjet_lists']);
	if ($mailjet_api!="" && $mailjet_secret!=""){
		$auth = base64_encode($mailjet_api . ':' . $mailjet_secret);
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.mailjet.com/v3/REST/contactslist",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Authorization: Basic $auth",
				"Content-Type: application/json"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			echo "Mailjet cURL Error: " . $err;
		} else {
			$data = json_decode($response, true);
			if (isset($data['Data'])) {
				$count=0;
				$_SESSION['mailjet_lists']="";
				foreach ($data['Data'] as $list) {
					$count++;
					$_SESSION['mailjet_lists'].='<option value="'.$list['ID'].'">'.$list['Name'].'</option>';
				}
				if (intval($count)==0) { unset($_SESSION['mailjet_lists']); }
			}
		}
	}
}

if (!isset($_SESSION['pabbly_lists'])) {

	unset($_SESSION['pabbly_lists']);
	if ($pabblytoken!=""){

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://emails.pabbly.com/api/subscribers-list',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
			'Authorization: Bearer '.$pabblytoken,
		  ),
		));
		$response = curl_exec($curl);
		curl_close($curl);

		$count=0;
		$_SESSION['pabbly_lists']="";
		$pabblydata = json_decode($response, true);

		if ($pabblydata['status'] === 'success') {
			foreach ($pabblydata['subscribers_list'] as $list) {
				$listId = $list['list_id'];
				$listName = $list['list_name'];
				$count++;
				$_SESSION['pabbly_lists'].='<option value="'.$listId.'">'.$listName.'</option>';
			}
		
		}
		if (intval($count)==0) { unset($_SESSION['pabbly_lists']); }
	}

}

if (!isset($_SESSION['sendiio_lists'])) {

	if ($sendiio_token!="" && $sendiio_secret!=""){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://sendiio.com/api/v1/lists/email',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"content-type: application/json",
				"token: ".$sendiio_token,
				"secret: ".$sendiio_secret
			),
		));				
		$resp = curl_exec($curl);
		curl_close($curl);
		$result=json_decode($resp,true);
		$count=0;
		$_SESSION['sendiio_lists']="";
		foreach ($result['data']['lists'] as $list){
					$count++;
					$_SESSION['sendiio_lists'].='<option value="'.$list['encrypted_id'].'">'.$list['name'].'</option>';
				}			
	}
	if (intval($count)==0) { unset($_SESSION['sendiio_lists']); }
}


if (!isset($_SESSION['gr_lists'])) {

	unset($_SESSION['gr_lists']);
	if ($gr_api!="" ){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.getresponse.com/v3/campaigns',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
			'X-Auth-Token: api-key '.$gr_api
		  ),
		));

		$response = curl_exec($curl);

		$grdata = json_decode($response, true);

		
		$_SESSION['gr_lists']="";
		foreach ($grdata as $campaign) {
			$_SESSION['gr_lists'].='<option value="'.$campaign["campaignId"].'">'.$campaign['name'].'</option>';
		}
	
	}	
}



if (!isset($_SESSION['ck_lists'])) {
	if ($ck_api!="" ){
		$key=$ck_api;
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'https://api.convertkit.com/v3/forms?api_key='.$key
		));

		$resp = curl_exec($curl);
		curl_close($curl);

		$resp=json_decode($resp);
		$count=0;
		$_SESSION['ck_lists']="";
		foreach ($resp->forms as $form){
			$count++;
			$_SESSION['ck_lists'].='<option value="'.$form->id.'">'.$form->name.'</option>';
		}
		
		if (intval($count)==0) { unset($_SESSION['ck_lists']); }
	
	}	
}

if (!isset($_SESSION['ml_lists'])) {
	if ($ml_api!="" ){
		$key=$ml_api;
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://connect.mailerlite.com/api/groups',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
			'Authorization: Bearer '.$key
		  ),
));

		$resp = curl_exec($curl);
		
		curl_close($curl);
		$resp=json_decode($resp);
			$count=0;
			$_SESSION['ml_lists']="";
			foreach ($resp->data as $resp2) {
				$count++;
				$_SESSION['ml_lists'].='<option value="'.$resp2->id.'">'.$resp2->name.'</option>';
			}			
			if (intval($count)==0) { unset($_SESSION['ml_lists']); }
	}	
}

if (!isset($_SESSION['mc_lists'])) {
	if ($mc_api!="" ){

			$parts = explode('-', $mc_api);
			$dc = end($parts);

			$url = "https://{$dc}.api.mailchimp.com/3.0/lists";

			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_USERPWD, 'anystring:' . $mc_api);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);
			$count=0;
			$_SESSION['mc_lists']="";
			$response=json_decode($response,true);
			foreach ($response['lists'] as $list){
				$count++;
				$_SESSION['mc_lists'].='<option value="'.$list['id'].'">'.$list['name'].'</option>';
			}

			if (intval($count)==0) { unset($_SESSION['mc_lists']); }
		
	}
}

if (!isset($_SESSION['ac_lists'])) {

	$addon_email="1";
	if ($ac_api!="" && $ac_url!=""){
		
		$ch = curl_init($ac_url.'/api/3/lists?limit=200');

		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'accept: application/json',
			'Api-Token: '.$ac_api
		]);

		$response = curl_exec($ch);


		if (curl_errno($ch)) {
			
		}

		curl_close($ch);
		$count=0;
		$_SESSION['ac_lists']="";
		$ac_lists="";		
		$aclists = json_decode($response, true);
		foreach ($aclists['lists'] as $list) {
			$name = $list['name'];
			$id = $list['id'];
			$ac_lists.='<option value="'.$id.'">'.$name.'</option>';
			$count++;			
		}	
		if (intval($count)==0) { unset($_SESSION['ac_lists']); } else {$_SESSION['ac_lists']=$ac_lists;}
	}
}
			
if (!isset($_SESSION['sendlane_lists'])) {
	$addon_email="1";
	if ($sendl_api!="" && $sendl_hash!="" && $sendl_domain!=""){

		if (strpos($sendl_domain, 'sendlane.com') == false) {
			$sendl_domain.=".sendlane.com";
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,            "https://".$sendl_domain."/api/v1/lists" );		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST,           true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,     "api=$sendl_api&hash=$sendl_hash");
		$result=curl_exec ($ch);
		if ($result!='{"info":{"messages":"No lists found."}}'){
			$result=json_decode($result);
			
			$count=0;
			$_SESSION['sendlane_lists']="";
			$sendlane_lists="";		
			foreach ($result as $sendlane_list) {
				$sendlane_lists.='<option value="'.$sendlane_list->list_id.'">'.$sendlane_list->list_name.'</option>';
				$count++;
				if (intval($count)==0) { unset($_SESSION['sendlane_lists']); } else {$_SESSION['sendlane_lists']=$sendlane_lists;}
			}
		}
	}
}

if (!isset($_SESSION['arpreach_lists'])) {
	
	$addon_email="1";
	if ($arp_key!="" && $arp_path!=""){
		// Modify Arp path if needed - check for a.php and then remove single or double slash at end
		$arp_path=str_replace("a.php","",$arp_path);
		$arp_path=rtrim($arp_path,"/");
		$arp_path="$arp_path/a.php/api/list_responders";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,            $arp_path );		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST,           true);
		curl_setopt($ch, CURLOPT_POSTFIELDS,     "api_key=$arp_key");
		$arplists=curl_exec ($ch);
		$arplists=json_decode($arplists);
		
		$count=0;
		$_SESSION['arpreach_lists']="";
		$arpreach_lists="";		
		foreach ($arplists as $arpreach_list) {
			$arpreach_lists.='<option value="'.$arpreach_list[0]->name.'">'.$arpreach_list[0]->name.'</option>';
			$count++;
			if (intval($count)==0) { unset($_SESSION['arpreach_lists']); } else {$_SESSION['arpreach_lists']=$arpreach_lists;}
		}
	}
}




if (!isset($_SESSION['octo_lists'])) {
	if ($octo_api!="" ){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://emailoctopus.com/api/1.5/lists?api_key='.$octo_api); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 3);
			$content = trim(curl_exec($ch));
			curl_close($ch);
			$result=json_decode($content,true);
			$count=0;
			$_SESSION['octo_lists']="";
			foreach ($result['data'] as $list){
				$count++;
				$_SESSION['octo_lists'].='<option value="'.$list['id'].'">'.$list['name'].'</option>';
			}
	}
	if (intval($count)==0) { unset($_SESSION['octo_lists']); }
}





if (!isset($_SESSION['moo_lists'])) {
	if ($moo_api!="" ){
							
			$apikey=$moo_api;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://api.moosend.com/v3/lists.json?apikey=$apikey&WithStatistics=false&short_by=name&sort_method=ASC");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);

			$response = curl_exec($ch);
			curl_close($ch);
			$response=json_decode($response,TRUE);
			$count=0;
			$_SESSION['moo_lists']="";
			foreach ($response['Context']['MailingLists'] as $resp) {
				$count++;
				$_SESSION['moo_lists'].='<option value="'.$resp['ID'].'">'.$resp['Name'].'</option>';
			}
			
	}
	if (intval($count)==0) { unset($_SESSION['moo_lists']); }
}



if (!isset($_SESSION['sg_lists'])) {
	
	if ($sg_api!="" ){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/marketing/lists?page_size=100');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			$headers = array();
			$headers[] = 'Authorization: Bearer '.$sg_api;
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$result = curl_exec($ch);
			if (curl_errno($ch)) {
				file_put_contents("TEMPsgerror.txt",'Error:' . curl_error($ch));
			} 
			curl_close($ch);
			$result=json_decode($result,true);
			$count=0;
			$_SESSION['sg_lists']="";
			foreach ($result['result'] as $list){
				$count++;
				$_SESSION['sg_lists'].='<option value="'.$list['id'].'">'.$list['name'].'</option>';
			}
	}
	if (intval($count)==0) { unset($_SESSION['sg_lists']); }
}


if (!isset($_SESSION['bm_lists'])) {
	if ($benchmark_api!="" ){

			$ch = curl_init();

			curl_setopt_array($ch, array(
			  CURLOPT_URL => 'https://clientapi.benchmarkemail.com/Contact/',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'GET',
			  CURLOPT_HTTPHEADER => array(
				'AuthToken: '.$benchmark_api
			  ),
			));

			$result = curl_exec($ch);
		
			$result=json_decode($result,true);

			$count=0;
			$_SESSION['bm_lists']="";
			foreach ($result["Response"]["Data"] as $list) {
				$count++;
				$_SESSION['bm_lists'].='<option value="'.$list['ID'].'">'.$list['Name'].'</option>';
			}
	}
	if (intval($count)==0) { unset($_SESSION['bm_lists']); }
}



// Webinars

if (!isset($_SESSION['webjam_webinars'])) {  // Get Webinarjam

	if ($webjam_api!="" ){
		$count=0;
		$data_array =  array(
			  "api_key"        => $webjam_api
			  );
		$make_call = callAPI('POST', 'https://api.webinarjam.com/webinarjam/webinars', json_encode($data_array));
		$response = json_decode($make_call, true);
		if ($response["status"]=="success"){
			$response_array=$response["webinars"];
			foreach ($response_array AS $response_item){
				$select_out="";
				$select_out_webbyname=$response_item["name"];
				$select_out_webbyid=$response_item["webinar_id"];
				// Now get individual webinar data
				$data_array = array ("api_key"        => $webjam_api,"webinar_id"=>$response_item["webinar_id"]);
				$make_call = callAPI('POST', 'https://api.webinarjam.com/webinarjam/webinar', json_encode($data_array));
				$response_single = json_decode($make_call, true);
				$webby_timezone=$response_item["timezone"];
				$response_array_single=$response_single["webinar"]["schedules"]; //  Pull the schedules array for the webinar
				if ($response_array_single!="") { // make sure that there are actual webinars scheduled
					foreach($response_array_single as $response_item_single){
						$count++;
						$_SESSION['webjam_webinars'].='<option value="'.$select_out_webbyid.'-'.$response_item_single["schedule"].'">'.$select_out_webbyname.' / '.$response_item_single["comment"].'</option>';
					} 
				}
			}
		}
		if (intval($count)==0) { unset($_SESSION['webjam_webinars']); }
	}
}


if (!isset($_SESSION['everweb_webinars'])) {  // Get EverWebinar
	
	if ($everweb_api!="" ){
		$count=0;
		$data_array =  array(
			  "api_key"        => $everweb_api
			  );
		$make_call = callAPI('POST', 'https://api.webinarjam.com/everwebinar/webinars', json_encode($data_array));
		$response = json_decode($make_call, true);
		if ($response["status"]=="success"){
			$response_array=$response["webinars"];
			foreach ($response_array AS $response_item){
				$select_out="";
				$select_out_webbyname=$response_item["name"];
				$select_out_webbyid=$response_item["webinar_id"];
				// Now get individual webinar data
				$data_array = array ("api_key"        => $everweb_api,"webinar_id"=>$response_item["webinar_id"]);
				$make_call = callAPI('POST', 'https://api.webinarjam.com/everwebinar/webinar', json_encode($data_array));
				$response_single = json_decode($make_call, true);
				//$webby_timezone=$response_item["timezone"];
				$response_array_single=$response_single["webinar"]["schedules"]; //  Pull the schedules array for the webinar
				if ($response_array_single!="") { // make sure that there are actual webinars scheduled
					foreach($response_array_single as $response_item_single){
						if ($response_item_single["schedule"]!=$previous_schedule){
							$count++;
							$_SESSION['everweb_webinars'].='<option value="'.$select_out_webbyid.'-'.$response_item_single["schedule"].'">'.$select_out_webbyname.' / '.$response_item_single["comment"].'</option>';
						}
					$previous_schedule=$response_item_single["schedule"];
					} 
				}
			}
		}
		if (intval($count)==0) { unset($_SESSION['everweb_webinars']); }
	}
}


if (!isset($_SESSION['demio_webinars'])) {  // Get demio

	if ($demio_api!="") {
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://my.demio.com/api/v1/events?api_key='.$demio_api.'&api_secret='.$demio_secret,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec($curl);
		curl_close($curl);
		$events = json_decode($response, true);
		$count=0;
		$_SESSION["demio_webinars"]="";
		foreach ($events as $event) {
			$id = $event['id'];
			$name = $event['name'];
			$count++;
			$_SESSION['demio_webinars'].='<option value="'.$id.'">'.$name.'</option>';
		}
		if (intval($count)==0) { unset($_SESSION['demio_webinars']); }
	}
	
}

if (!isset($_SESSION['sendfox_lists'])) {
	if ($sendfox_api!="" ){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.sendfox.com/lists',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
			'Authorization: Bearer '.$sendfox_api

		  ),
		));

		$response = curl_exec($curl);
		curl_close($curl);
	
		
		
		$response=json_decode($response,true);		
		$_SESSION['sendfox_lists']="";
		foreach ($response['data'] as $response_list) {
			$_SESSION['sendfox_lists'].='<option value="'.$response_list["id"].'">'.$response_list['name'].'</option>';
		}			
	
	}	
}

if (!isset($_SESSION['klaviyo_lists'])) {
	if ($klaviyo_api!="" ){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://a.klaviyo.com/api/v2/lists?api_key='.$klaviyo_api,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec($curl);
		curl_close($curl);
		$response=json_decode($response,true);	
		$_SESSION['klaviyo_lists']="";
		foreach ($response as $response_list) {
			$_SESSION['klaviyo_lists'].='<option value="'.$response_list["list_id"].'">'.$response_list['list_name'].'</option>';
		}			
	}	
}


if (!isset($_SESSION['platformly_projects'])) {
	if ($platformly_api!="" ){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.platform.ly/',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => array('api_key' => 'eTYW4NjXGyQ0AKvRteFGgzOgIAIR21xs','action' => 'list_projects','value' => '[]','' => ''),
		 
		));

		$response = curl_exec($curl);
		curl_close($curl);
		$response=json_decode($response,true);	

		$_SESSION['platformly_projects']="";
		foreach ($response as $response_list) {
			$_SESSION['platformly_projects'].='<option value="'.$response_list["id"].'">'.$response_list['name'].'</option>';
		}			
	}	
}

if (!isset($_SESSION['markethero_tags'])) {
	if ($markethero_api!="" ){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.markethero.io/v1/api/tags?apiKey=7528a73e2a9a9b0d83841b16b8f83c98bcb5ea165d5f06b93b817043644a9a63',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
			'apiKey: '.$markethero_api
		  ),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$response=json_decode($response,true);	
		$_SESSION['markethero_tags']="";
		foreach ($response['tags'] as $tag) {
			$_SESSION['markethero_tags'].='<option value="'.$tag.'">'.$tag.'</option>';
		}			
	}	
}

if (!isset($_SESSION['ontraport_campaigns'])) {
	if ($ontraport_apikey!="" ){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.ontraport.com/1/CampaignBuilderItems',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_POSTFIELDS => array('ids' => '0'),
		  CURLOPT_HTTPHEADER => array(
			'Api-key: '.$ontraport_apikey,
			'Api-Appid: '.$ontraport_appid
		  ),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		
		$response=json_decode($response,true);	

		$_SESSION['ontraport_campaigns']="";
		foreach ($response['data'] as $campaign) {
			$_SESSION['ontraport_campaigns'].='<option value="'.$campaign["id"].'">'.$campaign["name"].'</option>';
		}			
	
	}	
}


if (!isset($_SESSION['fm_lists'])) {
	if ($fm_key!=""){
		$url = "https://funnelmates.com/api/fi?token=".$fm_key;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      
		curl_setopt($ch, CURLOPT_URL, $url);

		$results = curl_exec($ch);
		curl_close($curl);

		$results=json_decode($results,true);

		$results_data=$results['data'];

		$count=0;
		$_SESSION['fm_lists']="";
		foreach ($results_data as $result_data){
					$count++;
					$_SESSION['fm_lists'].='<option value="'.$result_data['list_id'].'|'.$result_data['CCID'].'">'.$result_data['post_title'].'</option>';
				}			
	}
	if (intval($count)==0) { unset($_SESSION['fm_lists']); }
}

if (!isset($addon_email_artype)){$addon_email_artype==999;}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title> <?php echo $sitename; ?> </title>
		<!-- Custom fonts for this template -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="libs/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
		<style>
			.topgap {
				margin-top: 15px;
			}
			.topgap2 {
				margin-top: 20px;
			}
		</style>
	</head>
	<body id="page-top">
		<!-- Page Wrapper -->
		<div id="wrapper"> <?php require_once("admin_menu_sidenav.php"); ?>
			<!-- Content Wrapper -->
			<div id="content-wrapper" class="d-flex flex-column">
				<!-- Main Content -->
				<div id="content">
					<!-- Begin Page Content -->
					<div class="container-fluid" style="margin-top:50px;">
						<!-- Page Heading -->
						<h1 class="h3 mb-2 text-gray-800">Lead Capture Settings for "<?php echo $sitename ?>" </h1>
						<p class="mb-4">Select where to send new leads here.</p>
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="m-0 font-weight-bold text-primary">Lead Capture Settings</h6>
							</div>
							<div class="card-body">
								
							<div class="row topgap">
								<div class="col-3" >
									<label >Add To Autoresponder:</label><br>
									<span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which Autoresponder service you use.">
										<select id="addon_email_artype" name="addon_email_artype" data-size="5" class="custom-select" style="min-width:370px;">
											<option value="999" <?php if ($addon_email_artype==999){echo("selected");} ?> >Not Set</option> 
											<option value="4" <?php if($addon_email_artype==4){echo('selected="selected"');} ?>>Active Campaign</option>
											<option value="11" <?php if($addon_email_artype==11){echo('selected="selected"');} ?>>ArpReach</option>
											<option value="23" <?php if($addon_email_artype==23){echo('selected="selected"');} ?>>Benchmark</option>
											<option value="2" <?php if($addon_email_artype==2){echo('selected="selected"');} ?>>ConvertKit</option>
											<option value="14" <?php if($addon_email_artype==14){echo('selected="selected"');} ?>>EmailOctopus</option>
											<option value="24" <?php if($addon_email_artype==24){echo('selected="selected"');} ?>>FunnelMates</option>
											<option value="1" <?php if($addon_email_artype==1){echo('selected="selected"');} ?>>GetResponse</option>
											<option value="9" <?php if($addon_email_artype==9){echo('selected="selected"');} ?>>iContact</option>
											<option value="19" <?php if($addon_email_artype==19){echo('selected="selected"');} ?>>Klaviyo</option>
											<option value="3" <?php if($addon_email_artype==3){echo('selected="selected"');} ?>>MailChimp</option>
											<option value="28" <?php if($addon_email_artype==28){echo('selected="selected"');} ?>>Mailjet</option>
											<option value="6" <?php if($addon_email_artype==6){echo('selected="selected"');} ?>>MailerLite</option>
											<option value="29" <?php if($addon_email_artype==29){echo('selected="selected"');} ?>>Mailvio</option>
											<option value="31" <?php if($addon_email_artype==31){echo('selected="selected"');} ?>>Mautic</option>
											<option value="16" <?php if($addon_email_artype==16){echo('selected="selected"');} ?>>Moosend</option>
											<option value="22" <?php if($addon_email_artype==22){echo('selected="selected"');} ?>>Ontraport</option>
											<option value="26" <?php if($addon_email_artype==26){echo('selected="selected"');} ?>>Pabbly</option>
											<option value="18" <?php if($addon_email_artype==18){echo('selected="selected"');} ?>>SendFox</option>
											<option value="15" <?php if($addon_email_artype==15){echo('selected="selected"');} ?>>SendGrid</option>
											<option value="17" <?php if($addon_email_artype==17){echo('selected="selected"');} ?>>Sendiio</option>
											<option value="10" <?php if($addon_email_artype==10){echo('selected="selected"');} ?>>Sendlane</option>
											<option value="27" <?php if($addon_email_artype==27){echo('selected="selected"');} ?>>Sendy</option>
											
										</select>
									</span>
								</div>
								
								
								
								
								
								
								
								<div class="form-group col-md3" >
<?php			
$nameflag=0;


if (isset($_SESSION['mautic_segments']))
	{
		
		$mauticlist='<label>Segment</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which Segment to use."><select id="addon_email_mautic_segment" name="addon_email_mautic_segment" class="custom-select" data-size="4">'.$_SESSION['mautic_segments'].'</select>';
		$mauticlist2="</span>";
		$nameflag=1;
	}else{
		$mauticlist='<select id="addon_email_mautic_sefgment" name="addon_email_mautic_segment" style="display:none"><option value="none"></option></select><span style="padding-left:20px">Please visit your Integrations screen to connect your Autoresponder.</span>';
		$mauticlist2="";
	}
	if ($addon_email_artype!=31) {$showar="display:none;";} else {$showar="";}	
$sectionout_mautic=<<<EOT
			<span class="show_email show_mautic" style="$showar" >

						$mauticlist
				$mauticlist2
			</span>					

EOT;

echo $sectionout_mautic;


if (isset($_SESSION['mailvio_lists']))
	{
		
		$mailviolist='<label>List</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which list to use."><select id="addon_email_mailvio_group" name="addon_email_mailvio_list" class="custom-select" data-size="4">'.$_SESSION['mailvio_lists'].'</select>';
		$mailviolist2="</span>";
		$nameflag=1;
	}else{
		$mailviolist='<select id="addon_email_mailvio_list" name="addon_email_mailvio_group" style="display:none"><option value="none"></option></select><span style="padding-left:20px">Please visit your Integrations screen to connect your Autoresponder.</span>';
		$mailviolist2="";
	}
	if ($addon_email_artype!=29) {$showar="display:none;";} else {$showar="";}	
$sectionout_mailvio=<<<EOT
			<span class="show_email show_mailvio" style="$showar" >

						$mailviolist
				$mailviolist2
			</span>					

EOT;

echo $sectionout_mailvio;

if (isset($_SESSION['mailjet_lists']))
	{
		
		$mailjetlist='<label>List</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which list to use."><select id="addon_email_mailjet_list" name="addon_email_mailjet_list" class="custom-select" data-size="4">'.$_SESSION['mailjet_lists'].'</select>';
		$mailjetlist2="</span>";
		$nameflag=1;
	}else{
		$mailjetlist='<select id="addon_email_mailjet_list" name="addon_email_mailjet_list" style="display:none"><option value="none"></option></select><span style="padding-left:20px">Please visit your Integrations screen to connect your Autoresponder.</span>';
		$mailjetlist2="";
	}
	if ($addon_email_artype!=28) {$showar="display:none;";} else {$showar="";}	
$sectionout_mailjet=<<<EOT
			<span class="show_email show_mailjet" style="$showar" >

						$mailjetlist
				$mailjetlist2
			</span>					

EOT;

echo ($sectionout_mailjet);

if (isset($_SESSION['sendy_lists']))
	{
		
		$sendylist='<label>List</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which list to use."><select id="addon_email_sendy_list" name="addon_email_sendy_list" class="custom-select" data-size="4">'.$_SESSION['sendy_lists'].'</select>';
		$sendylist2="</span>";
		$nameflag=1;
	}else{
		$sendylist='<select id="addon_email_sendy_list" name="addon_email_sendy_list" style="display:none"><option value="none"></option></select><span style="padding-left:20px">Please visit your Integrations screen to connect your Autoresponder.</span>';
		$sendylist2="";
	}
	if ($addon_email_artype!=26) {$showar="display:none;";} else {$showar="";}	
$sectionout_sendy=<<<EOT
			<span class="show_email show_sendy" style="$showar" >

						$sendylist
				$sendylist2
			</span>					

EOT;

echo ($sectionout_sendy);

if (isset($_SESSION['pabbly_lists']))
	{
		
		$pabblylist='<label>List</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which list to use."><select id="addon_email_pabbly_list" name="addon_email_pabbly_list" class="custom-select" data-size="4">'.$_SESSION['pabbly_lists'].'</select>';
		$pabblylist2="</span>";
		$nameflag=1;
	}else{
		$pabblylist='<select id="addon_email_pabbly_list" name="addon_email_pabbly_list" style="display:none"><option value="none"></option></select><span style="padding-left:20px">Please visit your Integrations screen to connect your Autoresponder.</span>';
		$pabblylist2="";
	}
	if ($addon_email_artype!=26) {$showar="display:none;";} else {$showar="";}	
$sectionout_pabbly=<<<EOT
			<span class="show_email show_pabbly" style="$showar" >

						$pabblylist
				$pabblylist2
			</span>					

EOT;

echo ($sectionout_pabbly);

if (isset($_SESSION['fm_lists']))
	{
		
		$fmlist='<label>Funnel</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which funnel to use."><select id="addon_email_fm_list" name="addon_email_fm_list" class="custom-select" data-size="4">'.$_SESSION['fm_lists'].'</select>';
		$fmlist2="</span>";
		$nameflag=1;
	}else{
		$fmlist='<select id="addon_email_fm_list" name="addon_email_fm_list" style="display:none"><option value="none"></option></select><span style="padding-left:20px">Please visit your Integrations screen to connect your Autoresponder.</span>';
		$fmlist2="";
	}
	if ($addon_email_artype!=24) {$showar="display:none;";} else {$showar="";}	
$sectionout_fm=<<<EOT
			<span class="show_email show_fm" style="$showar" >

						$fmlist
				$fmlist2
			</span>					

EOT;

echo ($sectionout_fm);



if (isset($_SESSION['bm_lists']))
	{
		$benchmarklist='<label>Benchmark List</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which list to use."><select id="addon_email_benchmark_list" name="addon_email_benchmark_list" class="custom-select" data-size="4">'.$_SESSION['bm_lists'].'</select>';
		$benchmarklist2="</span>";
		$nameflag=1;
	}else{
		$benchmarklist='<select id="addon_email_benchmark_list" name="addon_email_benchmark_list" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$benchmarklist2="";
	}
if ($addon_email_artype!=23) {$showar="display:none;";} else {$showar="";}		
$sectionout_benchmark=<<<EOT
	
			<span class="show_email show_benchmark" style="$showar" >
				
				

						$benchmarklist
					
			
				$benchmarklist2
			</span>					
						

EOT;

echo ($sectionout_benchmark);

if (isset($_SESSION['sendiio_lists']))
	{
		$sendiiolist='<label>Sendiio List</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which list to use."><select id="addon_email_sendiio_list" name="addon_email_sendiio_list" class="custom-select" data-size="4">'.$_SESSION['sendiio_lists'].'</select>';
		$sendiiolist2="</span>";
		$nameflag=1;
	}else{
		$sendiiolist='<select id="addon_email_sendiio_list" name="addon_email_sendiio_list" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$sendiiolist2="";
	}
if ($addon_email_artype!=17) {$showar="display:none;";} else {$showar="";}		
$sectionout_sendiio=<<<EOT
	
			<span class="show_email show_sendiio" style="$showar" >
				
				

						$sendiiolist
					
			
				$sendiiolist2
			</span>					
						

EOT;

echo ($sectionout_sendiio);

if (isset($_SESSION['klaviyo_lists']))
	{
		$klaviyolist='<label>klaviyo List</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which list to use."><select id="addon_email_klaviyo_list" name="addon_email_klaviyo_list" class="custom-select" data-size="4">'.$_SESSION['klaviyo_lists'].'</select>';
		$klaviyolist2="</span>";
		$nameflag=1;
	}else{
		$klaviyolist='<select id="addon_email_klaviyo_list" name="addon_email_klaviyo_list" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$klaviyolist2="";
	}
if ($addon_email_artype!=19) {$showar="display:none;";} else {$showar="";}		
$sectionout_klaviyo=<<<EOT
	
			<span class="show_email show_klaviyo" style="$showar" >
				
				

						$klaviyolist
					
			
				$klaviyolist2
			</span>					
						

EOT;

echo ($sectionout_klaviyo);


if (isset($_SESSION['platformly_projects']))
	{
		$platformlyproject='<label>Platform.ly Project</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which list to use."><select id="addon_email_platformly_project" name="addon_email_platformly_project" class="custom-select" data-size="4">'.$_SESSION['platformly_projects'].'</select>';
		$platformlyproject2="</span>";
		$nameflag=1;
	}else{
		$platformlyproject='<select id="addon_email_platformly_project" name="addon_email_platformly_project" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$platformlyproject2="";
	}
if ($addon_email_artype!=20) {$showar="display:none;";} else {$showar="";}		
$sectionout_platformly=<<<EOT
	
			<span class="show_email show_platformly" style="$showar" >
				
				

						$platformlyproject
					
			
				$platformlyproject2
			</span>					
						

EOT;

echo ($sectionout_platformly);



if (isset($_SESSION['markethero_tags']))
	{
		$marketherotags='<label>Markethero tag</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which list to use."><select id="addon_email_markethero_tag" name="addon_email_markethero_tag" class="custom-select" data-size="4">'.$_SESSION['markethero_tags'].'</select>';
		$marketherotags2="</span>";
		$nameflag=1;
	}else{
		$marketherotags='<select id="addon_email_markethero_tag" name="addon_email_markethero_tag" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$marketherotags2="";
	}
if ($addon_email_artype!=21) {$showar="display:none;";} else {$showar="";}		
$sectionout_markethero=<<<EOT
	
			<span class="show_email show_markethero" style="$showar" >
				
				

						$marketherotags
					
			
				$marketherotags2
			</span>					
						

EOT;

echo ($sectionout_markethero);

if (isset($_SESSION['ontraport_campaigns']))
	{
		$ontraportcampaigns='<label>Ontraport Campaign</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which list to use."><select id="addon_email_ontraport_campaign" name="addon_email_ontraport_campaign" class="custom-select" data-size="4">'.$_SESSION['ontraport_campaigns'].'</select>';
		$ontraportcampaigns2="</span>";
		$nameflag=1;
	}else{
		$ontraportcampaigns='<select id="addon_email_ontraport_campaign" name="addon_email_ontraport_campaign" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$ontraportcampaigns2="";
	}
if ($addon_email_artype!=22) {$showar="display:none;";} else {$showar="";}		
$sectionout_ontraport=<<<EOT
	
			<span class="show_email show_ontraport" style="$showar" >
				
				

						$ontraportcampaigns
					
			
				$ontraportcampaigns2
			</span>					
						

EOT;

echo ($sectionout_ontraport);

if (isset($_SESSION['sendfox_lists']))
	{
		$sendfoxlist='<label>sendfox List</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which list to use."><select id="addon_email_sendfox_list" name="addon_email_sendfox_list" class="custom-select" data-size="4">'.$_SESSION['sendfox_lists'].'</select>';
		$sendfoxlist2="</span>";
		$nameflag=1;
	}else{
		$sendfoxlist='<select id="addon_email_sendfox_list" name="addon_email_sendfox_list" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$sendfoxlist2="";
	}
if ($addon_email_artype!=18) {$showar="display:none;";} else {$showar="";}		
$sectionout_sendfox=<<<EOT
	
			<span class="show_email show_sendfox" style="$showar" >
				
				

						$sendfoxlist
					
			
				$sendfoxlist2
			</span>					
						

EOT;

echo ($sectionout_sendfox);

if (isset($_SESSION['sg_lists']))
	{
		$sglist='<label>SendGrid List</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which list to use."><select id="addon_email_sg_list" name="addon_email_sg_list" class="custom-select" data-size="4">'.$_SESSION['sg_lists'].'</select>';
		$sglist2="</span>";
		$nameflag=1;
	}else{
		$sglist='<select id="addon_email_sg_list" name="addon_email_sg_list" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$sglist2="";
	}
if ($addon_email_artype!=15) {$showar="display:none;";} else {$showar="";}	
$sectionout_sg=<<<EOT
	
			<span class="show_email show_sg" style="$showar" >
				
				

						$sglist
					
			
				$sglist2
			</span>					
						

EOT;

echo ($sectionout_sg);

if (isset($_SESSION['octo_lists']))
	{
		$emailoctolist='<label>EmailOctopus List</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which list to use."><select id="addon_email_octolist" name="addon_email_octolist" class="custom-select" data-size="4">'.$_SESSION['octo_lists'].'</select>';
		$emailoctolist2="</span>";
		$nameflag=1;
	}else{
		$emailoctolist='<select id="addon_email_octolist" name="addon_email_octolist" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$emailoctolist2="";
	}
	
if ($addon_email_artype!=14) {$showar="display:none;";} else {$showar="";}		
$sectionout_emailocto=<<<EOT
	
			<span class="show_email show_octo" style="$showar" >
				
				

						$emailoctolist
					
			
				$emailoctolist2
			</span>					
						

EOT;

echo ($sectionout_emailocto);


if (isset($_SESSION['moo_lists']))

	{
		$emailmoolist='<label>Moosend List</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which list to use."><select id="addon_email_moolist" name="addon_email_moolist" class="custom-select" data-size="4">'.$_SESSION['moo_lists'].'</select>';
		$emailmoolist2="</span>";
		$nameflag=1;
	}else{
		$emailmoolist='<select id="addon_email_moolist" name="addon_email_moolist" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$emailmoolist2="";
	}
if ($addon_email_artype!=16) {$showar="display:none;";} else {$showar="";}		
$sectionout_emailmoo=<<<EOT
	
			<span class="show_email show_moo" style="$showar" >
				
				

						$emailmoolist
					
			
				$emailmoolist2
			</span>					
						

EOT;

echo ($sectionout_emailmoo);

if (isset($_SESSION['arpreach_lists']))
	{
		$arpreachlist='<label>ArpReach List</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which ArpReach list to use."><select id="addon_email_arp_list" name="addon_email_arp_list" class="custom-select" data-size="4">'.$_SESSION['arpreach_lists'].'</select>';
		$arpreachlist2="</span>";
		$nameflag=1;
	}else{
		$arpreachlist='<select id="addon_email_arp_list" name="addon_email_arp_list" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$arpreachlist2="";
	}
if ($addon_email_artype!=11) {$showar="display:none;";} else {$showar="";}		
$sectionout_arpreach=<<<EOT
	
			<span class="show_email show_arpreach" style="$showar" >
				
				

						$arpreachlist
					
			
				$arpreachlist2
			</span>					
						

EOT;

echo ($sectionout_arpreach);

if (isset($_SESSION['sendlane_lists']))
	{
		$sendlanelist='<label>Sendlane List</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which Sendlane list to use."><select id="addon_email_sendl_list" name="addon_email_sendl_list" class="custom-select" data-size="4">'.$_SESSION['sendlane_lists'].'</select>';
		$sendlanelist2="</span>";
		$nameflag=1;
	}else{
		$sendlanelist='<select id="addon_email_sendl_list" name="addon_email_sendl_list" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$sendlanelist2="";
	}
if ($addon_email_artype!=10) {$showar="display:none;";} else {$showar="";}	
$sectionout_sendlane=<<<EOT
	
			<span class="show_email show_sendlane" style="$showar" >
				
				

						$sendlanelist
					
			
				$sendlanelist2
			</span>					
						

EOT;

echo ($sectionout_sendlane);

if (isset($_SESSION['icontact_lists']))
	{
		$icontactlist='<label>iContact List</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which iContact list to use."><select id="addon_email_icont_list" name="addon_email_icont_list" class="custom-select" data-size="4">'.$_SESSION['icontact_lists'].'</select>';
		$icontactlist2="</span>";
		$nameflag=1;
	}else{
		$icontactlist='<select id="addon_email_icont_list" name="addon_email_icont_list" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$icontactlist2="";
	}
	
if ($addon_email_artype!=9) {$showar="display:none;";} else {$showar="";}
$sectionout_icontact=<<<EOT
	
			<span class="show_email show_icontact" style="$showar" >
				
				

						$icontactlist
					
			
				$icontactlist2
			</span>					
						

EOT;

echo ($sectionout_icontact);


if (isset($_SESSION['drip_lists']))
	{
		$driplist='<label>Drip Campaign</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which Drip Campaign to use."><select id="addon_email_dripcamp" name="addon_email_dripcamp" class="custom-select" data-size="4">'.$_SESSION['drip_lists'].'</select>';
		$driplist2="</span>";
		$nameflag=1;
	}else{
		$driplist='<select id="addon_email_dripcamp" name="addon_email_dripcamp" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$driplist2="";
	}
	
if ($addon_email_artype!=8) {$showar="display:none;";} else {$showar="";}	
	
$sectionout_drip=<<<EOT
	
			<span class="show_email show_drip" style="$showar" >
				
				

						$driplist
					
			
				$driplist2
			</span>					
						

EOT;

echo ($sectionout_drip);


if (isset($_SESSION['pk_lists']))
	{
		
		$pklist='<label>Perkzilla Campaign</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which Perkzilla Campaign to use."><select id="addon_email_pkcamp" name="addon_email_pkcamp" class="custom-select" data-size="4">'.$_SESSION['pk_lists'].'</select>';
		$pklist2="</span>";
		$nameflag=1;
	}else{
		$pklist='<select id="addon_email_pkcamp" name="addon_email_pkcamp" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$pklist2="";
	}
	
if ($addon_email_artype!=7) {$showar="display:none;";} else {$showar="";}	
	
$sectionout_pk=<<<EOT
	
			<span class="show_email show_pk" style="$showar" >
				
				

						$pklist
					
			
				$pklist2
			</span>					
						

EOT;

echo ($sectionout_pk);

if (isset($_SESSION['aw_lists']))
	{
		$nameflag=1;
		$awlist='<label>Aweber List</label><br><span  data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which Aweber list to use."><select id="addon_email_aweberlist" name="addon_email_aweberlist" class="custom-select" data-size="4" >'.$_SESSION['aw_lists'].'</select>';
		$awlist2="</span>";
	}else{
		$awlist='<select id="addon_email_aweberlist" name="addon_email_aweberlist" style="display:none;"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$awlist2="";
	}
	
if ($addon_email_artype!=0) {$showar="display:none;";} else {$showar="";}	


$sectionout_aw=<<<EOT
	
			<span class="show_email show_aweber" style="$showar" >
				
				

						$awlist
					
			
				$awlist2
			</span>					
						

EOT;

echo ($sectionout_aw);
if ($addon_email_artype!=1){$showar="display:none;";}else{$showar="";}

if (isset($_SESSION['gr_lists']))
	
	{
		$nameflag=1;
		$grlist='<label>GetResponse Campaign</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which GetResponse Campaign to use."><select id="addon_email_grcampaign" name="addon_email_grcampaign" class="custom-select" data-size="4">'.$_SESSION['gr_lists'].'</select>';
		$grlist2="</span>";
	}else{
		$grlist='<select id="addon_email_grcampaign" name="addon_email_grcampaign" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$grlist2="";
	}
$sectionout_gr=<<<EOT
	
			<span class=" show_email show_gr" style="$showar" >
				
				

						$grlist
					
			
				$grlist2
			</span>					
						

EOT;

echo ($sectionout_gr);
if ($addon_email_artype!=2){$showar="display:none;";}else{$showar="";}	
if (isset($_SESSION['ck_lists']))
	{
		$nameflag=1;
		$cklist='<label>ConvertKit Form</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which ConvertKit Form to use."><select id="addon_email_ckform" name="addon_email_ckform" class="custom-select" data-size="4">'.$_SESSION['ck_lists'].'</select>';
		$cklist2="</span>";
	}else{
		$cklist='<select id="addon_email_ckform" name="addon_email_ckform" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$cklist2="";
	}
$sectionout_ck=<<<EOT
	
			<span class="show_email show_ck" style="$showar" >
				
				

						$cklist
					
			
				$cklist2
			</span>					
						

EOT;

echo ($sectionout_ck);
if ($addon_email_artype!=3){$showar="display:none;";}else{$showar="";}	
$mclist3="";
if (isset($_SESSION['mc_lists']))
	{
	$nameflag=1;
		$mclist='<label>Mailchimp List</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which MailChimp List to use."><select id="addon_email_mclist" name="addon_email_mclist" class="custom-select" data-size="4">'.$_SESSION['mc_lists'].'</select>';
		$mclist2="</span>";
		$mclist3='<span class="show_email show_mc" style="display:none" data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Enable double optin rather than single.">&nbsp;&nbsp;Enable Double Optin<span > <input style="margin-left:7px" type="checkbox"  id="addon_email_mcoptin" name="addon_email_mcoptin" ></span>	</span>';
	}else{
		$mclist='<select id="addon_email_mclist" name="addon_email_mclist" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$mclist2="";
	}
$sectionout_mc=<<<EOT
	
			<span class="show_email show_mc" style="$showar" >
				
				

						$mclist
					
			
				$mclist2
				
			</span>
			
						

EOT;

echo ($sectionout_mc);
if ($addon_email_artype!=4){$showar="display:none;";}else{$showar="";}	
if (isset($_SESSION['ac_lists']))
	{
		$nameflag=1;
		$aclist='<label>Active Campaign List</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which Active Campaign List to use."><select id="addon_email_aclist" name="addon_email_aclist" class="custom-select" data-size="4">'.$_SESSION['ac_lists'].'</select>';
		$aclist2="</span>";
	}else{
		$aclist='<select id="addon_email_aclist" name="addon_email_aclist" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$aclist2="";
	}
$sectionout_ac=<<<EOT
	
			<span class="show_email show_ac" style="$showar" >
				
				

						$aclist
					
			
				$aclist2
			</span>					
						

EOT;

echo ($sectionout_ac);
if ($addon_email_artype!=6){$showar="display:none;";}else{$showar="";}	
if (isset($_SESSION['ml_lists']))
	{
		$nameflag=1;
		$mllist='<label>MailerLite Group</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which MailerLite Group to use."><select id="addon_email_mlgroup" name="addon_email_mlgroup" class="custom-select" data-size="4">'.$_SESSION['ml_lists'].'</select>';
		$mllist2="</span>";
	}else{
		$mllist='<select id="addon_email_mlgroup" name="addon_email_mlgroup" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Connect your Autoresponder</span><br><a href="adminarintegrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$mllist2="";
	}
$sectionout_ml=<<<EOT
	
			<span class="show_email show_ml" style="$showar" >
				
				

						$mllist
					
			
				$mllist2
			</span>					
						

EOT;

echo ($sectionout_ml);
if ($addon_email_artype!=5){$showar="display:none;";}else{$showar="";}	
		$formlist='<label>Custom Form Code</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Paste your custom for code here (HTML, not Javascript)."><textarea id="addon_email_form" name="addon_email_form" type="text" class="form-control"  style="resize:none; box-sizing: border-box;vertical-align: top;">'.base64_decode($addon_email_form).'</textarea>';
		$formlist2="</span>";


$sectionout_form=<<<EOT
			<span class="show_email show_form" style="$showar" >
			

						$formlist
				$formlist2
			</span>

EOT;

echo ($sectionout_form);

?>						

								</div>
						</div>
						

								
						<div class="row topgap">
							<div class="col-2">
								<label>Zapier Webhook</label>
								<input type="text" class="form-control" placeholder="Optional Zapier Webhook" maxlength="250" name="addon_email_zapier_hook" id="addon_email_zapier_hook" value="<?php echo $addon_email_zapier_hook; ?>">
							</div>
							<div class="col-2">
								<label>Other Webhook</label>
								<input type="text" class="form-control" placeholder="Optional Other Webhook" maxlength="250" name="webhook2" id="webhook2" value="<?php echo $webhook2; ?>">
							</div>
							<div class="col-2">
								<label>Add to Webinar</label>
								<select name="addon_email_webbytype" id="addon_email_webbytype" class="form-control custom-select" style="">
									<option value="0" <?php if ($addon_email_webbytype==0){echo 'selected="selected"';} ?> >None</option>
									<option value="1" <?php if ($addon_email_webbytype==1){echo 'selected="selected"';} ?> >WebinarJam</option>
									<option value="2" <?php if ($addon_email_webbytype==2){echo 'selected="selected"';} ?> >EverWebinar</option>
									<option value="3" <?php if ($addon_email_webbytype==3){echo 'selected="selected"';} ?> >Demio</option>
								</select>							
							</div>
							<div class="col-3">
							
<?php
// Write out webinar lists


if ($addon_email_webbytype!=1){$show_webby="display:none;";}else{$show_webby="";}	
if (isset($_SESSION['webjam_webinars']))
	{
		
		$webjam_webinars='<label>WebinarJam Webinars</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which webinar to use."><select id="addon_email_webjamid" name="addon_email_webjamid" class="custom-select" data-size="4">'.$_SESSION['webjam_webinars'].'</select>';
		$webjam_webinars2="</span>";
	}else{
		$webjam_webinars='<select id="addon_email_webjamid" name="addon_email_webjamid" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Please visit your Integrations screen to connect your Webinar API</span><br><a href="integrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$webjam_webinars2="";
	}
$sectionout_webjam=<<<EOT
	
			<span class="show_webinar show_webjam" style="$show_webby" >
				
				

						$webjam_webinars
					
			
				$webjam_webinars2
			</span>					
						

EOT;
echo($sectionout_webjam);

if ($addon_email_webbytype!=2){$show_webby="display:none;";}else{$show_webby="";}	
if (isset($_SESSION['everweb_webinars']))
	{
		
		$everweb_webinars='<label>EverWebinar Webinars</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which webinar to use."><select id="addon_email_everwebid" name="addon_email_everwebid" class="custom-select" data-size="4">'.$_SESSION['everweb_webinars'].'</select>';
		$everweb_webinars2="</span>";
	}else{
		$everweb_webinars='<select id="addon_email_everwebid" name="addon_email_everwebid" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Please visit your Integrations screen to connect your Webinar API</span><br><a href="integrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$everweb_webinars2="";
	}
$sectionout_everweb=<<<EOT
	
			<span class="show_webinar show_everweb" style="$show_webby" >
				
				

						$everweb_webinars
					
			
				$everweb_webinars2
			</span>					
						

EOT;
echo($sectionout_everweb);

if ($addon_email_webbytype!=3){$show_webby="display:none;";}else{$show_webby="";}	
if (isset($_SESSION['demio_webinars']))
	{
		
		$demio_webinars='<label>Demio Webinars</label><br><span data-toggle="tooltip" data-trigger="focus click hover" data-placement="top" title="Select which webinar to use."><select id="addon_email_demioid" name="addon_email_demioid" class="custom-select" data-size="4">'.$_SESSION['demio_webinars'].'</select>';
		$demio_webinars2="</span>";
	}else{
		$demio_webinars='<select id="addon_email_demioid" name="addon_email_demioid" style="display:none"><option value="none"></option></select><span class="non_selectable" style="padding-left:20px">Please visit your Integrations screen to connect your Webinar API</span><br><a href="integrations.php" class="btn btn-info shadow integrations_button" style="margin-left:20px; margin-top:7px;"><i class="fas fa-cog text-white-50"></i> Integrations</a>';
		$demio_webinars2="";
	}
$sectionout_demio=<<<EOT
	
			<span class="show_webinar show_demio" style="$show_webby" >

						$demio_webinars
			
				$demio_webinars2
			</span>					
						

EOT;
echo($sectionout_demio);
?>							
							</div>
							<div class="col-3">
								<label>&nbsp;</label>
								<br><button id="saveAr" class="btn btn-success btn-icon-split" style="margin-right:5px;"><span class="icon text-white-50"><i class="fas fa-save"></i></span><span class="text">Save Settings</span></button>							
							</div>
						</div>
								
							</div>
				
							</div>
						</div>
					<!-- /.container-fluid -->
					</div>
				
				
			<!-- End of Main Content -->
			<!-- Footer -->
			<footer class="sticky-footer bg-white">
				<div class="container my-auto">
					<div class="copyright text-center my-auto"><?php echo $footer; ?></div>
				</div>
			</footer>
			<!-- End of Footer -->
		</div>
		<!-- End of Content Wrapper -->
		</div>
		<!-- End of Page Wrapper -->
		<!-- Scroll to Top Button-->
		<a class="scroll-to-top rounded" href="#page-top">
			<i class="fas fa-angle-up"></i>
		</a>
		<!-- Logout Modal-->
		<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="logoutLabel">Ready to Leave?</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
						<a class="btn btn-primary" href="adminlogout.php">Logout</a>
					</div>
				</div>
			</div>
		</div>
		<div class="toast bg-gray-200" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000" style="position: absolute; top: 1rem; right: 1rem;" id="toast">
			<div class="toast-header bg-gray-400">
				<strong class="mr-auto">
					<span id="toastHeader"></span>
				</strong>
				<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="toast-body">
				<span id="toastBody"></span>
			</div>
		</div>
		<!-- Bootstrap core JavaScript-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.min.js" integrity="sha512-7rusk8kGPFynZWu26OKbTeI+QPoYchtxsmPeBqkHIEXJxeun4yJ4ISYe7C6sz9wdxeE1Gk3VxsIWgCZTc+vX3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<!-- Core plugin JavaScript-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js" integrity="sha512-0QbL0ph8Tc8g5bLhfVzSqxe9GERORsKhIn1IrpxDAgUsbBGz/V7iSav2zzW325XGd1OMLdL4UiqRJj702IeqnQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<!-- Custom scripts for all pages-->
		<script src="libs/sbadmin2/js/sb-admin-2.min.js"></script>
		<!-- Page level plugins -->
<script>
$(document).ready(function() {

});

$('#saveAr').click(function(){
	var ar=$('#addon_email_artype').val();
	var zap=$('#addon_email_zapier_hook').val();
	var hook2=$('#webhook2').val();
	var web=$('#addon_email_webbytype').val();

	if (ar!=999) {
		let arfields=['','addon_email_grcampaign','addon_email_ckform','addon_email_mclist','addon_email_aclist','addon_email_form','addon_email_mlgroup','','','addon_email_icont_list','addon_email_sendl_list','addon_email_arp_list','','','addon_email_octolist','addon_email_sg_list','addon_email_moolist','addon_email_sendiio_list','addon_email_sendfox_list','addon_email_klaviyo_list','addon_email_platformly_project','addon_email_markethero_tag','addon_email_ontraport_campaign','addon_email_benchmark_list','addon_email_fm_list','','addon_email_pabbly_list','addon_email_sendy_list','addon_email_mailjet_list','addon_email_mailvio_group','','addon_email_mautic_segment'];
		p1=arfields[ar];
		d1=$('#'+p1).val();
		console.log("P1: "+p1+" - D1: "+d1);
		if (d1!="none"){
			$.ajax({
				url: 'AJAX_admin_updatear.php',
				type: 'POST',
				data: {
					'p1': p1,
					'p2': 'addon_email_artype',
					'p3': 'filler',
					'd1': d1,
					'd2': ar,
					'd3': 'filler'
				},
				success: function(response) {

				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.error('Error:', textStatus, ',', errorThrown);
				}
			});			
		}
	}

	$.ajax({
		url: 'AJAX_admin_updatear.php',
		type: 'POST',
		data: {
			'p1': 'addon_email_zapier_hook',
			'p2': 'webhook2',
			'p3': 'addon_email_webbytype',
			'd1': zap,
			'd2': hook2,
			'd3': web
		},
		success: function(response) {
		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.error('Error:', textStatus, ',', errorThrown);
		}
	});
	
	var value = $('#addon_email_everwebid').val();
	var d1 = (value === "none") ? "" : value;
	var value = $('#addon_email_webjamid').val();
	var d2 = (value === "none") ? "" : value;
	var value = $('#addon_email_demioid').val();
	var d3 = (value === "none") ? "" : value;
	$.ajax({
		url: 'AJAX_admin_updatear.php',
		type: 'POST',
		data: {
			'p1': 'addon_email_everwebid',
			'p2': 'addon_email_webjamid',
			'p3': 'addon_email_demioid',
			'd1': d1,
			'd2': d2,
			'd3': d3
		},
		success: function(response) {
			$('#toastHeader').text('SAVED');
			$('#toastBody').text('Your settings have been saved!');
			$('#toast').toast('show');
		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.error('Error:', textStatus, ',', errorThrown);
		}
	});	
	
});

$('#adminLogout').click(function() {
	$('#logoutModal').modal('show');
});



// Webinar select box

$('#addon_email_webbytype').change(function() {
	$('.show_webjam,.show_everweb,.show_demio').hide();
	
	if ($('#addon_email_webbytype').val()=="0")  {
		$('.show_webjam,.show_everweb,.show_demio').hide();
	} else if ($('#addon_email_webbytype').val()=="1")  {
		$('.show_webjam').show();
	} else if ($('#addon_email_webbytype').val()=="2")  {
		$('.show_everweb').show();
	} else if ($('#addon_email_webbytype').val()=="3")  {
		$('.show_demio').show();
	}
});


$('#addon_email_artype').change(function() {

	
	$('#addon_email_tags').val();
	//$('.show_zapier').hide();
	$('#show_tags_multi_cr,#show_tags_multi_ac,#show_tags_multi_dr,#show_tags_multi_ck,.show_fm').hide();
	$('#addon_email_tags').show();
	$('.show_email').hide();
	
	if ($('#addon_email_artype').val()=="999")  {
		$('#show_tags').hide();
		$('#show_name').hide();
		
	} else if ($('#addon_email_artype').val()=="0")  {
		$('.show_aweber').show();
		$('#show_tags').show();
		$('#show_name').show();
		
	} else if ($('#addon_email_artype').val()=="1")  {

		$('.show_gr').show();
		$('#show_tags').hide();
		$('#show_name').show();
		
	} else if ($('#addon_email_artype').val()=="2")  {
		
		$('.show_ck').show();
		$('#show_tags_multi_ck').show();
		$('#addon_email_tags').hide();

		$('#show_tags').show();
		$('#show_name').show();
	} else if ($('#addon_email_artype').val()=="3")  {

		$('.show_mc').show();
		$('#show_tags').show();
		$('#show_name').show();
	} else if ($('#addon_email_artype').val()=="4")  {

		$('.show_ac').show();
		$('#show_tags').show();
		$('#show_name').show();
		$('#show_tags_multi_ac').show();
		$('#addon_email_tags').hide();
	} else if ($('#addon_email_artype').val()=="6")  {

		$('.show_ml').show();
		$('#show_tags').hide();		
		$('#show_name').show();
	} else if ($('#addon_email_artype').val()=="5")  {

		$('.show_form').show();
		$('#show_tags').hide();
		$('#show_name').show();
		
	} else if ($('#addon_email_artype').val()=="7")  {

		$('.show_pk').show();
		$('#show_tags').hide();
		$('#show_name').show();
		
	} else if ($('#addon_email_artype').val()=="8")  {

		$('.show_drip').show();
		$('#show_tags').show();
		$('#show_name').hide();
		$('#show_tags_multi_dr').show();
		$('#addon_email_tags').hide();		
		
	} else if ($('#addon_email_artype').val()=="9")  {

		$('.show_icontact').show();
		$('#show_tags').hide();
		$('#show_name').show();
		
	}  else if ($('#addon_email_artype').val()=="10")  {

		$('.show_sendlane').show();
		$('#show_tags').hide();
		$('#show_name').show();
		
	}  else if ($('#addon_email_artype').val()=="11")  {

		$('.show_arpreach').show();
		$('#show_tags').hide();
		$('#show_name').show();
		
	} else if ($('#addon_email_artype').val()=="12")  {
		
		$('#show_tags').hide();
		$('#show_name').show();
		$('.show_zapier').show();
		
	} else if ($('#addon_email_artype').val()=="13")  {
		
	
		$('#show_tags').show();
		$('#show_name').show();
		//$('.show_zapier').hide();
		$('#show_tags_multi_cr').show();
		$('#addon_email_tags').hide();
		
	} else if ($('#addon_email_artype').val()=="14")  {
		$('.show_octo').show();
		$('#show_tags').hide();
		$('#show_name').show();
		//$('.show_zapier').hide();
		$('#show_tags_multi_cr').hide();
		$('#addon_email_tags').hide();
		
	} else if ($('#addon_email_artype').val()=="16")  {
		$('.show_moo').show();
		$('#show_tags').hide();
		$('#show_name').show();
		//$('.show_zapier').hide();
		$('#show_tags_multi_cr').hide();
		$('#addon_email_tags').hide();
		
	} else if ($('#addon_email_artype').val()=="15")  {
		$('.show_sg').show();        
		$('#show_tags').hide();
		$('#show_name').show();
		//$('.show_zapier').hide();
		$('#show_tags_multi_cr').hide();
		$('#addon_email_tags').hide();
		
	} else if ($('#addon_email_artype').val()=="17")  {
		$('.show_sendiio').show();
		$('#show_tags').hide();
		$('#show_name').show();
		//$('.show_zapier').hide();
		$('#show_tags_multi_cr').hide();
		$('#addon_email_tags').hide();
		
	} else if ($('#addon_email_artype').val()=="18")  {
		$('.show_sendfox').show();
		$('#show_tags').hide();
		$('#show_name').show();
		//$('.show_zapier').hide();
		$('#show_tags_multi_cr').hide();
		$('#addon_email_tags').hide();
		
	} else if ($('#addon_email_artype').val()=="19")  {
		$('.show_klaviyo').show();
		$('#show_tags').hide();
		$('#show_name').show();
		//$('.show_zapier').hide();
		$('#show_tags_multi_cr').hide();
		$('#addon_email_tags').hide();
		
	} else if ($('#addon_email_artype').val()=="20")  {
		$('.show_platformly').show();
		$('#show_tags').hide();
		$('#show_name').show();
		//$('.show_zapier').hide();
		$('#show_tags_multi_cr').hide();
		$('#addon_email_tags').hide();
		
	} else if ($('#addon_email_artype').val()=="21")  {
		$('.show_markethero').show();
		$('#show_tags').hide();
		$('#show_name').show();
		//$('.show_zapier').hide();
		$('#show_tags_multi_cr').hide();
		$('#addon_email_tags').hide();
		
	} else if ($('#addon_email_artype').val()=="22")  {
		
		$('.show_ontraport').show();
		$('#show_tags').hide();
		$('#show_name').show();
		//$('.show_zapier').hide();
		$('#show_tags_multi_cr').hide();
		$('#addon_email_tags').hide();
		
	} else if ($('#addon_email_artype').val()=="23")  {
		
		$('.show_benchmark').show();
		$('#show_tags').hide();
		//$('#show_name').show();
		//$('.show_zapier').hide();
		$('#show_tags_multi_cr').hide();
		$('#addon_email_tags').hide();
	} else if ($('#addon_email_artype').val()=="24")  {
		
		$('.show_fm').show();
		$('#show_tags').hide();
		//$('#show_name').show();
		$('.show_zapier').hide();
		$('#show_tags_multi_cr').hide();
		$('#addon_email_tags').hide();
	} else if ($('#addon_email_artype').val()=="26")  {
		
		$('.show_pabbly').show();
		$('#show_tags').hide();
		$('.show_zapier').hide();
		$('#show_tags_multi_cr').hide();
		$('#addon_email_tags').hide();
	} else if ($('#addon_email_artype').val()=="27")  {
		
		$('.show_sendy').show();
		$('#show_tags').hide();
		$('.show_zapier').hide();
		$('#show_tags_multi_cr').hide();
		$('#addon_email_tags').hide();
	} else if ($('#addon_email_artype').val()=="28")  {
		
		$('.show_mailjet').show();
		$('#show_tags').hide();
		$('.show_zapier').hide();
		$('#show_tags_multi_cr').hide();
		$('#addon_email_tags').hide();
	} else if ($('#addon_email_artype').val()=="29")  {
		
		$('.show_mailvio').show();
		$('#show_tags').hide();
		$('.show_zapier').hide();
		$('#show_tags_multi_cr').hide();
		$('#addon_email_tags').hide();
	} else if ($('#addon_email_artype').val()=="31")  {
		
		$('.show_mautic').show();
		$('#show_tags').hide();
		$('.show_zapier').hide();
		$('#show_tags_multi_cr').hide();
		$('#addon_email_tags').hide();
	}
	
});


</script>
	</body>
</html>