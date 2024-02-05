<?php
include('config.php');

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
    <title>Index</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
.jumbotron {
            height: 550px;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('./images/hotel.jpg');
            background-size: cover;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .jumbotron h1 {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .jumbotron p {
            font-size: 1.5rem;
        }
        .image-card {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>

<?php
include('navbar.php');
?>

<div class="jumbotron text-center">
    <h1>Welcome to Home-Made Food Shop</h1>
    <p>Find and Enjoy Quality Home-Cooked Meals with our Opinion-Driven Platform</p>
    <a href="login.php" class="btn btn-primary btn-lg">Login to Explore</a>
</div>

<div class="container mt-5">
 

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
                            <a href="login.php" class="btn btn-primary">View Products</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

<footer class="mt-5 py-3 bg-light">
    <div class="container text-center">
        <p>&copy; 2024 Home Made Shops. All rights reserved.</p>
    </div>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
