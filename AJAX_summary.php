<?php
@set_time_limit(3000);
@ini_set('max_execution_time', 900);

$webscrape=$_POST['webscrape'];

$prompt="The text I am about to give you is the scraped text from a website that is selling an online product of some kind.  I am going to be promoting that product as an affiliate.  Analyse the text and ignore anything that is obviously unrelated to sales copy -  Please give me a 1000 word summary of the product on offer in a way that makes it attractive to my customers when I sell it as an affiliate.  Please also give me 5 key features and benefits - Here is the scraped text : [".$webscrape."]";
			
			
$aiengine=0;
$api_key="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";			
			
sendPrompt($prompt,$aiengine,$api_key);




function sendPrompt($prompt, $aiengine, $api_key){
    $endpoint = "chat/completions";
    $modelname = "gpt-3.5-turbo-16k";
	$aiengine=1;
    if ($aiengine == 1) {
       // $modelname = "gpt-3.5-turbo-1106";
    }
    
    $data = [
        "model" => $modelname,
        "messages" => [["role" => "user", "content" => $prompt]],
        "temperature" => .1,
        "max_tokens" => 4000
    ];

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
      CURLOPT_POSTFIELDS => json_encode($data),
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $api_key,
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);

    if ($response === false){
        echo "APIERROR - " . curl_error($curl);
    } else {
        $decodedResponse = json_decode($response, true);
        if (isset($decodedResponse['error'])) {
            // handle error
            print_r($decodedResponse);
        } else {
			$content = $decodedResponse['choices'][0]['message']['content'];
			echo $content;
        }       
    }
    curl_close($curl);
}


?>
