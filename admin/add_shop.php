<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch all managers from the database
$sqlManagers = "SELECT manager_id, Name FROM managers";
$resultManagers = mysqli_query($conn, $sqlManagers);

// Check if managers exist
if (mysqli_num_rows($resultManagers) > 0) {
    $managers = mysqli_fetch_all($resultManagers, MYSQLI_ASSOC);
} else {
    $managers = [];
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $selectedManagerId = $_POST["selected_manager"];
    $shopName = $_POST["shop_name"];
    $location = $_POST["location"];
    $approvalStatus = $_POST["approval_status"];
    $contactNumber = $_POST["contact_number"];

    // Upload shop picture (assuming you have a file input with name 'shop_picture')
    $target_dir = "shop_pictures/";
    $target_file = $target_dir . basename($_FILES["shop_picture"]["name"]);
    move_uploaded_file($_FILES["shop_picture"]["tmp_name"], $target_file);

    // Insert data into the HomeFoodShops table
    $sql = "INSERT INTO HomeFoodShops (ManagerID, ShopName, Location, ApprovalStatus, ShopPicture, ContactNumber) 
            VALUES ('$selectedManagerId', '$shopName', '$location', '$approvalStatus', '$target_file', '$contactNumber')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Shop added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Add Shop</title>
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
    <h2>Add Shop</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="shop_picture">Shop Picture:</label>
                <input type="file" class="form-control" id="shop_picture" name="shop_picture" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="selected_manager">Select Manager:</label>
                <select class="form-control" id="selected_manager" name="selected_manager" required>
                    <?php foreach ($managers as $manager): ?>
                        <option value="<?php echo $manager['manager_id']; ?>"><?php echo $manager['Name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="shop_name">Shop Name:</label>
                <input type="text" class="form-control" id="shop_name" name="shop_name" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="approval_status">Approval Status:</label>
                <select class="form-control" id="approval_status" name="approval_status" required>
                    <option value="Approved">Approved</option>
                    <option value="Disapproved">Disapproved</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="text" class="form-control" id="contact_number" name="contact_number" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Add Shop</button>
            <a class="btn btn-outline-dark" href="view_shops.php">View Shops</a>
        </div>
    </div>
</form>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
