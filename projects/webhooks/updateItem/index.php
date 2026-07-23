<?php
header('Content-Type: application/json');
//$json = file_get_contents('php://input');
//$data = json_decode($json, true);
//$id = $data["id"];
//$tech = $data["technician"];
//$summary = $data["text"];
//$type = $data["applianceType"];
//$mn = $data["applianceModelNumber"];
//$output = shell_exec("php -f ./updateItem.php {$id} '{$summary}' {$type} {$mn}");
//$output = shell_exec("php -f ./updateItem.php {$id} '{$tech}'");
$output = shell_exec("php -f ./updateItem.php");
echo $output;
?>
