<?php
// Simple database connection test
require_once 'includes/database.php';

echo "<h2>Database Connection Test</h2>";

try {
    $pdo = getDBConnection();
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test if tables exist
    $tables = ['admin_users', 'site_content', 'projects', 'services', 'skills', 'testimonials', 'contact_info', 'social_links', 'contact_messages'];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<p style='color: green;'>✅ Table '$table' exists</p>";
        } else {
            echo "<p style='color: red;'>❌ Table '$table' missing</p>";
        }
    }
    
    // Test admin user
    $stmt = $pdo->query("SELECT COUNT(*) FROM admin_users");
    $count = $stmt->fetchColumn();
    echo "<p style='color: green;'>✅ Admin users: $count</p>";
    
    // Test site content
    $stmt = $pdo->query("SELECT COUNT(*) FROM site_content");
    $count = $stmt->fetchColumn();
    echo "<p style='color: green;'>✅ Site content items: $count</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
    echo "<p><strong>Please make sure:</strong></p>";
    echo "<ul>";
    echo "<li>XAMPP is running (Apache and MySQL)</li>";
    echo "<li>Database 'portfolio_db' exists</li>";
    echo "<li>You've imported the database.sql file</li>";
    echo "<li>Database credentials in includes/database.php are correct</li>";
    echo "</ul>";
}
?> 
