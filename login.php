<?php
require 'config/db.php';
// Note: We do NOT include the standard header here to have full control over the design
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if (isset($user['status']) && $user['status'] == 'suspended') {
            $error = "Your account has been suspended. Please contact Admin.";
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: dashboard.php");
            exit;
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UniFest</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* --- SPECIAL LOGIN PAGE STYLES --- */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #800000 0%, #2c0000 100%); /* Maroon Gradient */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .login-card {
            background: white;
            padding: 40px 50px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-card h2 {
            color: #800000;
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            color: #333;
            margin-bottom: 5px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box; /* Important for padding */
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: #800000;
            outline: none;
            box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.1);
        }

        .btn {
            background: #800000;
            color: white;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
        }

        .btn:hover {
            background: #5c0000;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(128, 0, 0, 0.3);
        }

        .links {
            margin-top: 20px;
            font-size: 0.85rem;
            color: #666;
        }

        .links a {
            color: #800000;
            text-decoration: none;
            font-weight: 600;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .error-msg {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px;
            border-radius: 6px;
            font-size: 0.9rem;
            margin-bottom: 20px;
            border: 1px solid #f87171;
        }

        /* Back Button */
        .back-home {
            position: absolute;
            top: 20px;
            left: 20px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.9rem;
        }
        .back-home:hover { color: white; }
    </style>
</head>
<body>

    <a href="index.php" class="back-home">&larr; Back to Home</a>

    <div class="login-card">
        <h2>UniFest Login</h2>
        <p class="subtitle">Welcome back! Please enter your details.</p>

        <?php if($error): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="user@gmail.com">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <div class="links">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>

</body>
</html>