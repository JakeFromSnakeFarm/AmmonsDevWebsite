<?php
header('Content-Type: application/json');
$json = file_get_contents('php://input');
$data = json_decode($json, true);
$summary = $data["clientName"] . " " . $data["clientLastName"] . " | " . $data["clientPhoneNumber"] . " | " . $data["clientAddress"];
$description = $data["applianceSynopsis"];
$startTime = $data["jobDateTime"];
$endTime = $data["endTime"];
$modelNum = $data["applianceModelNumber"];
$applianceType = $data["applianceType"];
$applianceBrand = $data["applianceBrandName"];
$clientSymptoms = $data["appliancePrompt"];
$gateCode = $data["gateCode"];
$repairNotes = $data["repairNotes"];
$assignedTech = $data["tech"];
echo "Summary: $summary\n" .
     "Description: \n" .
     "Start Time: $startTime\n" .
     "End Time: $endTime\n" .
     "Appliance Type: $applianceType\n" .
     "Model Number: $modelNum\n" .
     "Appliance Brand: $applianceBrand\n" .
     "Client Symptoms: $clientSymptoms\n" .
     "Gate Code: $gateCode\n" .
     "Repair Notes: $repairNotes\n" .
     "Assigned Technician: $assignedTech\n";

$command = "php insertTest.php " . escapeshellarg($summary) . ' ' . escapeshellarg($description) . ' ' .
           escapeshellarg($startTime) . ' ' . escapeshellarg($endTime) . ' ' . escapeshellarg($applianceType) . ' ' .
           escapeshellarg($modelNum) . ' ' . escapeshellarg($applianceBrand) . ' ' . escapeshellarg($clientSymptoms) . ' ' .
           escapeshellarg($gateCode) . ' ' . escapeshellarg($repairNotes) . ' ' . escapeshellarg($assignedTech) . ' 2>&1';
$output = shell_exec($command);
echo json_encode(['message' => $output]);
?>