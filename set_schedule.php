<?php
// Include necessary files
include '../auth/session.php';
include 'dbcon.php';

// Function to convert 12-hour time format to 24-hour time format
function convertTo24Hour($time12hr) {
    return date("H:i:s", strtotime($time12hr));
}

function isOverlap($start_time, $end_time, $user_id, $days, $conn) {
    // Sort the days array to ensure consistent order
    sort($days);
    
    // Construct a more robust REGEXP pattern
    $dayPattern = implode("|", array_map(function($day) {
        return "\\b" . preg_quote($day) . "\\b";
    }, $days));

    // Adjusted query to check for overlaps and exact matches on the same days
    $query = "SELECT COUNT(*) as count FROM schedules WHERE user_id = ? AND (
        (start_time < ? AND ADDTIME(start_time, SEC_TO_TIME(duration)) > ?) OR 
        (? < ADDTIME(start_time, SEC_TO_TIME(duration)) AND ? > start_time) OR
        (? = ADDTIME(start_time, SEC_TO_TIME(duration))) OR
        (? = start_time) OR
        (? <= start_time AND ? >= ADDTIME(start_time, SEC_TO_TIME(duration))) OR
        (start_time <= ? AND ADDTIME(start_time, SEC_TO_TIME(duration)) >= ?)
    ) AND days REGEXP ?";
    
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die('Error preparing statement: ' . $conn->error);
    }
    
    $stmt->bind_param("isssssssssss", 
        $user_id, 
        $end_time, 
        $start_time, 
        $start_time, 
        $end_time, 
        $start_time,
        $end_time, 
        $start_time, 
        $end_time, 
        $start_time, 
        $end_time,
        $dayPattern
    );
    
    $stmt->execute();
    if ($stmt->error) {
        die('Error executing query: ' . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['count'] > 0;
}

// Initialize response array
$response = [
    'success' => false,
    'message' => 'Unknown error occurred.'
];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_time_12hr = $_POST["time"]; // Start time in 12-hour format (e.g., 12:30 AM)
    $duration = $_POST["duration"]; // Duration in seconds
    $user_id = $_SESSION['user_id']; // Get user_id from session
    $days = $_POST['days']; // Days of the week

    // Convert 12-hour time format to 24-hour time format
    $start_time = convertTo24Hour($start_time_12hr);

    // Calculate end time
    $startDateTime = new DateTime($start_time);
    $endDateTime = clone $startDateTime;
    $endDateTime->add(new DateInterval('PT' . $duration . 'S')); // Since duration is in seconds
    $end_time = $endDateTime->format('H:i:s'); // End time in 24-hour format

    // Get the current time for comparison
    $current_time = new DateTime();

    // Determine the status based on the current time
    // Check if it's exactly 12 AM
    $status = 'Pending'; // Default to Pending
    
    // If the current time is 12:00 AM, set status to Pending
    if ($current_time->format('H:i') == '00:00') {
        $status = 'Pending';
    } elseif ($current_time >= $startDateTime && $current_time < $endDateTime) {
        $status = 'Ongoing'; // This would be handled on the Arduino side as per your mention
    } elseif ($current_time >= $endDateTime) {
        $status = 'Ended'; // No need to include "Ongoing" here as you mentioned
    }

    // Check if the new schedule overlaps with existing schedules on the selected days
    if (isOverlap($start_time, $end_time, $user_id, $days, $conn)) {
        // If there's an overlap, return an error response
        $response = [
            'success' => false,
            'message' => 'Schedule overlaps with an existing schedule on the selected days. Please choose a different time or duration.'
        ];
    } else {
        // No overlap, proceed to insert the schedule
        $daysString = implode(",", $days);
        $insertQuery = "INSERT INTO schedules (user_id, start_time, duration, days, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("isiss", $user_id, $start_time, $duration, $daysString, $status); // Bind parameters

        if ($stmt->execute()) {
            // Schedule successfully added
            $response = [
                'success' => true,
                'message' => 'Schedule set successfully!'
            ];
        } else {
            // Error occurred while setting schedule
            $response = [
                'success' => false,
                'message' => 'Error: ' . $stmt->error
            ];
        }

        $stmt->close();
    }
} else {
    // If the request method is not POST, handle the error
    $response = [
        'success' => false,
        'message' => 'Method Not Allowed'
    ];
}

$conn->close();

// Send JSON response back to the client
header('Content-Type: application/json');
echo json_encode($response);

?>
