<?php
header('Content-Type: application/json');
$json = file_get_contents('php://input');
echo json_decode($json) . "\n";
$headers = getallheaders();
if($headers["token"] == '181SUdnacirema1013152') {
echo "match";
};
echo $headers["token"];
?>