<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch all shops with manager names from the database
$sql = "SELECT hfs.*, m.Name AS ManagerName FROM HomeFoodShops hfs
        INNER JOIN managers m ON hfs.ManagerID = m.manager_id";
$result = mysqli_query($conn, $sql);

// Check if shops exist
if (mysqli_num_rows($result) > 0) {
    $shops = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $shops = [];
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - View Shops</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
include('admin_navbar.php');
?>

<div class="container mt-5">
    <h2>View Shops</h2>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Manager Name</th>
            <th>Shop Name</th>
            <th>Location</th>
            <th>Approval Status</th>
            <th>Contact Number</th>
            <th>Shop Picture</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($shops as $shop): ?>
            <tr>
                <td><?php echo $shop['ShopID']; ?></td>
                <td><?php echo $shop['ManagerName']; ?></td>
                <td><?php echo $shop['ShopName']; ?></td>
                <td><?php echo $shop['Location']; ?></td>
                <td><?php echo $shop['ApprovalStatus']; ?></td>
                <td><?php echo $shop['ContactNumber']; ?></td>
                <td><img src="<?php echo $shop['ShopPicture']; ?>" alt="Shop Picture" style="max-width: 100px;"></td>
                <td>
                    <a href="edit_shop.php?id=<?php echo $shop['ShopID']; ?>" class="btn btn-warning">Edit</a>
                    <button class="btn btn-danger" onclick="confirmDelete(<?php echo $shop['ShopID']; ?>)">Delete</button>
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
    function confirmDelete(shopId) {
        var result = confirm("Are you sure you want to delete this shop?");
        if (result) {
            window.location.href = "delete_shop.php?id=" + shopId;
        }
    }
</script>
</body>
</html>
