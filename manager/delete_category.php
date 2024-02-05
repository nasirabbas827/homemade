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

// Delete the category from the database
$deleteSql = "DELETE FROM Categories WHERE CategoryID = $categoryId";

if (mysqli_query($conn, $deleteSql)) {
    header("Location: view_categories.php");
    exit;
} else {
    echo "Error deleting category: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
