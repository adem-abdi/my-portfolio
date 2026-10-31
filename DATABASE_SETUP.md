# Database Setup Guide

## Quick Fix for Database Connection Error

### Step 1: Start XAMPP Services
1. Open XAMPP Control Panel
2. Click "Start" next to Apache
3. Click "Start" next to MySQL
4. Both should show green status

### Step 2: Create Database
1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click "New" on the left sidebar
3. Enter database name: `portfolio_db`
4. Click "Create"

### Step 3: Import Database Schema
1. In phpMyAdmin, select the `portfolio_db` database
2. Click the "Import" tab
3. Click "Choose File" and select `database.sql` from your project folder
4. Click "Go" to import

### Step 4: Test Connection
1. Open your browser and go to: `http://localhost/your-portfolio/test_db.php`
2. This will show you exactly what's working and what needs to be fixed

## Common Issues and Solutions

### Issue 1: "Connection failed: Access denied"
**Solution:** 
- Make sure MySQL is running in XAMPP
- Default credentials are: username `root`, password `` (empty)

### Issue 2: "Database 'portfolio_db' doesn't exist"
**Solution:**
- Follow Step 2 above to create the database

### Issue 3: "Table doesn't exist"
**Solution:**
- Follow Step 3 above to import the database.sql file

### Issue 4: "PDO extension not loaded"
**Solution:**
- This is rare with XAMPP, but if it happens:
- Open `C:\xampp\php\php.ini`
- Make sure these lines are uncommented (no semicolon at start):
  ```ini
  extension=pdo
  extension=pdo_mysql
  ```
- Restart Apache in XAMPP

## Test Your Setup

After completing the steps above:

1. **Test Database:** Visit `http://localhost/your-portfolio/test_db.php`
2. **Test Portfolio:** Visit `http://localhost/your-portfolio/`
3. **Test Admin:** Visit `http://localhost/your-portfolio/admin/`
   - Username: `adem`
   - Password: `admin123`

### Existing installations: enable inbox statuses

If the `contact_messages` table was created before inbox status tracking was added, run this once in phpMyAdmin:

```sql
ALTER TABLE contact_messages
    ADD COLUMN status ENUM('new', 'read', 'replied', 'archived') NOT NULL DEFAULT 'new';
```

Fresh imports of `database.sql` already include this column.

## If Still Having Issues

1. Check XAMPP error logs:
   - Apache: `C:\xampp\apache\logs\error.log`
   - MySQL: `C:\xampp\mysql\data\mysql_error.log`

2. Make sure your project is in the correct folder:
   - Should be: `C:\xampp\htdocs\your-portfolio\`

3. Verify file permissions:
   - The `assets/images/` folder should be writable

## Database Credentials (for reference)
- Host: `localhost`
- Database: `portfolio_db`
- Username: `root`
- Password: `` (empty)
- Port: `3306` (default)

These are the default XAMPP MySQL credentials. If you've changed them, update the `includes/database.php` file accordingly. 
