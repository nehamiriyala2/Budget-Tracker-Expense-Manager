<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .navbar { background: linear-gradient(135deg, #ffffff, #e0f7fa); box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); padding: 1rem 0; animation: slideInDown 0.8s ease-in-out; }
        .navbar-brand { font-size: 1.6rem; font-weight: 700; color: #01579b; transition: color 0.3s ease, transform 0.3s ease; }
        .navbar-brand:hover { color: #0288d1; transform: scale(1.05); }
        .navbar-nav .nav-link { color: #0277bd; font-size: 1.1rem; font-weight: 500; padding: 0.5rem 1rem; transition: all 0.3s ease; position: relative; }
        .navbar-nav .nav-link:hover { color: #01579b; }
        .navbar-nav .nav-link::after { content: ''; position: absolute; width: 0; height: 2px; background: #0288d1; bottom: 0; left: 50%; transform: translateX(-50%); transition: width 0.3s ease; }
        .navbar-nav .nav-link:hover::after { width: 50%; }
        .btn-outline-danger { border-color: #d32f2f; color: #d32f2f; font-weight: 500; padding: 0.5rem 1.5rem; border-radius: 8px; transition: all 0.3s ease; }
        .btn-outline-danger:hover { background: linear-gradient(45deg, #d32f2f, #ef5350); color: #fff; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3); }
        .navbar-toggler { border: none; padding: 0.5rem; transition: transform 0.3s ease; }
        .navbar-toggler:hover { transform: rotate(90deg); }
        .navbar-toggler-icon { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='rgba(2, 119, 189, 0.75)' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E"); }
        @keyframes slideInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 991px) { .navbar-collapse { background: #ffffff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); margin-top: 1rem; padding: 1rem; } .navbar-nav .nav-link { text-align: center; padding: 0.75rem 0; } .navbar-nav .nav-link::after { display: none; } .btn-outline-danger { display: block; width: 100%; margin-top: 1rem; } }
        @media (max-width: 576px) { .navbar-brand { font-size: 1.3rem; } .navbar-nav .nav-link { font-size: 1rem; } .btn-outline-danger { padding: 0.4rem 1rem; } }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/budget-tracker">Budget Tracker & Expense Manager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="/budget-tracker/src/dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="/budget-tracker/src/transactions.php">Transactions</a></li>
                        <li class="nav-item"><a class="nav-link" href="/budget-tracker/src/budgets.php">Budgets</a></li>
                        <li class="nav-item"><a class="nav-link" href="/budget-tracker/src/reports.php">Reports</a></li>
                    <?php endif; ?>
                </ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/budget-tracker/src/auth/logout.php" class="btn btn-outline-danger">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>