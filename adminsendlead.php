<?php
session_start();

define('ar_integ_token', true);
require_once("ar_integ_settings.php");

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$fullname=$name;
$fname=$name;

$req_dump = print_r($_REQUEST, true);
$emailadd=cleanInput($email);
$fname=cleanInput($name);
$lname="";

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


function cleanInput($input) {
 
  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
  );
 
    $output = preg_replace($search, '', $input);
    return $output;
  }



if ($addon_email_artype>0 && $addon_email_artype<999){

	$req_dump = print_r($_REQUEST, true);
	$emailadd=cleanInput($email);
	$fname=cleanInput($name);
	$lname="";


	$fullname=$fname;

	if ($fname!="" && $lname!=""){$fullname=$fname." ".$lname;}
	if ($fname!="" && $lname==""){$fullname=$fname;}
	if ($fname=="" && $lname!=""){$fullname=$lname;}

	// check for tags
	$tags_present=false;
	if ($addon_email_tags!=""){
		$addon_email_tags=$addon_email_tags;
		$tags=explode(",",$addon_email_tags);
		$finaltags = array_map('trim', $tags);
		$tags_present=true;

	}

	if ($addon_email_artype=="31" && $mautic_url!="" && $addon_email_mautic_segment!="") {  // do Mautic Subscribe
		$token=base64_encode($mautic_user.":".$mautic_pass);
		// Add Contact
		$endpoint = '/api/contacts/new';
		$data = [
			'firstname' => $fname,
			'lastname' => $lname,
			'email' => $emailadd
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $mautic_url . $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic '.$token]);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

		$response = curl_exec($ch);
		$responseData = json_decode($response, true);
		$contactId = $responseData['contact']['id'];
		curl_close($ch);
		// Add to segment
		$endpoint = '/api/segments/'.$addon_email_mautic_segment.'/contact/'.$contactId.'/add';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $mautic_url . $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic '.$token]);
		curl_setopt($ch, CURLOPT_POST, 1);
		$response = curl_exec($ch);
		$responseData = json_decode($response, true);
		curl_close($ch);

	}

	if ($addon_email_artype=="29" && $mailvio_token!="" && $addon_email_mailvio_group!="") {  // do Mailvio Subscribe
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://apiv2.mailvio.com/group/".$addon_email_mailvio_group."/subscriber",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => '{
			"emailAddress": "'.$emailadd.'",
			"blackListed": false,
			"customFields": {
				  "FIRSTNAME": "'.$fullname.'",
				  "LASTNAME": ""
			 }
		}',
			CURLOPT_HTTPHEADER => array(
				"Cache-Control: no-cache",
				"Content-Type: application/json",
				"x-access-token: ".$mailvio_token
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:".$err;
		} else {
			echo $response;
		} 
	}

	if ($addon_email_artype=="28" && $mailjet_api!="" && $mailjet_secret!="" && $addon_email_mailjet_list!="") {  // do Mailjet Subscribe
		$ch1 = curl_init();

		curl_setopt($ch1, CURLOPT_URL, "https://api.mailjet.com/v3/REST/contact");
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch1, CURLOPT_USERPWD, $mailjet_api . ":" . $mailjet_secret);
		curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch1, CURLOPT_POST, true);

		$data1 = array(
			"IsExcludedFromCampaigns" => "true",
			"Name" => $fullname,
			"Email" => $emailadd
		);

		curl_setopt($ch1, CURLOPT_POSTFIELDS, json_encode($data1));

		$response1 = curl_exec($ch1);

		curl_close($ch1);
		$ch2 = curl_init();
		curl_setopt($ch2, CURLOPT_URL, "https://api.mailjet.com/v3/REST/listrecipient");
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch2, CURLOPT_USERPWD, $mailjet_api . ":" . $mailjet_secret);
		curl_setopt($ch2, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch2, CURLOPT_POST, true);

		$data2 = array(
			"IsUnsubscribed" => "true",
			"ContactAlt" => $emailadd,
			"ListID" => $addon_email_mailjet_list
		);

		curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($data2));
		$response2 = curl_exec($ch2);
		
		curl_close($ch2);
	}



	if ($addon_email_artype=="26" && $pabblytoken!="" && $emailadd!="" && $addon_email_pabbly_list!="") {  // do Pabbly Subscribe
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://emails.pabbly.com/api/subscribers',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>'{
			"import": "single",
			"list_id": "'.$addon_email_pabbly_list.'",
			"email": "'.$emailadd.'",
			"name": "'.$fullname.'"
			
		}',
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Authorization: Bearer '.$pabblytoken 
		  ),
		));
		$response = curl_exec($curl);

		curl_close($curl);

	}

	if ($addon_email_artype=="27" && $sendy_api!="" && $sendy_url!="" && $addon_email_sendy_list!="") {  // do Sendy Subscribe

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $sendy_url . '/subscribe');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
			'name' => $fullname,
			'email' => $emailadd,
			'list' => $addon_email_sendy_list,
			'api_key' => $sendy_api
		));
		$response = curl_exec($ch);
		
		curl_close($ch);	

	}


	if ($addon_email_artype=="1" && $gr_api!="" && $emailadd!="" && $addon_email_grcampaign!="") {  // do GR Subscribe
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.getresponse.com/v3/contacts',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>'{
			"name": "'.$fullname.'",
			"campaign": {
				"campaignId": "'.$addon_email_grcampaign.'"
			},
			"email": "'.$emailadd.'"
		}
		',
		  CURLOPT_HTTPHEADER => array(
			'X-Auth-Token: api-key '.$gr_api,
			'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);
		
		curl_close($curl);

	}

	if ($addon_email_artype=="2" && $ck_api!="" && $emailadd!="" && $addon_email_ckform!="") {  // do CK Subscribe
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.convertkit.com/v3/forms/'.$addon_email_ckform.'/subscribe?api_key='.$ck_api,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => array('email' => $emailadd,'first_name' => $fullname),
		));
		$response = curl_exec($curl);
		
		curl_close($curl);
	}



	if ($addon_email_artype=="10" && $sendl_api!="" && $sendl_hash!="" && $emailadd!="" && $sendl_domain!="" && $addon_email_sendl_list!="") {  // SICOM do sendlane Subscribe
		try { 
					
			if ($lname==""){
				$names=explode(" ",$fname);
				$fname=$names[0];
				$lname=$names[1];
			}
			
			$subscriber= "$fname $lname<$emailadd>";
			if (strpos($sendl_domain, 'sendlane.com') == false) {
				$sendl_domain.=".sendlane.com";
			}
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,            "https://".$sendl_domain."/api/v1/list-subscribers-add" );  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST,           true);
			curl_setopt($ch, CURLOPT_POSTFIELDS,     "api=$sendl_api&hash=$sendl_hash&email=$subscriber&list_id=$addon_email_sendl_list"); 
			$result=curl_exec ($ch);

		} catch (Exception $e) {
				file_put_contents("TEMPSLerror.txt",$e->getMessage());
		}
	}


	if ($addon_email_artype=="11" && $arp_key!="" && $arp_path!="" && $emailadd!="" && $addon_email_arp_list!="") {  // SICOM do ArpReach Subscribe

		try { 
		
					
			if ($lname==""){
				$names=explode(" ",$fname);
				$fname=$names[0];
				$lname=$names[1];
			}
			// Modify Arp path if needed - check for a.php and then remove single or double slash at end
			$arp_path=str_replace("a.php","",$arp_path);
			$arp_path=rtrim($arp_path,"/");
			$arp_path1="$arp_path/a.php/api/add_contact";
			$arp_path2="$arp_path/a.php/api/add_to_list";
			
			$fullname= "$fname $lname";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,            $arp_path1 );  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST,           true);
			curl_setopt($ch, CURLOPT_POSTFIELDS,     "api_key=$arp_key&email_address=$emailadd&first_name=$fname&last_name=$lname&full_name=$fullname"); 
			$result=curl_exec ($ch);
			
			curl_setopt($ch, CURLOPT_URL,            $arp_path2 );  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST,           true);
			curl_setopt($ch, CURLOPT_POSTFIELDS,     'api_key='.$arp_key.'&email_address='.$emailadd.'&lists=[{"list":"'.$addon_email_arp_list.'","status":0}]'); 
			$result=curl_exec ($ch);
			
		} catch (Exception $e) {
				file_put_contents("AR-ARPerror.txt",$e->getMessage());
		}
	}



	if ($addon_email_artype=="3" && $mc_api!="" && $emailadd!="" && $addon_email_mclist!="") {  // SICOM do MC Subscribe

		$parts = explode('-', $mc_api);
		if (count($parts) < 2) {
			die('Invalid Mailchimp API key format.');
		}
		$dc = end($parts);

		$url = "https://{$dc}.api.mailchimp.com/3.0/lists/{$addon_email_mclist}/members";

		$data = [
			'email_address' => $emailadd,
			'status' => 'subscribed', // "subscribed" or "pending"
			'merge_fields' => [
				'FNAME' => $fullname,
			]
		];

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, 'anystring:' . $mc_api);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

		$response = curl_exec($ch);
		curl_close($ch);
		
	}

	if ($addon_email_artype=="4" && $ac_api!="" && $ac_url!="" && $emailadd!="" && $addon_email_aclist!="") {  // do AC Subscribe

		$ch = curl_init($ac_url."/api/3/contacts");

		$postData = json_encode([
			"contact" => [
				"email" => $emailadd,
				"firstName" => $fullname
			]
		]);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Api-Token: ' . $ac_api,
			'Content-Type: application/json',
			'Content-Length: ' . strlen($postData)
		]);

		$response = curl_exec($ch);
		curl_close($ch);
		
		$responseData = json_decode($response, true);
		$contactId = $responseData['contact']['id'];

		$ch = curl_init($ac_url."/api/3/contactLists");

		$postData = json_encode([
			"contactList" => [
				"list" => $addon_email_aclist,
				"contact" => $contactId,
				"status" => 1
			]
		]);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Api-Token: ' . $ac_api,
			'Content-Type: application/json',
			'Content-Length: ' . strlen($postData)
		]);

		$response = curl_exec($ch);

		curl_close($ch);
		

	}



	if ($addon_email_artype=="6" && $ml_api!="" && $emailadd!="" && $addon_email_mlgroup!="") {  // SICOM do ML Subscribe
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://connect.mailerlite.com/api/subscribers',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>'{
			"email": "'.trim($emailadd).'",
			"fields": {
			  "name": "'.trim($fname).'",
			  "last_name": "'.trim($lname).'"
			},
			"groups": [
				"'.$addon_email_mlgroup.'"
			]
		}',
		  CURLOPT_HTTPHEADER => array(
			'Authorization: Bearer '.$ml_api,
			'Content-Type: application/json'
		  ),
		));
			
		$resp = curl_exec($curl);
		curl_close($curl);
	}	



	if ($addon_email_artype=="14" && $addon_email_octolist!="") {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://emailoctopus.com/api/1.6/lists/'.$addon_email_octolist.'/contacts');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, '{"api_key":"'.$octo_api.'","email_address":"'.$emailadd.'","fields":{"FirstName":"'.$fullname.'","LastName":"","Birthday":""},"tags":[""],"status":"SUBSCRIBED"}');
		$headers = array();
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		
		curl_close($ch);
	}

	if ($addon_email_artype=="15" && $addon_email_sg_list!="") {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/marketing/contacts');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,'{"list_ids":["'.$addon_email_sg_list.'"],"contacts":[{"email":"'.$emailadd.'","first_name":"'.$fname.'","last_name":"'.$lname.'"}]}');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		$headers = array();
		$headers[] = 'Authorization: Bearer '.$sg_api;
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		curl_close($ch);	
		
	}

	if ($addon_email_artype=="16" && $addon_email_moolist!="") {

		$ch = curl_init();
		$apikey=$moo_api;
		$moo_list=$addon_email_moolist;
		if ($lname!=""){
			$email_name=$fname." ".$lname;
		} else {
			$email_name=$fname;
		}
		$email_addy=$emailadd;
		
		curl_setopt($ch, CURLOPT_URL, "https://api.moosend.com/v3/subscribers/$moo_list/subscribe.json?apikey=$apikey");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, "{
		  \"Name\": \"$email_name\",
		  \"Email\": \"$email_addy\",
		  \"HasExternalDoubleOptIn\": false
		}");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "Accept: application/json"
		));

		$response = curl_exec($ch);
		curl_close($ch);
		
	}



	if ($addon_email_artype=="17" && $addon_email_sendiio_list!=""){
		
	$sendiio_list=$addon_email_sendiio_list;
	$sendiio_email=$emailadd;

		if ($lname!=""){
			$sendiio_name=$fname." ".$lname;
		} else {
			$sendiio_name=$fname;
		}
		
		$curl = curl_init();
		if (trim($sendiio_name)!=""){

			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://sendiio.com/api/v1/lists/subscribe/json',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => '{"email":"'.trim($sendiio_email).'", "email_list_id": "'.$sendiio_list.'", "name":"'.$sendiio_name.'"}',
				CURLOPT_HTTPHEADER => array(
					"content-type: application/json",
					"token: ".$sendiio_token,
					"secret: ".$sendiio_secret
				),
			));
		} else {

			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://sendiio.com/api/v1/lists/subscribe/json',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => '{"email":"'.trim($sendiio_email).'", "email_list_id": "'.$sendiio_list.'"}',
				//CURLOPT_POSTFIELDS => '{"email":"'.trim($sendiio_email).'", "email_list_id": "'.$sendiio_list.'", "name:":"'.$sendiio_name.'"}',
				CURLOPT_HTTPHEADER => array(
					"content-type: application/json",
					"token: ".$sendiio_token,
					"secret: ".$sendiio_secret
				),
			));
			
		}
		
																															 
		$resp = curl_exec($curl);
		
		curl_close($curl);		
		
	}


	if ($addon_email_artype=="18" && $addon_email_sendfox_list!="") {



	$listIds = ["$addon_email_sendfox_list"];  
	$data = [
		"email" => $emailadd
	];

	if (!empty($firstName)) {
		$data["first_name"] = $fullname;
	}

	if (!empty($listIds)) {
		$data["lists"] = $listIds;
	}

	$ch = curl_init("https://api.sendfox.com/contacts");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		"Authorization: Bearer $sendfox_api",
		"Content-Type: application/json"
	]);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	$response = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	$responseData = json_decode($response, true);
	
		
	}

	if ($addon_email_artype=="19" && $addon_email_klaviyo_list!="") {

		if (trim($fname)==""){$fname="Subscriber";}
		if (trim($lname)!=""){$fname=$fname." ".$lname;}
		
		$email_addy=$emailadd;
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://a.klaviyo.com/api/v2/list/'.$addon_email_klaviyo_list.'/subscribe?api_key='.$klaviyo_api,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>'{"profiles":{"email":"'.$email_addy.'","name":"'.$fname.'"}}',
		  CURLOPT_HTTPHEADER => array(
			'api_key: '.$klaviyo_api,
			'Content-Type: application/json'
		  ),
		));	

		$response = curl_exec($curl);
		
		curl_close($curl);
		
	}


	if ($addon_email_artype=="22" && $ontraport_apikey!="") {
		
		if (trim($fname)==""){$fname="Subscriber";}
		if (trim($lname)!=""){$fname=$fname." ".$lname;}
		
		$email_addy=$emailadd;

		$curl = curl_init();
		$postfields='firstname='.urlencode($fname).'&lastname='.urlencode($lname).'&email='.urlencode($email_addy);
		
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.ontraport.com/1/Contacts',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => $postfields,
		  CURLOPT_HTTPHEADER => array(
			'Api-key: '.$ontraport_apikey,
			'Api-Appid: '.$ontraport_appid,
			'Content-Type: application/x-www-form-urlencoded'
		  ),
		));
		$response = curl_exec($curl);

		curl_close($curl);
		

		$response=json_decode($response,true);


		//  Add contact to campaign
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.ontraport.com/1/objects/subscribe',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'PUT',
		  CURLOPT_POSTFIELDS => 'objectID=0&add_list='.$addon_email_ontraport_campaign.'&ids='.$response["data"]["id"].'&sub_type=Campaign',
		  CURLOPT_HTTPHEADER => array(
			'Api-key: '.$ontraport_apikey,
			'Api-Appid: '.$ontraport_appid,		'Content-Type: application/x-www-form-urlencoded'
		  ),
		));
		$response = curl_exec($curl);
		curl_close($curl);	
		
	}




	if ($addon_email_artype=="23" && $benchmark_api!="") {
		
		if (trim($fname)==""){$fname="Subscriber";}
		if (trim($lname)!=""){$fname=$fname." ".$lname;}
		
		$email_addy=$emailadd;

		$ch = curl_init();

		curl_setopt_array($ch, array(
		  CURLOPT_URL => 'https://clientapi.benchmarkemail.com/Contact/'.$addon_email_benchmark_list.'/ContactDetails',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>'{"Data":{"Email":"'.$email_addy.'","FirstName":"'.$fname.'","LastName":"'.$lname.'","EmailPerm":"1"}}',
		  CURLOPT_HTTPHEADER => array(
			'AuthToken: '.$benchmark_api,
			'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($ch);
		
		
	}


	if ($addon_email_artype=="24" && $addon_email_fm_list!=""){
		$fm_list_exp=explode("|",$addon_email_fm_list);

		$fm_list=$fm_list_exp[0];
		$fm_ccid=$fm_list_exp[1];
		$fm_email=$emailadd;
		if ($lname!=""){
			$fm_name=$fname." ".$lname;
		} else {
			$fm_name=$fname;
		}
		$fm_url = "https://funnelmates.com/api/su";
		$fm_data = array('token'=>$fm_key,'list_id'=>$fm_list, 'email'=>$fm_email, 'name'=>$fm_name, 'CCID'=>$fm_ccid);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $fm_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fm_data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$resp  = curl_exec($ch);
		curl_close($ch);
		
	}	

}

// Handle Zapier

if ($addon_email_zapier_hook!="") {  // SICOM do Zapier Webhook
	$maildata=array(array("name" => $fullname, "email" => $emailadd));
	$json = json_encode($maildata);
	$headers = array('Accept: application/json', 'Content-Type: application/json');
	zapier($addon_email_zapier_hook, $json, $headers);	
	
}



// Handle Other Webhook

if ($webhook2!="") {  // SICOM do Zapier Webhook
		
	$maildata=array(array("name" => $fullname, "email" => $emailadd));
	$json = json_encode($maildata);
	$headers = array('Accept: application/json', 'Content-Type: application/json');
	
	zapier($webhook2, $json, $headers);	
	
}

function zapier($url, $json, $headers) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $output = curl_exec($ch);
    curl_close($ch);
}



function pd($url, $json, $headers) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $output = curl_exec($ch);
    curl_close($ch);
}

