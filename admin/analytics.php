<?php

session_start();
require_once __DIR__ .'/../database/db.php';

//check if user should have access
if(!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../signIn.php');
    exit();
}

//total users
$totalUsers = $_db->selectOne("
    SELECT COUNT(*) as total 
    FROM Users
")['total'];

//active vs inactive defined by having at least one meal
$activeUsers = $_db->selectOne("
    SELECT COUNT(DISTINCT userID) as active
    FROM userMeals
")['active'];

$inactiveUsers = $totalUsers - $activeUsers;

//user registration overtime as a function of 1 day intervals
$userGrowth = $_db->select("
    SELECT DATE(created_at) as date, COUNT(*) as count 
    FROM Users 
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 90 DAY) 
    GROUP BY DATE(created_at) 
    ORDER BY date
");

//total meals
$totalMeals = $_db->selectOne("
    SELECT COUNT(*) as total 
    FROM UserMeals
")['total'];

// Most common meal type
$popularMealType = $_db->selectOne("
SELECT mealType, COUNT(*) as count 
FROM UserMeals 
GROUP BY mealType 
ORDER BY count DESC LIMIT 1
");

// Meal type breakdown
$mealBreakdown = $_db->select("
SELECT mealType, COUNT(*) as count
FROM UserMeals 
GROUP BY mealType
");

// Most used foods
$topFoods = $_db->select("
SELECT f.name, COUNT(*) as count 
FROM UserMeals um LEFT JOIN Foods f ON um.entreeID = f.id 
GROUP BY f.name 
ORDER BY count DESC LIMIT 5
");

// Users by role
$userRoles = $_db->select("
SELECT role, COUNT(*) as count 
FROM Users 
GROUP BY role
");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>Admin Analytics Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; padding: 20px; }
        .dashboard { max-width: 1200px; margin: auto; }
        .card { background: #fff; padding: 20px; margin-bottom: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        h2 { margin-bottom: 10px; color: #333; }
        canvas { max-width: 100%; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
    </style>   
        
    </head>
    <body>
        <div class='dashboard'>
            <h1>📊 Admin Analytics Dashboard</h1>
            
            <div class='grid'>
                <div class='card'>
                    <h2>Total Users: <?= htmlspecialchars($totalUsers) ?></h2>
                    <p>Active Users: <?=htmlspecialchars($activeUsers)?> | Inactive Users: <?=htmlspecialchars($inactiveUsers)?> </p>
                </div>
                
                <div class='card'>
                    <h2>Total Meals: <?= htmlspecialchars($totalMeals) ?></h2>
                    <p>Most Common Type: <?= htmlspecialchars(ucfirst($popularMealType['mealType'])) ?> (<?= htmlspecialchars($popularMealType['count']) ?>)</p>
                </div>
                
                <div class='card'>
                    <h2>📈 User Registrations (Last 30 Days)</h2>
                    <canvas id="userGrowthChart"></canvas>
                </div>
                
                <div class="card">
                    <h2>🍽️ Meal Type Breakdown</h2>
                    <canvas id="mealBreakdownChart"></canvas>
                </div>
                
                <div class="card">
                    <h2>🔥 Top 5 Most Selected Entrees</h2>
                    <canvas id="topFoodsChart"></canvas>
                </div>
                
                <div class="card">
                    <h2>👥 Users by Role</h2>
                    <canvas id="roleChart"></canvas>
                </div>
            </div>
        </div>
        
<script>
const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
new Chart(userGrowthCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($userGrowth, 'date')) ?>,
        datasets: [{
            label: 'New Registrations',
            data: <?= json_encode(array_column($userGrowth, 'count')) ?>,
            borderColor: '#36a2eb',
            backgroundColor: 'rgba(54,162,235,0.2)',
            fill: true,
        }]
    },
});

const mealBreakdownCtx = document.getElementById('mealBreakdownChart').getContext('2d');
new Chart(mealBreakdownCtx, {
    type: 'pie',
    data: {
        labels: <?= json_encode(array_column($mealBreakdown, 'mealType')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($mealBreakdown, 'count')) ?>,
            backgroundColor: ['#ffc107', '#17a2b8', '#dc3545']
        }]
    }
});

const topFoodsCtx = document.getElementById('topFoodsChart').getContext('2d');
new Chart(topFoodsCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($topFoods, 'name')) ?>,
        datasets: [{
            label: 'Times Selected',
            data: <?= json_encode(array_column($topFoods, 'count')) ?>,
            backgroundColor: '#28a745'
        }]
    }
});

const roleChartCtx = document.getElementById('roleChart').getContext('2d');
new Chart(roleChartCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_column($userRoles, 'role')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($userRoles, 'count')) ?>,
            backgroundColor: ['#007bff', '#6c757d']
        }]
    }
});
</script>
    </body>
</html>