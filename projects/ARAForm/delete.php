<?php
// Set content type to JSON
header('Content-Type: application/json');

// Get the raw POST data
$json = file_get_contents('php://input');
$file = 'data.json';

if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$currentData = json_decode($json, true);
file_put_contents($file, json_encode($currentData));

echo json_encode(['message' => 'Data submitted successfully!']);
?>
