<?php
session_start();
require_once __DIR__ . '/../database/db.php';

// Restrict access to admins only
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') 
{
    header('Location: ../pages/signIn.php');
    exit();
}

$message = $error = '';

// Handle all form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $action = $_POST['action'] ?? '';
    
    if ($action === 'delete') 
    {
        $mealID = $_POST['mealID'] ?? '';
        if ($mealID) {
            $result = $_db->insert("DELETE FROM UserMeals WHERE id = ?", [$mealID]);
            $message = $result ? "Meal deleted successfully!" : "Error deleting meal.";
        }
    } else {
        // Handle add/edit (consolidated)
        $mealID = $_POST['mealID'] ?? '';
        $userID = $_POST['userID'] ?? '';
        $mealName = trim($_POST['mealName'] ?? '');
        $mealType = $_POST['mealType'] ?? '';
        $entreeID = $_POST['entreeID'] ?: null;
        $side1ID = $_POST['side1ID'] ?: null;
        $side2ID = $_POST['side2ID'] ?: null;
        $drinkID = $_POST['drinkID'] ?: null;
        
        if (!$userID || !$mealName || !$mealType) 
        {
            $error = "User, meal name, and meal type are required.";
        } 
        else 
        {
            if ($action === 'edit' && $mealID) 
            {
                $sql = "UPDATE UserMeals SET userID=?, mealName=?, mealType=?, entreeID=?, side1ID=?, side2ID=?, drinkID=? WHERE id=?";
                $result = $_db->insert($sql, [$userID, $mealName, $mealType, $entreeID, $side1ID, $side2ID, $drinkID, $mealID]);
                $message = $result ? "Meal updated successfully!" : "Error updating meal.";
            } 
            else 
            {
                $sql = "INSERT INTO UserMeals (userID, mealName, mealType, entreeID, side1ID, side2ID, drinkID) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $result = $_db->insert($sql, [$userID, $mealName, $mealType, $entreeID, $side1ID, $side2ID, $drinkID]);
                $message = $result ? "Meal added successfully!" : "Error adding meal.";
            }
        }
    }
}

