<?php
include '../auth/session.php';
include 'dbcon.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch schedules from the database
    $sql = "SELECT days, start_time, duration FROM schedules WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $schedules = [];

    // Days mapping
    $daysMap = [
        'Monday' => 'M',
        'Tuesday' => 'T',
        'Wednesday' => 'W',
        'Thursday' => 'T',
        'Friday' => 'F',
        'Saturday' => 'S',
        'Sunday' => 'S'
    ];

    // Structure the data by day
    while ($row = $result->fetch_assoc()) {
        $days = explode(',', $row['days']); // Split multiple days into an array
        $schedule = [
            'time' => $row['start_time'],  // The start time in HH:MM:SS format
            'duration' => (int)$row['duration']  // Duration in seconds
        ];

        // Add the schedule for each day
        foreach ($days as $day) {
            // Convert the full day name into its abbreviation
            $shortDay = isset($daysMap[$day]) ? $daysMap[$day] : $day;

            // Ensure the day exists in the array, if not, initialize it as an empty array
            if (!isset($schedules[$shortDay])) {
                $schedules[$shortDay] = [];
            }

            // Add the schedule for the day
            $schedules[$shortDay][] = $schedule;
        }
    }

    // Send the data as JSON response
    echo json_encode($schedules);

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'User not logged in']);
}
?>
