<?php
include 'includes/nav.php';
include 'includes/dbcon.php';

// Function to fetch products based on category and optional filters
function fetchProductsByCategory($category, $filters) {
    global $conn;
    
    // Base SQL query
    $sql = "SELECT * FROM catalogue WHERE category = ?";
    $params = ['s', $category];

    // Add optional filters based on the provided $filters array
    if (!empty($filters)) {
        if (isset($filters['budget'])) {
            $sql .= " AND budget <= ?";
            $params[] = $filters['budget'];
        }
        if (isset($filters['soil_type'])) {
            $sql .= " AND soil_type = ?";
            $params[] = $filters['soil_type'];
        }
        if (isset($filters['usage'])) {
            $sql .= " AND usage = ?";
            $params[] = $filters['usage'];
        }
        if (isset($filters['item'])) {
            $sql .= " AND item = ?";
            $params[] = $filters['item'];
        }
    }

    // Prepare and execute SQL query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch and return products
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    return $products;
}

// Sample categories with descriptions (you can update these)
$categories = [
    'Irrigation Equipment' => [
        'name' => 'Irrigation Equipment',
        'description' => 'Explore our range of irrigation equipment suitable for various applications such as fields, gardens, and greenhouses. From efficient sprinkler systems to advanced drip irrigation solutions, we offer tools that ensure optimal water distribution and conservation.',
    ],
    'Fertilizers and Soil Conditioners' => [
        'name' => 'Fertilizers and Soil Conditioners',
        'description' => 'Discover fertilizers and soil conditioners designed to improve soil fertility and plant health. Our products include organic fertilizers, nutrient-rich composts, and specialized soil conditioners that promote robust plant growth and vitality.',
    ],
    'Gardening Tools' => [
        'name' => 'Gardening Tools',
        'description' => 'Find high-quality gardening tools that make planting, cultivating, and maintaining your garden easier. From ergonomic hand trowels to durable pruning shears and versatile hose nozzles, our tools are crafted to enhance your gardening experience.',
    ],
];

// Fetch products for each category if a category is specified in the URL
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;
$productsByCategory = [];
if ($selectedCategory && array_key_exists($selectedCategory, $categories)) {
    // Fetch optional filters if provided
    $filters = [
        'budget' => isset($_GET['budget']) ? $_GET['budget'] : null,
        'soil_type' => isset($_GET['soil_type']) ? $_GET['soil_type'] : null,
        'usage' => isset($_GET['usage']) ? $_GET['usage'] : null,
        'item' => isset($_GET['item']) ? $_GET['item'] : null,
    ];

    // Fetch products based on category and optional filters
    $productsByCategory = fetchProductsByCategory($selectedCategory, $filters);
}

$conn->close();
?>

