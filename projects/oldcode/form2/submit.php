<?php
header('Content-Type: application/json');
$file = 'data.json';

if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$inputData = $_POST;

$currentData = json_decode(file_get_contents($file), true);
$currentData[] = $inputData;
file_put_contents($file, json_encode($currentData));

echo json_encode(['message' => 'Data submitted successfully!']);
