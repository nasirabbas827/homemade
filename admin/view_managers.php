<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch all managers from the database
$sql = "SELECT * FROM managers";
$result = mysqli_query($conn, $sql);

// Check if managers exist
if (mysqli_num_rows($result) > 0) {
    $managers = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $managers = [];
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - View Managers</title>
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
    <h2>View Managers</h2>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($managers as $manager): ?>
            <tr>
                <td><?php echo $manager['manager_id']; ?></td>
                <td><?php echo $manager['Name']; ?></td>
                <td><?php echo $manager['Email']; ?></td>
                <td>
                    <a href="edit_manager.php?id=<?php echo $manager['manager_id']; ?>" class="btn btn-warning">Edit</a>
                    <button class="btn btn-danger" onclick="confirmDelete(<?php echo $manager['manager_id']; ?>)">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function confirmDelete(managerId) {
        var result = confirm("Are you sure you want to delete this manager?");
        if (result) {
            window.location.href = "delete_manager.php?id=" + managerId;
        }
    }
</script>
</body>
</html>
