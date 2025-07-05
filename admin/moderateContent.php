<?php
session_start();
require_once __DIR__ . '/../database/db.php';

define('BASE_URL', '/personalized-meal-planner/');

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/signIn.php');
    exit();
}

$meals = $_db->select("SELECT um.id AS mealID, u.userName, um.mealName, um.mealType, 
    e.name AS entree, s1.name AS side1, s2.name AS side2, d.name AS drink 
    FROM UserMeals um
    JOIN Users u ON um.userID = u.userID
    LEFT JOIN Foods e ON um.entreeID = e.id
    LEFT JOIN Foods s1 ON um.side1ID = s1.id
    LEFT JOIN Foods s2 ON um.side2ID = s2.id
    LEFT JOIN Foods d ON um.drinkID = d.id
    ORDER BY u.userName, um.mealType");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Moderate Meals</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f5f7fa; padding: 20px; }
        .container { max-width: 1200px; margin: auto; }
        .card { background: #fff; padding: 25px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #f0f0f0; }
        a.btn, button.btn { padding: 8px 14px; border-radius: 6px; font-size: 0.9rem; text-decoration: none; border: none; cursor: pointer; }
        .btn-edit { background: #17a2b8; color: white; }
        .btn-delete { background: #dc3545; color: white; }
        .btn-edit:hover { background: #138496; }
        .btn-delete:hover { background: #c82333; }
        .admin-link { text-align: center; margin-top: 30px; }
        .admin-link a { padding: 10px 20px; background: #667eea; color: white; border-radius: 8px; text-decoration: none; }
        .admin-link a:hover { background: #5a67d8; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h2>🛠️ Moderate User Meal Plans</h2>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Meal Name</th>
                    <th>Type</th>
                    <th>Entree</th>
                    <th>Side 1</th>
                    <th>Side 2</th>
                    <th>Drink</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($meals as $meal): ?>
                <tr>
                    <td><?= htmlspecialchars($meal['userName']) ?></td>
                    <td><?= htmlspecialchars($meal['mealName']) ?></td>
                    <td><?= htmlspecialchars($meal['mealType']) ?></td>
                    <td><?= htmlspecialchars($meal['entree']) ?></td>
                    <td><?= htmlspecialchars($meal['side1']) ?></td>
                    <td><?= htmlspecialchars($meal['side2']) ?></td>
                    <td><?= htmlspecialchars($meal['drink']) ?></td>
                    <td>
                        <a href="editMeal.php?id=<?= $meal['mealID'] ?>" class="btn btn-edit">Edit</a>
                        <a href="deleteMeal.php?id=<?= $meal['mealID'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this meal?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="admin-link">
        <a href="dashboard.php">← Back to Admin Panel</a>
    </div>
</div>
</body>
</html>