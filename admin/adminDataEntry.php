<?php
session_start();
require_once __DIR__ . '/../database/db.php';

// Restrict access to admins only
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/signIn.php');
    exit();
}

$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $name = trim($_POST['name'] ?? '');
        $category = $_POST['category'] ?? '';
        $serving_amount = $_POST['serving_amount'] ?? '';
        $serving_unit = trim($_POST['serving_unit'] ?? '');
        $calories = $_POST['calories'] ?? '';
        $protein = $_POST['protein'] ?? '';
        $sodium = $_POST['sodium'] ?? '';
        $sugar = $_POST['sugar'] ?? '';
        $carbs = $_POST['carbs'] ?? '';
        $fat = $_POST['fat'] ?? '';
        $fiber = $_POST['fiber'] ?? '';
        
        if (!$name || !$category) {
            $error = "Name and category are required.";
        } else {
            $sql = "INSERT INTO Foods (name, category, serving_amount, serving_unit, calories_per_serving, protein_grams, sodium_mg, sugar_grams, carbs_grams, fat_grams, fiber_grams) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $result = $_db->insert($sql, [$name, $category, $serving_amount, $serving_unit, $calories, $protein, $sodium, $sugar, $carbs, $fat, $fiber]);
            
            if ($result) {
                $message = "Food item added successfully!";
            } else {
                $error = "Error adding food item.";
            }
        }
    }
    
    if ($action === 'edit') {
        $id = $_POST['id'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $category = $_POST['category'] ?? '';
        $serving_amount = $_POST['serving_amount'] ?? '';
        $serving_unit = trim($_POST['serving_unit'] ?? '');
        $calories = $_POST['calories'] ?? '';
        $protein = $_POST['protein'] ?? '';
        $sodium = $_POST['sodium'] ?? '';
        $sugar = $_POST['sugar'] ?? '';
        $carbs = $_POST['carbs'] ?? '';
        $fat = $_POST['fat'] ?? '';
        $fiber = $_POST['fiber'] ?? '';
        
        if (!$name || !$category) {
            $error = "Name and category are required.";
        } else {
            $sql = "UPDATE Foods SET name=?, category=?, serving_amount=?, serving_unit=?, calories_per_serving=?, protein_grams=?, sodium_mg=?, sugar_grams=?, carbs_grams=?, fat_grams=?, fiber_grams=? WHERE id=?";
            $result = $_db->insert($sql, [$name, $category, $serving_amount, $serving_unit, $calories, $protein, $sodium, $sugar, $carbs, $fat, $fiber, $id]);
            
            if ($result) {
                $message = "Food item updated successfully!";
            } else {
                $error = "Error updating food item.";
            }
        }
    }
    
    if ($action === 'delete') {
        $id = $_POST['id'] ?? '';
        if ($id) {
            $result = $_db->insert("DELETE FROM Foods WHERE id = ?", [$id]);
            if ($result) {
                $message = "Food item deleted successfully!";
            } else {
                $error = "Error deleting food item.";
            }
        }
    }
}

// Get all foods for display
$foods = $_db->select("SELECT * FROM Foods ORDER BY name");

