<?php
header('Content-Type: application/json');
$file = 'data.json';
$index = $_POST['index'] ?? -1;

if ($index >= 0) {
    $data = json_decode(file_get_contents($file), true);
    array_splice($data, $index, 1);
    file_put_contents($file, json_encode($data));
    echo json_encode(['message' => 'Entry deleted successfully!']);
} else {
    echo json_encode(['message' => 'Failed to delete entry.']);
}
