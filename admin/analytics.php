<?php

session_start();
define('BASE_URL', '/personalized-meal-planner/');
require_once __DIR__ .'/../database/db.php';

//check if user should have access
if(!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../signIn.php');
    exit();
}

// Get date range from filter (default to last 30 days)
$startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
$endDate = $_GET['end_date'] ?? date('Y-m-d');

// Validate dates
if (!$startDate || !$endDate || strtotime($startDate) > strtotime($endDate)) {
    $startDate = date('Y-m-d', strtotime('-30 days'));
    $endDate = date('Y-m-d');
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

//total meals (filtered by date range)
$totalMeals = $_db->selectOne("
    SELECT COUNT(*) as total 
    FROM UserMeals um
    JOIN Users u ON um.userID = u.userID
    WHERE DATE(u.created_at) BETWEEN ? AND ?
", [$startDate, $endDate])['total'];

// Most common meal type (filtered by date range)
$popularMealType = $_db->selectOne("
SELECT mealType, COUNT(*) as count 
FROM UserMeals um
JOIN Users u ON um.userID = u.userID
WHERE DATE(u.created_at) BETWEEN ? AND ?
GROUP BY mealType 
ORDER BY count DESC LIMIT 1
", [$startDate, $endDate]);

// Meal type breakdown (filtered by date range)
$mealBreakdown = $_db->select("
SELECT mealType, COUNT(*) as count
FROM UserMeals um
JOIN Users u ON um.userID = u.userID
WHERE DATE(u.created_at) BETWEEN ? AND ?
GROUP BY mealType
", [$startDate, $endDate]);

// Most used foods (filtered by date range)
$topFoods = $_db->select("
SELECT f.name, COUNT(*) as count 
FROM UserMeals um 
LEFT JOIN Foods f ON um.entreeID = f.id 
JOIN Users u ON um.userID = u.userID
WHERE DATE(u.created_at) BETWEEN ? AND ? AND f.name IS NOT NULL
GROUP BY f.name 
ORDER BY count DESC LIMIT 5
", [$startDate, $endDate]);

// Users by role
$userRoles = $_db->select("
SELECT role, COUNT(*) as count 
FROM Users 
GROUP BY role
");

// Users created in date range
$filteredUsers = $_db->selectOne("
    SELECT COUNT(*) as total 
    FROM Users 
    WHERE DATE(created_at) BETWEEN ? AND ?
", [$startDate, $endDate])['total'];
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
        .filter-form { background: #e9ecef; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        .filter-form h3 { margin-top: 0; color: #495057; }
        .filter-row { display: flex; gap: 15px; align-items: end; flex-wrap: wrap; }
        .filter-group { display: flex; flex-direction: column; min-width: 150px; }
        .filter-group label { margin-bottom: 5px; font-weight: 500; color: #495057; }
        .filter-group input { padding: 8px 12px; border: 1px solid #ced4da; border-radius: 5px; }
        .filter-btn { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .filter-btn:hover { background: #0056b3; }
        .reset-btn { padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px; }
        .reset-btn:hover { background: #545b62; }
        .filter-info { background: #d1ecf1; padding: 10px; border-radius: 5px; margin-top: 15px; color: #0c5460; }
        .navigation { margin-bottom: 20px; }
        .nav-btn { padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .nav-btn:hover { background: #218838; text-decoration: none; color: white; }
    </style>   
        
    </head>
    <body>
        <div class='dashboard'>
            <div class="navigation">
                <a href="<?php echo BASE_URL; ?>admin/dashboard.php" class="nav-btn">← Back to Admin Dashboard</a>
            </div>
            
            <h1>📊 Admin Analytics Dashboard</h1>
            
            <!-- Date Range Filter -->
            <div class="filter-form">
                <h3>🔍 Filter Meal Data by User Registration Date</h3>
                <form method="GET">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="start_date">Start Date:</label>
                            <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($startDate) ?>">
                        </div>
                        <div class="filter-group">
                            <label for="end_date">End Date:</label>
                            <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($endDate) ?>">
                        </div>
                        <div class="filter-group">
                            <button type="submit" class="filter-btn">Apply Filter</button>
                            <a href="analytics.php" class="reset-btn" style="text-decoration: none; display: inline-block; text-align: center;">Reset</a>
                        </div>
                    </div>
                </form>
                <div class="filter-info">
                    <strong>Currently showing:</strong> Meal data for users who registered between <?= htmlspecialchars($startDate) ?> and <?= htmlspecialchars($endDate) ?> (<?= htmlspecialchars($filteredUsers) ?> users)
                </div>
            </div>
            
            <div class='grid'>
                <div class='card'>
                    <h2>Total Users: <?= htmlspecialchars($totalUsers) ?></h2>
                    <p>Active Users: <?=htmlspecialchars($activeUsers)?> | Inactive Users: <?=htmlspecialchars($inactiveUsers)?> </p>
                    <p><small>Filtered Users (<?= htmlspecialchars($startDate) ?> to <?= htmlspecialchars($endDate) ?>): <?= htmlspecialchars($filteredUsers) ?></small></p>
                </div>
                
                <div class='card'>
                    <h2>Total Meals: <?= htmlspecialchars($totalMeals) ?></h2>
                    <?php if ($popularMealType): ?>
                        <p>Most Common Type: <?= htmlspecialchars(ucfirst($popularMealType['mealType'])) ?> (<?= htmlspecialchars($popularMealType['count']) ?>)</p>
                    <?php else: ?>
                        <p>No meals found for selected date range</p>
                    <?php endif; ?>
                </div>
                
                <div class='card'>
                    <h2>📈 User Registrations (Last 90 Days)</h2>
                    <canvas id="userGrowthChart"></canvas>
                </div>
                
                <div class="card">
                    <h2>🍽️ Meal Type Breakdown</h2>
                    <?php if (!empty($mealBreakdown)): ?>
                        <canvas id="mealBreakdownChart"></canvas>
                    <?php else: ?>
                        <p>No meal data available for selected date range</p>
                    <?php endif; ?>
                </div>
                
                <div class="card">
                    <h2>🔥 Top 5 Most Selected Entrees</h2>
                    <?php if (!empty($topFoods)): ?>
                        <canvas id="topFoodsChart"></canvas>
                    <?php else: ?>
                        <p>No entree data available for selected date range</p>
                    <?php endif; ?>
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

<?php if (!empty($mealBreakdown)): ?>
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
<?php endif; ?>

<?php if (!empty($topFoods)): ?>
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
<?php endif; ?>

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