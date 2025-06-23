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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $date = $_POST['date'];
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, category, amount, description, transaction_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $type, $category, $amount, $description, $date]);
    header("Location: transactions.php");
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY transaction_date DESC");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - Budget Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #f4f7fa, #e0e0e0); font-family: 'Poppins', sans-serif; color: #333; min-height: 100vh; }
        .container { padding: 2rem; animation: fadeIn 1s ease-in-out; }
        h2 { color: #01579b; font-size: 2.2rem; font-weight: 600; margin-bottom: 2rem; animation: slideInLeft 0.8s ease-in-out; }
        .card { background: #ffffff; border-radius: 15px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 12px 25px rgba(2, 136, 209, 0.2); }
        .form-control, .form-select { border: 2px solid #e0e0e0; border-radius: 10px; padding: 0.75rem; font-size: 1rem; transition: all 0.3s ease; }
        .form-control:focus, .form-select:focus { border-color: #0288d1; box-shadow: 0 0 10px rgba(2, 136, 209, 0.3); outline: none; }
        .btn-primary { background: linear-gradient(45deg, #0288d1, #4fc3f7); border: none; border-radius: 10px; padding: 0.75rem; font-size: 1rem; font-weight: 500; transition: all 0.3s ease; }
        .btn-primary:hover { background: linear-gradient(45deg, #0277bd, #29b6f6); transform: translateY(-3px); box-shadow: 0 6px 15px rgba(2, 136, 209, 0.4); }
        .table { margin-bottom: 0; }
        thead th { background: linear-gradient(45deg, #0288d1, #4fc3f7); color: #fff; font-weight: 500; padding: 1rem; text-align: center; }
        tbody tr { transition: background-color 0.3s ease; }
        tbody tr:hover { background-color: #e3f2fd; }
        td { color: #37474f; font-size: 1rem; padding: 1rem; text-align: center; vertical-align: middle; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
        @media (max-width: 768px) { .container { padding: 1rem; } h2 { font-size: 1.8rem; } .card { margin-bottom: 1.5rem; } td { font-size: 0.9rem; padding: 0.8rem; } }
        @media (max-width: 576px) { h2 { font-size: 1.5rem; } .form-control, .form-select { font-size: 0.9rem; } .btn-primary { font-size: 0.9rem; padding: 0.6rem; } .table { font-size: 0.85rem; } }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container mt-5">
        <h2>Transactions</h2>
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-2"><select name="type" class="form-select" required><option value="income">Income</option><option value="expense">Expense</option></select></div>
                        <div class="col-md-2"><input type="text" name="category" class="form-control" placeholder="Category" required></div>
                        <div class="col-md-2"><input type="number" step="0.01" name="amount" class="form-control" placeholder="Amount" required></div>
                        <div class="col-md-3"><input type="text" name="description" class="form-control" placeholder="Description"></div>
                        <div class="col-md-2"><input type="date" name="date" class="form-control" required></div>
                        <div class="col-md-1"><button type="submit" class="btn btn-primary w-100">Add</button></div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead><tr><th>Date</th><th>Type</th><th>Category</th><th>Amount</th><th>Description</th></tr></thead>
                    <tbody><?php foreach ($transactions as $t): ?>
                        <tr><td><?php echo $t['transaction_date']; ?></td><td><?php echo ucfirst($t['type']); ?></td><td><?php echo $t['category']; ?></td><td>₹<?php echo number_format($t['amount'], 2); ?></td><td><?php echo $t['description']; ?></td></tr>
                    <?php endforeach; ?></tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>