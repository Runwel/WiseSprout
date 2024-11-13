<?php
// Include database connection or initialization
include 'dbcon.php'; // Adjust this to your database connection script

// Retrieve filter parameters from POST request
$category = $_POST['category'] ?? '';
$item = $_POST['item'] ?? '';
$budget = $_POST['budget'] ?? '';

// Base SQL query
$sql = "SELECT * FROM catalogue WHERE category = ?";
$params = [$category];
$paramsTypes = 's';

// Add filters to the SQL query based on provided parameters
if (!empty($item)) {
    $sql .= " AND item = ?";
    $params[] = $item;
    $paramsTypes .= 's';
}
if (!empty($budget)) {
    $sql .= " AND price <= ?";
    $params[] = $budget;
    $paramsTypes .= 'i'; // Assuming budget is an integer in your database
}
    $sql .= " ORDER BY price ASC";


// Prepare and execute the SQL query
$stmt = $conn->prepare($sql);
$stmt->bind_param($paramsTypes, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any results
if ($result->num_rows > 0) {
    // Fetch and display catalogue items
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-md-3 mb-2">';
        echo '<a id="black-link" href="' . htmlspecialchars($row['link']) . '">';
        echo '<div class="card" style="height: 100%">'; // Added fixed height style
        echo '<img src="assets/pictures/'.$row['image_url'].'" class="card-img-top" style="height: 200px;" alt="'.$row['item_name'].'">';
        echo '<div class="card-body">';
         $itemName = strip_tags(htmlspecialchars($row['item_name']));
        $truncatedItemName = strlen($itemName) > 50 ? substr($itemName, 0, 70) . '...' : $itemName;
        echo '<h5 class="card-title">' . $truncatedItemName . '</h5>'; // Display truncated and HTML-safe item name
        echo '</div>';
        echo '<div class="card-footer text-center">Starting Price: ₱' . htmlspecialchars($row['price']) . '</div>'; // Adjust field names as per your database structure
        echo '</div>';
        echo '</a>';
        echo '</div>';
    }
} else {
    echo '<div class="col">No items found.</div>';
}

// Close the database connection and statement
$stmt->close();
$conn->close();
?>
