<?php
header('Content-Type: application/json');
$json = file_get_contents('php://input');
$data = json_decode($json, true);
$id = $data["id"];
$summary = $data["text"];
$type = $data["applianceType"];
$mn = $data["applianceModelNumber"];
echo $id;
echo $summary;
echo $type;
echo $mn;
$output = shell_exec("php -f ./returnEvent.php {$id} '{$summary}' {$type} {$mn}");
echo $output;
?>
