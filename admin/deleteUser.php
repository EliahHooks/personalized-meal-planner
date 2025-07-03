<?php
require_once __DIR__ . '/../database/db.php';
session_start();

if ($_SESSION['role'] !== 'admin') exit();

$id = $_GET['id'] ?? null;
$_db->insert("DELETE FROM Users WHERE userID = ?", [$id]);

header("Location: manageUsers.php");
exit();
