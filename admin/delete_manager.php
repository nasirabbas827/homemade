<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Check if the manager ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_managers.php");
    exit;
}

$managerId = $_GET['id'];

// Fetch manager details from the database
$sql = "SELECT * FROM managers WHERE manager_id = $managerId";
$result = mysqli_query($conn, $sql);

// Check if the manager exists
if (mysqli_num_rows($result) == 1) {
    // Perform the deletion
    $deleteSql = "DELETE FROM managers WHERE manager_id = $managerId";
    if (mysqli_query($conn, $deleteSql)) {
        echo "<script>alert('Manager deleted successfully.'); window.location.href='view_managers.php';</script>";
        exit;
    } else {
        echo "Error deleting manager: " . mysqli_error($conn);
    }
} else {
    // Redirect if the manager does not exist
    header("Location: view_managers.php");
    exit;
}

mysqli_close($conn);
?>
