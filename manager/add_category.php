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
} else {
    // Redirect if the assigned shop does not exist
    header("Location: manager_login.php");
    exit;
}

// Check if the form is submitted for adding a category
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryName = $_POST["category_name"];

    // Validate and sanitize input data
    $categoryName = mysqli_real_escape_string($conn, $categoryName);

    // Insert category into the database
    $insertSql = "INSERT INTO Categories (ShopID, CategoryName) VALUES ('{$shop['ShopID']}', '$categoryName')";

    if (mysqli_query($conn, $insertSql)) {
        $successMessage = "Category added successfully!";
    } else {
        $errorMessage = "Error adding category: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category - Manager Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('manager_navbar.php'); ?>

<div class="container mt-5">
    <h2>Add Category</h2>
    <?php if (isset($successMessage)): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $successMessage; ?>
        </div>
    <?php endif; ?>
    <?php if (isset($errorMessage)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $errorMessage; ?>
        </div>
    <?php endif; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="category_name">Category Name:</label>
            <input type="text" class="form-control" id="category_name" name="category_name" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Category</button>
        <a class="btn btn-outline-dark" href="view_categories.php">View Categories</a>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
