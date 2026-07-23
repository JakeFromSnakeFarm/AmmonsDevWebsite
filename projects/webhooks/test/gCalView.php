<?php
header('Content-Type: application/json');
$url = "https://ammons.dev/projects/webhooks/";
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, array('token: 181SUdnacirema1013152'));
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$resp = curl_exec($ch);
curl_close($ch);
echo $resp;