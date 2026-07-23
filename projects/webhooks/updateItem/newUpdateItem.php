<?php
require __DIR__ . '/../vendor/autoload.php';

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
    $client->setAuthConfig(__DIR__ . '/../credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = __DIR__ . '/../token.json';
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



// Ensure the script only handles POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the event ID and summary from the POST data
    $eventId = $_POST['eventId'];
    $summary = $_POST['summary'];

    // Initialize an array to hold extended properties
    $extendedProperties = [];

    // Loop through POST data to collect extended properties
    foreach ($_POST as $key => $value) {
        // Exclude 'eventId' and 'summary' from extended properties
        if ($key !== 'eventId' && $key !== 'summary') {
            $extendedProperties[$key] = $value;
        }
    }

    // Proceed to update the event
    updateEvent($eventId, $summary, $extendedProperties);
}


function updateEvent($eventId, $summary, $extendedProperties) {
    // Get the API client and construct the service object.
    $client = getClient();
    $service = new Google_Service_Calendar($client);
    $calendarId = 'a0c6f3c994e24ebd6067720e1148623d222aa31edfb6d3aae017dc212971b0c3@group.calendar.google.com';

    try {
        // Fetch the existing event from the primary calendar
        $event = $service->events->get($calendarId, $eventId);

        // Update the event's summary
        $event->setSummary($summary);

        // Retrieve existing extended properties or initialize a new object
        $extendedProps = $event->getExtendedProperties();
        if (!$extendedProps) {
            $extendedProps = new Google_Service_Calendar_EventExtendedProperties();
        }

        // Retrieve existing private extended properties or initialize an empty array
        $privateProps = $extendedProps->getPrivate() ?: [];

        // Update extended properties with new values
        foreach ($extendedProperties as $key => $value) {
            $privateProps[$key] = $value;
        }

        // Set the updated private extended properties
        $extendedProps->setPrivate($privateProps);
        $event->setExtendedProperties($extendedProps);

        // Perform the PATCH request to update the event
        $updatedEvent = $service->events->patch($calendarId, $eventId, $event);

        // Success message or further processing
        echo $updatedEvent->getUpdated();
        echo "Event updated successfully. <a href='your-success-page.html'>Go Back</a>";
    } catch (Exception $e) {
        // Handle errors gracefully
        echo 'Error updating event: ' . htmlspecialchars($e->getMessage()) . "<br>";
        echo "<a href='your-error-page.html'>Go Back</a>";
    }
} 
?>