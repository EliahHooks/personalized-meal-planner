<?php
require_once __DIR__ . '/../database/db.php';
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../signIn.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if (!$username || !$email || !$password) {
        $error = "All fields are required.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $_db->insert("INSERT INTO Users (userName, email, password, role) VALUES (?, ?, ?, ?)", [
            $username, $email, $hashedPassword, $role
        ]);
        header("Location: manageUsers.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New User</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f9f9f9; padding: 40px;">
    <h2 style="text-align: center;">Add New User</h2>

    <?php if ($error): ?>
        <p style="color: red; text-align: center;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" style="max-width: 400px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <label>Username:</label><br>
        <input type="text" name="username" required style="width: 100%; padding: 8px;"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required style="width: 100%; padding: 8px;"><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required style="width: 100%; padding: 8px;"><br><br>

        <label>Role:</label><br>
        <select name="role" style="width: 100%; padding: 8px;">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <button type="submit" style="width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 6px;">Add User</button>
    </form>

    <div style="text-align: center; margin-top: 20px;">
        <a href="manageUsers.php" style="text-decoration: none; color: #007bff;">← Back to Manage Users</a>
    </div>
</body>
</html>
