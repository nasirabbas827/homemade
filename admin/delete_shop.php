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
$sql = "SELECT * FROM HomeFoodShops WHERE ShopID = $shopId";
$result = mysqli_query($conn, $sql);

// Check if the shop exists
if (mysqli_num_rows($result) == 1) {
    $shop = mysqli_fetch_assoc($result);

    // Delete shop from the database
    $deleteSql = "DELETE FROM HomeFoodShops WHERE ShopID = $shopId";
    if (mysqli_query($conn, $deleteSql)) {
        echo "<script>alert('Shop deleted successfully.'); window.location.href='view_shops.php';</script>";
        exit;
    } else {
        echo "Error deleting shop: " . mysqli_error($conn);
    }
} else {
    // Redirect if the shop does not exist
    header("Location: view_shops.php");
    exit;
}

mysqli_close($conn);
?>
