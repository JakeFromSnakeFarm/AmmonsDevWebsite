<?php
// Define the API URL
$api_url = 'https://ammons.dev/projects/webhooks/allEvents.php';

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification if necessary

// Execute cURL request
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
}

// Close cURL session
curl_close($ch);

// Handle cURL errors
if (isset($error_msg)) {
    // Display error message and exit
    echo "<h2>cURL Error:</h2><p>{$error_msg}</p>";
    exit;
}

// Decode the JSON response into an associative array
$data = json_decode($response, true);

// Check for JSON decoding errors
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "<h2>JSON Decode Error:</h2><p>" . json_last_error_msg() . "</p>";
    exit;
}

// Encode the data back to JSON for embedding in JavaScript
// Use JSON_HEX_* options to safely embed JSON in HTML
$json_data = json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

// Handle JSON encoding errors
if ($json_data === false) {
    echo "<h2>JSON Encode Error:</h2><p>" . json_last_error_msg() . "</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Webhook Events Dashboard</title>
    <script>
        // Embed the PHP JSON data into a JavaScript variable
        const eventData = <?php echo $json_data; ?>;

        /**
         * Counts the number of occurrences of a given assignedTech name.
         * @param {string} name - The name of the assigned technician to search for.
         * @returns {number} - The count of occurrences.
         */
        function countAssignedTech(name) {
            if (!eventData || !eventData.bookedTimes) {
                console.error("Invalid event data.");
                return 0;
            }

            // Use the Array.prototype.reduce method for efficient counting
            return eventData.bookedTimes.reduce((count, event) => {
                const assignedTech = event.extendedProperties && event.extendedProperties.private && event.extendedProperties.private.assignedTech;
                return count + (assignedTech === name ? 1 : 0);
            }, 0);
        }

        // Example usage:
        // You can call this function from the browser console or other scripts
        // For demonstration, we'll log the count for "Jake"
        document.addEventListener("DOMContentLoaded", () => {
            const techName = "Ryan";
            const count = countAssignedTech(techName);
            console.log(`Number of occurrences for "${techName}":`, count);

            // Optionally, display the count on the webpage
            const resultDiv = document.getElementById("result");
            resultDiv.textContent = `Number of occurrences for "${techName}": ${count}`;
        });
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        #result {
            margin-top: 20px;
            font-size: 1.2em;
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Webhook Events Dashboard</h1>
    <p>This dashboard fetches event data from the API and allows you to analyze it.</p>
    <div id="result">Loading...</div>
</body>
</html>
