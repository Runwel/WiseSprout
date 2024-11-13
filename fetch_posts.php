<?php
include 'dbcon.php';

header('Content-Type: application/json');

try {
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    $limit = 14;

    // Fetch posts with a limit and offset for pagination
    $sql = "SELECT p.post_id, p.title, p.author_id, p.content, p.image_path, p.created_at, u.username 
            FROM posts p
            INNER JOIN users u ON p.author_id = u.id
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception('Query preparation failed: ' . $conn->error);
    }
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    $posts = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Format the created_at date
            $date = new DateTime($row['created_at']);
            $row['created_at'] = $date->format('F j, Y');
            $posts[] = $row;
        }
    }

    $stmt->close();

    // Get the total count of posts
    $sqlCount = "SELECT COUNT(*) AS total FROM posts";
    $resultCount = $conn->query($sqlCount);
    $totalCount = $resultCount->fetch_assoc()['total'];

    $conn->close();

    echo json_encode(['posts' => $posts, 'total' => $totalCount]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
