<?php
// Include your database connection script
require_once 'dbcon.php';

// Check if it's a GET request and the ID is provided in the query string
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Sanitize the ID to prevent SQL injection (if needed)
    $scheduleId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare the SQL statement to delete the schedule
    $sql = "DELETE FROM schedules WHERE id = ?";

    try {
        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bind_param("i", $scheduleId);

        // Execute the statement
        $stmt->execute();

        // Check if any row was affected
        if ($stmt->affected_rows > 0) {
            // Return success message
            $response = [
                'success' => true,
                'message' => 'Schedule deleted successfully.'
            ];
        } else {
            // Return error message if no rows were affected
            $response = [
                'success' => false,
                'message' => 'No schedule found with that ID.'
            ];
        }
    } catch (Exception $e) {
        // Return error message if database error occurs
        $response = [
            'success' => false,
            'message' => 'Error deleting schedule: ' . $e->getMessage()
        ];
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid request if ID is not provided or not a GET request
    $response = [
        'success' => false,
        'message' => 'Invalid request. Please provide a valid schedule ID.'
    ];
}

// Output JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
