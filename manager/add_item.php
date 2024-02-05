<?php
session_start();
include('config.php');

// Check if the manager is logged in
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "manager") {
    header("Location: manager_login.php");
    exit;
}

// Fetch the assigned shop details based on manager_id
$managerId = $_SESSION["manager_id"];
$query = "SELECT * FROM HomeFoodShops WHERE ManagerID = '$managerId'";
$result = mysqli_query($conn, $query);

// Check if the assigned shop exists
if (mysqli_num_rows($result) == 1) {
    $shop = mysqli_fetch_assoc($result);

    // Fetch all categories for the shop
    $categoriesQuery = "SELECT * FROM Categories WHERE ShopID = '{$shop['ShopID']}'";
    $categoriesResult = mysqli_query($conn, $categoriesQuery);

    // Check if categories exist
    if (mysqli_num_rows($categoriesResult) > 0) {
        $categories = mysqli_fetch_all($categoriesResult, MYSQLI_ASSOC);
    } else {
        // Redirect if no categories exist
        header("Location: view_categories.php");
        exit;
    }
} else {
    // Redirect if the assigned shop does not exist
    header("Location: manager_login.php");
    exit;
}

// Check if the form is submitted for adding an item
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $categoryId = $_POST["category_id"];
    $itemName = $_POST["item_name"];
    $price = $_POST["price"];

    // Handle file upload
    $picture = '';
    if ($_FILES["picture"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an image
        $check = getimagesize($_FILES["picture"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if the file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size (limit to 2MB)
        if ($_FILES["picture"]["size"] > 2 * 1024 * 1024) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        $allowedFormats = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowedFormats)) {
            echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
            $uploadOk = 0;
        }

        // If everything is ok, try to upload the file
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                $picture = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Insert item details into the database
    $insertSql = "INSERT INTO Items (CategoryID, ShopID, ItemName, Price, Picture) 
                  VALUES ('$categoryId', '{$shop['ShopID']}', '$itemName', '$price', '$picture')";

    if (mysqli_query($conn, $insertSql)) {
        header("Location: view_items.php");
        exit;
    } else {
        echo "Error adding item: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Item - Manager Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('manager_navbar.php'); ?>

<div class="container mt-5">
    <h2>Add Item</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <div class="form-group">
            <label for="category_id">Category:</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['CategoryID']; ?>"><?php echo $category['CategoryName']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="item_name">Item Name:</label>
            <input type="text" class="form-control" id="item_name" name="item_name" required>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
        </div>
        <div class="form-group">
            <label for="picture">Picture:</label>
            <input type="file" class="form-control-file" id="picture" name="picture" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Item</button>
        <a class="btn btn-outline-dark" href="view_items.php">View Items</a>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
