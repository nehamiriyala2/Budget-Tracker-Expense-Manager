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
$balance = $total_income - $total_expense;
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Budget Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #f4f7fa, #e0e0e0); font-family: 'Poppins', sans-serif; color: #333; min-height: 100vh; }
        .dashboard-container { padding: 2rem; animation: fadeIn 1s ease-in-out; }
        h2 { color: #01579b; font-size: 2.2rem; font-weight: 600; margin-bottom: 2rem; animation: slideInLeft 0.8s ease-in-out; }
        h3 { color: #0277bd; font-size: 1.8rem; font-weight: 500; margin-top: 2rem; margin-bottom: 1.5rem; animation: fadeInUp 0.8s ease-in-out; }
        .overview-cards .card { background: #ffffff; border-radius: 15px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; border: 2px solid #e0e0e0; }
        .overview-cards .card:hover { transform: translateY(-8px); box-shadow: 0 12px 25px rgba(2, 136, 209, 0.2); border-color: #0288d1; background: linear-gradient(45deg, #e3f2fd, #bbdefb); }
        .overview-cards .card-body { padding: 1.5rem; text-align: center; }
        .overview-cards h5 { color: #01579b; font-size: 1.3rem; font-weight: 600; margin-bottom: 1rem; transition: color 0.3s ease; }
        .overview-cards .card:hover h5 { color: #0277bd; }
        .overview-cards p { color: #0288d1; font-size: 1.8rem; font-weight: 700; margin: 0; transition: color 0.3s ease; }
        .overview-cards .card:hover p { color: #01579b; }
        .transactions-table { background: #ffffff; border-radius: 15px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); overflow: hidden; }
        .transactions-table .table { margin-bottom: 0; }
        .transactions-table thead th { background: linear-gradient(45deg, #0288d1, #4fc3f7); color: #fff; font-weight: 500; padding: 1rem; text-align: center; }
        .transactions-table tbody tr { transition: background-color 0.3s ease; }
        .transactions-table tbody tr:hover { background-color: #e3f2fd; }
        .transactions-table td { color: #37474f; font-size: 1rem; padding: 1rem; text-align: center; vertical-align: middle; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 768px) { .dashboard-container { padding: 1rem; } h2 { font-size: 1.8rem; } h3 { font-size: 1.5rem; } .overview-cards .card { margin-bottom: 1.5rem; } .overview-cards p { font-size: 1.5rem; } .transactions-table td { font-size: 0.9rem; padding: 0.8rem; } }
        @media (max-width: 576px) { h2 { font-size: 1.5rem; } h3 { font-size: 1.2rem; } .overview-cards p { font-size: 1.3rem; } .transactions-table { font-size: 0.85rem; } }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container mt-5 dashboard-container">
        <h2>Financial Overview</h2>
        <div class="row overview-cards">
            <div class="col-md-4"><div class="card"><div class="card-body"><h5>Total Balance</h5><p>₹<?php echo number_format($balance, 2); ?></p></div></div></div>
            <div class="col-md-4"><div class="card"><div class="card-body"><h5>Income</h5><p>₹<?php echo number_format($balance, 2); ?></p></div></div></div>
            <div class="col-md-4"><div class="card"><div class="card-body"><h5>Expenses</h5><p>₹<?php echo number_format($total_expense, 2); ?></p>
            </div></div></div>
        </div>
        <h3 class="mt-4">Recent Transactions</h3>
        <div class="transactions-table">
            <table class="table">
                <thead><tr><th>Date</th><th>Type</th><th>Category</th><th>Amount</th></tr></thead>
                <tbody><?php foreach ($transactions as $t): ?>
                    <tr><td><?php echo $t['transaction_date']; ?></td><td><?php echo ucfirst($t['type']); ?></td><td><?php echo $t['category']; ?></td><td>₹<?php echo number_format($t['amount'], 2); ?></td></tr>
                <?php endforeach; ?></tbody>
            </table>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>