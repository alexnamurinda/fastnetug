<?php
/**
 * FastNetUG Database Setup Script
 * Run this file once to create all necessary database tables and initial data
 */

// Database configuration
$servername = "localhost";
$username = "fastnetug_user1";  // Replace with your database username
$password = "smartwatt@mysql123";  // Replace with your database password
$dbname = "fastnet_db";          // Database name

// HTML header for better display
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastNetUG Database Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success {
            color: #28a745;
            background: #d4edda;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .error {
            color: #dc3545;
            background: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .warning {
            color: #856404;
            background: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .info {
            color: #0c5460;
            background: #d1ecf1;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .step {
            margin: 20px 0;
            padding: 15px;
            border-left: 4px solid #007bff;
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>FastNetUG Database Setup</h1>
        
        <?php
        echo "<div class='warning'><strong>⚠️ Important:</strong> Make sure to update the database credentials at the top of this file before running!</div>";
        
        try {
            // First, connect without specifying database to create it
            echo "<div class='step'><strong>Step 1:</strong> Connecting to MySQL server...</div>";
            $pdo = new PDO("mysql:host=$servername;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<div class='success'>✅ Connected to MySQL server successfully!</div>";
            
            // Create database
            echo "<div class='step'><strong>Step 2:</strong> Creating database '$dbname'...</div>";
            $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            echo "<div class='success'>✅ Database '$dbname' created successfully!</div>";
            
            // Switch to the new database
            $pdo->exec("USE $dbname");
            echo "<div class='success'>✅ Switched to database '$dbname'</div>";
            
            // Create payment_requests table
            echo "<div class='step'><strong>Step 3:</strong> Creating payment_requests table...</div>";
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS payment_requests (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    request_id VARCHAR(50) UNIQUE NOT NULL,
                    phone VARCHAR(15) NOT NULL,
                    mac_address VARCHAR(17) NOT NULL,
                    package_name VARCHAR(100) NOT NULL,
                    package_price DECIMAL(10,2) NOT NULL,
                    admin_id VARCHAR(10),
                    status ENUM('pending', 'approved', 'rejected', 'expired') DEFAULT 'pending',
                    voucher_code VARCHAR(50) NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    expires_at TIMESTAMP NULL,
                    approved_at TIMESTAMP NULL,
                    approved_by VARCHAR(50) NULL,
                    notes TEXT NULL,
                    INDEX idx_mac_address (mac_address),
                    INDEX idx_phone (phone),
                    INDEX idx_status (status),
                    INDEX idx_request_id (request_id),
                    INDEX idx_created_at (created_at)
                )
            ");
            echo "<div class='success'>✅ payment_requests table created successfully!</div>";
            
            // Create admin_users table
            echo "<div class='step'><strong>Step 4:</strong> Creating admin_users table...</div>";
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS admin_users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(50) UNIQUE NOT NULL,
                    email VARCHAR(100) UNIQUE NOT NULL,
                    phone VARCHAR(15) NOT NULL,
                    password_hash VARCHAR(255) NOT NULL,
                    role ENUM('admin', 'super_admin') DEFAULT 'admin',
                    is_active BOOLEAN DEFAULT TRUE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    last_login TIMESTAMP NULL
                )
            ");
            echo "<div class='success'>✅ admin_users table created successfully!</div>";
            
            // Create voucher_codes table
            echo "<div class='step'><strong>Step 5:</strong> Creating voucher_codes table...</div>";
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS voucher_codes (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    voucher_code VARCHAR(50) UNIQUE NOT NULL,
                    package_name VARCHAR(100) NOT NULL,
                    package_price DECIMAL(10,2) NOT NULL,
                    duration_hours INT NOT NULL,
                    is_used BOOLEAN DEFAULT FALSE,
                    used_by_mac VARCHAR(17) NULL,
                    used_at TIMESTAMP NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    expires_at TIMESTAMP NULL,
                    created_by_request_id VARCHAR(50) NULL,
                    INDEX idx_voucher_code (voucher_code),
                    INDEX idx_is_used (is_used),
                    INDEX idx_created_at (created_at)
                )
            ");
            
            // Add foreign key constraint
            try {
                $pdo->exec("
                    ALTER TABLE voucher_codes 
                    ADD CONSTRAINT fk_voucher_request 
                    FOREIGN KEY (created_by_request_id) REFERENCES payment_requests(request_id) ON DELETE SET NULL
                ");
            } catch (Exception $e) {
                // Foreign key might already exist, ignore error
            }
            echo "<div class='success'>✅ voucher_codes table created successfully!</div>";
            
            // Create system_logs table
            echo "<div class='step'><strong>Step 6:</strong> Creating system_logs table...</div>";
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS system_logs (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    log_type ENUM('payment_request', 'payment_approval', 'voucher_generation', 'voucher_usage', 'admin_action') NOT NULL,
                    reference_id VARCHAR(50) NULL,
                    user_identifier VARCHAR(100) NULL,
                    action_description TEXT NOT NULL,
                    ip_address VARCHAR(45) NULL,
                    user_agent TEXT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_log_type (log_type),
                    INDEX idx_reference_id (reference_id),
                    INDEX idx_created_at (created_at)
                )
            ");
            echo "<div class='success'>✅ system_logs table created successfully!</div>";
            
            // Insert default admin user
            echo "<div class='step'><strong>Step 7:</strong> Creating default admin user...</div>";
            $defaultPasswordHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password123
            
            $stmt = $pdo->prepare("
                INSERT INTO admin_users (username, email, phone, password_hash, role) VALUES 
                (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE username=username
            ");
            $stmt->execute(['admin', 'admin@fastnetug.com', '+256744766410', $defaultPasswordHash, 'super_admin']);
            
            echo "<div class='success'>✅ Default admin user created!</div>";
            echo "<div class='warning'><strong>⚠️ SECURITY WARNING:</strong> Default admin password is 'password123' - CHANGE THIS IMMEDIATELY!</div>";
            
            // Create additional indexes for better performance
            echo "<div class='step'><strong>Step 8:</strong> Creating performance indexes...</div>";
            
            try {
                $pdo->exec("ALTER TABLE payment_requests ADD INDEX idx_status_created (status, created_at)");
            } catch (Exception $e) {
                // Index might already exist
            }
            
            try {
                $pdo->exec("ALTER TABLE payment_requests ADD INDEX idx_mac_status (mac_address, status)");
            } catch (Exception $e) {
                // Index might already exist
            }
            
            try {
                $pdo->exec("ALTER TABLE voucher_codes ADD INDEX idx_used_created (is_used, created_at)");
            } catch (Exception $e) {
                // Index might already exist
            }
            
            echo "<div class='success'>✅ Performance indexes created successfully!</div>";
            
            // Display summary
            echo "<div class='step'><h3>🎉 Database Setup Complete!</h3>";
            echo "<p><strong>Database Name:</strong> $dbname</p>";
            echo "<p><strong>Tables Created:</strong></p>";
            echo "<ul>";
            echo "<li>payment_requests - Stores payment requests from customers</li>";
            echo "<li>admin_users - Manages admin access</li>";
            echo "<li>voucher_codes - Stores generated voucher codes</li>";
            echo "<li>system_logs - Audit trail for all actions</li>";
            echo "</ul>";
            echo "</div>";
            
            // Show next steps
            echo "<div class='info'>";
            echo "<h4>📋 Next Steps:</h4>";
            echo "<ol>";
            echo "<li><strong>Change Admin Password:</strong> Login with username 'admin' and password 'password123', then change it immediately</li>";
            echo "<li><strong>Configure SMS Service:</strong> Update the SMS credentials in voucher_request.php and approve_payment.php</li>";
            echo "<li><strong>Upload PHP Files:</strong> Upload voucher_request.php and approve_payment.php to your web server</li>";
            echo "<li><strong>Update Captive Portal:</strong> Replace your login.html with the updated version</li>";
            echo "<li><strong>Test the System:</strong> Try the complete payment flow</li>";
            echo "</ol>";
            echo "</div>";
            
            // Show table status
            echo "<div class='step'>";
            echo "<h4>📊 Database Tables Status:</h4>";
            
            $tables = ['payment_requests', 'admin_users', 'voucher_codes', 'system_logs'];
            foreach ($tables as $table) {
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
                $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                echo "<p><strong>$table:</strong> $count records</p>";
            }
            echo "</div>";
            
            // Security reminder
            echo "<div class='error'>";
            echo "<h4>🔒 IMPORTANT SECURITY REMINDERS:</h4>";
            echo "<ul>";
            echo "<li>Change the default admin password immediately</li>";
            echo "<li>Update database credentials in all PHP files</li>";
            echo "<li>Set up proper SMS service credentials</li>";
            echo "<li>Ensure your web server has proper security configurations</li>";
            echo "<li>Consider deleting this setup file after successful installation</li>";
            echo "</ul>";
            echo "</div>";
            
        } catch (PDOException $e) {
            echo "<div class='error'>";
            echo "<h3>❌ Database Setup Failed!</h3>";
            echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
            echo "<p><strong>Possible causes:</strong></p>";
            echo "<ul>";
            echo "<li>Incorrect database credentials</li>";
            echo "<li>MySQL server not running</li>";
            echo "<li>Insufficient database privileges</li>";
            echo "<li>Database connection issues</li>";
            echo "</ul>";
            echo "<p><strong>Solution:</strong> Please check your database configuration and try again.</p>";
            echo "</div>";
        } catch (Exception $e) {
            echo "<div class='error'>";
            echo "<h3>❌ Setup Failed!</h3>";
            echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
            echo "</div>";
        }
        ?>
        
        <div class="step">
            <h4>🔧 Configuration Instructions:</h4>
            <p>After successful setup, you need to configure the following:</p>
            
            <h5>1. Update Database Credentials</h5>
            <p>In <strong>voucher_request.php</strong> and <strong>approve_payment.php</strong>, update:</p>
            <pre>$servername = "localhost";
$username = "<?php echo htmlspecialchars($username); ?>";
$password = "your_actual_password";
$dbname = "<?php echo htmlspecialchars($dbname); ?>";</pre>
            
            <h5>2. Configure SMS Service (Africa's Talking Recommended)</h5>
            <p>In both PHP files, update the SMS configuration:</p>
            <pre>function sendSMS_AfricasTalking($phone, $message) {
    $username = 'your_at_username';  // Your Africa's Talking username
    $apikey = 'your_at_api_key';     // Your Africa's Talking API key
    // ... rest of the function
}</pre>
            
            <h5>3. File Upload Locations</h5>
            <ul>
                <li><strong>voucher_request.php</strong> → Upload to: <code>https://www.fastnetug.com/pages/voucher_request.php</code></li>
                <li><strong>approve_payment.php</strong> → Upload to: <code>https://www.fastnetug.com/pages/approve_payment.php</code></li>
                <li><strong>login_updated.html</strong> → Replace your existing MikroTik login.html</li>
            </ul>
            
            <h5>4. Admin Access</h5>
            <p>Default admin credentials (CHANGE IMMEDIATELY):</p>
            <ul>
                <li><strong>Username:</strong> admin</li>
                <li><strong>Password:</strong> password123</li>
                <li><strong>Login URL:</strong> You'll need to create an admin login page or add authentication to approve_payment.php</li>
            </ul>
        </div>
        
        <div class="info">
            <h4>📱 How the System Works:</h4>
            <ol>
                <li>Customer selects package on captive portal</li>
                <li>Customer enters phone number and clicks "Continue"</li>
                <li>System shows persistent alert with payment instructions</li>
                <li>Admin receives SMS notification with approval link</li>
                <li>Admin clicks link and approves/rejects payment</li>
                <li>If approved, voucher code is automatically sent to customer</li>
                <li>Customer uses voucher code to access internet</li>
            </ol>
        </div>
        
        <div class="warning">
            <h4>🧪 Testing the System:</h4>
            <p>After setup, test with these steps:</p>
            <ol>
                <li>Access your captive portal</li>
                <li>Select a package</li>
                <li>Enter a test phone number (your own)</li>
                <li>Click "Continue" and verify the persistent alert appears</li>
                <li>Check if SMS is sent to admin phone (+256744766410)</li>
                <li>Click the approval link in SMS</li>
                <li>Approve the request and verify voucher is sent</li>
                <li>Test the voucher code in the login form</li>
            </ol>
        </div>
        
        <?php
        // Show PHP version and extensions
        echo "<div class='info'>";
        echo "<h4>🔍 System Information:</h4>";
        echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
        echo "<p><strong>PDO Extensions:</strong> " . (extension_loaded('pdo') ? '✅ Available' : '❌ Not Available') . "</p>";
        echo "<p><strong>PDO MySQL:</strong> " . (extension_loaded('pdo_mysql') ? '✅ Available' : '❌ Not Available') . "</p>";
        echo "<p><strong>cURL:</strong> " . (extension_loaded('curl') ? '✅ Available' : '❌ Not Available') . "</p>";
        echo "<p><strong>JSON:</strong> " . (extension_loaded('json') ? '✅ Available' : '❌ Not Available') . "</p>";
        echo "</div>";
        
        // Show database connection test
        if (isset($pdo)) {
            echo "<div class='success'>";
            echo "<h4>✅ Database Connection Test Successful</h4>";
            echo "<p>Your database is ready to use!</p>";
            echo "</div>";
            
            // Recommend next steps
            echo "<div class='step'>";
            echo "<h4>🚀 Ready to Deploy!</h4>";
            echo "<p>Your database setup is complete. Now follow these steps:</p>";
            echo "<ol>";
            echo "<li>Update the database credentials in your PHP files</li>";
            echo "<li>Configure your SMS service (Africa's Talking recommended)</li>";
            echo "<li>Upload the PHP files to your web server</li>";
            echo "<li>Replace your MikroTik login.html with the updated version</li>";
            echo "<li>Test the complete payment flow</li>";
            echo "</ol>";
            echo "</div>";
        }
        ?>
        
        <div class="warning">
            <h4>🗑️ Cleanup</h4>
            <p>After successful setup and testing, consider:</p>
            <ul>
                <li>Deleting this setup file for security</li>
                <li>Backing up your database regularly</li>
                <li>Setting up monitoring for the payment system</li>
            </ul>
        </div>
        
        <div class="info">
            <h4>📞 Support</h4>
            <p>If you encounter any issues during setup:</p>
            <ul>
                <li>Check PHP error logs</li>
                <li>Verify database permissions</li>
                <li>Test SMS service credentials separately</li>
                <li>Contact your hosting provider if needed</li>
            </ul>
        </div>
    </div>
    
    <script>
        // Auto-scroll to bottom to show results
        window.scrollTo(0, document.body.scrollHeight);
    </script>
</body>
</html>

<?php
/**
 * Additional utility functions for manual testing
 */

// Function to manually test database connection
function testDatabaseConnection($servername, $username, $password, $dbname) {
    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Function to generate test data (uncomment to use)
/*
function createTestData($pdo) {
    // Create a test payment request
    $test_request_id = 'TEST_' . strtoupper(uniqid());
    $stmt = $pdo->prepare("
        INSERT INTO payment_requests 
        (request_id, phone, mac_address, package_name, package_price, admin_id, expires_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $test_request_id,
        '256741234567',
        'aa:bb:cc:dd:ee:ff',
        '24 HOURS',
        1000,
        '55',
        date('Y-m-d H:i:s', strtotime('+24 hours'))
    ]);
    
    echo "<div class='info'>✅ Test payment request created with ID: $test_request_id</div>";
}
*/

// Function to check required PHP extensions
function checkPHPRequirements() {
    $required_extensions = ['pdo', 'pdo_mysql', 'curl', 'json'];
    $missing = [];
    
    foreach ($required_extensions as $ext) {
        if (!extension_loaded($ext)) {
            $missing[] = $ext;
        }
    }
    
    return $missing;
}

// Display PHP requirements check
$missing_extensions = checkPHPRequirements();
if (!empty($missing_extensions)) {
    echo "<div class='error'>";
    echo "<h4>❌ Missing PHP Extensions</h4>";
    echo "<p>The following required PHP extensions are missing:</p>";
    echo "<ul>";
    foreach ($missing_extensions as $ext) {
        echo "<li>$ext</li>";
    }
    echo "</ul>";
    echo "<p>Please install these extensions before proceeding.</p>";
    echo "</div>";
}
?>