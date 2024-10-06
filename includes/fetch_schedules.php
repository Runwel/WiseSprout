<?php
include '../auth/session.php';
include 'dbcon.php';

// Initialize response array
$response = array();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch schedules from the database
    $schedulesQuery = "SELECT id, start_time, duration, status, days FROM schedules WHERE user_id = ?";
    $stmt = $conn->prepare($schedulesQuery);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $schedules = array();
        while ($row = $result->fetch_assoc()) {
            // Determine status color based on status
            $statusColor = '';
            switch ($row['status']) {
                case 'ongoing':
                    $statusColor = 'green'; // Green for ongoing
                    break;
                case 'pending':
                    $statusColor = 'orange'; // Orange for pending
                    break;
                case 'ended':
                    $statusColor = 'red'; // Red for ended
                    break;
                default:
                    $statusColor = 'gray'; // Gray for unknown
            }

            // Add debugging output to see what is being fetched
            error_log('Schedule fetched: ' . print_r($row, true));
            
            $schedules[] = array(
                'id' => $row['id'],
                'start_time' => $row['start_time'],
                'duration' => $row['duration'],
                'status' => $row['status'],
                'status_color' => $statusColor, // Include status color in the response
                'days' => $row['days'] // Ensure 'days' is fetched and included in the response
            );
        }
        $response['success'] = true;
        $response['schedules'] = $schedules;
    } else {
        $response['success'] = false;
        $response['message'] = "No schedules found for this user";
    }

    // Close statement and database connection
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = "User session not found or expired";
}

// Close database connection
$conn->close();

// Send JSON response back to the client
header('Content-Type: application/json');
echo json_encode($response);
?>
