<?php
session_start();

define('BASE_URL', '/personalized-meal-planner/');

// Block access if not logged in
if (!isset($_SESSION['userID'])) {
    header('Location: signIn.php');
    exit();
}

$userName = $_SESSION['userName'];
$email = $_SESSION['email'];
$height = $_SESSION['height'];
$weight = $_SESSION['weight'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meal Planner</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($userName); ?>!</h1>
    <p>Email: <?php echo htmlspecialchars($email); ?></p>
    <p>Height: <?php echo htmlspecialchars($height); ?> inches</p>
    <p>Weight: <?php echo htmlspecialchars($weight); ?> lbs</p>

    <p>This is your meal planner dashboard. More features coming soon!</p>

    <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="<?php echo BASE_URL; ?>admin/dashboard.php"><button>Go to Admin Panel</button></a>
    <?php endif; ?>

    <br><br>
    <a href="logout.php"><button>Logout</button></a>
</div>
</body>
</html>
