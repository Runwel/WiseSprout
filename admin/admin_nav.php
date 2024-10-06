<?php
include '../auth/session.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'admin') {
    header("Location: ../index.php"); // Redirect non-admin users to login page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="sidebar" id="sidebar">
        <button type="button" class="close text-white close-btn" aria-label="Close" id="sidebarClose">
            <span aria-hidden="true">&times;</span>
        </button>
          <div class="username-section mt-5">
            <h5>Welcome <?php echo $_SESSION['username'] ?></h5>
        </div>
        <ul class="navbar-nav me-auto flex-column">
            <li class="nav-item">
                <a class="nav-link px-3" href="admin_dashboard.php">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-3" href="admin_users.php">
                    <i class="fas fa-users"></i>
                    Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-3" href="admin_posts.php">
                    <i class="fas fa-newspaper"></i>
                    Posts
                </a>
            </li>
            <li class="nav-item px-3">
                <a class="nav-link" href="admin_catalogue.php">
                    <i class="fas fa-cog"></i>
                    Catalogue
                </a>
            </li>
            <!-- <li class="nav-item px-3">
                <a class="nav-link" href="#">
                    <i class="fas fa-question-circle"></i>
                    Help
                </a>
            </li> -->
        </ul>
        <div class="dropdown-divider mx-3"></div>
        <div class="user-links mt-3">
            <ul class="navbar-nav me-auto flex-column">
                <li class="nav-item">
                    <a class="nav-link px-3" href="../index.php">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="../resources.php">
                        <i class="fas fa-book"></i>
                        Resources
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="../catalogue.php">
                        <i class="fas fa-book"></i>
                        E-Catalogue
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="../auth/logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
    

    <button class="btn btn-dark" id="sidebarToggle" style="position: fixed; top: 10px; left: 10px; z-index: 2;">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('show');
        });

        document.getElementById('sidebarClose').addEventListener('click', function () {
            document.getElementById('sidebar').classList.remove('show');
        });
    </script>
</body>
</html>
