<?php
// Set content type to JSON
header('Content-Type: application/json');

// Get the raw POST data
$json = file_get_contents('php://input');
$file = "withAnalysis.json";
file_put_contents($file, $json);
$scriptStr = "";
$transcript = "";
// Decode the JSON data
$data = json_decode($json, true);
foreach($data["transcripts"] as $item) {
  if($item["user"] == "user" || $item["user"] == "assistant") {
    $transcript .= $item["user"] . ": " .$item["text"] . "\n";
  }
}
$clientName = $data["analysis"]["clientName"];
$clientNumber = $data["variables"]["from"];
$clientPrefNumber = $data["analysis"]["clientPrefferedPhone"];
$clientAppliance = $data["analysis"]["applianceType"];
$clientBrand = $data["analysis"]["applianceBrand"];
$callId = $data["call_id"];
//Model Number
//Booking Date

$scriptStr .= $transcript;
$scriptStr .= "\nCondensed Data:\n";
$scriptStr .= $clientName . "\n";
$scriptStr .= "<tel:".$clientNumber."|".$clientNumber.">" . "\n";
$scriptStr .= $clientPrefNumber . "\n";
$scriptStr .= $clientAppliance . "\n";
$scriptStr .= $clientBrand . "\n";
$scriptStr .= $data["analysis"]["applianceSymptoms"] . "\n";

// Check if data is successfully decoded
if ($data) {
    // Process the data
    $forwardData = array(
        'text' => $scriptStr
    );

    // Respond with a JSON message
    $response = array(
        'status' => 'success',
        'message' => 'Data received successfully',
        'data' => $data
    );
} else {
    // Error handling
    $response = array(
        'status' => 'error',
        'message' => 'Invalid JSON data'
    );
}
//echo var_dump($response) . "\n";
//SEND TO SLACK
$url = 'https://hooks.slack.com/services/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';

// Convert data to JSON format
$jsonData = json_encode($forwardData);

// cURL setup
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData)
));
$curlresponse = curl_exec($ch);
curl_close($ch);
//END OF SLACK

//START OF ARANOCTIS & ARANEXUS
$url = 'https://flask-service.6pkt8q75gkone.us-east-2.cs.amazonlightsail.com/get_summary';
$transcriptForward = array(
    'prompt' => $transcript
);
$transcriptData = json_encode($transcriptForward);

// cURL setup
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $transcriptData);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($transcriptData)
));
$curlresponse = curl_exec($ch);
curl_close($ch);
//Decode double JSON
$output = json_decode($curlresponse);

//tempOut IS A CLEAN ARANEXUS OUTPUT
//SEND ORIGINAL TRANSCRIPT AND tempOut TO DB
$databaseForward = array(
        'transcript' => $transcript,
        'synopsis' => "",
        'initialPrompt' => $output,
        'name' => $clientName,
        'address' => "",
        'phone' => $clientNumber,
        'prefferedPhone' => $clientPrefNumber,
        'appliance' => $clientAppliance,
        'brand' => $clientBrand,
        'modelNumber' => "",
        'bookingDate' => "",
        'callId' => $callId
    );
$databaseForward = json_encode($databaseForward);
$url = 'https://ammons.dev/projects/ARAForm/storeJSON.php';
// cURL setup
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $databaseForward);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($databaseForward)
));
$curlresponse = curl_exec($ch);
curl_close($ch);
echo $curlresponse . "\n";
//END OF ARANOCTIS AND ARANEXUS
?>
