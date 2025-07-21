<?php 

session_start();
require_once __DIR__ . '/../database/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_URL', '/personalized-meal-planner/');

if (!isset($_SESSION['userID'])) {
    header('Location: signIn.php');
    exit();
}

$userID = $_SESSION['userID'];
$message = $error = '';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_meal') {
        $mealName = trim($_POST['mealName'] ?? '');
        $mealType = $_POST['mealType'] ?? '';
        $entreeID = $_POST['entreeID'] ?: null;
        $side1ID = $_POST['side1ID'] ?: null;
        $side2ID = $_POST['side2ID'] ?: null;
        $drinkID = $_POST['drinkID'] ?: null;

        if (!$mealName || !$mealType) {
            $error = "Meal name and type are required.";
        } else {
            $result = $_db->insert(
                "INSERT INTO UserMeals (userID, mealName, mealType, entreeID, side1ID, side2ID, drinkID) VALUES (?, ?, ?, ?, ?, ?, ?)",
                [$userID, $mealName, $mealType, $entreeID, $side1ID, $side2ID, $drinkID]
            );
            $message = $result ? "Meal added successfully!" : "Error adding meal.";
        }
    } elseif ($action === 'delete_meal' && !empty($_POST['mealID'])) {
        $result = $_db->insert("DELETE FROM UserMeals WHERE id = ? AND userID = ?", [$_POST['mealID'], $userID]);
        $message = $result ? "Meal deleted successfully!" : "Error deleting meal.";
    } elseif ($action === 'assign_meal') {
        $mealID = $_POST['mealID'] ?? '';
        $day = $_POST['day'] ?? '';
        $mealType = $_POST['mealType'] ?? '';

        if (!$mealID || !$day || !$mealType) {
            $error = "Please select a meal, day, and meal type.";
        } else {
            $mealCheck = $_db->selectOne("SELECT id FROM UserMeals WHERE id = ? AND userID = ?", [$mealID, $userID]);
            if ($mealCheck) {
                $_db->insert("DELETE FROM WeeklyPlan WHERE userID = ? AND day_of_week = ? AND meal_type = ?", [$userID, $day, $mealType]);
                $result = $_db->insert("INSERT INTO WeeklyPlan (userID, meal_id, day_of_week, meal_type) VALUES (?, ?, ?, ?)", [$userID, $mealID, $day, $mealType]);
                $message = $result ? "Meal assigned successfully!" : "Error assigning meal.";
            } else {
                $error = "Invalid meal selection.";
            }
        }
    } elseif ($action === 'remove_meal') {
        $planID = $_POST['planID'] ?? '';
        if ($planID) {
            $result = $_db->insert("DELETE FROM WeeklyPlan WHERE id = ? AND userID = ?", [$planID, $userID]);
            $message = $result ? "Meal removed from plan!" : "Error removing meal.";
        }
    } elseif ($action === 'generate_weekly_plan') {
    generateWeeklyMealPlan($_db, $userID);
    $message = "Brand‑new weekly plan generated!";
}
}


function getUserPreferences($_db, $userID) {

    $sql = "SELECT * FROM UserPreferences WHERE userID = ?";
    $preferences = $_db->selectOne($sql, [$userID]);
    if (!$preferences) return null;

    return [
        'activity_level' => $preferences['activity_level'],
        'dietaryStyle'   => $preferences['dietaryStyle'],
        'goal'           => $preferences['goal'],
        'allergies'      => array_filter(array_map('trim', explode(",", strtolower($preferences['allergies'] ?? '')))),
        'dislikes'       => array_filter(array_map('trim', explode(",", strtolower($preferences['dislikes'] ?? '')))),
        'calorie_goal'   => (int)$preferences['calorie_goal']
    ];
}

