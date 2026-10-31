<?php
session_start();
require_once __DIR__ . '/../includes/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
$valid = false;

$pdo = getDBConnection();
if ($pdo instanceof PDO) {
    try {
        $stmt = $pdo->prepare('SELECT password FROM admin_users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        $valid = $user && password_verify($password, (string) $user['password']);
    } catch (PDOException $e) {
        $valid = false;
    }
}

// Local fallback credentials match the database setup documentation.
if (!$valid && !($pdo instanceof PDO)) {
    $fallbackUser = getenv('PORTFOLIO_ADMIN_USER') ?: 'adem';
    $fallbackPassword = getenv('PORTFOLIO_ADMIN_PASSWORD') ?: 'admin123';
    $valid = hash_equals($fallbackUser, $username) && hash_equals($fallbackPassword, $password);
}

if ($valid) {
    session_regenerate_id(true);
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $username;
    $_SESSION['admin_csrf'] = bin2hex(random_bytes(24));
    header('Location: dashboard-main.php');
    exit;
}

header('Location: index.php?error=Invalid+credentials');
exit;
