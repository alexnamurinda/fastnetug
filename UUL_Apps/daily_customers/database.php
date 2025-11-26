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

        createClientsTable($conn);
        createDailySalesTable($conn);
        createUploadHistoryTable($conn);
        createSalesPersonsTable($conn);      // ADD THIS
        createDailyReportsTable($conn);      // ADD THIS

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
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

        INDEX idx_client_name (client_name),
        INDEX idx_client_type (client_type),
        INDEX idx_sales_person (sales_person)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    if (!$conn->query($sql)) {
        throw new Exception("Error creating clients table: " . $conn->error);
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
            'tables_created' => ['clients', 'daily_sales', 'upload_history']
        ]);
        $conn->close();
    }
}
