<?php
header('Content-Type: application/json');
$url = 'https://ammons.dev/projects/webhooks/';

// cURL setup
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'token: 181SUdnacirema1013152'
));
$curlresponse = curl_exec($ch);
curl_close($ch);
//Decode double JSON
$output = $curlresponse;
echo $output;

// $scriptStr .= "<tel:+17729132268|7729132268>";


// $forwardData = array(
//     'text' => $scriptStr
// );
// //SEND TO SLACK
// $url = 'https://hooks.slack.com/services/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';

// // Convert data to JSON format
// $jsonData = json_encode($forwardData);

// // cURL setup
// $ch = curl_init($url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
// curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//     'Content-Type: application/json',
//     'Content-Length: ' . strlen($jsonData)
// ));
// $curlresponse = curl_exec($ch);
// curl_close($ch);

// $json = "{\"appliancePrompt\":\"I have an F20 error code with my Whirlpool Duet Front Load Washer WFW9400SW00.\"}";
// //$url = 'https://flask-service.6pkt8q75gkone.us-east-2.cs.amazonlightsail.com/json_test'; //Mechanic AI API
// $url = 'https://ammons.dev/projects/webhooks/AIResponse/pplx.php';
// $prompt = "What is the best way for me to pass a coding job interview?";
// $json = "{\"appliancePrompt\":\"" . $prompt . "\"}";
// //echo $json ." Initial call";


// //cURL setup
// $ch = curl_init($url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
// curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//     'Content-Type: application/json',
//     'Content-Length: ' . strlen($json)
// ));

// // Execute the request
// $response = curl_exec($ch);
// curl_close($ch);
// echo $response;
?>

