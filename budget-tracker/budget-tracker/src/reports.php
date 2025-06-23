<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$total_income = $pdo->query("SELECT SUM(amount) FROM transactions WHERE user_id = $user_id AND type = 'income'")->fetchColumn();
$total_expense = $pdo->query("SELECT SUM(amount) FROM transactions WHERE user_id = $user_id AND type = 'expense'")->fetchColumn();
$savings = $total_income - $total_expense;

// Spending by category
$stmt = $pdo->prepare("SELECT category, SUM(amount) as total FROM transactions WHERE user_id = ? AND type = 'expense' GROUP BY category");
$stmt->execute([$user_id]);
$spending_by_category = $stmt->fetchAll();

// Income by category
$stmt = $pdo->prepare("SELECT category, SUM(amount) as total FROM transactions WHERE user_id = ? AND type = 'income' GROUP BY category");
$stmt->execute([$user_id]);
$income_by_category = $stmt->fetchAll();

// Monthly trends
$stmt = $pdo->prepare("SELECT DATE_FORMAT(transaction_date, '%Y-%m') as period, SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income, SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense FROM transactions WHERE user_id = ? GROUP BY period ORDER BY period DESC LIMIT 6");
$stmt->execute([$user_id]);
$monthly_trends = $stmt->fetchAll();

// Weekly trends
$stmt = $pdo->prepare("SELECT DATE_FORMAT(transaction_date, '%Y-W%U') as period, SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income, SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense FROM transactions WHERE user_id = ? GROUP BY period ORDER BY period DESC LIMIT 6");
$stmt->execute([$user_id]);
$weekly_trends = $stmt->fetchAll();

