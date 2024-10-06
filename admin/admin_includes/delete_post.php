<?php 
include '../../auth/session.php';
include '../../includes/dbcon.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
    header('Location: index.php'); // Redirect to home page or login page
    exit();
}

$post_id = $_GET['id'];

$sql = "DELETE FROM posts WHERE post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);

if ($stmt->execute()) {
    echo "Post deleted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

header("Location: ../admin_posts.php");
exit;
?>
