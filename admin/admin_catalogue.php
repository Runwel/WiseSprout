<?php
include 'admin_nav.php';
include '../includes/dbcon.php';

// Handle form submission (Add or Edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'edit') {
        // Handle Edit Form Submission
        $id = $_POST['edit_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $item = $_POST['item'];
        $link = $_POST['link'];

        // Check if a new image is uploaded
        if ($_FILES['image']['size'] > 0) {
            $image = $_FILES['image']['name'];
            $target_dir = "../assets/pictures/";
            $target_file = $target_dir . basename($image);
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

            // Update with new image
            $sql = "UPDATE catalogue SET item_name=?, description=?, price=?, category=?, item=?, link=?, image_url=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdssssi", $name, $description, $price, $category, $item, $link, $image, $id);
        } else {
            // Update without changing image
            $sql = "UPDATE catalogue SET item_name=?, description=?, price=?, category=?, item=?, link=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdsssi", $name, $description, $price, $category, $item, $link, $id);
        }

        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        // Handle Delete Form Submission
        $id = $_POST['delete_id'];
        $sql = "DELETE FROM catalogue WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Handle Add Form Submission (already implemented)
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $image = $_FILES['image']['name'];
        $target_dir = "../assets/pictures/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        $item = $_POST['item'];
        $link = $_POST['link']; // Added line to retrieve link from form

        $sql = "INSERT INTO catalogue (item_name, description, price, category, image_url, item, link) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdssss", $name, $description, $price, $category, $image, $item, $link);
        $stmt->execute();
        $stmt->close();
    }
}

// Retrieve catalog items
$sql = "SELECT * FROM catalogue";
$result = $conn->query($sql);
$catalogueItems = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $catalogueItems[] = $row;
    }
}

