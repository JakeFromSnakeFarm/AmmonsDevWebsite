<?php
$YOUTUBE_API_KEY = "AIzaSyDafnHPhpI7iMF8-wjpq-DARlm8u_1YojM";
function searchYouTubeVideos($query, $maxResults = 5)
{
    global $YOUTUBE_API_KEY;
    $apiKey = $YOUTUBE_API_KEY;
    $apiUrl = 'https://www.googleapis.com/youtube/v3/search';

    $params = [
        'part' => 'snippet',
        'q' => $query,
        //'type' => 'video',
        'maxResults' => $maxResults,
        'key' => $apiKey
    ];

    $url = $apiUrl . '?' . http_build_query($params);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return ['error' => 'cURL Error: ' . curl_error($ch)];
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($httpCode !== 200) {
        return [
            'error' => 'YouTube API Error',
            'status_code' => $httpCode,
            'message' => $result['error']['message'] ?? 'Unknown error'
        ];
    }

    if (isset($result['items'])) {
        $videos = [];
        foreach ($result['items'] as $item) {
            $videos[] = [
                'title' => $item['snippet']['title'],
                'description' => $item['snippet']['description'],
                'thumbnail' => $item['snippet']['thumbnails']['default']['url'],
                'videoId' => $item['id']['videoId']
            ];
        }
        return $videos;
    } else {
        return ['error' => 'Unable to retrieve videos from YouTube.'];
    }
}
$output = json_encode(searchYouTubeVideos("My Whirpool Dishwasher KTEC9827 is giving me issues. The dishwasher is filling with water but not spraying the water around."));
echo $output;
?>