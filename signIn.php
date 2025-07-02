<?php
session_start();
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
require_once 'db.php';

$errorMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!$username || !$password) {
        $errorMessage = "Both username and password are required.";
    } else {
        // Query database for user
        $sql = "SELECT userID, userName, password, email, height, weight, role FROM Users WHERE userName = ?";
        $user = $_db->selectOne($sql, [$username]);
        
        if ($user && password_verify($password, $user['password'])) {
            // Login successful - store user info in session
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['userName'] = $user['userName'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['height'] = $user['height'];
            $_SESSION['weight'] = $user['weight'];
            $_SESSION['role'] = $user['role'];
            
            // Clear any captured output
            ob_end_clean();
            
            // Redirect to meal planner
            header('Location: mealplanner.php');
            exit();
        } else {
            $errorMessage = "Invalid username or password.";
        }
    }
}

// Clear any captured output from database errors
ob_end_clean();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Meal Planner</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #28a745;
        }
        
        .error-message {
            color: #dc3545;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        .back-link {
            margin-top: 1rem;
        }
        
        .back-link a {
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .back-link a:hover {
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sign In</h1>
        
        <?php if ($errorMessage): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="signIn.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Sign In</button>
        </form>
        
        <div class="back-link">
            <a href="index.php">← Back to Home</a>
        </div>
    </div>
</body>
</html>