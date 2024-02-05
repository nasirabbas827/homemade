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
        $categories = [];
    }
} else {
    // Redirect if the assigned shop does not exist
    header("Location: manager_login.php");
    exit;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager Dashboard - View Categories</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('manager_navbar.php'); ?>

<div class="container mt-5">
    <h2>View Categories</h2>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Category Name</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?php echo $category['CategoryID']; ?></td>
                <td><?php echo $category['CategoryName']; ?></td>
                <td>
                    <a href="edit_category.php?id=<?php echo $category['CategoryID']; ?>" class="btn btn-warning">Edit</a>
                    <button class="btn btn-danger" onclick="confirmDelete(<?php echo $category['CategoryID']; ?>)">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function confirmDelete(categoryId) {
        var result = confirm("Are you sure you want to delete this category?");
        if (result) {
            window.location.href = "delete_category.php?id=" + categoryId;
        }
    }
</script>
</body>
</html>
