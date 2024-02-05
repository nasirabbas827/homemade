<?php
session_start();
include('config.php');

// Check if the manager is logged in
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "manager") {
    header("Location: manager_login.php");
    exit;
}

// Check if the category ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_categories.php");
    exit;
}

$categoryId = $_GET['id'];

// Fetch category details from the database
$categoryQuery = "SELECT * FROM Categories WHERE CategoryID = $categoryId";
$categoryResult = mysqli_query($conn, $categoryQuery);

// Check if the category exists
if (mysqli_num_rows($categoryResult) == 1) {
    $category = mysqli_fetch_assoc($categoryResult);
} else {
    // Redirect if the category does not exist
    header("Location: view_categories.php");
    exit;
}

// Check if the form is submitted for updating category details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $categoryName = $_POST["category_name"];
    $categoryName = mysqli_real_escape_string($conn, $categoryName);

    // Update category details in the database
    $updateSql = "UPDATE Categories SET CategoryName = '$categoryName' WHERE CategoryID = $categoryId";

    if (mysqli_query($conn, $updateSql)) {
        header("Location: view_categories.php");
        exit;
    } else {
        echo "Error updating category: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category - Manager Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('manager_navbar.php'); ?>

<div class="container mt-5">
    <h2>Edit Category</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $categoryId; ?>">
        <div class="form-group">
            <label for="category_name">Category Name:</label>
            <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo $category['CategoryName']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Category</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
