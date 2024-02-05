<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Check if the shop ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_shops.php");
    exit;
}

$shopId = $_GET['id'];

// Fetch shop details from the database
$sql = "SELECT hfs.*, m.Name AS ManagerName, m.manager_id AS ManagerID FROM HomeFoodShops hfs
        INNER JOIN managers m ON hfs.ManagerID = m.manager_id
        WHERE ShopID = $shopId";
$result = mysqli_query($conn, $sql);

// Check if the shop exists
if (mysqli_num_rows($result) == 1) {
    $shop = mysqli_fetch_assoc($result);
} else {
    // Redirect if the shop does not exist
    header("Location: view_shops.php");
    exit;
}

// Fetch all managers for the dropdown
$managersSql = "SELECT * FROM managers";
$managersResult = mysqli_query($conn, $managersSql);

// Check if managers exist
if (mysqli_num_rows($managersResult) > 0) {
    $managers = mysqli_fetch_all($managersResult, MYSQLI_ASSOC);
} else {
    $managers = [];
}

// Check if the form is submitted for updating shop details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $selectedManagerId = $_POST["manager_id"];
    $shopName = $_POST["shop_name"];
    $location = $_POST["location"];
    $approvalStatus = $_POST["approval_status"];
    $contactNumber = $_POST["contact_number"];

    // Update shop details in the database
    $updateSql = "UPDATE HomeFoodShops 
                  SET ManagerID = '$selectedManagerId', ShopName = '$shopName', Location = '$location', 
                      ApprovalStatus = '$approvalStatus', ContactNumber = '$contactNumber'
                  WHERE ShopID = $shopId";

    if (mysqli_query($conn, $updateSql)) {
        header("Location: view_shops.php");
        exit;
    } else {
        echo "Error updating shop: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Edit Shop</title>
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
    <h2>Edit Shop</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $shopId; ?>">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="manager_id">Manager:</label>
                <select class="form-control" id="manager_id" name="manager_id" required>
                    <?php foreach ($managers as $manager): ?>
                        <option value="<?php echo $manager['manager_id']; ?>" <?php echo ($shop['ManagerID'] == $manager['manager_id']) ? 'selected' : ''; ?>>
                            <?php echo $manager['Name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="shop_name">Shop Name:</label>
                <input type="text" class="form-control" id="shop_name" name="shop_name" value="<?php echo $shop['ShopName']; ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="location">Location:</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo $shop['Location']; ?>" required>
            </div>
            <div class="form-group col-md-6">
                <label for="approval_status">Approval Status:</label>
                <select class="form-control" id="approval_status" name="approval_status" required>
                    <option value="Approved" <?php echo ($shop['ApprovalStatus'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                    <option value="Disapproved" <?php echo ($shop['ApprovalStatus'] == 'Disapproved') ? 'selected' : ''; ?>>Disapproved</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="contact_number">Contact Number:</label>
                <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo $shop['ContactNumber']; ?>" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Shop</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
