<?php
$json = file_get_contents('php://input');
//echo $json ." First Incoming JSON";
//echo $prompt;
$prompt = json_decode($json, true);
$prompt = $prompt['appliancePrompt'];
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.perplexity.ai/chat/completions",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 45,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{
  	\"model\": \"sonar\",
  	\"messages\":
  		[
  		{
  			\"role\": \"system\",
  			\"content\": \"You are providing information to an appliance repair technician about the diagnosis of his problem and how to fix them.\"
  		},
  		{
  		\"role\": \"user\",
  		\"content\": \"$prompt.\"
  		}
  		],
  		\"temperature\": 0.2,
  		\"top_p\": 0.9,
  		\"return_citations\": true,
  		
  		\"return_images\": false,
  		\"return_related_questions\": false,
  		\"search_recency_filter\": \"month\",
  		\"top_k\": 0,
  		\"stream\": false,
  		\"presence_penalty\": 0,
  		\"frequency_penalty\": 1}",
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer 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",
    "Content-Type: application/json"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}