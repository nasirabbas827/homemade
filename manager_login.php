<?php
session_start();
include('config.php');

// Check if the form is submitted for manager login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate and sanitize input data
    $email = mysqli_real_escape_string($conn, $email);

    // Check manager credentials
    $query = "SELECT * FROM managers WHERE Email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $manager = mysqli_fetch_assoc($result);

        // Verify the hashed password
        if (password_verify($password, $manager['Password'])) {
            // Store manager details in session
            $_SESSION["usertype"] = "manager";
            $_SESSION["manager_id"] = $manager["manager_id"];
            $_SESSION["manager_name"] = $manager["Name"];

            // Redirect to manager dashboard or assigned shop page
            header("Location: ./manager/manager_dashboard.php");
            exit;
        } else {
            $loginError = "Invalid email or password";
        }
    } else {
        $loginError = "Invalid email or password";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php
include('navbar.php');
?>

<div class="container mt-5">
    <h2>Manager Login</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <?php if (isset($loginError)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $loginError; ?>
            </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
