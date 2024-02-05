<?php
include('config.php');
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: login.php");
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION["id"];

// Fetch user details from the database
$sql = "SELECT id, username, email, age FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $fetched_id, $username, $email, $age);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
// Fetch approved shops from the database
$sql = "SELECT ShopID, ManagerID, ShopName, Location, ShopPicture, ContactNumber FROM HomeFoodShops WHERE ApprovalStatus = 'Approved'";
$result = mysqli_query($conn, $sql);

// Check if approved shops exist
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
    <title>HomePage</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .image-card {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h2>Welcome, <?php echo $username; ?>!</h2>

        <h3>Available Shops:</h3>
        <div class="row">
            <?php foreach ($shops as $shop) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="./admin/<?php echo $shop['ShopPicture']; ?>" class="card-img-top img-fluid image-card" alt="<?php echo $shop['ShopName']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $shop['ShopName']; ?></h5>
                            <p class="card-text">Location: <?php echo $shop['Location']; ?></p>
                            <p class="card-text">Contact: <?php echo $shop['ContactNumber']; ?></p>
                            <a href="view_products.php?shop_id=<?php echo $shop['ShopID']; ?>" class="btn btn-primary">View Products</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>



    </div>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>