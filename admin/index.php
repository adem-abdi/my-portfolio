<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Adem Portfolio</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/improvements.css">
    <style>
        body { 
            background: #222 url('../assets/images/mypic.png') no-repeat center center fixed; 
            background-size: cover; 
            position: relative;
        }
        .login-container {
            max-width: 400px;
            margin: 120px auto 0 auto;
            background: rgba(255,255,255,0.95);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            padding: 40px 32px 32px 32px;
            border: 1px solid rgba(173,139,115,0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        .login-container h2 {
            font-family: 'Sora', sans-serif;
            color: #AD8B73;
            font-size: 2rem;
            margin-bottom: 32px;
            font-weight: 700;
        }
        .login-form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .form-group label {
            font-weight: 600;
            color: #333;
            font-family: 'Sora', sans-serif;
        }
        .form-group input {
            padding: 14px 16px;
            border-radius: 8px;
            border: 2px solid rgba(173,139,115,0.2);
            font-size: 1rem;
            font-family: 'Lora', serif;
            background: rgba(255,255,255,0.9);
            color: #333;
            transition: border-color 0.3s;
            position: relative;
            z-index: 10;
            pointer-events: auto;
        }
        .form-group input:focus {
            outline: none;
            border-color: #AD8B73;
            background: #fff;
            z-index: 11;
        }
        .login-btn {
            background: linear-gradient(135deg, #AD8B73 0%, #9B7A63 100%);
            color: #FFFBE9;
            border: none;
            padding: 15px 0;
            border-radius: 8px;
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        .login-btn:hover {
            background: linear-gradient(135deg, #9B7A63 0%, #AD8B73 100%);
            box-shadow: 0 8px 24px rgba(173,139,115,0.2);
        }
        .error-message {
            color: #c62828;
            background: rgba(244, 67, 54, 0.1);
            border: 1px solid rgba(244, 67, 54, 0.3);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 16px;
            text-align: center;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form class="login-form" method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
    
    <script>
        // Test script to ensure input fields are working
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const usernameInput = document.getElementById('username');
            
            // Test if inputs are accessible
            console.log('Password input:', passwordInput);
            console.log('Username input:', usernameInput);
            
            // Add event listeners to test functionality
            passwordInput.addEventListener('focus', function() {
                console.log('Password field focused');
            });
            
            passwordInput.addEventListener('input', function() {
                console.log('Password input detected');
            });
            
            usernameInput.addEventListener('focus', function() {
                console.log('Username field focused');
            });
            
            usernameInput.addEventListener('input', function() {
                console.log('Username input detected');
            });
        });
    </script>
</body>
</html> 