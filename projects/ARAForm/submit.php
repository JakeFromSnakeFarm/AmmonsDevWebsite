<?php
header('Content-Type: application/json');
$json = file_get_contents('php://input');
$data = json_decode($json, true);
// Convert data to JSON format
$pplxForward = "{\"appliancePrompt\":\"" . $data['appliancePrompt'] . "\"}";
$url = 'https://ammons.dev/projects/webhooks/AIResponse/pplx.php'; //Mechanic AI API
//echo $url;
//cURL setup
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $pplxForward);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($pplxForward)
));

// Execute the request
$response = curl_exec($ch);
curl_close($ch);
echo $response;
$response = json_decode($response, true);
echo var_dump($response);
// Output response
$data["clientAddress"] = $data["clientAddress"] ." ". $data["clientCity"] ." ". $data["clientZip"]; //Change Address to current format for PWA parsing
//$data["applianceSynopsis"] = $response->{"choices"}[0]->{"message"}->{"content"} . "\n";
$data["applianceSynopsis"] = $response["choices"][0]["message"]["content"] . "\n";
if(array_key_exists('citations', $response)){
    $data["applianceSynopsis"] .= implode("\n", $response["citations"]);
    $data["applianceSynopsis"] = str_replace('"', "'", $data["applianceSynopsis"]);
}
echo var_dump($data);
$url = 'https://ammons.dev/projects/webhooks/insert.php'; //Google Cal API
$json = json_encode($data);
// cURL setup
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($json)
));
$curlresponse = curl_exec($ch);
curl_close($ch);
echo json_encode(['message' => 'Data submitted successfully!' . $curlresponse, "sentString" => $data["applianceSynopsis"]]);
?>
