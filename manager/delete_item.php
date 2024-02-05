<?php
session_start();
include('config.php');

// Check if the manager is logged in
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "manager") {
    header("Location: manager_login.php");
    exit;
}

// Check if the item ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_items.php");
    exit;
}

$itemId = $_GET['id'];

// Fetch item details from the database
$sql = "SELECT * FROM Items WHERE ItemID = $itemId";
$result = mysqli_query($conn, $sql);

// Check if the item exists
if (mysqli_num_rows($result) == 1) {
    $item = mysqli_fetch_assoc($result);
} else {
    // Redirect if the item does not exist
    header("Location: view_items.php");
    exit;
}

// Delete the item from the database
$deleteSql = "DELETE FROM Items WHERE ItemID = $itemId";

if (mysqli_query($conn, $deleteSql)) {
    header("Location: view_items.php");
    exit;
} else {
    echo "Error deleting item: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