// Get food for editing if edit mode
$editFood = null;
if (isset($_GET['edit'])) {
    $editId = $_GET['edit'];
    $editFood = $_db->selectOne("SELECT * FROM Foods WHERE id = ?", [$editId]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Data Entry - Foods</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .form-group {
            flex: 1;
            min-width: 200px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .foods-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .foods-table th, .foods-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .foods-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        
        .foods-table tr:hover {
            background: #f8f9fa;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .btn-small {
            padding: 5px 10px;
            font-size: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-edit {
            background: #007bff;
            color: white;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        
        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .section-title {
            margin-top: 30px;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 2px solid #28a745;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Admin Data Entry - Foods Management</h1>
    
    <?php if ($message): ?>
        <div class="message success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <!-- Add/Edit Food Form -->
    <div class="form-container">
        <h2><?php echo $editFood ? 'Edit Food' : 'Add New Food'; ?></h2>
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $editFood ? 'edit' : 'add'; ?>">
            <?php if ($editFood): ?>
                <input type="hidden" name="id" value="<?php echo $editFood['id']; ?>">
            <?php endif; ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Food Name *</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($editFood['name'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="category">Category *</label>
                    <select id="category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="protein" <?php echo ($editFood['category'] ?? '') === 'protein' ? 'selected' : ''; ?>>Protein</option>
                        <option value="grain" <?php echo ($editFood['category'] ?? '') === 'grain' ? 'selected' : ''; ?>>Grain</option>
                        <option value="vegetable" <?php echo ($editFood['category'] ?? '') === 'vegetable' ? 'selected' : ''; ?>>Vegetable</option>
                        <option value="fruit" <?php echo ($editFood['category'] ?? '') === 'fruit' ? 'selected' : ''; ?>>Fruit</option>
                        <option value="dairy" <?php echo ($editFood['category'] ?? '') === 'dairy' ? 'selected' : ''; ?>>Dairy</option>
                        <option value="beverage" <?php echo ($editFood['category'] ?? '') === 'beverage' ? 'selected' : ''; ?>>Beverage</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="serving_amount">Serving Amount</label>
                    <input type="number" step="0.01" id="serving_amount" name="serving_amount" value="<?php echo htmlspecialchars($editFood['serving_amount'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="serving_unit">Serving Unit</label>
                    <input type="text" id="serving_unit" name="serving_unit" value="<?php echo htmlspecialchars($editFood['serving_unit'] ?? ''); ?>" placeholder="e.g., oz, cup, g">
                </div>
                
                <div class="form-group">
                    <label for="calories">Calories per Serving</label>
                    <input type="number" id="calories" name="calories" value="<?php echo htmlspecialchars($editFood['calories_per_serving'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="protein">Protein (g)</label>
                    <input type="number" step="0.01" id="protein" name="protein" value="<?php echo htmlspecialchars($editFood['protein_grams'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="sodium">Sodium (mg)</label>
                    <input type="number" id="sodium" name="sodium" value="<?php echo htmlspecialchars($editFood['sodium_mg'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="sugar">Sugar (g)</label>
                    <input type="number" step="0.01" id="sugar" name="sugar" value="<?php echo htmlspecialchars($editFood['sugar_grams'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="carbs">Carbs (g)</label>
                    <input type="number" step="0.01" id="carbs" name="carbs" value="<?php echo htmlspecialchars($editFood['carbs_grams'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="fat">Fat (g)</label>
                    <input type="number" step="0.01" id="fat" name="fat" value="<?php echo htmlspecialchars($editFood['fat_grams'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="fiber">Fiber (g)</label>
                    <input type="number" step="0.01" id="fiber" name="fiber" value="<?php echo htmlspecialchars($editFood['fiber_grams'] ?? ''); ?>">
                </div>
            </div>
            
            <button type="submit"><?php echo $editFood ? 'Update Food' : 'Add Food'; ?></button>
            <?php if ($editFood): ?>
                <a href="adminDataEntry.php" style="margin-left: 10px; text-decoration: none; color: #666;">Cancel</a>
            <?php endif; ?>
        </form>
    </div>
    
    <!-- Foods List -->
    <h2 class="section-title">All Foods</h2>
    <?php if ($foods): ?>
        <table class="foods-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Serving</th>
                    <th>Calories</th>
                    <th>Protein (g)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($foods as $food): ?>
                    <tr>
                        <td><?php echo $food['id']; ?></td>
                        <td><?php echo htmlspecialchars($food['name']); ?></td>
                        <td><?php echo ucfirst($food['category']); ?></td>
                        <td><?php echo $food['serving_amount'] ? $food['serving_amount'] . ' ' . $food['serving_unit'] : '-'; ?></td>
                        <td><?php echo $food['calories_per_serving'] ?? '-'; ?></td>
                        <td><?php echo $food['protein_grams'] ?? '-'; ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="adminDataEntry.php?edit=<?php echo $food['id']; ?>" class="btn-small btn-edit">Edit</a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this food item?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $food['id']; ?>">
                                    <button type="submit" class="btn-small btn-delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No foods found. Add your first food item above!</p>
    <?php endif; ?>
    
    <!-- Back to Dashboard -->
    <div style="text-align: center; margin-top: 30px;">
        <a href="dashboard.php" style="display: inline-block; background: #28a745; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none;">
            ← Back to Admin Dashboard
        </a>
    </div>
</div>
</body>
</html>