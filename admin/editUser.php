<?php 
require_once __DIR__ . '/../database/db.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../signIn.php");
    exit();
}

$id = $_GET['id'] ?? null;

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    $_db->insert("UPDATE Users SET userName=?, email=?, role=? WHERE userID=?", [$username, $email, $role, $id]);

    header("Location: manageUsers.php");
    exit();
}

$user = $_db->selectOne("SELECT * FROM Users WHERE userID = ?", [$id]);
if (!$user) {
    echo "<p style='color:red; text-align:center;'>User not found.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f9f9f9; padding: 40px;">
    <h2 style="text-align: center;">Edit User</h2>

    <form method="POST" style="max-width: 400px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <label>Username:</label><br>
        <input type="text" name="username" value="<?= htmlspecialchars($user['userName']) ?>" required style="width: 100%; padding: 8px;"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required style="width: 100%; padding: 8px;"><br><br>

        <label>Role:</label><br>
        <select name="role" style="width: 100%; padding: 8px;">
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select><br><br>

        <button type="submit" style="width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 6px;">Update User</button>
    </form>

    <div style="text-align: center; margin-top: 20px;">
        <a href="manageUsers.php" style="text-decoration: none; color: #007bff;">← Back to Manage Users</a>
    </div>
</body>
</html>
