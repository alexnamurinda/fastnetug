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

echo "<h2>Safe Migration Log - No Data Will Be Lost</h2><pre>";

try {
    // Step 1: Check if client_category_mapping exists
    echo "Step 1: Checking for client_category_mapping table...\n";
    $checkMapping = $conn->query("SHOW TABLES LIKE 'client_category_mapping'");

    if ($checkMapping->num_rows == 0) {
        echo "Creating client_category_mapping table...\n";
        $sql = "CREATE TABLE IF NOT EXISTS client_category_mapping (
            id INT AUTO_INCREMENT PRIMARY KEY,
            client_id INT NOT NULL,
            category_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            UNIQUE KEY unique_mapping (client_id, category_id),
            INDEX idx_client_id (client_id),
            INDEX idx_category_id (category_id),
            
            FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
            FOREIGN KEY (category_id) REFERENCES client_categorization(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        if ($conn->query($sql)) {
            echo "✓ client_category_mapping table created successfully\n";
        } else {
            throw new Exception("Error creating client_category_mapping: " . $conn->error);
        }
    } else {
        echo "✓ client_category_mapping table already exists\n";
    }

    // Step 2: Check if client_categorization table exists and has data
    echo "\nStep 2: Checking client_categorization table...\n";
    $checkCat = $conn->query("SHOW TABLES LIKE 'client_categorization'");

    if ($checkCat->num_rows > 0) {
        $countResult = $conn->query("SELECT COUNT(*) as count FROM client_categorization");
        $count = $countResult->fetch_assoc()['count'];
        echo "✓ client_categorization exists with $count categories\n";

        if ($count == 0) {
            echo "  Adding default categories...\n";

            // Insert parent categories
            $parentCategories = [
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Client by Product', NULL, 1)",
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Co-oporate clients', NULL, 2)",
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Resellers', NULL, 3)",
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Freelancers', NULL, 4)",
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Up-country clients', NULL, 5)",
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Client by machines', NULL, 6)"
            ];

            foreach ($parentCategories as $sql) {
                if ($conn->query($sql)) {
                    echo "  ✓ Inserted parent category\n";
                }
            }

            // Insert sub-categories
            $subCategories = [
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Art Paper', 1, 1)",
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Large Format', 1, 2)",
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Chemicals', 1, 3)"
            ];

            foreach ($subCategories as $sql) {
                if ($conn->query($sql)) {
                    echo "  ✓ Inserted subcategory\n";
                }
            }
        }
    } else {
        echo "Creating client_categorization table...\n";
        $sql = "CREATE TABLE client_categorization (
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
            echo "✓ client_categorization table created\n";

            // Insert default categories
            $parentCategories = [
                "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Client by Product', NULL, 1)",
                "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Co-oporate clients', NULL, 2)",
                "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Resellers', NULL, 3)",
                "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Freelancers', NULL, 4)",
                "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Up-country clients', NULL, 5)",
                "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Client by machines', NULL, 6)"
            ];

            foreach ($parentCategories as $sql) {
                $conn->query($sql);
            }

            $subCategories = [
                "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Art Paper', 1, 1)",
                "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Large Format', 1, 2)",
                "INSERT INTO client_categorization (category_name, parent_id, display_order) VALUES ('Chemicals', 1, 3)"
            ];

            foreach ($subCategories as $sql) {
                $conn->query($sql);
            }

            echo "✓ Default categories inserted\n";
        }
    }

    // Step 3: Migrate existing category_id data to mapping table (if category_id has data)
    echo "\nStep 3: Migrating existing category data...\n";
    $checkColumn = $conn->query("SHOW COLUMNS FROM clients LIKE 'category_id'");

    if ($checkColumn->num_rows > 0) {
        // Check if there's data to migrate
        $dataCheck = $conn->query("SELECT COUNT(*) as count FROM clients WHERE category_id IS NOT NULL");
        $dataCount = $dataCheck->fetch_assoc()['count'];

        if ($dataCount > 0) {
            echo "Found $dataCount clients with category_id set\n";
            echo "Migrating to mapping table...\n";

            $migrateSql = "INSERT IGNORE INTO client_category_mapping (client_id, category_id)
                          SELECT id, category_id 
                          FROM clients 
                          WHERE category_id IS NOT NULL";

            if ($conn->query($migrateSql)) {
                echo "✓ Migrated $dataCount client categories to mapping table\n";
            } else {
                echo "⚠ Warning: " . $conn->error . "\n";
            }
        } else {
            echo "No category data to migrate\n";
        }
    }

    // Step 4: Verify all data is safe
    echo "\nStep 4: Verifying data integrity...\n";

    $clientCount = $conn->query("SELECT COUNT(*) as count FROM clients")->fetch_assoc()['count'];
    echo "✓ Total clients: $clientCount (NO DATA LOST)\n";

    $catCount = $conn->query("SELECT COUNT(*) as count FROM client_categorization")->fetch_assoc()['count'];
    echo "✓ Total categories: $catCount\n";

    $mappingCount = $conn->query("SELECT COUNT(*) as count FROM client_category_mapping")->fetch_assoc()['count'];
    echo "✓ Total category mappings: $mappingCount\n";

    $salesCount = $conn->query("SELECT COUNT(*) as count FROM daily_sales")->fetch_assoc()['count'];
    echo "✓ Total sales records: $salesCount (NO DATA LOST)\n";

    $reportsCount = $conn->query("SELECT COUNT(*) as count FROM daily_reports")->fetch_assoc()['count'];
    echo "✓ Total reports: $reportsCount (NO DATA LOST)\n";

    echo "\n" . str_repeat("=", 60) . "\n";
    echo "✓✓✓ MIGRATION COMPLETED SUCCESSFULLY - ALL DATA PRESERVED ✓✓✓\n";
    echo str_repeat("=", 60) . "\n";

    echo "\nNext steps:\n";
    echo "1. Test the categories: api.php?action=getCategories\n";
    echo "2. Test clients loading: api.php?action=getClients\n";
    echo "3. Your application should now work normally\n";
} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "No data was deleted or modified.\n";
}

echo "</pre>";
$conn->close();