$sql = "SELECT DISTINCT item FROM catalogue";
$result = $conn->query($sql);
$items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row['item'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Catalogue</title>
</head>
<body class="content">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h5>E-Catalogue Items</h5>
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addCatalogueModal">
            <i class="fas fa-plus"></i>
        </button>
    </div>
    
    <table class="table table-striped text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($catalogueItems as $item): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td style="width: 400px; overflow-x: auto;"><?php echo $item['item_name']; ?></td>
                    <td>₱<?php echo $item['price']; ?></td>
                    <td><?php echo $item['category']; ?></td>
                    <td><img src="../assets/pictures/<?php echo $item['image_url']; ?>" alt="<?php echo $item['item_name']; ?>" width="50"></td>
                    <td>
                        <!-- Edit Button -->
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal<?php echo $item['id']; ?>">
                            <i class="fas fa-edit"></i>
                        </button>
                        
                        <div class="modal fade" id="editModal<?php echo $item['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $item['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel<?php echo $item['id']; ?>">Edit Catalogue Item</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <input type="hidden" name="action" value="edit">
                                            <input type="hidden" name="edit_id" value="<?php echo $item['id']; ?>">
                                            <div class="form-group">
                                                <label for="edit_name<?php echo $item['id']; ?>">Name</label>
                                                <input type="text" class="form-control" id="edit_name<?php echo $item['id']; ?>" name="name" value="<?php echo $item['item_name']; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_description<?php echo $item['id']; ?>">Description</label>
                                                <textarea class="form-control" id="edit_description<?php echo $item['id']; ?>" name="description" rows="3" required><?php echo $item['description']; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_price<?php echo $item['id']; ?>">Price</label>
                                                <input type="text" class="form-control" id="edit_price<?php echo $item['id']; ?>" name="price" value="<?php echo $item['price']; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_category<?php echo $item['id']; ?>">Category</label>
                                                <select class="form-control edit-category" id="edit_category<?php echo $item['id']; ?>" name="category" required>
                                                    <option value="Irrigation Equipment" <?php echo ($item['category'] == 'Irrigation Equipment') ? 'selected' : ''; ?>>Irrigation Equipment</option>
                                                    <option value="Fertilizers and Soil Conditioners" <?php echo ($item['category'] == 'Fertilizers and Soil Conditioners') ? 'selected' : ''; ?>>Fertilizers and Soil Conditioners</option>
                                                    <option value="Gardening Tools" <?php echo ($item['category'] == 'Gardening Tools') ? 'selected' : ''; ?>>Gardening Tools</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_image<?php echo $item['id']; ?>">Image</label>
                                                <input type="file" class="form-control-file" id="edit_image<?php echo $item['id']; ?>" name="image">
                                                <small id="edit_imageHelp" class="form-text text-muted">Leave blank if you don't want to change the image.</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_item<?php echo $item['id']; ?>">Item</label>
                                                <select class="form-control" id="edit_item<?php echo $item['id']; ?>" name="item" required>
                                                    <?php foreach ($items as $option): ?>
                                                        <option value="<?php echo $option; ?>" <?php echo ($item['item'] == $option) ? 'selected' : ''; ?>><?php echo $option; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_link<?php echo $item['id']; ?>">Link</label>
                                                <input type="text" class="form-control" id="edit_link<?php echo $item['id']; ?>" name="link" value="<?php echo $item['link']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Delete Button -->
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?php echo $item['id']; ?>">
                            <i class="fas fa-trash"></i>
                        </button>
                        
                        <div class="modal fade" id="deleteModal<?php echo $item['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?php echo $item['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel<?php echo $item['id']; ?>">Confirm Delete</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="" method="post">
                                        <div class="modal-body">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="delete_id" value="<?php echo $item['id']; ?>">
                                            Are you sure you want to delete "<?php echo $item['item_name']; ?>"?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Catalogue Modal -->
<div class="modal fade" id="addCatalogueModal" tabindex="-1" role="dialog" aria-labelledby="addCatalogueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCatalogueModalLabel">Add Catalogue Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="text" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="Irrigation Equipment">Irrigation Equipment</option>
                            <option value="Fertilizers and Soil Conditioners">Fertilizers and Soil Conditioners</option>
                            <option value="Gardening Tools">Gardening Tools</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control-file" id="image" name="image" required>
                    </div>
                    <div class="form-group">
                        <label for="item">Item</label>
                        <select class="form-control" id="item" name="item" required>
                            <?php foreach ($items as $option): ?>
                                <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="link">Link</label>
                        <input type="text" class="form-control" id="link" name="link" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function updateItemDropdown(category, itemDropdown) {
        itemDropdown.empty();

        // Define items based on category (replace with actual database values)
        var items = [];
        switch(category) {
            case 'Irrigation Equipment':
                items = ['Hose', 'Sprinkler', 'Watering Can', 'Rain Barrel'];
                break;
            case 'Fertilizers and Soil Conditioners':
                items = ['Organic Fertilizer', 'Compost', 'Mulch'];
                break;
            case 'Gardening Tools':
                items = ['Hand Trowel', 'Pruning Shears', 'Hose Nozzle'];
                break;
            default:
                items = [];
        }

        // Populate dropdown with items
        $.each(items, function(index, value) {
            itemDropdown.append($('<option></option>').attr('value', value).text(value));
        });
    }

    // function toggleAdditionalInputs(category, form) {
    //     var soilTypeRow = form.find('.additional-inputs[id$=_row]');
    //     var usageRow = form.find('.additional-inputs[id$=_row]');

    //     switch(category) {
    //         case 'Irrigation Equipment':
    //             soilTypeRow.show();
    //             usageRow.show();
    //             break;
    //         case 'Fertilizers and Soil Conditioners':
    //             soilTypeRow.show();
    //             usageRow.show();
    //             break;
    //         case 'Gardening Tools':
    //             soilTypeRow.hide();
    //             usageRow.hide();
    //             break;
    //         default:
    //             soilTypeRow.hide();
    //             usageRow.hide();
    //     }
    // }

    // For add modal
    $('#category').change(function() {
        var category = $(this).val();
        var itemDropdown = $('#item');
        updateItemDropdown(category, itemDropdown);
        toggleAdditionalInputs(category, $(this).closest('form'));
    });

    // For edit modals
    $('.edit-category').change(function() {
        var category = $(this).val();
        var itemDropdown = $(this).closest('.modal-body').find('.edit-item');
        updateItemDropdown(category, itemDropdown);
        toggleAdditionalInputs(category, $(this).closest('form'));
    });

    // Initial call for add modal
    updateItemDropdown($('#category').val(), $('#item'));
    toggleAdditionalInputs($('#category').val(), $('#addCatalogueModal form'));

    // Initial call for edit modals
    $('.edit-category').each(function() {
        var category = $(this).val();
        var itemDropdown = $(this).closest('.modal-body').find('.edit-item');
        updateItemDropdown(category, itemDropdown);
        toggleAdditionalInputs(category, $(this).closest('form'));
    });
});

</script>

</body>
</html>
