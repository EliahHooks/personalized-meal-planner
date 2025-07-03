<?php
session_start();
define('BASE_URL', '/personalized-meal-planner/');
// Restrict access to admins only
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../signIn.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-links {
            margin-top: 2rem;
            text-align: left;
        }
        .admin-links a {
            display: block;
            margin: 12px 0;
            font-size: 1.1rem;
            color: #28a745;
            text-decoration: none;
        }
        .admin-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Welcome Admin, <?php echo htmlspecialchars($_SESSION['userName']); ?> 👋</h1>
    <p>Use the links below to manage the application.</p>

    <div class="admin-links">
        <a href="<?php echo BASE_URL; ?>admin/manageUsers.php">👤 Manage Users</a>
        <a href="<?php echo BASE_URL; ?>admin/moderateContent.php">📝 Moderate Content</a>
        <a href="<?php echo BASE_URL; ?>admin/adminDataEntry.php">📦 Admin Data Entry</a>
    </div>

    <br><br>
    <a href="<?php echo BASE_URL; ?>pages/mealPlanner.php"><button>Back to User Dashboard</button></a>
</div>
</body>
</html>
