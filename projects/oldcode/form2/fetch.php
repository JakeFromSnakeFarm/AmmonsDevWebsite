<?php
header('Content-Type: application/json'); // Ensures the output is sent as JSON
$file = 'data.json';
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
if (is_null($data)) { // Check if json_decode fails
    echo json_encode([]);
} else {
    echo json_encode($data);
}
