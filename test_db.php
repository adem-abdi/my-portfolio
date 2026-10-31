<?php
// Simple database connection test
echo "<h2>Database Connection Test</h2>";

// Test 1: Check if PHP PDO extension is loaded
echo "<h3>1. PHP Extensions Check:</h3>";
if (extension_loaded('pdo')) {
    echo "✅ PDO extension is loaded<br>";
} else {
    echo "❌ PDO extension is NOT loaded<br>";
}

if (extension_loaded('pdo_mysql')) {
    echo "✅ PDO MySQL extension is loaded<br>";
} else {
    echo "❌ PDO MySQL extension is NOT loaded<br>";
}

// Test 2: Try to connect to MySQL
echo "<h3>2. MySQL Connection Test:</h3>";
try {
    $pdo = new PDO("mysql:host=localhost", "root", "");
    echo "✅ Successfully connected to MySQL server<br>";
    
    // Test 3: Check if database exists
    echo "<h3>3. Database Check:</h3>";
    $stmt = $pdo->query("SHOW DATABASES LIKE 'portfolio_db'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Database 'portfolio_db' exists<br>";
        
        // Test 4: Try to connect to the specific database
        echo "<h3>4. Portfolio Database Connection:</h3>";
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=portfolio_db", "root", "");
            echo "✅ Successfully connected to portfolio_db<br>";
            
            // Test 5: Check if tables exist
            echo "<h3>5. Tables Check:</h3>";
            $tables = ['admin_users', 'site_content', 'projects', 'services', 'skills', 'testimonials', 'contact_info', 'social_links', 'contact_messages'];
            foreach ($tables as $table) {
                $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
                if ($stmt->rowCount() > 0) {
                    echo "✅ Table '$table' exists<br>";
                } else {
                    echo "❌ Table '$table' does NOT exist<br>";
                }
            }
            
        } catch (PDOException $e) {
            echo "❌ Failed to connect to portfolio_db: " . $e->getMessage() . "<br>";
        }
        
    } else {
        echo "❌ Database 'portfolio_db' does NOT exist<br>";
        echo "<p><strong>Solution:</strong> You need to create the database. You can:</p>";
        echo "<ol>";
        echo "<li>Open phpMyAdmin (http://localhost/phpmyadmin)</li>";
        echo "<li>Create a new database named 'portfolio_db'</li>";
        echo "<li>Import the database.sql file</li>";
        echo "</ol>";
    }
    
} catch (PDOException $e) {
    echo "❌ Failed to connect to MySQL: " . $e->getMessage() . "<br>";
    echo "<p><strong>Solution:</strong> Make sure XAMPP MySQL service is running.</p>";
}

// Test 6: Check XAMPP status
echo "<h3>6. XAMPP Status Check:</h3>";
if (file_exists('C:/xampp/mysql/data')) {
    echo "✅ XAMPP MySQL directory exists<br>";
} else {
    echo "❌ XAMPP MySQL directory not found<br>";
}

if (file_exists('C:/xampp/apache/conf/httpd.conf')) {
    echo "✅ XAMPP Apache directory exists<br>";
} else {
    echo "❌ XAMPP Apache directory not found<br>";
}

echo "<h3>7. Next Steps:</h3>";
echo "<ol>";
echo "<li>Make sure XAMPP Control Panel is open</li>";
echo "<li>Start Apache and MySQL services</li>";
echo "<li>Open http://localhost/phpmyadmin</li>";
echo "<li>Create database 'portfolio_db' if it doesn't exist</li>";
echo "<li>Import the database.sql file</li>";
echo "<li>Refresh this page to test again</li>";
echo "</ol>";
?> 
