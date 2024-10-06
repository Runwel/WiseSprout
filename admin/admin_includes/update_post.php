<?php
include '../../auth/session.php';
include '../../includes/dbcon.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
    header('Location: index.php'); // Redirect to home page or login page
    exit();
}

$post_id = $_POST['post_id'];
$title = $_POST['title'];
$content = $_POST['content'];
$author_id = $_POST['author_id'];

// Handle image upload if a new image is provided
$imagePath = '';

if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
    // Define upload directory and allowed file types
    $uploadDir = '../../assets/pictures/';
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    // Handle file upload
    $tempName = $_FILES['image']['tmp_name'];
    $originalName = basename($_FILES['image']['name']);
    $fileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    // Check file type
    if (in_array($fileType, $allowedTypes)) {
        // Generate unique file name to prevent overwriting existing files
        $fileName = uniqid('img_') . '.' . $fileType;
        $targetPath = $uploadDir . $fileName;

        // Move uploaded file to target directory
        if (move_uploaded_file($tempName, $targetPath)) {
            $imagePath = 'assets/pictures/' . $fileName;
        } else {
            echo "Error uploading file.";
            exit();
        }
    } else {
        echo "Invalid file type. Allowed types: jpg, jpeg, png, gif.";
        exit();
    }
}

// Prepare SQL statement to update post, including image path if provided
if (!empty($imagePath)) {
    $sql = "UPDATE posts SET title = ?, content = ?, author_id = ?, image_path = ?, updated_at = NOW() WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $title, $content, $author_id, $imagePath, $post_id);
} else {
    $sql = "UPDATE posts SET title = ?, content = ?, author_id = ?, updated_at = NOW() WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $title, $content, $author_id, $post_id);
}

// Execute SQL statement
if ($stmt->execute()) {
    echo "Post updated successfully";
} else {
    echo "Error updating post: " . $conn->error;
}

$conn->close();

header("Location: ../admin_posts.php");
exit;
?>
