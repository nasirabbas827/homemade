<?php
include('config.php');
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: login.php");
    exit;
}

// Check if the shop ID is provided in the URL
if (!isset($_GET['shop_id']) || empty($_GET['shop_id'])) {
    header("Location: index.php");
    exit;
}

$shopId = $_GET['shop_id'];

// Fetch shop details from the database
$sqlShop = "SELECT ShopID, ShopName FROM HomeFoodShops WHERE ShopID = $shopId";
$resultShop = mysqli_query($conn, $sqlShop);

// Check if the shop exists
if (mysqli_num_rows($resultShop) == 1) {
    $shop = mysqli_fetch_assoc($resultShop);
} else {
    // Redirect if the shop does not exist
    header("Location: index.php");
    exit;
}

// Fetch products with their category names of the selected shop from the database
$sqlProducts = "SELECT Items.ItemID, Items.CategoryID, Categories.CategoryName, Items.ItemName, Items.Price, Items.Picture
                FROM Items
                INNER JOIN Categories ON Items.CategoryID = Categories.CategoryID
                WHERE Items.ShopID = $shopId";
$resultProducts = mysqli_query($conn, $sqlProducts);

// Check if products exist
if (mysqli_num_rows($resultProducts) > 0) {
    $products = mysqli_fetch_all($resultProducts, MYSQLI_ASSOC);
} else {
    $products = [];
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Products - <?php echo $shop['ShopName']; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .product-card {
            height: 400px;
        }
    </style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Products of <?php echo $shop['ShopName']; ?></h2>

    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card product-card">
                    <img src="./manager/<?php echo $product['Picture']; ?>" class="card-img-top" alt="<?php echo $product['ItemName']; ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['ItemName']; ?></h5>
                        <p class="card-text">Category: <?php echo $product['CategoryName']; ?></p>
                        <p class="card-text">Price: $<?php echo $product['Price']; ?></p>
                        <a href="#" class="btn btn-primary">Order Now</a>
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