function getAvailableFoods($_db, $allergies, $dislikes, $dietaryStyle = null) {
    $sql = "SELECT * FROM Foods";
    $foods = $_db->select($sql);
    if (!$foods) {
        die("Database query failed.");
    }

    $availableFoods = [];

    foreach ($foods as $food) {
        $foodAllergens = array_filter(array_map('trim', explode(",", strtolower($food['allergens'] ?? ''))));
        $foodIngredients = array_filter(array_map('trim', explode(",", strtolower($food['ingredients'] ?? ''))));
        $foodDietaryTags = array_filter(array_map('trim', explode(",", strtolower($food['dietary_tags'] ?? ''))));
        $foodName = strtolower(trim($food['name'] ?? ''));
        $foodCategory = strtolower(trim($food['category'] ?? ''));

        // Check allergies in ingredients, name, and category
        $hasAllergy = false;
        
        // Check allergens field
        if (count(array_intersect($foodAllergens, $allergies)) > 0) {
            $hasAllergy = true;
        }
        
        // Check ingredients for allergies
        if (!$hasAllergy && count(array_intersect($foodIngredients, $allergies)) > 0) {
            $hasAllergy = true;
        }
        
        // Check food name for allergies
        if (!$hasAllergy) {
            foreach ($allergies as $allergy) {
                if (strpos($foodName, $allergy) !== false) {
                    $hasAllergy = true;
                    break;
                }
            }
        }
        
        // Check food category for allergies
        if (!$hasAllergy) {
            foreach ($allergies as $allergy) {
                if (strpos($foodCategory, $allergy) !== false) {
                    $hasAllergy = true;
                    break;
                }
            }
        }

        // Check dislikes in ingredients, name, and category
        $hasDislike = false;
        
        // Check ingredients for dislikes
        if (count(array_intersect($foodIngredients, $dislikes)) > 0) {
            $hasDislike = true;
        }
        
        // Check food name for dislikes
        if (!$hasDislike) {
            foreach ($dislikes as $dislike) {
                if (strpos($foodName, $dislike) !== false) {
                    $hasDislike = true;
                    break;
                }
            }
        }
        
        // Check food category for dislikes
        if (!$hasDislike) {
            foreach ($dislikes as $dislike) {
                if (strpos($foodCategory, $dislike) !== false) {
                    $hasDislike = true;
                    break;
                }
            }
        }

        // Skip if has allergy or dislike
        if ($hasAllergy || $hasDislike) {
            continue;
        }

        // Apply dietary style filtering - FIXED VERSION
        $dietaryMatch = true;
        if ($dietaryStyle && !empty($dietaryStyle)) {
            switch (strtolower(trim($dietaryStyle))) {
                case 'vegan':
                    $dietaryMatch = in_array('vegan', $foodDietaryTags);
                    break;
                case 'vegetarian':
                    $dietaryMatch = in_array('vegan', $foodDietaryTags) || in_array('vegetarian', $foodDietaryTags);
                    break;
                case 'keto':
                    // For keto, we need foods that are keto-friendly OR have low carbs
                    $dietaryMatch = in_array('keto', $foodDietaryTags) || 
                                   in_array('low-carb', $foodDietaryTags) ||
                                   in_array('ketogenic', $foodDietaryTags);
                    
                    // Additional keto logic: exclude high-carb categories and foods
                    $highCarbCategories = ['grain', 'fruit']; // Typically high in carbs
                    $lowCarbCategories = ['protein', 'dairy', 'vegetable', 'beverage'];
                    
                    // If no keto tags but is a typically low-carb category, allow it
                    if (!$dietaryMatch && in_array(strtolower($food['category']), $lowCarbCategories)) {
                        // Additional check for specific high-carb foods even in low-carb categories
                        $highCarbFoods = ['potato', 'sweet potato', 'corn', 'rice', 'pasta', 'bread', 'banana', 'apple', 'orange'];
                        $isHighCarbFood = false;
                        
                        foreach ($highCarbFoods as $highCarbFood) {
                            if (strpos($foodName, $highCarbFood) !== false) {
                                $isHighCarbFood = true;
                                break;
                            }
                        }
                        
                        if (!$isHighCarbFood) {
                            $dietaryMatch = true;
                        }
                    }
                    
                    // Exclude obvious high-carb categories unless specifically tagged as keto
                    if (in_array(strtolower($food['category']), $highCarbCategories) && 
                        !in_array('keto', $foodDietaryTags) && 
                        !in_array('low-carb', $foodDietaryTags)) {
                        $dietaryMatch = false;
                    }
                    break;
                case 'pescatarian':
                    $dietaryMatch = in_array('vegan', $foodDietaryTags) || 
                                   in_array('vegetarian', $foodDietaryTags) || 
                                   in_array('pescatarian', $foodDietaryTags);
                    break;
                case 'paleo':
                    $dietaryMatch = in_array('paleo', $foodDietaryTags);
                    break;
                case 'mediterranean':
                    $dietaryMatch = in_array('mediterranean', $foodDietaryTags);
                    break;
                default:
                    $dietaryMatch = true; // No dietary restriction or unrecognized diet
                    break;
            }
        }

        if ($dietaryMatch) {
            $availableFoods[] = $food;
        }
    }

    // Debug: Log available foods for keto
    if ($dietaryStyle && strtolower(trim($dietaryStyle)) === 'keto') {
        error_log("Available keto foods: " . count($availableFoods));
        foreach ($availableFoods as $food) {
            error_log("Keto food: " . $food['name'] . " (Category: " . $food['category'] . ", Tags: " . $food['dietary_tags'] . ")");
        }
    }

    return $availableFoods;
}

