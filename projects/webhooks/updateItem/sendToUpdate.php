<?php
$url = 'https://ammons.dev/projects/webhooks/updateItem/';
$json = "{\"id\":\"ke2be1c4542q679krrlr9vvmtc\",\"technician\":\"Ryan Lehtola\"}";
//echo $json ." Initial call";


//cURL setup
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($json)
));

// Execute the request
$response = curl_exec($ch);
curl_close($ch);
echo $response;
?>

