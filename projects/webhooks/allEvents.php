<?php
header('Content-Type: application/json');
require __DIR__ . '/vendor/autoload.php';

// if (php_sapi_name() != 'cli') {
//     throw new Exception('This application must be run on the command line.');
// }

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Calendar API PHP Quickstart');
    $client->setScopes(Google_Service_Calendar::CALENDAR);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Calendar($client);


// Print the next 10 events on the user's calendar.
//$calendarId = 'primary';
$calendarId = 'a0c6f3c994e24ebd6067720e1148623d222aa31edfb6d3aae017dc212971b0c3@group.calendar.google.com';
$optParams = array(
  'maxResults' => 2000,
  'orderBy' => 'startTime',
  'singleEvents' => true,
);
$results = $service->events->listEvents($calendarId, $optParams);
$events = $results->getItems();
//echo var_dump($events);
if (empty($events)) {
    //print "No upcoming events found.\n";
    echo "empty here";
} else {
    //Check if each event contains certain strings then handle accordingly
    $occlusionTimesArray = array();
    foreach ($events as $event) {
        $startTime = $event;
        //printf(var_dump($event));
        // $response = array(
        //     "message" => $startTime,
        //     "date" => date('c'),
        //     "status" => "success"
        // );
        array_push($occlusionTimesArray, $startTime);
    }
    $response = array("bookedTimes"=> $occlusionTimesArray);
    echo json_encode($response);

}
?>