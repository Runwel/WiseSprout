<?php
$user_id = $_GET['user_id'];
$logFile = "logs/log_$user_id.txt";

// Default response if no data
$latestData = [
    'moisture' => 'No data',
    'condition' => 'No data',
    'status' => 'Offline',
    'timestamp' => 'No timestamp available'
];

// Check if log file exists and retrieve the last entry
if (file_exists($logFile)) {
    $fileContents = file($logFile);  // Reads all lines
    $lastLine = trim(end($fileContents));  // Gets the latest entry

    // Extract data from the last log entry with error handling for unusual values
    if (preg_match('/Timestamp: (.*?), Moisture: (\d+)%?, Condition: (.*?), Device Status: (.*)/', $lastLine, $matches)) {
        $moistureValue = (int)$matches[2];

        // Cap the moisture percentage between 0 and 100 if needed
        $moisturePercentage = min(max($moistureValue, 0), 100);

        // Convert timestamp to a more readable format
        $timestamp = $matches[1];
        $readableTimestamp = (new DateTime($timestamp))->format('l, F j, Y g:i A'); // Example: "Monday, November 13, 2024 3:03 PM"

        $latestData = [
            'moisture' => $moisturePercentage,
            'condition' => $matches[3],
            'status' => $matches[4] ?: 'Offline',
            'timestamp' => $readableTimestamp
        ];
    }
}

// Return JSON response
echo json_encode($latestData);
?>
