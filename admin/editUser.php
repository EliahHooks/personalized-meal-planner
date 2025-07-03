<?php
require_once '../db.php';
session_start();

if ($_SESSION['role'] !== 'admin') exit();

$id = $_GET['id'] ?? null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $_db->insert("UPDATE Users SET userName=?, email=?, role=? WHERE userID=?", [$username, $email, $role, $id]);
    header("Location: manageUsers.php");
    exit();
}

$user = $_db->selectOne("SELECT * FROM Users WHERE userID = ?", [$id]);
?>

<form method="POST">
    Username: <input name="username" value="<?= $user['userName'] ?>"><br>
    Email: <input name="email" value="<?= $user['email'] ?>"><br>
    Role: 
    <select name="role">
        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
    </select><br>
    <input type="submit" value="Update User">
</form>