// Yearly trends
$stmt = $pdo->prepare("SELECT DATE_FORMAT(transaction_date, '%Y') as period, SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income, SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense FROM transactions WHERE user_id = ? GROUP BY period ORDER BY period DESC LIMIT 6");
$stmt->execute([$user_id]);
$yearly_trends = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Budget Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #f4f7fa, #e0e0e0); font-family: 'Poppins', sans-serif; color: #333; min-height: 100vh; }
        .container { padding: 2rem; animation: fadeIn 1s ease-in-out; }
        h2 { color: #01579b; font-size: 2.2rem; font-weight: 600; margin-bottom: 2rem; animation: slideInLeft 0.8s ease-in-out; }
        h3 { color: #0277bd; font-size: 1.8rem; font-weight: 500; margin-bottom: 1.5rem; animation: fadeInUp 0.8s ease-in-out; }
        .card { background: #ffffff; border-radius: 15px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; border: 2px solid #e0e0e0; }
        .card:hover { transform: translateY(-8px); box-shadow: 0 12px 25px rgba(2, 136, 209, 0.2); border-color: #0288d1; background: linear-gradient(45deg, #e3f2fd, #bbdefb); }
        .card-body { padding: 1.5rem; text-align: center; }
        .card h5 { color: #01579b; font-size: 1.3rem; font-weight: 600; margin-bottom: 1rem; transition: color 0.3s ease; }
        .card:hover h5 { color: #0277bd; }
        .card p { color: #0288d1; font-size: 1.8rem; font-weight: 700; margin: 0; transition: color 0.3s ease; }
        .card:hover p { color: #01579b; }
        .table { margin-bottom: 0; }
        thead th { background: linear-gradient(45deg, #0288d1, #4fc3f7); color: #fff; font-weight: 500; padding: 1rem; text-align: center; }
        tbody tr { transition: background-color 0.3s ease; }
        tbody tr:hover { background-color: #e3f2fd; }
        td { color: #37474f; font-size: 1rem; padding: 1rem; text-align: center; vertical-align: middle; }
        canvas { border-radius: 10px; background: #fff; padding: 1rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); transition: transform 0.3s ease; }
        canvas:hover { transform: scale(1.02); }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 768px) { .container { padding: 1rem; } h2 { font-size: 1.8rem; } h3 { font-size: 1.5rem; } .card { margin-bottom: 1.5rem; } .card p { font-size: 1.5rem; } td { font-size: 0.9rem; padding: 0.8rem; } }
        @media (max-width: 576px) { h2 { font-size: 1.5rem; } h3 { font-size: 1.2rem; } .card p { font-size: 1.3rem; } .table { font-size: 0.85rem; } }
        .trend-selector { margin-bottom: 1.5rem; }
        select { padding: 0.5rem; border-radius: 8px; border: 1px solid #0288d1; font-size: 1rem; }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container mt-5">
        <h2>Reports & Analytics</h2>
        <div class="row mb-4">
            <div class="col-md-4"><div class="card"><div class="card-body"><h5>Total Income</h5><p>₹<?php echo number_format($total_income, 2); ?></p></div></div></div>
            <div class="col-md-4"><div class="card"><div class="card-body"><h5>Total Expenses</h5><p>₹<?php echo number_format($total_expense, 2); ?></p></div></div></div>
            <div class="col-md-4"><div class="card"><div class="card-body"><h5>Savings</h5><p>₹<?php echo number_format($savings, 2); ?></p></div></div></div>
        </div>

        <h3>Spending by Category</h3>
        <div class="card mb-4">
            <div class="card-body">
                <table class="table">
                    <thead><tr><th>Category</th><th>Total Spent</th></tr></thead>
                    <tbody><?php foreach ($spending_by_category as $cat): ?>
                        <tr><td><?php echo $cat['category']; ?></td><td>₹<?php echo number_format($cat['total'], 2); ?></td></tr>
                    <?php endforeach; ?></tbody>
                </table>
            </div>
        </div>

        <h3>Category Breakdown</h3>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <canvas id="incomePieChart" height="150"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <canvas id="expensePieChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <h3>Trends</h3>
        <div class="trend-selector">
            <select id="trendType" onchange="updateTrendChart()">
                <option value="monthly">Monthly Trends</option>
                <option value="weekly">Weekly Trends</option>
                <option value="yearly">Yearly Trends</option>
            </select>
        </div>
        <div class="card">
            <div class="card-body">
                <canvas id="trendsChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const monthlyTrends = <?php echo json_encode($monthly_trends); ?>;
        const weeklyTrends = <?php echo json_encode($weekly_trends); ?>;
        const yearlyTrends = <?php echo json_encode($yearly_trends); ?>;
        const incomeByCategory = <?php echo json_encode($income_by_category); ?>;
        const spendingByCategory = <?php echo json_encode($spending_by_category); ?>;

        let trendChart, incomePieChart, expensePieChart;

        // Income Pie Chart
        const incomePieCtx = document.getElementById('incomePieChart').getContext('2d');
        incomePieChart = new Chart(incomePieCtx, {
            type: 'pie',
            data: {
                labels: incomeByCategory.map(c => c.category),
                datasets: [{
                    data: incomeByCategory.map(c => c.total),
                    backgroundColor: ['rgba(54, 162, 235, 0.6)', 'rgba(54, 162, 235, 0.8)', 'rgba(54, 162, 235, 1)', 'rgba(54, 162, 235, 0.4)'],
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Income by Category' }
                },
                animation: { animateScale: true, animateRotate: true }
            }
        });

        // Expense Pie Chart
        const expensePieCtx = document.getElementById('expensePieChart').getContext('2d');
        expensePieChart = new Chart(expensePieCtx, {
            type: 'pie',
            data: {
                labels: spendingByCategory.map(c => c.category),
                datasets: [{
                    data: spendingByCategory.map(c => c.total),
                    backgroundColor: ['rgba(255, 99, 132, 0.6)', 'rgba(255, 99, 132, 0.8)', 'rgba(255, 99, 132, 1)', 'rgba(255, 99, 132, 0.4)'],
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Expenses by Category' }
                },
                animation: { animateScale: true, animateRotate: true }
            }
        });

        // Trends Bar Chart
        const trendCtx = document.getElementById('trendsChart').getContext('2d');
        function updateTrendChart() {
            const trendType = document.getElementById('trendType').value;
            let data;

            switch(trendType) {
                case 'monthly': data = monthlyTrends; break;
                case 'weekly': data = weeklyTrends; break;
                case 'yearly': data = yearlyTrends; break;
            }

            if (trendChart) { trendChart.destroy(); }

            trendChart = new Chart(trendCtx, {
                type: 'bar',
                data: {
                    labels: data.map(t => t.period),
                    datasets: [
                        { 
                            label: 'Income', 
                            data: data.map(t => t.income), 
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            stack: 'Stack 0'
                        },
                        { 
                            label: 'Expenses', 
                            data: data.map(t => t.expense), 
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            stack: 'Stack 0'
                        }
                    ]
                },
                options: { 
                    scales: { 
                        y: { beginAtZero: true, stacked: true },
                        x: { stacked: true }
                    },
                    plugins: {
                        legend: { display: true },
                        title: { display: true, text: `${trendType.charAt(0).toUpperCase() + trendType.slice(1)} Trends` },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ₹${context.raw.toLocaleString('en-IN', { minimumFractionDigits: 2 })}`;
                                }
                            }
                        }
                    },
                    animation: { duration: 1000, easing: 'easeInOutQuad' }
                }
            });
        }

        // Initial chart loads
        updateTrendChart();
    </script>
    <?php include '../includes/footer.php'; ?>
</body>
</html>