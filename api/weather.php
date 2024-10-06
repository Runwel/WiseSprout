<?php
// includes/weather.php
$apiKey = '8c4c42127e664464bd9994c59d2cc9f6'; // Replace with your Weatherbit API key
$city = 'Cavite'; // Replace with your desired city, e.g., GMA, PH for GMA Cavite, Philippines
$country = 'PH';
$apiUrl = "https://api.weatherbit.io/v2.0/current?city=$city&country=$country&key=$apiKey";

// Fetch weather data from Weatherbit API
$response = file_get_contents($apiUrl);

// If the API request was successful
if ($response) {
    // Decode JSON response
    $weatherData = json_decode($response);

    // Output weather data as JSON
    echo json_encode($weatherData);
} else {
    // If there was an error fetching data
    echo json_encode(['error' => 'Failed to fetch weather data']);
}
?>