<?php
require 'db.php'; // This should be the file where your db class is defined

// Run a simple test query to see if we're connected and the table exists
$sql = "SELECT * FROM Users";

$result = $_db->select($sql);

if ($result === null) {
    echo "❌ Connection or query failed.";
} elseif (count($result) === 0) {
    echo "✅ Connected to database! Query ran successfully, but no users found.";
} else {
    echo "✅ Connected to database! Found " . count($result) . " user(s):<br><br>";
    foreach ($result as $user) {
        echo "🔹 Username: " . htmlspecialchars($user['userName']) . "<br>";
    }
}
?>
