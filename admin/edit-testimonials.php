<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Testimonials - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background: #222 url('../assets/images/mypic.png') no-repeat center center fixed; background-size: cover; }
        .edit-container {
            max-width: 800px;
            margin: 120px auto 0 auto;
            background: rgba(255,255,255,0.95);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            padding: 40px;
            border: 1px solid rgba(173,139,115,0.2);
            position: relative;
            z-index: 2;
        }
        .edit-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .edit-header h1 {
            font-family: 'Sora', sans-serif;
            color: #AD8B73;
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <div class="edit-header">
            <h1>Edit Testimonials</h1>
            <p>Add or remove client testimonials and reviews here.</p>
        </div>
        <!-- Testimonials editing form goes here -->
        <p style="text-align:center; color:#888;">Testimonials editing functionality coming soon.</p>
    </div>
</body>
</html>