// Get data
$userMeals = $_db->select("
    SELECT um.*, u.userName, u.email,
           e.name as entree_name, e.calories_per_serving as entree_calories,
           s1.name as side1_name, s1.calories_per_serving as side1_calories,
           s2.name as side2_name, s2.calories_per_serving as side2_calories,
           d.name as drink_name, d.calories_per_serving as drink_calories
    FROM UserMeals um
    LEFT JOIN Users u ON um.userID = u.userID
    LEFT JOIN Foods e ON um.entreeID = e.id
    LEFT JOIN Foods s1 ON um.side1ID = s1.id
    LEFT JOIN Foods s2 ON um.side2ID = s2.id
    LEFT JOIN Foods d ON um.drinkID = d.id
    ORDER BY u.userName, um.mealType, um.mealName
");

$users = $_db->select("SELECT userID, userName FROM Users ORDER BY userName");
$nonBeverageFoods = $_db->select("SELECT * FROM Foods WHERE category != 'beverage' ORDER BY category, name");
$beverages = $_db->select("SELECT * FROM Foods WHERE category = 'beverage' ORDER BY name");

// Get meal for editing
$editMeal = isset($_GET['edit']) ? $_db->selectOne("SELECT * FROM UserMeals WHERE id = ?", [$_GET['edit']]) : null;

// Helper function to render form fields
function renderFormField($label, $name, $type = 'text', $options = [], $value = '', $required = false) 
{
    $req = $required ? 'required' : '';
    echo "<div class='form-group'><label for='$name'>$label" . ($required ? ' *' : '') . "</label>";
    
    if ($type === 'select') 
    {
        echo "<select id='$name' name='$name' $req>";
        foreach ($options as $optValue => $optText) 
        {
            $selected = ($value == $optValue) ? 'selected' : '';
            echo "<option value='$optValue' $selected>$optText</option>";
        }
        echo "</select>";
    } 
    else 
    {
        echo "<input type='$type' id='$name' name='$name' value='" . htmlspecialchars($value) . "' $req>";
    }
    echo "</div>";
}

// Prepare form options
$userOptions = ['' => 'Select User'];
foreach ($users as $user) 
{
    $userOptions[$user['userID']] = $user['userName'];
}

$mealTypeOptions = ['' => 'Select Type', 'breakfast' => 'Breakfast', 'lunch' => 'Lunch', 'dinner' => 'Dinner'];

$entreeOptions = ['' => 'Select Entree'];
foreach ($nonBeverageFoods as $food) 
{
    $entreeOptions[$food['id']] = $food['name'] . ' (' . ucfirst($food['category']) . ')';
}

$beverageOptions = ['' => 'Select Beverage'];
foreach ($beverages as $beverage) 
{
    $beverageOptions[$beverage['id']] = $beverage['name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Moderate Content - User Meals</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .form-container { background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { margin-bottom: 8px; font-weight: 600; color: #333; }
        .form-group input, .form-group select { padding: 12px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 14px; transition: border-color 0.3s; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #667eea; }
        .meals-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-top: 20px; }
        .meals-table th, .meals-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .meals-table th { background: #f8f9fa; font-weight: 600; color: #333; }
        .meals-table tr:hover { background: #f8f9fa; }
        .meal-type-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .meal-type-badge.breakfast { background: rgba(255, 193, 7, 0.2); color: #856404; }
        .meal-type-badge.lunch { background: rgba(23, 162, 184, 0.2); color: #0c5460; }
        .meal-type-badge.dinner { background: rgba(220, 53, 69, 0.2); color: #721c24; }
        .action-buttons { display: flex; gap: 8px; align-items: center; }
        .btn-small { padding: 6px 12px; font-size: 12px; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-block; transition: all 0.3s; }
        .btn-edit { background: #007bff; color: white; }
        .btn-edit:hover { background: #0056b3; }
        .btn-delete { background: #dc3545; color: white; }
        .btn-delete:hover { background: #c82333; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3); }
        .message { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; }
        .message.success { background: rgba(40, 167, 69, 0.1); color: #155724; border: 1px solid rgba(40, 167, 69, 0.3); }
        .message.error { background: rgba(220, 53, 69, 0.1); color: #721c24; border: 1px solid rgba(220, 53, 69, 0.3); }
        .section-title { margin-top: 40px; margin-bottom: 25px; color: #333; border-bottom: 3px solid #28a745; padding-bottom: 10px; font-size: 1.8rem; }
        .user-info { font-size: 12px; color: #666; margin-top: 4px; }
        .meal-details { font-size: 12px; color: #666; margin-top: 5px; }
        .calories-info { font-weight: 600; color: #28a745; }
        .back-link { display: inline-block; background: #28a745; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; margin-top: 30px; transition: all 0.3s; }
        .back-link:hover { background: #218838; transform: translateY(-2px); }
        @media (max-width: 768px) { .form-row { grid-template-columns: 1fr; } .meals-table { font-size: 14px; } .meals-table th, .meals-table td { padding: 10px; } }
    </style>
</head>
<body>
<div class="container">
    <h1>Moderate Content - User Meals Management</h1>
    
    <?php if ($message): ?>
        <div class="message success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <!-- Add/Edit Meal Form -->
    <div class="form-container">
        <h2><?php echo $editMeal ? 'Edit User Meal' : 'Add New Meal for User'; ?></h2>
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $editMeal ? 'edit' : 'add'; ?>">
            <?php if ($editMeal): ?>
                <input type="hidden" name="mealID" value="<?php echo $editMeal['id']; ?>">
            <?php endif; ?>
            
            <div class="form-row">
                <?php renderFormField('User', 'userID', 'select', $userOptions, $editMeal['userID'] ?? '', true); ?>
                <?php renderFormField('Meal Name', 'mealName', 'text', [], $editMeal['mealName'] ?? '', true); ?>
            </div>
            
            <div class="form-row">
                <?php renderFormField('Meal Type', 'mealType', 'select', $mealTypeOptions, $editMeal['mealType'] ?? '', true); ?>
                <?php renderFormField('Main Entree', 'entreeID', 'select', $entreeOptions, $editMeal['entreeID'] ?? ''); ?>
            </div>
            
            <div class="form-row">
                <?php renderFormField('Side Dish 1', 'side1ID', 'select', $entreeOptions, $editMeal['side1ID'] ?? ''); ?>
                <?php renderFormField('Side Dish 2', 'side2ID', 'select', $entreeOptions, $editMeal['side2ID'] ?? ''); ?>
            </div>
            
            <div class="form-row">
                <?php renderFormField('Beverage', 'drinkID', 'select', $beverageOptions, $editMeal['drinkID'] ?? ''); ?>
            </div>
            
            <button type="submit" class="btn-primary"><?php echo $editMeal ? 'Update Meal' : 'Add Meal'; ?></button>
            <?php if ($editMeal): ?>
                <a href="moderateContent.php" style="margin-left: 15px; text-decoration: none; color: #666;">Cancel</a>
            <?php endif; ?>
        </form>
    </div>
    
    <!-- User Meals List -->
    <h2 class="section-title">All User Meals</h2>
    <?php if ($userMeals): ?>
        <table class="meals-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Meal Name</th>
                    <th>Type</th>
                    <th>Meal Components</th>
                    <th>Total Calories</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userMeals as $meal): ?>
                    <?php
                    $totalCalories = 0;
                    $components = [];
                    $items = [
                        ['name' => $meal['entree_name'], 'calories' => $meal['entree_calories'], 'icon' => '🍖'],
                        ['name' => $meal['side1_name'], 'calories' => $meal['side1_calories'], 'icon' => '🥗'],
                        ['name' => $meal['side2_name'], 'calories' => $meal['side2_calories'], 'icon' => '🥙'],
                        ['name' => $meal['drink_name'], 'calories' => $meal['drink_calories'], 'icon' => '🥤']
                    ];
                    
                    foreach ($items as $item) {
                        if ($item['name']) {
                            $components[] = $item['icon'] . ' ' . $item['name'];
                            $totalCalories += $item['calories'] ?? 0;
                        }
                    }
                    ?>
                    <tr>
                        <td><?php echo $meal['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($meal['userName']); ?></strong>
                            <div class="user-info"><?php echo htmlspecialchars($meal['email']); ?></div>
                        </td>
                        <td><?php echo htmlspecialchars($meal['mealName']); ?></td>
                        <td>
                            <span class="meal-type-badge <?php echo $meal['mealType']; ?>">
                                <?php echo ucfirst($meal['mealType']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($components): ?>
                                <div class="meal-details">
                                    <?php echo implode('<br>', $components); ?>
                                </div>
                            <?php else: ?>
                                <span style="color: #999; font-style: italic;">No components</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="calories-info"><?php echo $totalCalories; ?> cal</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="moderateContent.php?edit=<?php echo $meal['id']; ?>" class="btn-small btn-edit">Edit</a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this meal?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="mealID" value="<?php echo $meal['id']; ?>">
                                    <button type="submit" class="btn-small btn-delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 10px;">
            <p style="color: #666; font-size: 1.2rem;">No user meals found. Add the first meal above!</p>
        </div>
    <?php endif; ?>
    
    <!-- Back to Dashboard -->
    <div style="text-align: center; margin-top: 40px;">
        <a href="dashboard.php" class="back-link">← Back to Admin Dashboard</a>
    </div>
</div>
</body>
</html>