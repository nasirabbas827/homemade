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

    // Fetch all items for the shop
    $itemsQuery = "SELECT i.*, c.CategoryName
                   FROM Items i
                   JOIN Categories c ON i.CategoryID = c.CategoryID
                   WHERE i.ShopID = '{$shop['ShopID']}'";
    $itemsResult = mysqli_query($conn, $itemsQuery);

    // Check if items exist
    if (mysqli_num_rows($itemsResult) > 0) {
        $items = mysqli_fetch_all($itemsResult, MYSQLI_ASSOC);
    } else {
        // No items found
        $items = [];
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
    <title>View Items - Manager Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('manager_navbar.php'); ?>

<div class="container mt-5">
    <h2>View Items</h2>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Name</th>
            <th>Price</th>
            <th>Picture</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo $item['ItemID']; ?></td>
                <td><?php echo $item['CategoryName']; ?></td>
                <td><?php echo $item['ItemName']; ?></td>
                <td><?php echo $item['Price']; ?></td>
                <td>
                    <?php if (!empty($item['Picture'])): ?>
                        <img src="<?php echo $item['Picture']; ?>" alt="Item Picture" height="50">
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_item.php?id=<?php echo $item['ItemID']; ?>" class="btn btn-warning">Edit</a>
                    <button class="btn btn-danger" onclick="confirmDelete(<?php echo $item['ItemID']; ?>)">Delete</button>
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
    function confirmDelete(itemId) {
        var result = confirm("Are you sure you want to delete this item?");
        if (result) {
            window.location.href = "delete_item.php?id=" + itemId;
        }
    }
</script>
</body>
</html>
