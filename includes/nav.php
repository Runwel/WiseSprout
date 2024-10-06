<?php include 'auth/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white fixed-top">
        <div class="d-flex align-items-center justify-content-center">
            <img src="assets/pictures/WiseSprout.png" style="height: 50px; width: 60px;" alt="WiseSprout Logo">
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#About-Us">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#Features">Features</a> 
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="catalogue.php">E-Catalogue</a> 
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="resources.php">Resources</a> 
                </li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= htmlspecialchars($_SESSION['username']) ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <?php if (!$isAdmin): ?>
                            <a class="dropdown-item" data-toggle="modal" data-target="#profileModal">Profile</a>
                            <div class="dropdown-divider"></div>
                            <?php endif; ?>
                        
                            <?php if ($isVerified): ?>
                                <a class="dropdown-item" href="home.php">Dashboard</a>
                            <?php elseif ($isAdmin): ?>
                                <a class="dropdown-item" href="admin/admin_dashboard.php">Admin Dashboard</a>
                            <?php endif; ?>

                            <a class="dropdown-item" href="auth/logout.php">Logout</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </nav>

    <!-- Modal for Profile -->
     <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
    <form id="profileForm" action="includes/update_profile.php">
        <!-- Username and Email Display -->
        <div class="d-flex">
            <label>Username:</label>
            <p class="ml-1 text-muted"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
        </div>
        <div class="d-flex">
            <label>Email:</label>
            <p class="ml-1 text-muted"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
        </div>

        <!-- Password Change Section -->
        <div class="form-group">
            <label for="currentPassword">Current Password:</label>
            <input type="password" class="form-control" id="currentPassword" name="currentPassword">
        </div>
        <div class="form-group">
            <label for="newPassword">New Password:</label>
            <input type="password" class="form-control" id="newPassword" name="newPassword">
        </div>
        <div class="form-group">
            <label for="confirmPassword">Confirm New Password:</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
            <!-- Error message display for password mismatch -->
            <small id="passwordMismatch" class="text-danger d-none">Passwords do not match.</small>
        </div>

        <button type="button" class="btn btn-primary" onclick="updateProfile()">Update Password</button>
    </form>
</div>
            </div>
        </div>
    </div>
    <script>
function updateProfile() {
    var currentPassword = document.getElementById('currentPassword').value;
    var newPassword = document.getElementById('newPassword').value;
    var confirmPassword = document.getElementById('confirmPassword').value;

    // Validate if passwords match
    if (newPassword !== confirmPassword) {
        document.getElementById('passwordMismatch').classList.remove('d-none');
        return;
    }

    // AJAX request to update profile
    var xhr = new XMLHttpRequest();
    var formData = new FormData(document.getElementById('profileForm'));
    xhr.open('POST', 'includes/update_profile.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Handle response, e.g., show success message or handle errors
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Password updated successfully, close modal or show success message
                location.reload();
            } else {
                // Handle errors, e.g., show error messages
                alert(response.message);
            }
        }
    };
    xhr.send(formData);
}
</script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
