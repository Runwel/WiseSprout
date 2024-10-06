<?php
include '../auth/session.php';
include 'dbcon.php';

// Function to convert 12-hour time format to 24-hour time format
function convertTo24Hour($time12hr) {
    return date("H:i:s", strtotime($time12hr));
}

function isOverlap($start_time, $end_time, $user_id, $days, $conn, $schedule_id) {
    // Sort the days array to ensure consistent order
    sort($days);
    
    // Construct a more robust REGEXP pattern
    $dayPattern = implode("|", array_map(function($day) {
        return "\\b" . preg_quote($day) . "\\b";
    }, $days));

    $query = "SELECT COUNT(*) as count FROM schedules WHERE user_id = ? AND id != ? AND (
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
    
    $stmt->bind_param("iisssssssssss", 
        $user_id, 
        $schedule_id,
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

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $id = $_POST['id'];
    $start_time_12hr = $_POST['time'];
    $duration = $_POST['duration'];
    $user_id = $_SESSION['user_id']; // Get user_id from session
    $days = isset($_POST['days']) ? $_POST['days'] : []; // Days of the week

    // Convert 12-hour time format to 24-hour time format
    $start_time = convertTo24Hour($start_time_12hr);

    // Calculate end time
    $startDateTime = new DateTime($start_time);
    $endDateTime = clone $startDateTime;
    $endDateTime->add(new DateInterval('PT' . $duration . 'S'));
    $end_time = $endDateTime->format('H:i:s');

    // Check if at least one day is selected
    if (empty($days)) {
        $response = [
            'success' => false,
            'message' => 'Please select at least one day for the schedule.'
        ];
    } else {
        // Check if the updated schedule overlaps with existing schedules on the selected days
        if (isOverlap($start_time, $end_time, $user_id, $days, $conn, $id)) {
            $response = [
                'success' => false,
                'message' => 'Schedule overlaps with an existing schedule on the selected days. Please choose a different time or duration.'
            ];
        } else {
            // No overlap, proceed to update the schedule
            $daysString = implode(",", $days);
            $updateQuery = "UPDATE schedules SET start_time = ?, duration = ?, days = ? WHERE id = ? AND user_id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("sisii", $start_time, $duration, $daysString, $id, $user_id);

            if ($stmt->execute()) {
                $response = [
                    'success' => true,
                    'message' => 'Schedule updated successfully!'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Error: ' . $stmt->error
                ];
            }

            $stmt->close();
        }
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
