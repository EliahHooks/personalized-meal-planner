<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db.php';

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$username || !$email || !$password) {
    die("All fields are required.");
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO Users (userName, password, email) VALUES (?, ?, ?)";
$result = $_db->insert($sql, [$username, $hashedPassword, $email]);

if ($result) {
    echo "✅ User registered!";
} else {
    echo "❌ Registration failed.";
}
?>
