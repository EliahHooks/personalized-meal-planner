<?php
session_start();
require_once __DIR__ . '/../database/db.php';
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
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f2f2f2; padding: 40px;">
    <h1 style="text-align: center; color: #333;">Manage Users</h1>

    <table border="1" cellpadding="10" cellspacing="0" style="margin: auto; background: #fff; border-collapse: collapse; width: 80%;">
        <tr style="background-color: #ddd;">
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr style="text-align: center;">
                <td><?= $user['userID'] ?></td>
                <td><?= htmlspecialchars($user['userName']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['role'] ?></td>
                <td>
                    <a href="editUser.php?id=<?= $user['userID'] ?>" style="color: #007bff; text-decoration: none;">Edit</a> |
                    <a href="deleteUser.php?id=<?= $user['userID'] ?>" style="color: red; text-decoration: none;" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div style="text-align: center; margin-top: 30px;">
        <a href="dashboard.php" 
           style="display: inline-block; background: green; color: white; padding: 12px 30px; border-radius: 8px; font-size: 1rem; font-weight: 500; text-decoration: none; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3); transition: all 0.3s ease;"
           onmouseover="this.style.boxShadow='0 8px 25px rgba(40, 167, 69, 0.4)'; this.style.transform='translateY(-2px)'"
           onmouseout="this.style.boxShadow='0 4px 15px rgba(40, 167, 69, 0.3)'; this.style.transform='translateY(0)'"
        >
            ← Back
        </a>
    </div>
</body>
</html>
