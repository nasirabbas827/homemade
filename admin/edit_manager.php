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
    $manager = mysqli_fetch_assoc($result);
} else {
    // Redirect if the manager does not exist
    header("Location: view_managers.php");
    exit;
}

// Check if the form is submitted for updating manager details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $name = $_POST["name"];
    $email = $_POST["email"];

    // Update manager details in the database
    $updateSql = "UPDATE managers SET Name = '$name', Email = '$email' WHERE manager_id = $managerId";
    if (mysqli_query($conn, $updateSql)) {
        header("Location: view_managers.php");
        exit;
    } else {
        echo "Error updating manager: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Edit Manager</title>
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
    <h2>Edit Manager</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $managerId; ?>">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $manager['Name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $manager['Email']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Manager</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
