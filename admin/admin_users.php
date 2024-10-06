<?php 
include 'admin_nav.php';
include '../includes/dbcon.php';

// Function to fetch users based on status
function getUsersByStatus($status) {
    global $conn;
    if ($status === '') {
        $sql = "SELECT * FROM users";
    } else {
        $sql = "SELECT * FROM users WHERE status = ?";
    }
    $stmt = $conn->prepare($sql);
    if ($status !== '') {
        $stmt->bind_param("s", $status);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

// Default status filter (all users)
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Fetch users based on selected status filter
if ($statusFilter == 'all') {
    $users = getUsersByStatus('');
} else {
    $users = getUsersByStatus($statusFilter);
}

// Counting badges
$totalAdmin = count(getUsersByStatus('admin'));
$totalVerified = count(getUsersByStatus('verified'));
$totalPending = count(getUsersByStatus('pending'));

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>
<body class="content">
    
<div class="container mt-5">
    
    <!-- Admin, Verified, Pending Badges -->
    <div class="mb-3">
        <span class="badge badge-success mr-2">Admin: <?php echo $totalAdmin; ?></span>
        <span class="badge badge-primary mr-2">Verified: <?php echo $totalVerified; ?></span>
        <span class="badge badge-warning">Pending: <?php echo $totalPending; ?></span>
    </div>

    <!-- Filter Dropdown -->
    <div class="mb-3">
        <label for="statusFilter">Filter by Status:</label>
        <select id="statusFilter" class="form-control">
            <option value="all" <?php echo ($statusFilter == 'all') ? 'selected' : ''; ?>>All</option>
            <option value="semi_verified" <?php echo ($statusFilter == 'semi_verified') ? 'selected' : ''; ?>>Semi Verified</option>
            <option value="verified" <?php echo ($statusFilter == 'verified') ? 'selected' : ''; ?>>Verified</option>
            <option value="pending" <?php echo ($statusFilter == 'pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="admin" <?php echo ($statusFilter == 'admin') ? 'selected' : ''; ?>>Admin</option>
        </select>
    </div>

    <!-- User Table -->
    <table class="table table-striped text-center">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo ucfirst($user['status']); ?></td>
                    <td>
                    <?php if ($user['status'] !== 'admin'): ?>
                        <button type="button" class="btn btn-warning" onclick="lockUser(<?php echo $user['id']; ?>)">Locked for Maintenance</button>
                    <?php else: ?>
                        <span class="text-muted">None</span>
                    <?php endif; ?>
                </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        // Handle dropdown change event
        $('#statusFilter').change(function() {
            var status = $(this).val();
            window.location.href = 'admin_users.php?status=' + status;
        });
    });

    function lockUser(userId) {
        // Add your AJAX or form submission logic here to lock the user for maintenance
        alert("User " + userId + " will be locked for maintenance.");
    }
</script>

</body>
</html>
