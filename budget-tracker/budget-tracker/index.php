<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Tracker - Manage Your Finances</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/budget-tracker/public/css/style.css">
    <style>
        .hero-section { background: linear-gradient(120deg, #e0f7fa, #b2ebf2); padding: 4rem 2rem; border-radius: 15px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); animation: slideIn 1s ease-out; }
        .hero-section .display-4 { font-size: 3.8rem; color: #01579b; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1); animation: bounceIn 1.2s ease-in-out; }
        .hero-section .lead { font-size: 1.4rem; color: #0277bd; animation: fadeInUp 1.5s ease-in-out 0.5s; opacity: 0; animation-fill-mode: forwards; }
        .hero-section p { color: #0288d1; font-size: 1.1rem; }
        .feature-cards .card { background: #ffffff; border: 2px solid #e0e0e0; border-radius: 15px; transition: all 0.4s ease; }
        .feature-cards .card:hover { background: linear-gradient(45deg, #e3f2fd, #bbdefb); border-color: #0288d1; transform: translateY(-10px) scale(1.03); box-shadow: 0 10px 25px rgba(2, 136, 209, 0.2); }
        .feature-cards .card-title { color: #01579b; font-size: 1.3rem; transition: color 0.3s ease; }
        .feature-cards .card:hover .card-title { color: #0277bd; }
        .feature-cards .card-text { color: #455a64; font-size: 1rem; }
        .why-choose { background: #fafafa; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); }
        .why-choose h2 { color: #0277bd; font-size: 2rem; animation: fadeInDown 1s ease-in-out; }
        .why-choose .list-group-item { color: #37474f; font-size: 1.1rem; padding: 0.8rem 0; transition: all 0.3s ease; position: relative; }
        .why-choose .list-group-item:hover { color: #0288d1; transform: translateX(10px); }
        .why-choose .list-group-item::before { content: "✔"; color: #0277bd; margin-right: 10px; font-size: 1.2rem; transition: color 0.3s ease; }
        .why-choose .list-group-item:hover::before { color: #01579b; }
        .cta-section { background: linear-gradient(135deg, #0288d1, #4fc3f7); padding: 3rem 2rem; border-radius: 15px; color: #fff; box-shadow: 0 8px 20px rgba(2, 136, 209, 0.3); }
        .cta-section h3 { font-size: 2.2rem; text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2); animation: fadeInUp 1s ease-in-out; }
        .cta-section p { font-size: 1.2rem; margin-bottom: 2rem; }
        .cta-section .btn-primary { background: linear-gradient(45deg, #fff, #e0e0e0); color: #0288d1; font-weight: 600; padding: 0.9rem 2.5rem; border: none; transition: all 0.3s ease; }
        .cta-section .btn-primary:hover { background: linear-gradient(45deg, #e0e0e0, #fff); color: #01579b; transform: scale(1.1); box-shadow: 0 6px 18px rgba(255, 255, 255, 0.5); }
        .cta-section .btn-outline-primary { border-color: #fff; color: #fff; padding: 0.9rem 2.5rem; transition: all 0.3s ease; }
        .cta-section .btn-outline-primary:hover { background-color: #fff; color: #0288d1; transform: scale(1.1); }
        .graph-section { background: #fff; padding: 2rem; border-radius: 15px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); }
        .graph-section h2 { color: #0277bd; font-size: 2rem; text-align: center; margin-bottom: 2rem; }
        .graph-container { max-width: 600px; margin: 0 auto 2rem auto; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes bounceIn { 0% { opacity: 0; transform: scale(0.9); } 60% { opacity: 1; transform: scale(1.05); } 100% { transform: scale(1); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 768px) { 
            .hero-section .display-4 { font-size: 2.5rem; } 
            .hero-section .lead { font-size: 1.2rem; } 
            .feature-cards .card { margin-bottom: 20px; } 
            .cta-section .btn { display: block; width: 100%; margin-bottom: 10px; } 
            .graph-container { max-width: 100%; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/budget-tracker/index.php">Budget Tracker & Expense Manager</a>
            <div class="d-flex">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/budget-tracker/src/dashboard.php" class="btn btn-primary me-2">Dashboard</a>
                    <a href="/budget-tracker/src/auth/logout.php" class="btn btn-outline-danger">Logout</a>
                <?php else: ?>
                    <a href="/budget-tracker/src/auth/login.php" class="btn btn-outline-primary me-2">Login</a>
                    <a href="/budget-tracker/src/auth/register.php" class="btn btn-primary">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container my-5 text-center hero-section">
        <h1 class="display-4">Welcome to Budget Tracker & Expense Manager</h1>
        <p class="lead">Take control of your finances with our intuitive and powerful expense management tool.</p>
        <p>Track your income, manage expenses, set budgets, and gain insights with detailed reports—all in one place.</p>
    </div>

    <div class="container mb-5 feature-cards">
        <div class="row">
            <div class="col-md-4 col-lg-3"><div class="card mb-4"><div class="card-body"><h5 class="card-title">Financial Overview</h5><p class="card-text">Get a quick snapshot of your balance, income, and expenses with an interactive dashboard.</p></div></div></div>
            <div class="col-md-4 col-lg-3"><div class="card mb-4"><div class="card-body"><h5 class="card-title">Budget Management</h5><p class="card-text">Set category-based budgets and receive alerts when you're nearing your limits.</p></div></div></div>
            <div class="col-md-4 col-lg-3"><div class="card mb-4"><div class="card-body"><h5 class="card-title">Reports & Analytics</h5><p class="card-text">Analyze your spending patterns and track financial trends over time.</p></div></div></div>
            <div class="col-md-4 col-lg-3"><div class="card mb-4"><div class="card-body"><h5 class="card-title">Goal Setting</h5><p class="card-text">Create savings goals and monitor your progress with visual trackers.</p></div></div></div>
            <div class="col-md-4 col-lg-3"><div class="card mb-4"><div class="card-body"><h5 class="card-title">Bill Reminders</h5><p class="card-text">Never miss a payment with automated reminders for bills and due dates.</p></div></div></div>
            <div class="col-md-4 col-lg-3"><div class="card mb-4"><div class="card-body"><h5 class="card-title">Multi-Device Sync</h5><p class="card-text">Access your data seamlessly across all your devices in real-time.</p></div></div></div>
            <div class="col-md-4 col-lg-3"><div class="card mb-4"><div class="card-body"><h5 class="card-title">Expense Categorization</h5><p class="card-text">Automatically categorize expenses with smart AI-powered suggestions.</p></div></div></div>
        </div>
    </div>

    <!-- New Graph Section -->
    <div class="container mb-5 graph-section">
        <h2>Visualize Your Budget</h2>
        <div class="row">
            <div class="col-md-6 graph-container">
                <canvas id="monthlyBudgetChart"></canvas>
            </div>
            <div class="col-md-6 graph-container">
                <canvas id="categorySpendingChart"></canvas>
            </div>
        </div>
    </div>

    <div class="container mb-5 why-choose">
        <h2 class="text-center mb-4">Why Choose Budget Tracker?</h2>
        <div class="row">
            <div class="col-md-6"><ul class="list-group list-group-flush"><li class="list-group-item">User-friendly and responsive design</li><li class="list-group-item">Secure data storage and authentication</li><li class="list-group-item">Customizable categories and multi-currency support</li></ul></div>
            <div class="col-md-6"><ul class="list-group list-group-flush"><li class="list-group-item">Detailed transaction history</li><li class="list-group-item">Exportable financial data</li><li class="list-group-item">Built with modern technologies</li></ul></div>
        </div>
    </div>

    <div class="container text-center mb-5 cta-section">
        <h3>Get Started Today!</h3>
        <p>Join thousands of users managing their finances effortlessly.</p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="/budget-tracker/src/auth/register.php" class="btn btn-primary btn-lg me-2">Register Now</a>
            <a href="/budget-tracker/src/auth/login.php" class="btn btn-outline-primary btn-lg">Login</a>
        <?php else: ?>
            <a href="/budget-tracker/src/dashboard.php" class="btn btn-primary btn-lg">Go to Dashboard</a>
        <?php endif; ?>
    </div>

    
    <?php include 'includes/footer.php'; ?>

    <!-- Include Chart.js -->
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Monthly Budget Line Graph
        const monthlyBudgetCtx = document.getElementById('monthlyBudgetChart').getContext('2d');
        const monthlyBudgetChart = new Chart(monthlyBudgetCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Budget Spent ($)',
                    data: [1200, 1500, 1300, 1700, 1400, 1600],
                    borderColor: '#0277bd',
                    backgroundColor: 'rgba(2, 119, 189, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Amount ($)' }
                    },
                    x: {
                        title: { display: true, text: 'Month' }
                    }
                },
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Monthly Budget Overview' }
                }
            }
        });

        // Category Spending Line Graph
        const categorySpendingCtx = document.getElementById('categorySpendingChart').getContext('2d');
        const categorySpendingChart = new Chart(categorySpendingCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Food ($)',
                    data: [300, 350, 320, 400, 330, 360],
                    borderColor: '#0288d1',
                    backgroundColor: 'rgba(2, 136, 209, 0.2)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Entertainment ($)',
                    data: [150, 200, 180, 220, 170, 190],
                    borderColor: '#01579b',
                    backgroundColor: 'rgba(1, 87, 155, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Amount ($)' }
                    },
                    x: {
                        title: { display: true, text: 'Month' }
                    }
                },
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Spending by Category' }
                }
            }
        });
    </script>
</body>
</html>