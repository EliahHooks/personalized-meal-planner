<?php
session_start();
require_once __DIR__ . '/../database/db.php';

define('BASE_URL', '/personalized-meal-planner/');

if (!isset($_SESSION['userID'])) {
    header('Location: signIn.php');
    exit();
}

$userID = $_SESSION['userID'];
$message = $error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add_meal') {
        $mealName = trim($_POST['mealName'] ?? '');
        $mealType = $_POST['mealType'] ?? '';
        $entreeID = $_POST['entreeID'] ?: null;
        $side1ID = $_POST['side1ID'] ?: null;
        $side2ID = $_POST['side2ID'] ?: null;
        $drinkID = $_POST['drinkID'] ?: null;
        
        if (!$mealName || !$mealType) {
            $error = "Meal name and type are required.";
        } else {
            $result = $_db->insert("INSERT INTO UserMeals (userID, mealName, mealType, entreeID, side1ID, side2ID, drinkID) VALUES (?, ?, ?, ?, ?, ?, ?)", 
                                 [$userID, $mealName, $mealType, $entreeID, $side1ID, $side2ID, $drinkID]);
            $message = $result ? "Meal added successfully!" : "Error adding meal.";
        }
    } elseif ($_POST['action'] === 'delete_meal' && $_POST['mealID']) {
        $result = $_db->insert("DELETE FROM UserMeals WHERE id = ? AND userID = ?", [$_POST['mealID'], $userID]);
        $message = $result ? "Meal deleted successfully!" : "Error deleting meal.";
    }
}

// Get data
$userMeals = $_db->select("SELECT um.*, e.name as entree_name, e.calories_per_serving as entree_calories, s1.name as side1_name, s1.calories_per_serving as side1_calories, s2.name as side2_name, s2.calories_per_serving as side2_calories, d.name as drink_name, d.calories_per_serving as drink_calories FROM UserMeals um LEFT JOIN Foods e ON um.entreeID = e.id LEFT JOIN Foods s1 ON um.side1ID = s1.id LEFT JOIN Foods s2 ON um.side2ID = s2.id LEFT JOIN Foods d ON um.drinkID = d.id WHERE um.userID = ? ORDER BY um.mealType, um.mealName", [$userID]);
$nonBeverageFoods = $_db->select("SELECT * FROM Foods WHERE category != 'beverage' ORDER BY category, name");
$beverages = $_db->select("SELECT * FROM Foods WHERE category = 'beverage' ORDER BY name");

