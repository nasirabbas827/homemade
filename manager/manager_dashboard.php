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
    <title>Manager Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('manager_navbar.php'); ?>

<div class="container mt-5">
    <h2>Welcome, <?php echo $_SESSION["manager_name"]; ?>!</h2>
    <h3>Your Assigned Shop Details:</h3>
    <ul>
        <li>Shop Name: <?php echo $shop["ShopName"]; ?></li>
        <li>Location: <?php echo $shop["Location"]; ?></li>
        <li>Contact Number: <?php echo $shop["ContactNumber"]; ?></li>
    </ul>
    <a href="logout.php" class="btn btn-primary">Logout</a>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