function groupFoodsByMealSlot(array $foods) {
    $slots = [
        'entree' => [],
        'side1'  => [],
        'side2'  => [],
        'drink'  => []
    ];

    foreach ($foods as $food) {
        $cat = strtolower($food['category']);
        if ($cat === 'protein') {
            $slots['entree'][] = $food;
        } elseif (in_array($cat, ['grain', 'vegetable', 'fruit', 'dairy'])) {
            $slots['side1'][] = $food;
            $slots['side2'][] = $food;
        } elseif ($cat === 'beverage') {
            $slots['drink'][] = $food;
        }
    }

    return $slots;
}

function assembleMeal(array $slots, float $targetCalories, float $tolerance = 0.15, int $maxAttempts = 150) {
    // Check if we have foods in all required slots
    if (
        empty($slots['entree']) || 
        empty($slots['side1']) || 
        empty($slots['side2']) || 
        empty($slots['drink'])
    ) {
        // Log which slots are empty for debugging
        $emptySlots = [];
        if (empty($slots['entree'])) $emptySlots[] = 'entree';
        if (empty($slots['side1'])) $emptySlots[] = 'side1';
        if (empty($slots['side2'])) $emptySlots[] = 'side2';
        if (empty($slots['drink'])) $emptySlots[] = 'drink';
        
        error_log("Cannot assemble meal - empty slots: " . implode(', ', $emptySlots));
        error_log("Slot counts - Entree: " . count($slots['entree']) . 
                  ", Side1: " . count($slots['side1']) . 
                  ", Side2: " . count($slots['side2']) . 
                  ", Drink: " . count($slots['drink']));
        return null;
    }

    $minCal = $targetCalories * (1 - $tolerance);
    $maxCal = $targetCalories * (1 + $tolerance);

    for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
        $entree = $slots['entree'][array_rand($slots['entree'])];
        $side1 = $slots['side1'][array_rand($slots['side1'])];
        $side2 = $slots['side2'][array_rand($slots['side2'])];
        $drink = $slots['drink'][array_rand($slots['drink'])];

        // Ensure side dishes are different
        if ($side1['id'] === $side2['id']) continue;

        $totalCalories = ($entree['calories_per_serving'] ?? 0) +
                         ($side1['calories_per_serving'] ?? 0) +
                         ($side2['calories_per_serving'] ?? 0) +
                         ($drink['calories_per_serving'] ?? 0);

        if ($totalCalories >= $minCal && $totalCalories <= $maxCal) {
            return [
                'entree' => $entree,
                'side1' => $side1,
                'side2' => $side2,
                'drink' => $drink,
                'total_calories' => $totalCalories
            ];
        }
    }
    
    error_log("Could not assemble meal within calorie range $minCal-$maxCal after $maxAttempts attempts");
    return null;
}

function generateDailyMealPlan($_db, $userID) {
    $preferences = getUserPreferences($_db, $userID);
    if (!$preferences) {
        error_log("No preferences found for user $userID");
        return null;
    }

    error_log("Generating meal plan for user $userID with dietary style: " . ($preferences['dietaryStyle'] ?? 'none'));

    $availableFoods = getAvailableFoods($_db, $preferences['allergies'], $preferences['dislikes'], $preferences['dietaryStyle']);
    if (empty($availableFoods)) {
        error_log("No available foods found for user $userID with dietary style: " . ($preferences['dietaryStyle'] ?? 'none'));
        return null;
    }

    error_log("Found " . count($availableFoods) . " available foods for user $userID");

    $slots = groupFoodsByMealSlot($availableFoods);
    $caloriesPerMeal = $preferences['calorie_goal'] / 3;

    $mealPlan = [];
    $mealNames = ['breakfast', 'lunch', 'dinner'];

    foreach ($mealNames as $name) {
        $meal = assembleMeal($slots, $caloriesPerMeal);
        if ($meal === null) {
            error_log("Could not assemble $name meal for user $userID");
            $mealPlan[$name] = null;
        } else {
            error_log("Successfully assembled $name meal for user $userID");
            $mealPlan[$name] = $meal;
        }
    }

    return $mealPlan;
}

