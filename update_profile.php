<?php
include '../auth/session.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("HTTP/1.1 403 Forbidden");
    exit();
}

include 'dbcon.php';

// Validate input and sanitize
$currentPassword = $_POST['currentPassword'] ?? '';
$newPassword = $_POST['newPassword'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

// Check if password fields are not empty and new password matches confirm password
if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all password fields.']);
    exit();
}

if ($newPassword !== $confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
    exit();
}

// Prepare SQL statement to fetch current password from the database
$sql = "SELECT password FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();

    // Verify current password
    if (!password_verify($currentPassword, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
        exit();
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update password
    $updateSql = "UPDATE users SET password = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("si", $hashedPassword, $_SESSION['user_id']);
    $updateStmt->execute();

    if ($updateStmt->affected_rows > 0) {
        // Password updated successfully
        echo json_encode(['success' => true]);
        exit();
    } else {
        // Password update failed
        echo json_encode(['success' => false, 'message' => 'Failed to update password.']);
        exit();
    }
} else {
    // User not found
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit();
}

// Close database connections
$stmt->close();
$updateStmt->close();
$conn->close();
?>
