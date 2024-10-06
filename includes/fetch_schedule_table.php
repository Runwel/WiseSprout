<?php
include '../auth/session.php';
include 'dbcon.php';

function minutesToReadable($minutes) {
    $hours = floor($minutes / 60);
    $minutes = $minutes % 60;
    return ($hours > 0 ? $hours . " hr " : "") . ($minutes > 0 ? $minutes . " min" : "");
}

$schedulesQuery = "SELECT id, start_time, duration, days, status FROM schedules WHERE user_id = ? ORDER BY start_time ASC";
$stmt = $conn->prepare($schedulesQuery);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

$tableRows = '';
while ($schedule = $result->fetch_assoc()) {
    $start_time_12hr = date("h:i A", strtotime($schedule['start_time']));
    
    $start_time = new DateTime($schedule['start_time']);
    $end_time = clone $start_time;
    $end_time->add(new DateInterval('PT' . $schedule['duration'] . 'S'));
    $end_time_12hr = $end_time->format('h:i A');
    
    $duration_readable = minutesToReadable($schedule['duration'] / 60);
    
    $days = !empty($schedule['days']) ? explode(',', $schedule['days']) : [];

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
        default:
            $statusColor = 'bg-secondary'; // Gray for unknown
    }
    
    $tableRows .= "<tr data-id='{$schedule['id']}' data-days='" . htmlspecialchars(json_encode($days)) . "'>
                    <td class='text-center'>{$start_time_12hr} - {$end_time_12hr}</td>
                    <td class='text-center'>{$schedule['days']}</td>
                    <td class='text-center status'>
                        <span class='status-indicator {$statusColor}' style='display: inline-block; width: 12px; height: 12px; border-radius: 50%;'></span>
                        {$schedule['status']}
                    </td>
                    <td class='text-center' style='width: 15%;'>
                        <button class='btn btn-sm btn-outline-light edit-btn' data-id='{$schedule['id']}' 
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

$stmt->close();
$conn->close();

echo $tableRows;
?>