// Handle Webinars


if ($addon_email_webbytype==1 && $addon_email_webjamid!="") { // WebinarJam
	$name=explode(" ",$fname);
	$fname=$name[0];
	$lname=$name[1];
	if ($fname==""){$fname="Firstname";}
	if ($lname==""){$lname="Lastname";}
	
	$webbyid=explode("-",$addon_email_webjamid);

	$data_array =  array(
		  "api_key"        => $webjam_api,
		  "webinar_id" 	=> $webbyid[0],
		  "first_name" => $fname,
		  "last_name" => $lname,
		  "email" => trim($emailadd),
		  "schedule" => $webbyid[1]
		  );
	$make_call = callAPI('POST', 'https://api.webinarjam.com/webinarjam/register', json_encode($data_array));
	$response = json_decode($make_call, true);	
	

}

if ($addon_email_webbytype==2 && $addon_email_everwebid!="") { // EverWebinar
	$name=explode(" ",$fname);
	$fname=$name[0];
	$lname=$name[1];
	if ($fname==""){$fname="Firstname";}
	if ($lname==""){$lname="Lastname";}
	
	$webbyid=explode("-",$addon_email_everwebid);

	$data_array =  array(
		  "api_key"        => $everweb_api,
		  "webinar_id" 	=> $webbyid[0],
		  "first_name" => $fname,
		  "last_name" => $lname,
		  "email" => trim($emailadd),
		  "schedule" => $webbyid[1]
		  );
	$make_call = callAPI('POST', 'https://api.webinarjam.com/everwebinar/register', json_encode($data_array));
	$response = json_decode($make_call, true);	
	

}

if ($addon_email_webbytype==3 && $addon_email_demioid!="") { // Demio
	$ch = curl_init();

	$url = "https://my.demio.com/api/v1/event/register?api_key={$demio_api}&api_secret={$demio_secret}";
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);

	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

	$postFields = [
		"id" => $addon_email_demioid,
		"ref_url" => null,
		"date_id" => null,
		"name" => $fullname,
		"email" => $emailadd
	];
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
	curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
	$response = curl_exec($ch);
	curl_close($ch);

}
	
	


?>