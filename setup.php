<?php
// Setup script for Portfolio Website
// Run this file in your browser to test the installation

echo "<h1>Portfolio Website Setup</h1>";
echo "<p>This script will test your installation and provide setup instructions.</p>";

// Test 1: Check if we can connect to database
echo "<h2>1. Database Connection Test</h2>";

try {
    require_once 'includes/database.php';
    $pdo = getDBConnection();
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test if tables exist
    $tables = ['admin_users', 'site_content', 'projects', 'services', 'skills', 'testimonials', 'contact_info', 'social_links', 'contact_messages'];
    $missing_tables = [];
    
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        if (!$stmt->fetch()) {
            $missing_tables[] = $table;
        }
    }
    
    if (empty($missing_tables)) {
        echo "<p style='color: green;'>✅ All database tables exist!</p>";
    } else {
        echo "<p style='color: red;'>❌ Missing tables: " . implode(', ', $missing_tables) . "</p>";
        echo "<p>Please import the database.sql file in phpMyAdmin.</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration in includes/database.php</p>";
}

// Test 2: Check file structure
echo "<h2>2. File Structure Test</h2>";

$required_files = [
    'index.php',
    'admin/index.php',
    'admin/dashboard.php',
    'admin/edit-hero.php',
    'admin/edit-projects.php',
    'includes/database.php',
    'assets/css/style.css',
    'assets/js/script.js',
    'sections/hero.php',
    'sections/services.php',
    'sections/projects.php',
    'sections/skills.php',
    'sections/testimonials.php',
    'sections/contact.php',
    'sections/footer.php'
];

$missing_files = [];
foreach ($required_files as $file) {
    if (!file_exists($file)) {
        $missing_files[] = $file;
    }
}

if (empty($missing_files)) {
    echo "<p style='color: green;'>✅ All required files exist!</p>";
} else {
    echo "<p style='color: red;'>❌ Missing files:</p>";
    echo "<ul>";
    foreach ($missing_files as $file) {
        echo "<li>$file</li>";
    }
    echo "</ul>";
}

// Test 3: Check image files
echo "<h2>3. Image Files Test</h2>";

$image_files = [
    'assets/images/mypic.png'
];

$missing_images = [];
foreach ($image_files as $image) {
    if (!file_exists($image)) {
        $missing_images[] = $image;
    }
}

if (empty($missing_images)) {
    echo "<p style='color: green;'>✅ All image files exist!</p>";
} else {
    echo "<p style='color: orange;'>⚠️ Missing images:</p>";
    echo "<ul>";
    foreach ($missing_images as $image) {
        echo "<li>$image</li>";
    }
    echo "</ul>";
    echo "<p>These are optional but recommended for the best experience.</p>";
}

// Test 4: Check PHP extensions
echo "<h2>4. PHP Extensions Test</h2>";

$required_extensions = ['pdo', 'pdo_mysql', 'session'];
$missing_extensions = [];

foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $missing_extensions[] = $ext;
    }
}

if (empty($missing_extensions)) {
    echo "<p style='color: green;'>✅ All required PHP extensions are loaded!</p>";
} else {
    echo "<p style='color: red;'>❌ Missing PHP extensions:</p>";
    echo "<ul>";
    foreach ($missing_extensions as $ext) {
        echo "<li>$ext</li>";
    }
    echo "</ul>";
    echo "<p>Please enable these extensions in your php.ini file.</p>";
}

// Test 5: Check permissions
echo "<h2>5. File Permissions Test</h2>";

$writable_dirs = [
    'assets/images'
];

$permission_issues = [];
foreach ($writable_dirs as $dir) {
    if (!is_writable($dir)) {
        $permission_issues[] = $dir;
    }
}

if (empty($permission_issues)) {
    echo "<p style='color: green;'>✅ All directories are writable!</p>";
} else {
    echo "<p style='color: orange;'>⚠️ Permission issues:</p>";
    echo "<ul>";
    foreach ($permission_issues as $dir) {
        echo "<li>$dir (needs write permission)</li>";
    }
    echo "</ul>";
}

// Summary and next steps
echo "<h2>6. Setup Summary</h2>";

echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li><strong>Database Setup:</strong> If database tests failed, import database.sql in phpMyAdmin</li>";
echo "<li><strong>Admin Access:</strong> Visit <a href='admin/'>http://localhost/your-portfolio/admin/</a></li>";
echo "<li><strong>Login Credentials:</strong> Username: adem, Password: admin123</li>";
echo "<li><strong>Main Website:</strong> Visit <a href='index.php'>http://localhost/your-portfolio/</a></li>";
echo "<li><strong>Customization:</strong> Edit content through the admin panel</li>";
echo "</ol>";

echo "<h3>Important Security Notes:</h3>";
echo "<ul>";
echo "<li>Change the default admin password after first login</li>";
echo "<li>Keep XAMPP updated for security</li>";
echo "<li>Use HTTPS in production</li>";
echo "<li>Regularly backup your database and files</li>";
echo "</ul>";

echo "<h3>Support:</h3>";
echo "<p>If you encounter issues:</p>";
echo "<ul>";
echo "<li>Check the README.md file for detailed instructions</li>";
echo "<li>Ensure XAMPP Apache and MySQL services are running</li>";
echo "<li>Verify database connection settings in includes/database.php</li>";
echo "<li>Check file permissions for upload directories</li>";
echo "</ul>";

echo "<p><strong>Setup complete! Your portfolio website is ready to use.</strong></p>";
?> 
