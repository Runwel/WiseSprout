<?php
include 'includes/nav.php';
include 'includes/dbcon.php';

// Get the post ID from the URL
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$title = isset($_GET['title']) ? $_GET['title'] : '';

// Fetch the post details from the database
$post = fetchPost($post_id);

function fetchPost($post_id) {
    global $conn;

    $sql = "SELECT p.post_id, p.title, p.author_id, p.content, p.image_path, p.created_at, u.username 
            FROM posts p
            INNER JOIN users u ON p.author_id = u.id
            WHERE p.post_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        .post-container {
            text-align: center;
        }
        .post-image {
            width: 100%;
            height: auto;
            margin: 0 auto;
        }
        .post-content {
            text-align: justify;
            margin: 0 auto;
        }
    </style>
</head>
<body class="pt-5">
    <div class="container py-4 post-container">
        <?php if ($post): ?>
            <h2 class="card-title"><?php echo $post['title']; ?></h2>
            <p>By: <span class="text-primary"><?php echo $post['username']; ?></span> (<?php echo $post['created_at']; ?>)</p>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <img src="<?php echo $post['image_path']; ?>" alt="" class="post-image mb-4">
                    <div class="post-content">
                        <p><?php echo nl2br($post['content']); ?></p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">
                <strong>Error!</strong> The post you are looking for does not exist.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); 
include 'includes/footer.php'?>
