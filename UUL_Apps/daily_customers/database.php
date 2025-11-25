<?php
/**
 * Database Setup Script
 * This file automatically creates the database and tables if they don't exist
 * Include this file before any database operations
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'uul_user');
define('DB_PASS', 'uul@mysql123');
define('DB_NAME', 'sales_dashboard');

/**
 * Create database connection and setup tables
 */
function setupDatabase() {
    try {
        // First, connect without selecting a database
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        $conn->set_charset('utf8mb4');
        
        // Create database if it doesn't exist
        $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " 
                CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        
        if (!$conn->query($sql)) {
            throw new Exception("Error creating database: " . $conn->error);
        }
        
        // Select the database
        $conn->select_db(DB_NAME);
        
        // Create tables
        createTables($conn);
        
        return $conn;
        
    } catch (Exception $e) {
        die(json_encode([
            'success' => false, 
            'message' => 'Database setup error: ' . $e->getMessage()
        ]));
    }
}

/**
 * Create all required tables
 */
function createTables($conn) {
    
    // ===== CLIENTS TABLE =====
    $clientsTable = "CREATE TABLE IF NOT EXISTS clients (
        id INT AUTO_INCREMENT PRIMARY KEY,
        customer_name VARCHAR(255) NOT NULL,
        phone_number VARCHAR(50) DEFAULT NULL,
        sales_person VARCHAR(100) DEFAULT NULL,
        first_order_date DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_customer (customer_name),
        INDEX idx_customer_name (customer_name),
        INDEX idx_sales_person (sales_person),
        INDEX idx_first_order_date (first_order_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if (!$conn->query($clientsTable)) {
        throw new Exception("Error creating clients table: " . $conn->error);
    }
    
    // ===== DAILY SALES TABLE =====
    $dailySalesTable = "CREATE TABLE IF NOT EXISTS daily_sales (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sale_date DATE NOT NULL,
        customer_name VARCHAR(255) NOT NULL,
        customer_id INT DEFAULT NULL,
        order_count INT NOT NULL DEFAULT 0,
        is_new_client TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_sale_date (sale_date),
        INDEX idx_customer_name (customer_name),
        INDEX idx_customer_id (customer_id),
        INDEX idx_is_new_client (is_new_client),
        FOREIGN KEY (customer_id) REFERENCES clients(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if (!$conn->query($dailySalesTable)) {
        throw new Exception("Error creating daily_sales table: " . $conn->error);
    }
    
    // ===== UPLOAD HISTORY TABLE =====
    $uploadHistoryTable = "CREATE TABLE IF NOT EXISTS upload_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        upload_date DATE NOT NULL,
        new_clients INT DEFAULT 0,
        total_orders INT DEFAULT 0,
        uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_upload_date (upload_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if (!$conn->query($uploadHistoryTable)) {
        throw new Exception("Error creating upload_history table: " . $conn->error);
    }
    
    // Insert sample data if tables are empty
    insertSampleData($conn);
}

/**
 * Insert sample data if tables are empty
 */
function insertSampleData($conn) {
    // Check if clients table is empty
    $result = $conn->query("SELECT COUNT(*) as count FROM clients");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        // Insert sample clients
        $sampleClients = "INSERT INTO clients (customer_name, phone_number, sales_person, first_order_date) VALUES
            ('A KISITU AND COMPANY', '+256700123456', 'John Doe', '2024-11-20'),
            ('BMAXI GRAPHICS LTD', '+256700234567', 'Jane Smith', '2024-11-18'),
            ('EMAX SUPPLIES AND LOGISTICS', '+256700345678', 'John Doe', '2024-11-15'),
            ('TK VISION INVESTMENTS LTD', '+256700456789', 'Jane Smith', '2024-11-10'),
            ('GLOBAL TECH SOLUTIONS', '+256700567890', 'Mike Johnson', '2024-11-08'),
            ('UNITY TRADERS', '+256700678901', 'Sarah Williams', '2024-11-05')";
        
        $conn->query($sampleClients);
        
        // Insert sample daily sales
        $sampleSales = "INSERT INTO daily_sales (sale_date, customer_name, customer_id, order_count, is_new_client) VALUES
            ('2024-11-25', 'A KISITU AND COMPANY', 1, 1, 0),
            ('2024-11-25', 'BMAXI GRAPHICS LTD', 2, 9, 0),
            ('2024-11-25', 'EMAX SUPPLIES AND LOGISTICS', 3, 4, 0),
            ('2024-11-25', 'TK VISION INVESTMENTS LTD', 4, 2, 0),
            ('2024-11-24', 'A KISITU AND COMPANY', 1, 2, 0),
            ('2024-11-24', 'BMAXI GRAPHICS LTD', 2, 5, 0),
            ('2024-11-24', 'GLOBAL TECH SOLUTIONS', 5, 3, 0),
            ('2024-11-23', 'EMAX SUPPLIES AND LOGISTICS', 3, 3, 0),
            ('2024-11-23', 'UNITY TRADERS', 6, 6, 0),
            ('2024-11-22', 'TK VISION INVESTMENTS LTD', 4, 4, 0),
            ('2024-11-22', 'A KISITU AND COMPANY', 1, 1, 0),
            ('2024-11-21', 'BMAXI GRAPHICS LTD', 2, 7, 0),
            ('2024-11-21', 'GLOBAL TECH SOLUTIONS', 5, 2, 0),
            ('2024-11-20', 'EMAX SUPPLIES AND LOGISTICS', 3, 5, 0),
            ('2024-11-20', 'UNITY TRADERS', 6, 3, 0)";
        
        $conn->query($sampleSales);
        
        // Insert upload history
        $sampleHistory = "INSERT INTO upload_history (upload_date, new_clients, total_orders) VALUES
            ('2024-11-25', 0, 16),
            ('2024-11-24', 0, 10),
            ('2024-11-23', 1, 9),
            ('2024-11-22', 0, 5),
            ('2024-11-21', 1, 9),
            ('2024-11-20', 1, 8)";
        
        $conn->query($sampleHistory);
    }
}

/**
 * Get database connection
 * This function should be called at the start of your API file
 */
function getDbConnection() {
    return setupDatabase();
}

// If this file is called directly, setup the database
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    $conn = setupDatabase();
    
    if ($conn) {
        echo json_encode([
            'success' => true,
            'message' => 'Database setup completed successfully!',
            'database' => DB_NAME,
            'tables' => ['clients', 'daily_sales', 'upload_history']
        ]);
        $conn->close();
    }
}
?>