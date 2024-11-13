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
            // Determine schedule times
            $start_time_parts = explode(":", $row['start_time']);
            $start_time = strtotime("today " . $start_time_parts[0] . ":" . $start_time_parts[1] . ":" . $start_time_parts[2]);

            // Calculate the end time based on duration
            $end_time = $start_time + $row['duration'];

            // Get current time
            $current_time = time();

            // Update status based on time logic
            if ($current_time >= $start_time && $current_time <= $end_time && $row['status'] !== 'Ongoing') {
                // If the schedule is ongoing but hasn't been marked as "Ongoing", set it to "Loading..."
                $new_status = 'Loading...';
            } elseif ($current_time > $end_time && $row['status'] === 'Loading...') {
                // If the schedule has ended but is still marked as "Loading...", set it to "Device Connected"
                $new_status = 'Device Disconnected';
            } else {
                $new_status = $row['status']; // Keep the existing status if no condition is met
            }

            // Update the schedule's status if it has changed
            if ($new_status !== $row['status']) {
                $updateQuery = "UPDATE schedules SET status = ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("si", $new_status, $row['id']);
                $updateStmt->execute();
                $updateStmt->close();
            }

            // Determine the status color based on the current or updated status
            $statusColor = '';
            switch ($new_status) {
                case 'ongoing':
                    $statusColor = 'green'; // Green for ongoing
                    break;
                case 'pending':
                    $statusColor = 'orange'; // Orange for pending
                    break;
                case 'ended':
                    $statusColor = 'red'; // Red for ended
                    break;
                case 'Loading...':
                    $statusColor = 'blue'; // Blue for Loading...
                    break;
                case 'Device Disconnected':
                    $statusColor = 'purple'; // Purple for Device Connected
                    break;
                default:
                    $statusColor = 'gray'; // Gray for unknown
            }

            // Add schedule data to response
            $schedules[] = array(
                'id' => $row['id'],
                'start_time' => $row['start_time'],
                'duration' => $row['duration'],
                'status' => $new_status, // Return updated status
                'status_color' => $statusColor, // Include updated status color
                'days' => $row['days'] // Ensure 'days' is fetched and included
            );
        }

        // Success response
        $response['success'] = true;
        $response['schedules'] = $schedules;
    } else {
        $response['success'] = false;
        $response['message'] = "No schedules found for this user";
    }

    // Close statement
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
