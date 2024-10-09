<?php
include 'dbcon.php';

// Validate incoming JSON payload
$json_data = file_get_contents('php://input');
$data = json_decode($json_data);

if (!isset($data->id) || !isset($data->status)) {
    // If required parameters are missing, return an error response
    $response = [
        'success' => false,
        'message' => 'Required parameters not provided'
    ];
} else {
    // Prepare SQL statement to update schedule status
    $updateQuery = "UPDATE schedules SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    
    if ($stmt === false) {
        // Handle preparation error
        $response = [
            'success' => false,
            'message' => 'Failed to prepare statement'
        ];
    } else {
        $stmt->bind_param("si", $data->status, $data->id);

        // Execute the update query
        if ($stmt->execute()) {
            // If update is successful
            $response = [
                'success' => true,
                'message' => 'Schedule status updated successfully'
            ];
        } else {
            // If update fails
            $response = [
                'success' => false,
                'message' => 'Failed to update schedule status'
            ];
        }

        // Close statement
        $stmt->close();
    }
}

// Close database connection
$conn->close();

// Send JSON response back to the client
header('Content-Type: application/json');
echo json_encode($response);
?>
