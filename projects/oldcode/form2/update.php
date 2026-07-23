<?php
header('Content-Type: application/json');
$file = 'data.json';
$index = $_POST['index'] ?? -1;
$newData = $_POST['data'] ?? [];

if ($index >= 0 && !empty($newData)) {
    $data = json_decode(file_get_contents($file), true);
    $data[$index] = array_merge($data[$index], $newData); // Merge new data into existing entry
    file_put_contents($file, json_encode($data));
    echo json_encode(['message' => 'Entry updated successfully!']);
} else {
    echo json_encode(['message' => 'Failed to update entry.']);
}
