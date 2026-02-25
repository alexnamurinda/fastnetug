<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'uul_user');
define('DB_PASS', 'uul@mysql123');
define('DB_NAME', 'sales_dashboard');

function setupDatabase()
{
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        $conn->set_charset('utf8mb4');

        // Create database
        $db = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . "
               CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

        if (!$conn->query($db)) {
            throw new Exception("Error creating database: " . $conn->error);
        }

        $conn->select_db(DB_NAME);

        // IMPORTANT: Create client_categorization BEFORE clients table
        createClientCategorizationTable($conn);
        createClientCategoryMappingTable($conn);
        createClientsTable($conn);
        createDailySalesTable($conn);
        createUploadHistoryTable($conn);
        createSalesPersonsTable($conn);
        createDailyReportsTable($conn);
        createChristmasCalendarsTable($conn);
        createProductsTable($conn);
        createProductCategoriesTable($conn);
        createMarginHistoryTable($conn);

        return $conn;
    } catch (Exception $e) {
        die(json_encode([
            'success' => false,
            'message' => 'Setup Error: ' . $e->getMessage()
        ]));
    }
}
function createClientsTable($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS clients (
        id INT AUTO_INCREMENT PRIMARY KEY,
        client_type VARCHAR(100) NOT NULL,
        client_name VARCHAR(255) NOT NULL,
        contact VARCHAR(50) DEFAULT NULL,
        address VARCHAR(255) DEFAULT NULL,
        sales_person VARCHAR(100) DEFAULT NULL,
        category_id INT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

        INDEX idx_client_name (client_name),
        INDEX idx_client_type (client_type),
        INDEX idx_sales_person (sales_person),
        INDEX idx_category_id (category_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if (!$conn->query($sql)) {
        throw new Exception("Error creating clients table: " . $conn->error);
    }

    // Check if foreign key already exists before adding it
    $checkFK = $conn->query("
        SELECT CONSTRAINT_NAME 
        FROM information_schema.TABLE_CONSTRAINTS 
        WHERE CONSTRAINT_SCHEMA = '" . DB_NAME . "' 
        AND TABLE_NAME = 'clients' 
        AND CONSTRAINT_NAME = 'fk_client_category'
    ");

    if ($checkFK->num_rows == 0) {
        // Foreign key doesn't exist, so add it
        $fkSql = "ALTER TABLE clients 
                  ADD CONSTRAINT fk_client_category 
                  FOREIGN KEY (category_id) 
                  REFERENCES client_categorization(id) 
                  ON DELETE SET NULL";

        // Try to add foreign key, but don't fail if it doesn't work
        if (!$conn->query($fkSql)) {
            // Just log the error, don't throw exception
            error_log("Note: Could not add foreign key constraint: " . $conn->error);
        }
    }
}

function createDailySalesTable($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS daily_sales (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sale_date DATE NOT NULL,
        client_id INT NOT NULL,
        method ENUM('M','C') DEFAULT NULL,
        discussion TEXT DEFAULT NULL,
        feedback TEXT DEFAULT NULL,
        sales_value DECIMAL(15,2) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

        INDEX idx_sale_date (sale_date),
        INDEX idx_client_id (client_id),

        FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if (!$conn->query($sql)) {
        throw new Exception("Error creating daily_sales table: " . $conn->error);
    }
}

function createUploadHistoryTable($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS upload_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        upload_date DATE NOT NULL,
        new_clients INT DEFAULT 0,
        total_orders INT DEFAULT 0,
        uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

        INDEX idx_upload_date (upload_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if (!$conn->query($sql)) {
        throw new Exception("Error creating upload_history table: " . $conn->error);
    }
}

function createSalesPersonsTable($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS sales_persons (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        passcode VARCHAR(255) NOT NULL,
        role ENUM('salesperson','supervisor') DEFAULT 'salesperson',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        INDEX idx_name (name),
        INDEX idx_role (role)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if (!$conn->query($sql)) {
        throw new Exception("Error creating sales_persons table: " . $conn->error);
    }
}

function createDailyReportsTable($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS daily_reports (
        id INT AUTO_INCREMENT PRIMARY KEY,
        report_date DATE NOT NULL,
        client_id INT NOT NULL,
        sales_person_id INT NOT NULL,
        method ENUM('M','C') NOT NULL,
        discussion TEXT,
        feedback TEXT,
        approved ENUM('pending','approved','rejected') DEFAULT 'pending',
        approved_by INT DEFAULT NULL,
        approved_at TIMESTAMP NULL,
        rejection_seen_at TIMESTAMP NULL DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        INDEX idx_report_date (report_date),
        INDEX idx_client_id (client_id),
        INDEX idx_sales_person_id (sales_person_id),
        INDEX idx_approved (approved),
        
        FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
        FOREIGN KEY (sales_person_id) REFERENCES sales_persons(id) ON DELETE CASCADE,
        FOREIGN KEY (approved_by) REFERENCES sales_persons(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if (!$conn->query($sql)) {
        throw new Exception("Error creating daily_reports table: " . $conn->error);
    }
}

function createChristmasCalendarsTable($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS christmas_calendars (
        id INT AUTO_INCREMENT PRIMARY KEY,
        recipient_name VARCHAR(255) NOT NULL,
        contact VARCHAR(50) NOT NULL,
        company_id INT DEFAULT NULL,
        company_name VARCHAR(255) NOT NULL,
        issue_date DATE NOT NULL,
        other_comment TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        INDEX idx_issue_date (issue_date),
        INDEX idx_company_id (company_id),
        INDEX idx_recipient_name (recipient_name),
        
        FOREIGN KEY (company_id) REFERENCES clients(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if (!$conn->query($sql)) {
        throw new Exception("Error creating christmas_calendars table: " . $conn->error);
    }
}

function createProductsTable($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_name VARCHAR(255) NOT NULL,
        category VARCHAR(100) DEFAULT 'Art Paper',
        packing_quantity VARCHAR(100) DEFAULT NULL,
        selling_price DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        cost_price DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        stock_available INT DEFAULT 0,
        price_last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

        INDEX idx_product_name (product_name),
        INDEX idx_category (category),
        INDEX idx_selling_price (selling_price),
        UNIQUE KEY unique_product (product_name, packing_quantity)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if (!$conn->query($sql)) {
        throw new Exception("Error creating products table: " . $conn->error);
    }
}

function createProductCategoriesTable($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS product_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_name VARCHAR(100) NOT NULL UNIQUE,
        description TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

        INDEX idx_category_name (category_name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if (!$conn->query($sql)) {
        throw new Exception("Error creating product_categories table: " . $conn->error);
    }

    // Insert default category
    $defaultCategory = "INSERT IGNORE INTO product_categories (category_name, description) 
                       VALUES ('Art Paper', 'Default category for art paper products')";
    $conn->query($defaultCategory);
}

function createMarginHistoryTable($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS margin_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        old_selling_price DECIMAL(15,2) NOT NULL,
        new_selling_price DECIMAL(15,2) NOT NULL,
        old_margin DECIMAL(10,2) NOT NULL,
        new_margin DECIMAL(10,2) NOT NULL,
        changed_by VARCHAR(100) DEFAULT NULL,
        changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

        INDEX idx_product_id (product_id),
        INDEX idx_changed_at (changed_at),

        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if (!$conn->query($sql)) {
        throw new Exception("Error creating margin_history table: " . $conn->error);
    }
}

function createClientCategorizationTable($conn)
{
    // DON'T drop the table - just create if not exists
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

    if (!$conn->query($sql)) {
        // Table might already exist, that's okay
        if (strpos($conn->error, 'already exists') === false) {
            throw new Exception("Error with client_categorization table: " . $conn->error);
        }
    }

    // Check if categories exist before inserting
    $checkSql = "SELECT COUNT(*) as count FROM client_categorization";
    $result = $conn->query($checkSql);

    if ($result) {
        $row = $result->fetch_assoc();
        if ($row['count'] == 0) {
            // Insert default categories only if table is empty
            $parentCategories = [
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Client by Product', NULL, 1)",
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Co-oporate clients', NULL, 2)",
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Resellers', NULL, 3)",
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Freelancers', NULL, 4)",
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Up-country clients', NULL, 5)",
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Client by machines', NULL, 6)"
            ];

            foreach ($parentCategories as $sql) {
                $conn->query($sql);
            }

            $subCategories = [
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Art Paper', 1, 1)",
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Large Format', 1, 2)",
                "INSERT IGNORE INTO client_categorization (category_name, parent_id, display_order) VALUES ('Chemicals', 1, 3)"
            ];

            foreach ($subCategories as $sql) {
                $conn->query($sql);
            }
        }
    }
}
function createClientCategoryMappingTable($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS client_category_mapping (
        id INT AUTO_INCREMENT PRIMARY KEY,
        client_id INT NOT NULL,
        category_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        
        UNIQUE KEY unique_mapping (client_id, category_id),
        INDEX idx_client_id (client_id),
        INDEX idx_category_id (category_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if (!$conn->query($sql)) {
        throw new Exception("Error creating client_category_mapping table: " . $conn->error);
    }

    // Add foreign keys only if they don't exist
    $checkFK1 = $conn->query("
        SELECT CONSTRAINT_NAME 
        FROM information_schema.TABLE_CONSTRAINTS 
        WHERE CONSTRAINT_SCHEMA = '" . DB_NAME . "' 
        AND TABLE_NAME = 'client_category_mapping' 
        AND CONSTRAINT_NAME = 'client_category_mapping_ibfk_1'
    ");

    if ($checkFK1->num_rows == 0) {
        $fk1Sql = "ALTER TABLE client_category_mapping 
                   ADD CONSTRAINT client_category_mapping_ibfk_1
                   FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE";
        @$conn->query($fk1Sql);
    }

    $checkFK2 = $conn->query("
        SELECT CONSTRAINT_NAME 
        FROM information_schema.TABLE_CONSTRAINTS 
        WHERE CONSTRAINT_SCHEMA = '" . DB_NAME . "' 
        AND TABLE_NAME = 'client_category_mapping' 
        AND CONSTRAINT_NAME = 'client_category_mapping_ibfk_2'
    ");

    if ($checkFK2->num_rows == 0) {
        $fk2Sql = "ALTER TABLE client_category_mapping 
                   ADD CONSTRAINT client_category_mapping_ibfk_2
                   FOREIGN KEY (category_id) REFERENCES client_categorization(id) ON DELETE CASCADE";
        @$conn->query($fk2Sql);
    }
}

function getDbConnection()
{
    return setupDatabase();
}

if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    $conn = setupDatabase();
    if ($conn) {
        echo json_encode([
            'success' => true,
            'message' => 'Database initialized successfully.',
            'database' => DB_NAME,
            'tables_created' => ['client_categorization', 'clients', 'daily_sales', 'upload_history', 'sales_persons', 'daily_reports', 'christmas_calendars', 'products', 'product_categories', 'margin_history']
        ]);
        $conn->close();
    }
}