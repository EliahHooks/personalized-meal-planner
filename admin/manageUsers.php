<?php
session_start();
require_once '../db.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../signIn.php");
    exit();
}

$users = $_db->select("SELECT * FROM Users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
</head>
<body>
<h1>Manage Users</h1>
<table border="1">
    <tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Actions</th></tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['userID'] ?></td>
            <td><?= htmlspecialchars($user['userName']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= $user['role'] ?></td>
            <td>
                <a href="editUser.php?id=<?= $user['userID'] ?>">Edit</a> |
                <a href="deleteUser.php?id=<?= $user['userID'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="dashboard.php">← Back</a>
</body>
</html>
