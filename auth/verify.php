<?php
include '../includes/dbcon.php';

// Retrieve email and token from query parameters
$email = $_GET['email'];
$token = $_GET['token'];

if ($email && $token) {
    // Prepare SQL statement to update user status
    $sql = "UPDATE users SET status = 'verified' WHERE email = ? AND verification_token = ?";
    
    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $token);

    // Execute the update statement
    if ($stmt->execute()) {
        $verification_successful = true;
    } else {
        $verification_successful = false;
    }

    // Close statement
    $stmt->close();
} else {
    $verification_successful = false;
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body class="d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="verification-container ">
        <?php if ($verification_successful): ?>
            <h1 class="text-center success-message">Email Verification Successful</h1>
            <p class="text-center">Your email has been successfully verified. You can now login to your account.</p>
        <?php else: ?>
            <h1 class="text-center error-message">Email Verification Failed</h1>
            <p class="text-center">The provided verification link is invalid. Please make sure you clicked on the correct link.</p>
        <?php endif; ?>
        <div class="text-center mt-3">
            <a href="../index.php" class="btn btn-primary">Back to Website</a>
        </div>
    </div>
</body>
</html>