<?php if ($selectedCategory && array_key_exists($selectedCategory, $categories)): ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>E-Catalogue - <?php echo $categories[$selectedCategory]['name']; ?></title>
        <style>
            .category-container {
                margin-bottom: 30px;
            }
            .category-description {
                padding: 20px;
            }
            .category-slideshow {
                padding: 20px;
            }
            .carousel-item img {
                max-height: 200px;
                object-fit: cover;
            }
            .card:hover {
                transform: scale(1.05);
                transition: transform 0.2s ease-in-out;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            }
        </style>
    </head>
    <body>
        <div class="container mt-5 pt-5">
            <h1 class="mb-4">E-Catalogue - <?php echo $categories[$selectedCategory]['name']; ?></h1>
            <!-- Filter Section -->
             <div class="">
                    <h3>Filter Products</h3>
                    <p>Select the filters below to narrow down your search for the best product options.</p>
                </div>
            <div class="form-row">
                
                    <div class="col-md-3 mb-3">
                        <label for="item">Filter by Item:</label>
                        <select class="form-control" id="item">
                            <option value="">All</option>
                            <?php
                            $items = [];
                            switch ($selectedCategory) {
                                case 'Irrigation Equipment':
                                    $items = ['Hose', 'Sprinkler', 'Watering Can', 'Rain Barrel'];
                                    break;
                                case 'Fertilizers and Soil Conditioners':
                                    $items = ['Organic Fertilizer', 'Compost', 'Mulch'];
                                    break;
                                case 'Gardening Tools':
                                    $items = ['Hand Trowel', 'Pruning Shears', 'Hose Nozzle'];
                                    break;
                            }
                            
                            foreach ($items as $item_option) {
                                echo '<option value="' . $item_option . '">' . $item_option . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label for="budget">Filter by Budget:</label>
                        <select class="form-control" id="budget">
                            <option value="">All</option>
                            <option value="500">500 PHP or less</option>
                            <option value="1000">1000 PHP or less</option>
                            <option value="1500">1500 PHP or less</option>
                            <option value="2000">2000 PHP or less</option>
                            <option value="2500">2500 PHP or less</option>
                        </select>
                    </div>
                <!-- <?php if ($selectedCategory === 'Fertilizers and Soil Conditioners'): ?>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label for="soil_type">Soil Type:</label>
                        <select class="form-control" id="soil_type">
                            <option value="">All</option>
                            <option value="Loam">Loam</option>
                            <option value="Clay">Clay</option>
                            <option value="Sandy">Sandy</option>
                            <option value="Peaty">Peaty</option>
                            <option value="Chalky">Chalky</option>
                        </select>
                    </div>
                <?php endif; ?> -->
                <!-- <?php if ($selectedCategory === 'Irrigation Equipment' || $selectedCategory === 'Fertilizers and Soil Conditioners' || $selectedCategory === 'Water Management Solutions'): ?>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <label for="usage">Filter by Usage:</label>
                        <select class="form-control" id="usage">
                            <option value="">All</option>
                            <option value="Field">Field</option>
                            <option value="Garden">Garden</option>
                            <option value="Greenhouse">Greenhouse</option>
                        </select>
                    </div>
                <?php endif; ?> -->
            </div>
            
            <!-- Catalog Items -->
            <div class="row" id="catalogueItems">
                <?php foreach ($productsByCategory as $item): ?>
                    
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Inside the <script> tag in your main PHP file -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function fetchFilteredItems() {
            var category = '<?php echo $selectedCategory; ?>';
            var budget = document.getElementById('budget') ? document.getElementById('budget').value : '';
            var item = document.getElementById('item') ? document.getElementById('item').value : '';

            fetch('includes/fetch_catalogue_items.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'category=' + encodeURIComponent(category) + 
                    '&budget=' + encodeURIComponent(budget) + 
                    '&item=' + encodeURIComponent(item),
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('catalogueItems').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
        }

        // Add event listeners for budget and item filters
        var budgetElement = document.getElementById('budget');
        if (budgetElement) {
            budgetElement.addEventListener('change', fetchFilteredItems);
        }

        var itemElement = document.getElementById('item');
        if (itemElement) {
            itemElement.addEventListener('change', fetchFilteredItems);
        }

        // Fetch initial data on page load
        fetchFilteredItems();
    });
</script>

    </body>
    </html>
<?php else: ?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Catalogue Overview</title>
    <style>
        .category-description {
            padding: 20px;
            color: white;
        }
        .category-bg {
            background-size: cover;
            background-position: center;
            height: 70vh;
            display: flex;
            align-items: center;
            color: white; /* Set text color to white */
        }
        .category-overlay {
            background-color: rgba(0, 0, 0, 0.5); /* Adjust opacity as needed */
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php foreach ($categories as $key => $category): ?>
            <div class="category-bg" style="background-image: url('assets/backgrounds/<?php echo $key; ?>.jpg');">
                <div class="">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="category-overlay">
                                <h2><?php echo $category['name']; ?></h2>
                                <p><?php echo $category['description']; ?></p>
                                <a href="?category=<?php echo $key; ?>" class="btn btn-primary">View More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php include 'includes/footer.php'; ?>
</body>
</html>


<?php endif; ?>
