<?php
// Old dashboard - backup
// This file has been replaced with the new sidebar dashboard
// Redirecting to new dashboard
header('Location: dashboard-main.php');
exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Adem Portfolio</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background: #222 url('../assets/images/mypic.png') no-repeat center center fixed; background-size: cover; }
        .dashboard-container {
            max-width: 1200px;
            margin: 120px auto 0 auto;
            background: rgba(255,255,255,0.95);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            padding: 40px;
            border: 1px solid rgba(173,139,115,0.2);
            position: relative;
            z-index: 2;
        }
        .dashboard-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .dashboard-header h1 {
            font-family: 'Sora', sans-serif;
            color: #AD8B73;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .dashboard-header p {
            color: #666;
            font-size: 1.1rem;
        }
        .admin-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .admin-card {
            background: rgba(255,255,255,0.9);
            border: 1px solid rgba(173,139,115,0.2);
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
        }
        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(173,139,115,0.2);
            background: rgba(255,255,255,1);
        }
        .admin-card h3 {
            font-family: 'Sora', sans-serif;
            color: #AD8B73;
            font-size: 1.3rem;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .admin-card p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        .admin-btn {
            background: linear-gradient(135deg, #AD8B73 0%, #9B7A63 100%);
            color: #FFFBE9;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .admin-btn:hover {
            background: linear-gradient(135deg, #9B7A63 0%, #AD8B73 100%);
            transform: translateY(-2px);
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(244, 67, 54, 0.9);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }
        .logout-btn:hover {
            background: rgba(244, 67, 54, 1);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <a href="logout.php" class="logout-btn">Logout</a>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Admin Dashboard</h1>
            <p>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>! Manage your portfolio content below.</p>
        </div>
        <div class="admin-grid">
            <div class="admin-card">
                <h3>Hero Section</h3>
                <p>Edit hero title, subtitle, description, stats, and profile image</p>
                <a href="edit-hero.php" class="admin-btn">Edit Hero</a>
            </div>
            <div class="admin-card">
                <h3>Projects</h3>
                <p>Add, edit, or delete portfolio projects with images and links</p>
                <a href="edit-projects.php" class="admin-btn">Manage Projects</a>
            </div>
            <div class="admin-card">
                <h3>Services</h3>
                <p>Update your service offerings and descriptions</p>
                <a href="edit-services.php" class="admin-btn">Edit Services</a>
            </div>
            <div class="admin-card">
                <h3>Skills</h3>
                <p>Manage your technical skills and proficiency levels</p>
                <a href="edit-skills.php" class="admin-btn">Edit Skills</a>
            </div>
            <div class="admin-card">
                <h3>Testimonials</h3>
                <p>Add or remove client testimonials and reviews</p>
                <a href="edit-testimonials.php" class="admin-btn">Manage Testimonials</a>
            </div>
            <div class="admin-card">
                <h3>Contact Info</h3>
                <p>Update contact details and social media links</p>
                <a href="edit-contact.php" class="admin-btn">Edit Contact</a>
            </div>
        </div>
    </div>
</body>
</html> 