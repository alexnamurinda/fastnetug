<?php
/**
 * Add hourly_vouchers table without affecting existing data
 */

// Database configuration
$servername = "localhost";
$username = "fastnetug_user1";
$password = "smartwatt@mysql123";
$dbname = "fastnet_db";

echo "Adding hourly_vouchers table to FastNetUG Database\n";
echo "===================================================\n\n";

try {
    // Connect to database
    echo "Step 1: Connecting to database...\n";
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connected successfully!\n\n";
    
    // Check if table already exists
    echo "Step 2: Checking if hourly_vouchers table exists...\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'hourly_vouchers'");
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        echo "⚠️  hourly_vouchers table already exists!\n";
        echo "No changes made to preserve existing data.\n";
        exit(0);
    }
    
    // Create hourly_vouchers table
    echo "Step 3: Creating hourly_vouchers table...\n";
    $pdo->exec("
        CREATE TABLE hourly_vouchers (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ hourly_vouchers table created successfully!\n\n";
    
    // Verify table creation
    echo "Step 4: Verifying table structure...\n";
    $stmt = $pdo->query("DESCRIBE hourly_vouchers");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Table columns:\n";
    foreach ($columns as $column) {
        echo "  - {$column['Field']} ({$column['Type']})\n";
    }
    
    echo "\n✅ Setup completed successfully!\n";
    echo "\nNext steps:\n";
    echo "1. Generate some 5-hour vouchers for testing\n";
    echo "2. Update your frontend to use the new package\n";
    echo "3. Test the complete flow\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error!\n";
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Setup Failed!\n";
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
