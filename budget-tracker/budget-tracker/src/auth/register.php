<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
    $stmt = $pdo->prepare("INSERT INTO users (email, password, full_name) VALUES (?, ?, ?)");
    if ($stmt->execute([$email, $password, $full_name])) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Registration failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Budget Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #e0f7fa, #b2ebf2); font-family: 'Poppins', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .register-container { max-width: 450px; width: 100%; padding: 2rem; animation: fadeInUp 0.8s ease-in-out; }
        .register-card { background: #ffffff; border-radius: 20px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); padding: 2rem; transition: transform 0.3s ease; }
        .register-card:hover { transform: translateY(-5px); }
        .register-card h2 { color: #01579b; font-size: 2rem; font-weight: 600; text-align: center; margin-bottom: 1.5rem; animation: bounceIn 1s ease-in-out; }
        .text-danger { background: #ffebee; color: #d32f2f; padding: 0.5rem 1rem; border-radius: 8px; text-align: center; font-size: 0.9rem; margin-bottom: 1rem; animation: shake 0.5s ease-in-out; }
        .mb-3 label { color: #0277bd; font-weight: 500; font-size: 1.1rem; }
        .form-control { border: 2px solid #e0e0e0; border-radius: 10px; padding: 0.75rem; font-size: 1rem; transition: all 0.3s ease; }
        .form-control:focus { border-color: #0288d1; box-shadow: 0 0 10px rgba(2, 136, 209, 0.3); outline: none; }
        .btn-primary { background: linear-gradient(45deg, #0288d1, #4fc3f7); border: none; border-radius: 10px; padding: 0.8rem 1.5rem; font-size: 1.1rem; font-weight: 500; width: 100%; transition: all 0.3s ease; }
        .btn-primary:hover { background: linear-gradient(45deg, #0277bd, #29b6f6); transform: translateY(-3px); box-shadow: 0 6px 15px rgba(2, 136, 209, 0.4); }
        .login-btn { 
            background: linear-gradient(45deg, #0288d1, #4fc3f7); 
            color: #fff; 
            border: none; 
            border-radius: 10px; 
            padding: 0.6rem 1.2rem; 
            font-size: 1rem; 
            font-weight: 500; 
            width: 100%; 
            text-align: center; 
            display: block; 
            margin-top: 1rem; 
            transition: all 0.3s ease; 
            text-decoration: none; 
        }
        .login-btn:hover { 
            background: linear-gradient(45deg, #0277bd, #29b6f6); 
            transform: translateY(-3px); 
            box-shadow: 0 6px 15px rgba(2, 136, 209, 0.4); 
            color: #fff; 
            text-decoration: none; 
        }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes bounceIn { 0% { opacity: 0; transform: scale(0.9); } 60% { opacity: 1; transform: scale(1.05); } 100% { transform: scale(1); } }
        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
        @media (max-width: 576px) { 
            .register-container { padding: 1rem; } 
            .register-card { padding: 1.5rem; } 
            .register-card h2 { font-size: 1.5rem; } 
            .form-control { font-size: 0.9rem; } 
            .btn-primary { font-size: 1rem; padding: 0.7rem 1rem; } 
            .login-btn { font-size: 0.9rem; padding: 0.5rem 1rem; } 
        }
    </style>
</head>
<body>
    <div class="container mt-5 register-container">
        <div class="register-card">
            <h2>Register</h2>
            <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Full Name</label>
                    <input type="text" name="full_name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
            <a href="login.php" class="login-btn">Already Registered?</a>
        </div>
    </div>
</body>
</html>