function generateWeeklyMealPlan($db, int $userID): void
{
    // Delete previous weekly plans for the user
    $db->insert("DELETE FROM WeeklyPlan WHERE userID = ?", [$userID]);

    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $mealTypes = ['breakfast', 'lunch', 'dinner'];

    foreach ($days as $day) {
        $dailyMeals = generateDailyMealPlan($db, $userID);
        if (!$dailyMeals) {
            error_log("No meals generated for $day (user $userID)");
            continue;
        }

        foreach ($mealTypes as $mealType) {
            if (!isset($dailyMeals[$mealType]) || $dailyMeals[$mealType] === null) {
                error_log("No $mealType meal generated for $day (user $userID)");
                continue;
            }

            $parts = $dailyMeals[$mealType];

            $mealName = $parts['entree']['name'] . ' with ' .
                $parts['side1']['name'] . ' & ' . $parts['side2']['name'];

            // Insert meal into UserMeals
            $insertMealSQL = "INSERT INTO UserMeals (userID, mealName, mealType, entreeID, side1ID, side2ID, drinkID) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)";
            $result = $db->insert(
                $insertMealSQL,
                [
                    $userID,
                    $mealName,
                    $mealType,
                    $parts['entree']['id'],
                    $parts['side1']['id'],
                    $parts['side2']['id'],
                    $parts['drink']['id']
                ]
            );

            if (!$result) {
                error_log("Failed to insert meal for $day $mealType (user $userID)");
                continue;
            }

            $newMealID = $db->lastInsertId();

            // Insert into WeeklyPlan linking user, meal, day, and meal type
            $insertWeeklyPlanSQL = "INSERT INTO WeeklyPlan (userID, meal_id, day_of_week, meal_type)
                                    VALUES (?, ?, ?, ?)";
            $result2 = $db->insert($insertWeeklyPlanSQL, [$userID, $newMealID, $day, $mealType]);

            if (!$result2) {
                error_log("Failed to assign meal $newMealID to weekly plan for $day $mealType (user $userID)");
            } else {
                error_log("Successfully created and assigned $mealType meal for $day (user $userID)");
            }
        }
    }
}

// Get data
$userMeals = $_db->select("SELECT um.*, e.name as entree_name, e.calories_per_serving as entree_calories, s1.name as side1_name, s1.calories_per_serving as side1_calories, s2.name as side2_name, s2.calories_per_serving as side2_calories, d.name as drink_name, d.calories_per_serving as drink_calories FROM UserMeals um LEFT JOIN Foods e ON um.entreeID = e.id LEFT JOIN Foods s1 ON um.side1ID = s1.id LEFT JOIN Foods s2 ON um.side2ID = s2.id LEFT JOIN Foods d ON um.drinkID = d.id WHERE um.userID = ? ORDER BY um.mealType, um.mealName", [$userID]);
$nonBeverageFoods = $_db->select("SELECT * FROM Foods WHERE category != 'beverage' ORDER BY category, name");
$beverages = $_db->select("SELECT * FROM Foods WHERE category = 'beverage' ORDER BY name");

// Get user preferences
$userPreferences = getUserPreferences($_db, $userID);

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
    
    <h1 class="text-center mb-4">🍽️ Your Weekly Meal Plan</h1>

    <?php if ($message): ?>
        <div class="alert alert-success text-center"><?= $message ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>


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
                
            </div>
            
<?php if (!empty($userPreferences['allergies']) || !empty($userPreferences['dislikes'])): ?>
    <div style="margin-top: 20px;">
        <?php if (!empty($userPreferences['allergies'])): ?>
            <div style="margin-bottom: 10px;">
                <strong>🚫 Allergies:</strong>
                <?= htmlspecialchars(implode(", ", $userPreferences['allergies'])) ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($userPreferences['dislikes'])): ?>
            <div>
                <strong>❌ Dislikes:</strong>
                <?= htmlspecialchars(implode(", ", $userPreferences['dislikes'])) ?>
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
    
</div>
</body>
</html>


<?php
// Weekly meal plan assignment logic
// Handle weekly meal plan actions

