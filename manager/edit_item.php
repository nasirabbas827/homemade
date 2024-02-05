<?php
session_start();
include('config.php');

// Check if the manager is logged in
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "manager") {
    header("Location: manager_login.php");
    exit;
}

// Check if the item ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_items.php");
    exit;
}

$itemId = $_GET['id'];

// Fetch item details from the database
$sql = "SELECT * FROM Items WHERE ItemID = $itemId";
$result = mysqli_query($conn, $sql);

// Check if the item exists
if (mysqli_num_rows($result) == 1) {
    $item = mysqli_fetch_assoc($result);
} else {
    // Redirect if the item does not exist
    header("Location: view_items.php");
    exit;
}

// Check if the form is submitted for updating item details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $categoryId = $_POST["category_id"];
    $itemName = $_POST["item_name"];
    $price = $_POST["price"];
    
    // Handle picture upload
    $picture = $item['Picture']; // Default to existing picture

    if ($_FILES['new_picture']['error'] == 0) {
        // If a new picture is uploaded, handle it
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["new_picture"]["name"]);
        move_uploaded_file($_FILES["new_picture"]["tmp_name"], $targetFile);
        $picture = $targetFile;
    }

    // Update item details in the database
    $updateSql = "UPDATE Items 
                  SET CategoryID = '$categoryId', ItemName = '$itemName', 
                      Price = '$price', Picture = '$picture'
                  WHERE ItemID = $itemId";
    
    if (mysqli_query($conn, $updateSql)) {
        header("Location: view_items.php");
        exit;
    } else {
        echo "Error updating item: " . mysqli_error($conn);
    }
}

// Fetch all categories for the shop
$categoriesQuery = "SELECT * FROM Categories WHERE ShopID = '{$item['ShopID']}'";
$categoriesResult = mysqli_query($conn, $categoriesQuery);

// Check if categories exist
if (mysqli_num_rows($categoriesResult) > 0) {
    $categories = mysqli_fetch_all($categoriesResult, MYSQLI_ASSOC);
} else {
    // No categories found
    $categories = [];
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Item - Manager Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('manager_navbar.php'); ?>

<div class="container mt-5">
    <h2>Edit Item</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $itemId; ?>" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="category_id">Category:</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['CategoryID']; ?>" <?php echo ($category['CategoryID'] == $item['CategoryID']) ? 'selected' : ''; ?>>
                            <?php echo $category['CategoryName']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="item_name">Item Name:</label>
                <input type="text" class="form-control" id="item_name" name="item_name" value="<?php echo $item['ItemName']; ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="price">Price:</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $item['Price']; ?>" required>
            </div>
            <div class="form-group col-md-6">
                <label for="new_picture">New Picture:</label>
                <input type="file" class="form-control-file" id="new_picture" name="new_picture">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Item</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
