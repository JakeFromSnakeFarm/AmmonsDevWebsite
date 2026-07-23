<?php
header('Content-Type: application/json');
$json = file_get_contents('php://input');
$url = 'https://flask-service.6pkt8q75gkone.us-east-2.cs.amazonlightsail.com/update_synopsis';
$data = json_decode($json, true);
// Convert data to JSON format
$jsonData = json_encode($data);

// cURL setup
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData)
));

// Execute the request
$response = curl_exec($ch);
curl_close($ch);
$response = json_decode($response);
$response = json_encode($response);
// Output response
echo json_encode(['message' => stripslashes($response)]);
?>
