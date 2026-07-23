<?php
// process_audio.php

if (isset($_FILES['audio'])) {
    $audioFile = $_FILES['audio']['tmp_name'];

    $apiKey = "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";

    // Prepare the cURL request to OpenAI Whisper API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/audio/transcriptions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'file' => curl_file_create($audioFile, 'audio/wav', 'audio.wav'),
        'model' => 'whisper-1'
    ]);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: multipart/form-data'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    // Output the transcription as JSON
    header('Content-Type: application/json');
    echo $response;
}
