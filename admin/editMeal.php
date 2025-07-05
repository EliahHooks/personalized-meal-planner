<?php
// editMeal.php
session_start();
require_once __DIR__ . '/../database/db.php';

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/signIn.php');
    exit();
}

$mealID = $_GET['id'] ?? null;

if (!$mealID) {
    die('Invalid meal ID');
}

$meal = $_db->selectOne("SELECT * FROM UserMeals WHERE id = ?", [$mealID]);
$foods = $_db->select("SELECT * FROM Foods ORDER BY category, name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mealName = $_POST['mealName'];
    $mealType = $_POST['mealType'];
    $entreeID = $_POST['entreeID'] ?: null;
    $side1ID = $_POST['side1ID'] ?: null;
    $side2ID = $_POST['side2ID'] ?: null;
    $drinkID = $_POST['drinkID'] ?: null;

    $update = $_db->update("UPDATE UserMeals SET mealName=?, mealType=?, entreeID=?, side1ID=?, side2ID=?, drinkID=? WHERE id=?",
        [$mealName, $mealType, $entreeID, $side1ID, $side2ID, $drinkID, $mealID]);

    header('Location: moderateContent.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Meal</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body { font-family: sans-serif; background: #f5f7fa; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 20px; text-align: center; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
        button { margin-top: 20px; padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #218838; }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Meal</h2>
    <form method="POST">
        <label for="mealName">Meal Name</label>
        <input type="text" name="mealName" value="<?= htmlspecialchars($meal['mealName']) ?>" required>

        <label for="mealType">Meal Type</label>
        <select name="mealType" required>
            <option value="breakfast" <?= $meal['mealType'] === 'breakfast' ? 'selected' : '' ?>>Breakfast</option>
            <option value="lunch" <?= $meal['mealType'] === 'lunch' ? 'selected' : '' ?>>Lunch</option>
            <option value="dinner" <?= $meal['mealType'] === 'dinner' ? 'selected' : '' ?>>Dinner</option>
        </select>

        <?php foreach (['entreeID' => 'Entree', 'side1ID' => 'Side 1', 'side2ID' => 'Side 2', 'drinkID' => 'Drink'] as $field => $label): ?>
            <label for="<?= $field ?>"><?= $label ?></label>
            <select name="<?= $field ?>">
                <option value="">None</option>
                <?php foreach ($foods as $food): ?>
                    <option value="<?= $food['id'] ?>" <?= ($meal[$field] == $food['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($food['name']) ?> (<?= $food['category'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endforeach; ?>

        <button type="submit">Update Meal</button>
    </form>
</div>
</body>
</html>
