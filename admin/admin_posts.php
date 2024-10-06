<?php 
include 'admin_nav.php';
include '../includes/dbcon.php';

// Fetch posts
$sqlPosts = "SELECT p.post_id, p.title, p.content, p.author_id, p.image_path, u.username AS author, p.created_at 
             FROM posts p 
             JOIN users u ON p.author_id = u.id";
$resultPosts = $conn->query($sqlPosts);
$posts = [];
if ($resultPosts->num_rows > 0) {
    while ($row = $resultPosts->fetch_assoc()) {
        $posts[] = $row;
    }
}

$sqlPostsCount = "SELECT COUNT(*) AS totalPosts FROM posts";
$resultPostsCount = $conn->query($sqlPostsCount);
$totalPosts = 0; // Default value
if ($resultPostsCount->num_rows > 0) {
    $rowPostsCount = $resultPostsCount->fetch_assoc();
    $totalPosts = $rowPostsCount['totalPosts'];
}


// Fetch users for the author dropdown
$sqlUsers = "SELECT id, username FROM users WHERE status = 'admin'";
$resultUsers = $conn->query($sqlUsers);
$users = [];
if ($resultUsers->num_rows > 0) {
    while ($row = $resultUsers->fetch_assoc()) {
        $users[] = $row;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>

</head>
<body class="content">

<div class="container mt-5">

<div class="d-flex justify-content-between align-items-center mb-1">
    <span class="badge badge-pill badge-primary"><?php echo $totalPosts; ?> Posts</span>
    <button class="btn btn-primary" data-toggle="modal" data-target="#addPostModal"> <i class="fas fa-plus"></i></button>
</div>
    <!-- Posts Table -->
    <table class="table table-striped text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Created At</th>
                
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?php echo $post['post_id']; ?></td>
                    <td><?php echo $post['title']; ?></td>
                    <td><?php echo $post['author']; ?></td>
                    <td><?php echo $post['created_at']; ?></td>
                    <td>
                        <button class="btn btn-primary btn-sm edit-post-btn" 
                                data-id="<?php echo $post['post_id']; ?>" 
                                data-title="<?php echo $post['title']; ?>" 
                                data-content="<?php echo htmlspecialchars($post['content']); ?>" 
                                data-author_id="<?php echo $post['author_id']; ?>" 
                                data-image_path="<?php echo $post['image_path']; ?>"
                                data-toggle="modal" 
                                data-target="#editPostModal"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm delete-post-btn" 
                                data-id="<?php echo $post['post_id']; ?>"><i class="fas fa-trash-alt"></i></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- Add Post Modal -->
<div class="modal fade" id="addPostModal" tabindex="-1" role="dialog" aria-labelledby="addPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="addPostForm" action="admin_includes/add_post.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPostModalLabel">Add Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="author_id">Author</label>
                        <select class="form-control" id="author_id" name="author_id" required>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image">Upload Image</label>
                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                        <div id="image-preview" class="mt-2 d-flex justify-content-center align-items-center">
                            <img id="preview-image" src="#" alt="Image Preview">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit modal -->
<div class="modal fade" id="editPostModal" tabindex="-1" role="dialog" aria-labelledby="editPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="editPostForm" action="admin_includes/update_post.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-post-id" name="post_id">
                    <div class="form-group">
                        <label for="edit-title">Title</label>
                        <input type="text" class="form-control" id="edit-title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-content">Content</label>
                        <textarea class="form-control" id="edit-content" name="content" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit-author_id">Author</label>
                        <select class="form-control" id="edit-author_id" name="author_id" required>
                            <!-- Populate dynamically from PHP -->
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-image">Current Image</label>
                        <img src="" id="current-image" class="img-fluid d-block mb-2" alt="Current Image">
                        <label for="edit-image">Upload New Image (Optional)</label>
                        <input type="file" class="form-control-file" id="edit-image" name="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Edit button functionality
        document.querySelectorAll('.edit-post-btn').forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.getAttribute('data-id');
                const postTitle = this.getAttribute('data-title');
                const postContent = this.getAttribute('data-content');
                const postAuthorId = this.getAttribute('data-author_id');
                const imagePath = this.getAttribute('data-image_path');

                document.getElementById('edit-post-id').value = postId;
                document.getElementById('edit-title').value = postTitle;
                document.getElementById('edit-content').value = postContent;
                document.getElementById('edit-author_id').value = postAuthorId;
                document.getElementById('current-image').src = "../" + imagePath;
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Image preview
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('w-100', 'h-100', 'img-fluid');
                    imagePreview.innerHTML = '';
                    imagePreview.appendChild(img);
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.innerHTML = '';
            }
        });
    });

    // Delete button functionality
        document.querySelectorAll('.delete-post-btn').forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.getAttribute('data-id');
                if (confirm('Are you sure you want to delete this post?')) {
                    window.location.href = `delete_post.php?id=${postId}`;
                }
            });
        });
</script>

</body>
</html>
