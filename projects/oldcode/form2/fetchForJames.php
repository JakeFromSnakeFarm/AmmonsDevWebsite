<?php
header('Content-Type: application/json');
$file = 'dataForJames.json';
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
echo json_encode($data);
