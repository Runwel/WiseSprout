<?php
include 'includes/nav.php';
include 'includes/dbcon.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resources</title>
    <style>
        .loading {
            display: none;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body class="pt-5">
    <div class="resources-page">
        <div class="overlay">
            <div class="container text-center">
                <h3>Welcome to Our Resources Page!</h3>
                <p>Discover the latest updates, guides, and articles to help you get the most out of your water sprinkler system. For more information, check out the links below:</p>
                <a href="how-it-works.php" class="btn btn-outline-primary" data-scroll>How It Works</a>
            </div>
        </div>
    </div>
    <section class="container py-4">
        <h4>Self-Reliant Resources: Everything you need to know starts here!</h4>
        <hr>
        <div id="posts-container" class="row">
            <!-- Posts will be dynamically loaded here -->
        </div>
        <div class="loading" id="loading-spinner">
            <p>Loading...</p>
        </div>
        <button id="view-more-button" class="btn btn-outline-primary btn-block" style="display: none;">View More</button>
    </section>

    <script>
        let offset = 0;
        const limit = 14;
        const postsContainer = document.getElementById('posts-container');
        const viewMoreButton = document.getElementById('view-more-button');
        const loadingSpinner = document.getElementById('loading-spinner');
        let totalPosts = 0;

        function fetchPosts() {
            loadingSpinner.style.display = 'block';
            fetch(`includes/fetch_posts.php?offset=${offset}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    loadingSpinner.style.display = 'none';
                    if (data.error) {
                        console.error('Error fetching posts:', data.error);
                        return;
                    }
                    const posts = data.posts;
                    totalPosts = data.total;
                    if (posts.length > 0) {
                        posts.forEach(post => {
                            const postElement = createPostElement(post);
                            postsContainer.appendChild(postElement);
                        });
                        offset += limit;
                    }

                    // Show or hide the "View More" button based on the remaining posts
                    if (offset >= totalPosts) {
                        viewMoreButton.style.display = 'none';
                    } else {
                        viewMoreButton.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error fetching posts:', error);
                    loadingSpinner.style.display = 'none';
                });
        }

        function createPostElement(post) {
            const postCol = document.createElement('div');
            postCol.classList.add('col-sm-12', 'col-md-6', 'col-lg-3');

            const postHTML = `
                <div>
                    <a href="full-post.php?id=${post.post_id}&title=${post.title}">
                        <img src="${post.image_path}" height="225px" width="100%" alt="${post.title}">
                    </a>
                    <h4><a id="green-link" href="full-post.php?id=${post.post_id}&title=${post.title}">${post.title}</a></h4>
                    <p>By: <span class="text-primary">${post.username}</span> (${post.created_at})</p>
                </div>
            `;
            postCol.innerHTML = postHTML;
            return postCol;
        }

        viewMoreButton.addEventListener('click', fetchPosts);

        // Load initial posts
        fetchPosts();
    </script>
</body>
</html>

<?php 
$conn->close(); 
include 'includes/footer.php';
?>