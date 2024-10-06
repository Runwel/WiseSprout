<?php
include '../../auth/session.php';
include '../../includes/dbcon.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
    header('Location: index.php'); // Redirect to home page or login page
    exit();
}

// Define upload directory and allowed file types
$uploadDir = '../../assets/pictures/';
$allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

// Initialize variables for storing upload status
$imagePath = '';
$uploadStatus = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle file upload
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
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
                $uploadStatus = 'File uploaded successfully.';
            } else {
                $uploadStatus = 'Error uploading file.';
            }
        } else {
            $uploadStatus = 'Invalid file type. Allowed types: jpg, jpeg, png, gif.';
        }
    } else {
        $uploadStatus = 'Error: ' . $_FILES['image']['error'];
    }

    // If image upload was successful, proceed to insert post
    if (!empty($_POST['title']) && !empty($_POST['content']) && !empty($_POST['author_id']) && !empty($imagePath)) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $authorId = $_POST['author_id'];

        // Prepare SQL statement to insert post with image path
        $sql = "INSERT INTO posts (title, content, author_id, image_path, created_at, updated_at)
                VALUES (?, ?, ?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssis", $title, $content, $authorId, $imagePath);

        // Execute SQL statement
        if ($stmt->execute()) {
            $uploadStatus .= ' Post added successfully.';
        } else {
            $uploadStatus .= ' Error adding post: ' . $conn->error;
        }
    } else {
        $uploadStatus .= ' Please fill in all required fields and upload an image.';
    }
}

// Close database connection
$conn->close();

// Redirect back to admin_posts.php with a status message
header("Location: ../admin_posts.php");
exit();
?>
