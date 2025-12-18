<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'uul_user');
define('DB_PASS', 'uul@mysql123');
define('DB_NAME', 'sales_dashboard');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

$conn->set_charset('utf8mb4');

echo "<h2>Migration Log:</h2><pre>";

try {
    // Step 1: Create client_categorization table (without self-referencing FK)
    echo "Step 1: Creating client_categorization table...\n";
    $sql = "CREATE TABLE IF NOT EXISTS client_categorization (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_name VARCHAR(100) NOT NULL,
        parent_id INT DEFAULT NULL,
        description TEXT DEFAULT NULL,
        display_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        INDEX idx_category_name (category_name),
        INDEX idx_parent_id (parent_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if ($conn->query($sql)) {
        echo "✓ client_categorization table created successfully\n";
    } else {
        echo "✗ Error creating table: " . $conn->error . "\n";
    }

    // Step 2: Insert default categories
    echo "\nStep 2: Inserting default categories...\n";
    
    // Check if categories already exist
    $checkSql = "SELECT COUNT(*) as count FROM client_categorization";
    $result = $conn->query($checkSql);
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        // Insert parent categories
        $parentCategories = [
            "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Client by Product', NULL, 1)",
            "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Co-oporate clients', NULL, 2)",
            "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Resellers', NULL, 3)",
            "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Freelancers', NULL, 4)",
            "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Up-country clients', NULL, 5)",
            "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Client by machines', NULL, 6)"
        ];
        
        foreach ($parentCategories as $sql) {
            if ($conn->query($sql)) {
                echo "✓ Inserted: " . explode("'", $sql)[1] . "\n";
            } else {
                echo "✗ Error: " . $conn->error . "\n";
            }
        }
        
        // Insert sub-categories
        $subCategories = [
            "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Art Paper', 1, 1)",
            "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Large Format', 1, 2)",
            "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Chemicals', 1, 3)"
        ];
        
        foreach ($subCategories as $sql) {
            if ($conn->query($sql)) {
                echo "✓ Inserted: " . explode("'", $sql)[1] . "\n";
            } else {
                echo "✗ Error: " . $conn->error . "\n";
            }
        }
    } else {
        echo "Categories already exist, skipping...\n";
    }

    // Step 3: Check if category_id column exists in clients table
    echo "\nStep 3: Checking clients table structure...\n";
    $columnCheck = $conn->query("SHOW COLUMNS FROM clients LIKE 'category_id'");
    
    if ($columnCheck->num_rows == 0) {
        echo "Adding category_id column to clients table...\n";
        $alterSql = "ALTER TABLE clients ADD COLUMN category_id INT DEFAULT NULL AFTER sales_person";
        
        if ($conn->query($alterSql)) {
            echo "✓ category_id column added successfully\n";
        } else {
            echo "✗ Error adding column: " . $conn->error . "\n";
        }
        
        // Add index
        $indexSql = "ALTER TABLE clients ADD INDEX idx_category_id (category_id)";
        if ($conn->query($indexSql)) {
            echo "✓ Index added successfully\n";
        } else {
            echo "✗ Error adding index: " . $conn->error . "\n";
        }
        
        // Add foreign key (try, but don't fail if it doesn't work)
        $fkSql = "ALTER TABLE clients 
                  ADD CONSTRAINT fk_client_category 
                  FOREIGN KEY (category_id) 
                  REFERENCES client_categorization(id) 
                  ON DELETE SET NULL";
        
        if ($conn->query($fkSql)) {
            echo "✓ Foreign key constraint added successfully\n";
        } else {
            echo "⚠ Warning: Could not add foreign key (this is okay): " . $conn->error . "\n";
        }
    } else {
        echo "category_id column already exists\n";
    }

    // Step 4: Verify setup
    echo "\nStep 4: Verifying setup...\n";
    
    $catCount = $conn->query("SELECT COUNT(*) as count FROM client_categorization")->fetch_assoc()['count'];
    echo "✓ Total categories in database: " . $catCount . "\n";
    
    $clientCount = $conn->query("SELECT COUNT(*) as count FROM clients")->fetch_assoc()['count'];
    echo "✓ Total clients in database: " . $clientCount . "\n";
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "MIGRATION COMPLETED SUCCESSFULLY!\n";
    echo str_repeat("=", 50) . "\n";
    echo "\nYou can now:\n";
    echo "1. Visit api.php?action=getCategories to test\n";
    echo "2. Visit api.php?action=getClients to test\n";
    echo "3. Go back to your application\n";

} catch (Exception $e) {
    echo "\n✗ FATAL ERROR: " . $e->getMessage() . "\n";
}

echo "</pre>";
$conn->close();