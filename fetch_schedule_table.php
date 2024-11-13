<?php
include '../auth/session.php';  // Include session
include 'dbcon.php';  // Include database connection

// Function to convert minutes to readable format
function minutesToReadable($minutes) {
    $hours = floor($minutes / 60);
    $minutes = $minutes % 60;
    return ($hours > 0 ? $hours . " hr " : "") . ($minutes > 0 ? $minutes . " min" : "");
}

// Query schedules from the database
$schedulesQuery = "SELECT id, start_time, duration, days, status FROM schedules WHERE user_id = ? ORDER BY start_time ASC";
$stmt = $conn->prepare($schedulesQuery);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

// Initialize table rows
$tableRows = '';

// Day names and their abbreviations
$dayNames = [
    'Monday' => 'Monday',
    'Tuesday' => 'Tuesday',
    'Wednesday' => 'Wednesday',
    'Thursday' => 'Thursday',
    'Friday' => 'Friday',
    'Saturday' => 'Saturday',
    'Sunday' => 'Sunday'
];
$dayAbbreviations = [
    'Monday' => 'M',
    'Tuesday' => 'T',
    'Wednesday' => 'W',
    'Thursday' => 'TH',
    'Friday' => 'F',
    'Saturday' => 'S',
    'Sunday' => 'SU'
];

while ($schedule = $result->fetch_assoc()) {
    // Get the 12-hour formatted start time
    $start_time_12hr = date("h:i A", strtotime($schedule['start_time']));
    
    // Calculate the end time based on start time and duration
    $start_time = new DateTime($schedule['start_time']);
    $end_time = clone $start_time;
    $end_time->add(new DateInterval('PT' . $schedule['duration'] . 'S'));
    $end_time_12hr = $end_time->format('h:i A');
    
    // Get the duration in readable format
    $duration_readable = minutesToReadable($schedule['duration'] / 60);
    
    // Get current time for comparison
    $current_time = new DateTime();  // Current timestamp
    $current_timestamp = $current_time->format('Y-m-d H:i:s');  // Format for logging

    // Check if current time is past end time and the schedule is still "Ongoing"
    if ($current_time > $end_time && $schedule['status'] == 'Ongoing') {
        // Log device disconnection event
        $logMessage = "Timestamp: " . $current_timestamp . ", Device Status: Disconnected\n";
        file_put_contents('../logs/log_' . $_SESSION['user_id'] . '.txt', $logMessage, FILE_APPEND);  // Append to user-specific log file
        
        // Update the status of the schedule to "Device Disconnected"
        $updateStatusQuery = "UPDATE schedules SET status = 'Device Disconnected' WHERE id = ?";
        $updateStmt = $conn->prepare($updateStatusQuery);
        $updateStmt->bind_param("i", $schedule['id']);
        $updateStmt->execute();
        $updateStmt->close();
    }

    // Process days array
    $days = !empty($schedule['days']) ? explode(',', $schedule['days']) : [];

    // Check if all days are selected
    if (count($days) == 7) {
        // If all days are selected, show the abbreviated days
        $daysString = implode('-', $dayAbbreviations);
    } else {
        // Otherwise, show abbreviations for the selected days
        $shortDays = array_map(function($day) use ($dayAbbreviations) {
            return isset($dayAbbreviations[$day]) ? $dayAbbreviations[$day] : '';
        }, $days);
        
        $daysString = implode('-', $shortDays);
    }

    // Assign color based on status
    $statusColor = '';
    switch ($schedule['status']) {
        case 'Ongoing':
            $statusColor = 'bg-success'; // Green for ongoing
            break;
        case 'Pending':
            $statusColor = 'bg-warning'; // Orange for pending
            break;
        case 'Ended':
            $statusColor = 'bg-danger'; // Red for ended
            break;
        case 'Device Disconnected':
            $statusColor = 'bg-secondary'; // Gray for disconnected
            break;
        default:
            $statusColor = 'bg-secondary'; // Default for unknown
    }

    // Create table rows with status and action buttons
    $tableRows .= "<tr data-id='{$schedule['id']}'>
                    <td class='text-center'>{$start_time_12hr} - {$end_time_12hr}</td>
                    <td class='text-center'>{$daysString}</td>
                    <td class='text-center status'>
                        <span class='status-indicator {$statusColor}' style='display: inline-block; width: 12px; height: 12px; border-radius: 50%;'></span>
                        {$schedule['status']}
                    </td>
                    <td class='text-center'>
                       <button class='btn btn-sm btn-outline-light edit-btn' 
                            data-id='{$schedule['id']}' 
                            data-time='{$schedule['start_time']}' 
                            data-duration='{$schedule['duration']}' 
                            data-days='" . htmlspecialchars(json_encode($days)) . "'>
                        <i class='fas fa-edit'></i>
                    </button>
                        <button class='btn btn-sm btn-outline-light delete-btn' data-id='{$schedule['id']}'>
                            <i class='fas fa-trash-alt'></i>
                        </button>
                    </td>
                </tr>";
}

// Close the database connection
$stmt->close();
$conn->close();

// Output the table rows
echo $tableRows;
?>