// Get weekly plan
$weeklyPlan = $_db->select("
    SELECT wp.*, um.mealName, um.mealType as original_meal_type,
           e.name as entree_name, e.calories_per_serving as entree_calories,
           s1.name as side1_name, s1.calories_per_serving as side1_calories,
           s2.name as side2_name, s2.calories_per_serving as side2_calories,
           d.name as drink_name, d.calories_per_serving as drink_calories
    FROM WeeklyPlan wp
    JOIN UserMeals um ON wp.meal_id = um.id
    LEFT JOIN Foods e ON um.entreeID = e.id
    LEFT JOIN Foods s1 ON um.side1ID = s1.id
    LEFT JOIN Foods s2 ON um.side2ID = s2.id
    LEFT JOIN Foods d ON um.drinkID = d.id
    WHERE wp.userID = ?
    ORDER BY wp.day_of_week, wp.meal_type
", [$userID]);

$plan = [];
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$mealTypes = ['breakfast', 'lunch', 'dinner'];
foreach ($days as $day) {
    $plan[$day] = [];
    foreach ($mealTypes as $type) {
        $plan[$day][$type] = null;
    }
}
foreach ($weeklyPlan as $item) {
    $plan[$item['day_of_week']][$item['meal_type']] = $item;
}
?>

<form method="POST" action="mealPlanner.php" style="text-align: center; margin: 30px 0;">
    <input type="hidden" name="action" value="generate_weekly_plan">
    <button type="submit" class="btn btn-success">⚡ Generate Weekly Meal Plan</button>
</form>


<!-- Weekly Calendar UI -->
<div style="margin-top: 50px;">
    <h2 style="color: #333; margin-bottom: 20px;">📅 Weekly Meal Plan</h2>

    <?php if ($message): ?><div class="message success"><?= $message ?></div><?php endif; ?>
    <?php if ($error): ?><div class="message error"><?= $error ?></div><?php endif; ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <?php foreach ($days as $day): ?>
            <div style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 10px; padding: 15px;">
                <h4 style="text-align: center; margin-bottom: 10px;"><?= $day ?></h4>
                <?php foreach ($mealTypes as $mealType): ?>
                    <div style="margin-bottom: 15px;">
                        <strong><?= ucfirst($mealType) ?>:</strong><br>
                        <?php if ($plan[$day][$mealType]): ?>
                            <?php 
                            $meal = $plan[$day][$mealType];
                            $calories = ($meal['entree_calories'] ?? 0) + ($meal['side1_calories'] ?? 0) + 
                                       ($meal['side2_calories'] ?? 0) + ($meal['drink_calories'] ?? 0);
                            ?>
                            <div style="padding: 10px; background: #e2e6ea; border-radius: 5px; margin-top: 5px;">
                                <?= htmlspecialchars($meal['mealName']) ?> (<?= $calories ?> cal)
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="remove_meal">
                                    <input type="hidden" name="planID" value="<?= $meal['id'] ?>">
                                    <button type="submit" style="float:right; background:#dc3545; color:#fff; border:none; padding: 2px 8px; border-radius:5px;" onclick="return confirm('Remove this meal?')">×</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <em style="color:#888;">No meal assigned</em>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <hr style="margin: 40px 0;">

    <h3>🍽️ Assign Meal to Day</h3>
    <?php if ($userMeals): ?>
        <form method="POST" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; align-items: end;">
            <input type="hidden" name="action" value="assign_meal">
            <div>
                <label>Meal</label>
                <select name="mealID" required style="width:100%; padding:10px;">
                    <option value="">Select a meal...</option>
                    <?php foreach ($userMeals as $meal): ?>
                        <option value="<?= $meal['id'] ?>"><?= htmlspecialchars($meal['mealName']) ?> (<?= ucfirst($meal['mealType']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Day</label>
                <select name="day" required style="width:100%; padding:10px;">
                    <option value="">Select a day...</option>
                    <?php foreach ($days as $day): ?>
                        <option value="<?= $day ?>"><?= $day ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Meal Time</label>
                <select name="mealType" required style="width:100%; padding:10px;">
                    <option value="">Select time...</option>
                    <option value="breakfast">Breakfast</option>
                    <option value="lunch">Lunch</option>
                    <option value="dinner">Dinner</option>
                </select>
            </div>
            <div>
                <button type="submit" style="padding:10px 20px; background:#28a745; color:white; border:none; border-radius:5px;">Assign</button>
            </div>
        </form>
    <?php else: ?>
        <p>No meals found. <a href="mealPlanner.php">Create some meals</a> first.</p>
    <?php endif; ?>
</div>

 <div class="admin-link">
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="<?= BASE_URL ?>admin/dashboard.php">Go to Admin Panel</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
 </div>