// Get user preferences
$userPreferences = $_db->selectOne("SELECT * FROM UserPreferences WHERE userID = ?", [$userID]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meal Planner</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f7fa; min-height: 100vh; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 15px; padding: 25px; margin-bottom: 30px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); }
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { margin-bottom: 8px; font-weight: 600; color: #333; }
        .form-group input, .form-group select { padding: 12px 15px; border: 2px solid #e1e5e9; border-radius: 10px; font-size: 1rem; transition: border-color 0.3s; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #667eea; }
        .btn { padding: 12px 24px; border: none; border-radius: 10px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; width: 100%; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3); }
        .btn-secondary { background: #6c757d; color: white; padding: 10px 20px; margin-left: 10px; }
        .btn-secondary:hover { background: #5a6268; }
        .btn-danger { background: #dc3545; color: white; font-size: 0.85rem; padding: 8px 15px; }
        .btn-danger:hover { background: #c82333; }
        .meals-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 25px; }
        .meal-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 15px; padding: 25px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); transition: transform 0.3s; }
        .meal-card:hover { transform: translateY(-5px); }
        .meal-card.breakfast { border-left: 5px solid #ffc107; }
        .meal-card.lunch { border-left: 5px solid #17a2b8; }
        .meal-card.dinner { border-left: 5px solid #dc3545; }
        .meal-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
        .meal-type { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; margin-top: 8px; }
        .meal-type.breakfast { background: rgba(255, 193, 7, 0.2); color: #856404; }
        .meal-type.lunch { background: rgba(23, 162, 184, 0.2); color: #0c5460; }
        .meal-type.dinner { background: rgba(220, 53, 69, 0.2); color: #721c24; }
        .meal-item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid rgba(0, 0, 0, 0.1); }
        .meal-item:last-child { border-bottom: none; }
        .meal-item-calories { background: rgba(102, 126, 234, 0.1); padding: 4px 8px; border-radius: 12px; font-size: 0.9rem; }
        .total-calories { font-weight: bold; font-size: 1.1rem; color: #28a745; border-top: 2px solid #28a745; padding-top: 15px; margin-top: 15px; text-align: center; }
        .message { padding: 15px; border-radius: 10px; margin-bottom: 20px; font-weight: 500; }
        .message.success { background: rgba(40, 167, 69, 0.1); color: #155724; border: 1px solid rgba(40, 167, 69, 0.3); }
        .message.error { background: rgba(220, 53, 69, 0.1); color: #721c24; border: 1px solid rgba(220, 53, 69, 0.3); }
        .section-title { color: #333; font-size: 2rem; margin-bottom: 25px; text-align: center; }
        .admin-link { margin-top: 40px; text-align: center; }
        .admin-link a { background: #667eea; color: white; padding: 12px 25px; border-radius: 10px; text-decoration: none; margin: 0 10px; transition: all 0.3s; }
        .admin-link a:hover { background: #5a67d8; transform: translateY(-2px); }
        .no-meals { text-align: center; padding: 40px; color: #666; font-size: 1.2rem; background: rgba(255, 255, 255, 0.8); border-radius: 15px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1); }
        
        /* Preferences styles */
        .preferences-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .preferences-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .preference-item { background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); border-radius: 10px; padding: 15px; text-align: center; }
        .preference-item h4 { margin-bottom: 8px; font-size: 0.9rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px; }
        .preference-item p { font-size: 1.1rem; font-weight: 600; }
        .preference-icon { font-size: 1.5rem; margin-bottom: 10px; }
        .no-preferences { text-align: center; padding: 20px; background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        .no-preferences a { color: #fff; text-decoration: underline; }
        
        @media (max-width: 768px) { 
            .form-row, .meals-grid, .preferences-grid { grid-template-columns: 1fr; } 
            .container { padding: 10px; } 
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['userName']) ?>! 👋</h2>
        <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['email']) ?></p>
        <?php if ($_SESSION['height'] && $_SESSION['weight']): ?>
            <p><strong>Height:</strong> <?= $_SESSION['height'] ?> inches | <strong>Weight:</strong> <?= $_SESSION['weight'] ?> lbs</p>
        <?php endif; ?>
    </div>

    <!-- User Preferences Section -->
    <div class="card preferences-card">
        <h3>⚙️ Your Preferences</h3>
        <?php if ($userPreferences): ?>
            <div class="preferences-grid">
                <div class="preference-item">
                    <div class="preference-icon">🏃‍♂️</div>
                    <h4>Activity Level</h4>
                    <p><?= ucfirst($userPreferences['activity_level'] ?? 'Not set') ?></p>
                </div>
                <div class="preference-item">
                    <div class="preference-icon">🥗</div>
                    <h4>Dietary Style</h4>
                    <p><?= ucfirst($userPreferences['dietaryStyle'] ?? 'None') ?></p>
                </div>
                <div class="preference-item">
                    <div class="preference-icon">🎯</div>
                    <h4>Goal</h4>
                    <p><?= ucfirst($userPreferences['goal'] ?? 'Not set') ?></p>
                </div>
                <div class="preference-item">
                    <div class="preference-icon">🍽️</div>
                    <h4>Meals Per Day</h4>
                    <p><?= $userPreferences['meals_per_day'] ?? 3 ?></p>
                </div>
                <div class="preference-item">
                    <div class="preference-icon">🔥</div>
                    <h4>Daily Calorie Goal</h4>
                    <p><?= number_format($userPreferences['calorie_goal'] ?? 2000) ?></p>
                </div>
                <?php if ($userPreferences['dietary_preference']): ?>
                <div class="preference-item">
                    <div class="preference-icon">🍴</div>
                    <h4>Dietary Preference</h4>
                    <p><?= htmlspecialchars($userPreferences['dietary_preference']) ?></p>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if ($userPreferences['allergies'] || $userPreferences['dislikes']): ?>
            <div style="margin-top: 20px;">
                <?php if ($userPreferences['allergies']): ?>
                <div style="margin-bottom: 10px;">
                    <strong>🚫 Allergies:</strong> <?= htmlspecialchars($userPreferences['allergies']) ?>
                </div>
                <?php endif; ?>
                <?php if ($userPreferences['dislikes']): ?>
                <div>
                    <strong>❌ Dislikes:</strong> <?= htmlspecialchars($userPreferences['dislikes']) ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="preferences.php" class="btn btn-secondary">Update Preferences</a>
            </div>
        <?php else: ?>
            <div class="no-preferences">
                <p>🔧 No preferences set yet. Set your preferences to get personalized meal recommendations!</p>
                <a href="preferences.php">Set Your Preferences</a>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($message): ?><div class="message success"><?= $message ?></div><?php endif; ?>
    <?php if ($error): ?><div class="message error"><?= $error ?></div><?php endif; ?>
    
    <div class="card">
        <h3>🍽️ Add New Meal</h3>
        <form method="POST">
            <input type="hidden" name="action" value="add_meal">
            <div class="form-row">
                <div class="form-group">
                    <label for="mealName">Meal Name *</label>
                    <input type="text" id="mealName" name="mealName" required placeholder="e.g., Chicken & Spinach Lunch">
                </div>
                <div class="form-group">
                    <label for="mealType">Meal Type *</label>
                    <select id="mealType" name="mealType" required>
                        <option value="">Select Type</option>
                        <option value="breakfast">🌅 Breakfast</option>
                        <option value="lunch">🌞 Lunch</option>
                        <option value="dinner">🌙 Dinner</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="entreeID">Main Entree</label>
                    <select id="entreeID" name="entreeID">
                        <option value="">Select Entree</option>
                        <?php foreach ($nonBeverageFoods as $food): ?>
                            <option value="<?= $food['id'] ?>"><?= htmlspecialchars($food['name']) ?> (<?= ucfirst($food['category']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="side1ID">Side Dish 1</label>
                    <select id="side1ID" name="side1ID">
                        <option value="">Select Side</option>
                        <?php foreach ($nonBeverageFoods as $food): ?>
                            <option value="<?= $food['id'] ?>"><?= htmlspecialchars($food['name']) ?> (<?= ucfirst($food['category']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="side2ID">Side Dish 2</label>
                    <select id="side2ID" name="side2ID">
                        <option value="">Select Side</option>
                        <?php foreach ($nonBeverageFoods as $food): ?>
                            <option value="<?= $food['id'] ?>"><?= htmlspecialchars($food['name']) ?> (<?= ucfirst($food['category']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="drinkID">Beverage</label>
                    <select id="drinkID" name="drinkID">
                        <option value="">Select Beverage</option>
                        <?php foreach ($beverages as $beverage): ?>
                            <option value="<?= $beverage['id'] ?>"><?= htmlspecialchars($beverage['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add Meal to My Plan</button>
        </form>
    </div>
    
    <h2 class="section-title">Your Saved Meals</h2>
    
    <?php if ($userMeals): ?>
        <div class="meals-grid">
            <?php foreach ($userMeals as $meal): ?>
                <div class="meal-card <?= $meal['mealType'] ?>">
                    <div class="meal-header">
                        <div>
                            <h4><?= htmlspecialchars($meal['mealName']) ?></h4>
                            <span class="meal-type <?= $meal['mealType'] ?>"><?= ucfirst($meal['mealType']) ?></span>
                        </div>
                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this meal?');">
                            <input type="hidden" name="action" value="delete_meal">
                            <input type="hidden" name="mealID" value="<?= $meal['id'] ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                    <div class="meal-items">
                        <?php 
                        $totalCalories = 0;
                        $items = [
                            ['name' => $meal['entree_name'], 'calories' => $meal['entree_calories'], 'icon' => '🍖', 'label' => 'Entree'],
                            ['name' => $meal['side1_name'], 'calories' => $meal['side1_calories'], 'icon' => '🥗', 'label' => 'Side 1'],
                            ['name' => $meal['side2_name'], 'calories' => $meal['side2_calories'], 'icon' => '🥙', 'label' => 'Side 2'],
                            ['name' => $meal['drink_name'], 'calories' => $meal['drink_calories'], 'icon' => '🥤', 'label' => 'Drink']
                        ];
                        
                        foreach ($items as $item) {
                            if ($item['name']) {
                                $calories = $item['calories'] ?? 0;
                                $totalCalories += $calories;
                                echo "<div class='meal-item'><span class='meal-item-name'>{$item['icon']} {$item['label']}: " . htmlspecialchars($item['name']) . "</span><span class='meal-item-calories'>{$calories} cal</span></div>";
                            }
                        }
                        ?>
                        <div class="total-calories">Total: <?= $totalCalories ?> calories</div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-meals"><p>🍽️ No meals saved yet. Add your first meal above!</p></div>
    <?php endif; ?>
    
    <div class="admin-link">
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="<?= BASE_URL ?>admin/dashboard.php">Go to Admin Panel</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </div>
</div>
</body>
</html>