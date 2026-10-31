<?php
session_start();
require_once '../includes/database.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle image upload
    if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/images/';
        $fileName = 'profile.jpg';
        $uploadPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $uploadPath)) {
            updateSiteContent('hero_image', 'assets/images/profile.jpg');
            $message = 'Image uploaded successfully!';
        } else {
            $message = 'Error uploading image.';
        }
    }
    
    // Handle text content updates
    $fields = [
        'hero_title', 'hero_subtitle', 'hero_description',
        'cta_primary_text', 'cta_secondary_text',
        'stat_number_1', 'stat_label_1',
        'stat_number_2', 'stat_label_2',
        'stat_number_3', 'stat_label_3'
    ];
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            updateSiteContent($field, $_POST[$field]);
        }
    }
    
    if (empty($message)) {
        $message = 'Content updated successfully!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hero - Admin</title>
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
        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            background: rgba(255,255,255,0.8);
            border-radius: 8px;
            border: 1px solid rgba(173,139,115,0.1);
        }
        .form-section h3 {
            font-family: 'Sora', sans-serif;
            color: #AD8B73;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            font-family: 'Sora', sans-serif;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid rgba(173,139,115,0.3);
            border-radius: 6px;
            font-family: 'Lora', serif;
            box-sizing: border-box;
        }
        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .submit-btn {
            background: linear-gradient(135deg, #AD8B73 0%, #9B7A63 100%);
            color: #FFFBE9;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
        }
        .submit-btn:hover {
            background: linear-gradient(135deg, #9B7A63 0%, #AD8B73 100%);
            transform: translateY(-2px);
        }
        .back-btn {
            background: rgba(173,139,115,0.2);
            color: #AD8B73;
            border: 1px solid rgba(173,139,115,0.3);
            padding: 10px 20px;
            border-radius: 6px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        .back-btn:hover {
            background: rgba(173,139,115,0.3);
        }
        .message {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
        }
        .message.success {
            background: rgba(76, 175, 80, 0.1);
            color: #2e7d32;
            border: 1px solid rgba(76, 175, 80, 0.3);
        }
        .message.error {
            background: rgba(244, 67, 54, 0.1);
            color: #c62828;
            border: 1px solid rgba(244, 67, 54, 0.3);
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        
        <div class="edit-header">
            <h1>Edit Hero Section</h1>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-section">
                <h3>Profile Image</h3>
                <div class="form-group">
                    <label for="hero_image">Upload New Profile Image:</label>
                    <input type="file" id="hero_image" name="hero_image" accept="image/*">
                </div>
            </div>
            
            <div class="form-section">
                <h3>Hero Content</h3>
                <div class="form-group">
                    <label for="hero_title">Main Title:</label>
                    <input type="text" id="hero_title" name="hero_title" value="<?php echo htmlspecialchars(getSiteContent('hero_title', 'Adem Abdifith')); ?>" required>
                </div>
                <div class="form-group">
                    <label for="hero_subtitle">Subtitle:</label>
                    <input type="text" id="hero_subtitle" name="hero_subtitle" value="<?php echo htmlspecialchars(getSiteContent('hero_subtitle', 'Full Stack Developer')); ?>" required>
                </div>
                <div class="form-group">
                    <label for="hero_description">Description:</label>
                    <textarea id="hero_description" name="hero_description" required><?php echo htmlspecialchars(getSiteContent('hero_description', 'Passionate developer crafting digital experiences with modern technologies.')); ?></textarea>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Call-to-Action Buttons</h3>
                <div class="form-group">
                    <label for="cta_primary_text">Primary Button Text:</label>
                    <input type="text" id="cta_primary_text" name="cta_primary_text" value="<?php echo htmlspecialchars(getSiteContent('cta_primary_text', 'Get In Touch')); ?>" required>
                </div>
                <div class="form-group">
                    <label for="cta_secondary_text">Secondary Button Text:</label>
                    <input type="text" id="cta_secondary_text" name="cta_secondary_text" value="<?php echo htmlspecialchars(getSiteContent('cta_secondary_text', 'View My Work')); ?>" required>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Statistics</h3>
                <div class="stats-grid">
                    <div class="form-group">
                        <label for="stat_number_1">Stat 1 Number:</label>
                        <input type="text" id="stat_number_1" name="stat_number_1" value="<?php echo htmlspecialchars(getSiteContent('stat_number_1', '50+')); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="stat_label_1">Stat 1 Label:</label>
                        <input type="text" id="stat_label_1" name="stat_label_1" value="<?php echo htmlspecialchars(getSiteContent('stat_label_1', 'Projects Completed')); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="stat_number_2">Stat 2 Number:</label>
                        <input type="text" id="stat_number_2" name="stat_number_2" value="<?php echo htmlspecialchars(getSiteContent('stat_number_2', '3+')); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="stat_label_2">Stat 2 Label:</label>
                        <input type="text" id="stat_label_2" name="stat_label_2" value="<?php echo htmlspecialchars(getSiteContent('stat_label_2', 'Years Experience')); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="stat_number_3">Stat 3 Number:</label>
                        <input type="text" id="stat_number_3" name="stat_number_3" value="<?php echo htmlspecialchars(getSiteContent('stat_number_3', '100%')); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="stat_label_3">Stat 3 Label:</label>
                        <input type="text" id="stat_label_3" name="stat_label_3" value="<?php echo htmlspecialchars(getSiteContent('stat_label_3', 'Client Satisfaction')); ?>" required>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="submit-btn">Update Hero Section</button>
        </form>
    </div>
</body>
</html> 