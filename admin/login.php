<?php
/**
 * NCB Admin - Login Page
 * Standalone page - does NOT include auth.php for session check
 */
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// If already logged in, redirect to dashboard
if (is_logged_in()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } elseif (login_user($username, $password)) {
        redirect('index.php');
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCB Admin - Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 48px 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #e94560, #0f3460);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -1px;
        }
        .login-container h1 {
            font-size: 22px;
            color: #1a1a2e;
            margin-bottom: 4px;
        }
        .login-container p {
            font-size: 14px;
            color: #666;
            margin-bottom: 32px;
        }
        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.2s;
            outline: none;
        }
        .form-group input:focus {
            border-color: #0f3460;
        }
        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #e94560, #c23152);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-login:hover { opacity: 0.9; }
        .error-msg {
            background: #fee2e2;
            color: #dc2626;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: left;
        }
        .back-link {
            display: inline-block;
            margin-top: 24px;
            color: #0f3460;
            font-size: 13px;
            text-decoration: none;
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo">NCB</div>
        <h1>Admin Panel</h1>
        <p>Nepalese Community in Busan</p>

        <?php if ($error): ?>
            <div class="error-msg"><?php echo e($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter username" autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password">
            </div>
            <button type="submit" class="btn-login">Sign In</button>
        </form>

        <a href="../index.php" class="back-link">&larr; Back to Website</a>
    </div>
</body>
</html>