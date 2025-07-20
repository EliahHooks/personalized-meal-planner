<?php
session_start();
require_once __DIR__ . '/../database/db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);



$result = false;
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$email || !$password) {
        $errorMessage = "All fields are required.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO Users (userName, password, email) VALUES (?, ?, ?)";
        $result = $_db->insert($sql, [$username, $hashedPassword, $email]);

        if ($result) {
            $userID = $_db->lastInsertId();
            $user = $_db->select("SELECT * FROM Users WHERE userID = ?", [$userID]);

            if ($user) {
                $user = $user[0];
                $_SESSION['userID'] = $user['userID'];
                $_SESSION['userName'] = $user['userName'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['height'] = $user['height'];
                $_SESSION['weight'] = $user['weight'];
                $_SESSION['role'] = $user['role'];
            }

            header("Location: preferences.php");
            exit;
        } else {
            $errorMessage = "Username or email already exists.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register | Meal Planner</title>
  <link rel="stylesheet" href="../style.css" />
</head>
<body>
  <div class="container">
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
      <?php if ($result): ?>
        <h1>Welcome aboard!</h1>
        <p>Your account has been created successfully.</p>
        <form action="../index.php" method="get">
          <button type="submit">Go to Home</button>
        </form>
      <?php else: ?>
        <h1>Registration Failed</h1>
        <p><?php echo htmlspecialchars($errorMessage); ?></p>
        <form action="register.php" method="get">
          <button type="submit">Try Again</button>
        </form>
      <?php endif; ?>
    <?php else: ?>
      <h1>Create an Account</h1>
      <form action="register.php" method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" value="Register">
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
