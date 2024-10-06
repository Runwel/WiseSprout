<?php 
include 'admin_nav.php';
include '../includes/dbcon.php';

// Retrieve total number of users
$sqlUsers = "SELECT COUNT(*) AS totalUsers FROM users";
$resultUsers = $conn->query($sqlUsers);
$totalUsers = 0; // Default value
if ($resultUsers->num_rows > 0) {
    $rowUsers = $resultUsers->fetch_assoc();
    $totalUsers = $rowUsers['totalUsers'];
}

// Retrieve total number of posts
$sqlPosts = "SELECT COUNT(*) AS totalPosts FROM posts";
$resultPosts = $conn->query($sqlPosts);
$totalPosts = 0; // Default value
if ($resultPosts->num_rows > 0) {
    $rowPosts = $resultPosts->fetch_assoc();
    $totalPosts = $rowPosts['totalPosts'];
}

$sqlItems = "SELECT COUNT(*) AS totalItems FROM catalogue";
$resultItems = $conn->query($sqlItems);
$totalItems = 0; // Default value
if ($resultItems->num_rows > 0) {
    $rowItems = $resultItems->fetch_assoc();
    $totalItems = $rowItems['totalItems'];
}

// Retrieve system performance metrics (example data)
$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
$uptimeData = [98, 99, 97, 98, 99, 98]; // Example: Server uptime percentages
$responseTimeData = [150, 145, 160, 140, 155, 150]; // Example: Response time in milliseconds

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="content">
        
<div class="container mt-5">
    <h2>Dashboard</h2>

    <!-- Highlight cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-users fa-3x text-primary"></i>
                    <h5 class="card-title mt-3">Total Users</h5>
                    <p class="card-text display-4"><?php echo $totalUsers; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-pencil-alt fa-3x text-success"></i>
                    <h5 class="card-title mt-3">Total Posts</h5>
                    <p class="card-text display-4"><?php echo $totalPosts; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-shopping-cart fa-3x text-warning"></i>
                    <h5 class="card-title mt-3">Total Catalogue Items</h5>
                    <p class="card-text display-4"><?php echo $totalItems; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- System Performance Description -->
    <div class="row mt-5">
        <div class="col-md-12">
            <h4>System Performance</h4>
            <p>
                <strong>Uptime:</strong> The system has maintained an average uptime of 98% over the past six months, ensuring consistent availability for users.<br>
                <strong>Response Time:</strong> Average response times have been around 150 milliseconds, ensuring fast interactions with the system.<br>
                <strong>Database Performance:</strong> Database queries have a success rate of over 99%, ensuring reliable data retrieval and updates.
            </p>
        </div>
    </div>

    <!-- System Performance Charts -->
    <div class="row mt-5">
        <div class="col-md-6">
            <div>
                <canvas id="uptimeChart" width="400" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div>
                <canvas id="responseTimeChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript for Chart.js initialization
    $(document).ready(function() {
        // Uptime chart
        var uptimeCtx = document.getElementById('uptimeChart').getContext('2d');
        var uptimeChart = new Chart(uptimeCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Uptime (%)',
                    data: <?php echo json_encode($uptimeData); ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0 // Ensure no decimals are displayed
                        }
                    }]
                }
            }
        });

        // Response time chart
        var responseTimeCtx = document.getElementById('responseTimeChart').getContext('2d');
        var responseTimeChart = new Chart(responseTimeCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Response Time (ms)',
                    data: <?php echo json_encode($responseTimeData); ?>,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    });
</script>

</body>
</html>
