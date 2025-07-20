    <?php
    session_start();
    require_once __DIR__ . '/../database/db.php';
    

    if (!isset($_SESSION['userID'])) {
        header("Location: login.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_SESSION['userID'];
        $activity = $_POST['activity_level'] ?? null;
        $style = $_POST['dietaryStyle'] ?? 'none';
        $goal = $_POST['goal'] ?? null;
        $allergies = $_POST['allergies'] ?? '';
        $dislikes = $_POST['dislikes'] ?? '';
        $calories = $_POST['calorie_goal'] ?? 2000;

        $sql = "INSERT INTO UserPreferences (
                    userID, activity_level, dietaryStyle, goal,
                   allergies, dislikes,
                   calorie_goal
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    activity_level = VALUES(activity_level),
                    dietaryStyle = VALUES(dietaryStyle),
                    goal = VALUES(goal),
                    allergies = VALUES(allergies),
                    dislikes = VALUES(dislikes),
                    calorie_goal = VALUES(calorie_goal)";

        $success = $_db->insert($sql, [
            $userID, $activity, $style, $goal,
            $allergies, $dislikes,
            $calories
        ]);

        if ($success) {
            header("Location: mealPlanner.php");
            exit;
        } else {
            $error = "Something went wrong while saving preferences.";
        }
    }
    ?>


    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <title>User Preferences</title>
      <link rel="stylesheet" href="../style.css" />
    </head>
    <body>
      <div class="container">
        <h1>Tell Us About Your Preferences</h1>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
          <?php if ($success): ?>
            <p>Preferences saved successfully!</p>
            <a href="dashboard.php"><button>Go to Dashboard</button></a>
          <?php else: ?>
            <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
          <?php endif; ?>
        <?php endif; ?>

        <form action="preferences.php" method="POST">
      <h2>Activity Level</h2>
      <label><input type="radio" name="activity_level" value="low" required> Low</label><br>
      <label><input type="radio" name="activity_level" value="medium"> Medium</label><br>
      <label><input type="radio" name="activity_level" value="high"> High</label><br><br>

      <h2>Dietary Style</h2>
      <select name="dietaryStyle" required>
        <option value="none">None</option>
        <option value="vegetarian">Vegetarian</option>
        <option value="vegan">Vegan</option>
        <option value="keto">Keto</option>
        <option value="pescatarian">Pescatarian</option>
      </select><br><br>

      <h2>What is your goal?</h2>
      <select name="goal" required>
        <option value="lose">Lose Weight</option>
        <option value="gain">Gain Weight</option>
        <option value="maintain">Maintain</option>
      </select><br><br>

      <h2>Allergies</h2>
      <textarea name="allergies" rows="3" cols="40" placeholder="e.g., peanuts, shellfish"></textarea><br><br>

      <h2>Disliked Foods</h2>
      <textarea name="dislikes" rows="3" cols="40" placeholder="e.g., broccoli, tofu"></textarea><br><br>

      <h2>Daily Calorie Goal</h2>
      <input type="number" name="calorie_goal" min="1000" max="5000" step="50" value="2000"><br><br>

      <input type="submit" value="Save Preferences">
    </form>

      </div>
    </body>
    </html>
