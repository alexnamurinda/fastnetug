<?php

/**
 * FastNetUG Database Setup Script - Updated Version
 * Run this file once to create all necessary database tables
 */

// Database configuration
$servername = "localhost";
$username = "fastnetug_user1";  // Replace with your database username
$password = "smartwatt@mysql123";  // Replace with your database password
$dbname = "fastnet_db";          // Database name

echo "FastNetUG Database Setup - Updated Version\n";
echo "============================================\n\n";

try {
    // First, connect without specifying database to create it
    echo "Step 1: Connecting to MySQL server...\n";
    $pdo = new PDO("mysql:host=$servername;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connected to MySQL server successfully!\n\n";

    // Create database
    echo "Step 2: Creating database '$dbname'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database '$dbname' created successfully!\n\n";

    // Switch to the new database
    $pdo->exec("USE $dbname");
    echo "✅ Switched to database '$dbname'\n\n";

    // Create daily_vouchers table
    echo "Step 3: Creating daily_vouchers table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS daily_vouchers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            voucher_code VARCHAR(50) UNIQUE NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            profile VARCHAR(10) DEFAULT '1D',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            status ENUM('Available', 'Used', 'Expired') DEFAULT 'Available',
            user_phone VARCHAR(15) NULL,
            used_at TIMESTAMP NULL,
            INDEX idx_voucher_code (voucher_code),
            INDEX idx_status (status),
            INDEX idx_created_at (created_at),
            INDEX idx_user_phone (user_phone)
        )
    ");
    echo "✅ daily_vouchers table created successfully!\n\n";

    // Create weekly_vouchers table
    echo "Step 4: Creating weekly_vouchers table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS weekly_vouchers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            voucher_code VARCHAR(50) UNIQUE NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            profile VARCHAR(10) DEFAULT '1W',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            status ENUM('Available', 'Used', 'Expired') DEFAULT 'Available',
            user_phone VARCHAR(15) NULL,
            used_at TIMESTAMP NULL,
            INDEX idx_voucher_code (voucher_code),
            INDEX idx_status (status),
            INDEX idx_created_at (created_at),
            INDEX idx_user_phone (user_phone)
        )
    ");
    echo "✅ weekly_vouchers table created successfully!\n\n";

    // Create monthly_vouchers table
    echo "Step 5: Creating monthly_vouchers table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS monthly_vouchers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            voucher_code VARCHAR(50) UNIQUE NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            profile VARCHAR(10) DEFAULT '1M',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            status ENUM('Available', 'Used', 'Expired') DEFAULT 'Available',
            user_phone VARCHAR(15) NULL,
            used_at TIMESTAMP NULL,
            INDEX idx_voucher_code (voucher_code),
            INDEX idx_status (status),
            INDEX idx_created_at (created_at),
            INDEX idx_user_phone (user_phone)
        )
    ");
    echo "✅ monthly_vouchers table created successfully!\n\n";

    // Create hourly_vouchers table (5 hours short pass)
    echo "Step 5b: Creating hourly_vouchers table...\n";
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS hourly_vouchers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        voucher_code VARCHAR(50) UNIQUE NOT NULL,
        price DECIMAL(10,2) NOT NULL DEFAULT 500.00,
        profile VARCHAR(10) DEFAULT '5H',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status ENUM('Available', 'Used', 'Expired') DEFAULT 'Available',
        user_phone VARCHAR(15) NULL,
        used_at TIMESTAMP NULL,
        INDEX idx_voucher_code (voucher_code),
        INDEX idx_status (status),
        INDEX idx_created_at (created_at),
        INDEX idx_user_phone (user_phone)
    )
");
    echo "✅ hourly_vouchers table created successfully!\n\n";

    // Create simplified voucher_requests table (no MAC address)
    echo "Step 6: Creating voucher_requests table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS voucher_requests (
            id INT AUTO_INCREMENT PRIMARY KEY,
            request_id VARCHAR(20) UNIQUE NOT NULL,
            phone VARCHAR(15) NOT NULL,
            package VARCHAR(100) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            status ENUM('pending', 'approved', 'rejected', 'expired') DEFAULT 'pending',
            voucher_code VARCHAR(50) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            approved_at TIMESTAMP NULL,
            notes TEXT NULL,
            INDEX idx_request_id (request_id),
            INDEX idx_phone (phone),
            INDEX idx_status (status),
            INDEX idx_created_at (created_at),
            INDEX idx_status_created (status, created_at)
        )
    ");
    echo "✅ voucher_requests table created successfully!\n\n";

    // Create system_logs table
    echo "Step 7: Creating system_logs table...\n";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS system_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            log_type ENUM('voucher_request', 'voucher_approval', 'voucher_generation', 'voucher_usage', 'system_action') NOT NULL,
            reference_id VARCHAR(50) NULL,
            user_identifier VARCHAR(100) NULL,
            action_description TEXT NOT NULL,
            ip_address VARCHAR(45) NULL,
            user_agent TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_log_type (log_type),
            INDEX idx_reference_id (reference_id),
            INDEX idx_created_at (created_at),
            INDEX idx_user_identifier (user_identifier)
        )
    ");
    echo "✅ system_logs table created successfully!\n\n";

    // Display summary
    echo "🎉 Database Setup Complete!\n";
    echo "==========================\n";
    echo "Database Name: $dbname\n";
    echo "Tables Created:\n";
    echo "- hourly_vouchers: Stores 5-hour voucher codes\n";
    echo "- daily_vouchers: Stores daily voucher codes\n";
    echo "- weekly_vouchers: Stores weekly voucher codes\n";
    echo "- monthly_vouchers: Stores monthly voucher codes\n";
    echo "- voucher_requests: Stores customer payment requests (simplified)\n";
    echo "- system_logs: Audit trail for all actions\n\n";

    // Show table status
    echo "📊 Database Tables Status:\n";
    echo "==========================\n";
    $tables = ['hourly_vouchers','daily_vouchers', 'weekly_vouchers', 'monthly_vouchers', 'voucher_requests', 'system_logs'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "$table: $count records\n";
    }

    echo "\n✅ Database setup completed successfully!\n";
    echo "You can now use the voucher_request.php API endpoint.\n";
} catch (PDOException $e) {
    echo "❌ Database Setup Failed!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Possible causes:\n";
    echo "- Incorrect database credentials\n";
    echo "- MySQL server not running\n";
    echo "- Insufficient database privileges\n";
    echo "- Database connection issues\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Setup Failed!\n";
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

/**
 * Utility function to check database connection
 */
function testDatabaseConnection($servername, $username, $password, $dbname)
{
    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Function to check required PHP extensions
 */
function checkPHPRequirements()
{
    $required_extensions = ['pdo', 'pdo_mysql', 'curl', 'json'];
    $missing = [];

    foreach ($required_extensions as $ext) {
        if (!extension_loaded($ext)) {
            $missing[] = $ext;
        }
    }

    return $missing;
}

// Check PHP requirements
$missing_extensions = checkPHPRequirements();
if (!empty($missing_extensions)) {
    echo "❌ Missing PHP Extensions:\n";
    foreach ($missing_extensions as $ext) {
        echo "- $ext\n";
    }
    echo "Please install these extensions before proceeding.\n";
    exit(1);
}
