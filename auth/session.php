<?php
session_start();

$user_id = $username = $status = null;
$isAdmin = $isSemiVerified = $isVerified = false;

if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['status'], $_SESSION['email'])) {
    // User is logged in, retrieve session variables
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $status = $_SESSION['status'];
    $email = $_SESSION['email'];

    // Determine user's status and role
    $isVerified = ($status === 'verified');
    $isSemiVerified = ($status === 'pending' || $status === 'semi-verified');
    $isAdmin = ($status === 'admin');
}
?>