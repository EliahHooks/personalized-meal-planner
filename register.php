<?php
// Capture any output from database errors
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
require 'db.php';

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// force user to enter into all fields
if (!$username || !$email || !$password) 
{
    $result = false;
    $errorMessage = "All fields are required.";
} 

// Stores the information from the user to the database
else 
{
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO Users (userName, password, email) VALUES (?, ?, ?)";
    $result = $_db->insert($sql, [$username, $hashedPassword, $email]);
    
//    Error message for non-unique field
    if (!$result) 
    {
        $errorMessage = "Username or email already exists. Please try different credentials.";
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
    <title>Registration Result</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php if ($result): ?>
            <h1>Welcome aboard!</h1>
            <p>Your account has been created successfully.</p>
            <form action="index.php" method="get">
                <button type="submit">Continue to Home</button>
            </form>
        <?php else: ?>
            <h1>Registration Failed</h1>
            <p><?php echo isset($errorMessage) ? htmlspecialchars($errorMessage) : 'Something went wrong. Please try again.'; ?></p>
            <form action="register.html" method="get">
                <button type="submit">Try Again</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>