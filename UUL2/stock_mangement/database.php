<?php

/**
 * Stock Management System - Database Handler
 * This file handles all database operations for the stock management system
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'stock_management');
define('DB_USER', 'uul_user');
define('DB_PASS', 'uul@mysql123');

// Set content type to JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

class StockDatabase
{
    private $pdo;

    public function __construct()
    {
        try {
            // First, create database if it doesn't exist
            $this->createDatabase();

            $this->pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
            $this->createTables();
        } catch (PDOException $e) {
            $this->sendError("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Create database if it doesn't exist
     */
    private function createDatabase()
    {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        } catch (PDOException $e) {
            throw new PDOException("Failed to create database: " . $e->getMessage());
        }
    }

    /**
     * Create necessary tables if they don't exist
     */
    private function createTables()
    {
        $sql = "
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_category VARCHAR(100) NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    offload_date DATE NOT NULL,
    manufacture_date DATE NOT NULL,
    expiry_date DATE NULL,
    consignment_track VARCHAR(100) NOT NULL,
    initial_quantity INT NOT NULL DEFAULT 0,
    current_quantity INT NOT NULL DEFAULT 0,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (product_category),
    INDEX idx_offload_date (offload_date),
    INDEX idx_expiry_date (expiry_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->pdo->exec($sql);
    }

    /**
     * Add a new product to the database
     */
    public function addProduct($data)
    {
        try {
            $sql = "INSERT INTO products 
        (product_category, product_name, offload_date, manufacture_date, 
         expiry_date, consignment_track, initial_quantity, current_quantity, notes) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                $data['product_category'],
                $data['product_name'],
                $data['offload_date'],
                $data['manufacture_date'],
                !empty($data['expiry_date']) ? $data['expiry_date'] : null,
                $data['consignment_track'],
                $data['initial_quantity'],
                $data['initial_quantity'],
                $data['notes'] ?? null
            ]);

            if ($result) {
                $this->sendSuccess("Product added successfully", ['id' => $this->pdo->lastInsertId()]);
            } else {
                $this->sendError("Failed to add product");
            }
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get all products from the database
     */
    public function getAllProducts()
    {
        try {
            $sql = "SELECT * FROM products ORDER BY created_at DESC";
            $stmt = $this->pdo->query($sql);
            $products = $stmt->fetchAll();

            $this->sendSuccess("Products retrieved successfully", $products);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    /**
     * Update product quantity
     */
    public function updateProduct($data)
    {
        try {
            $sql = "UPDATE products 
                    SET current_quantity = ?, 
                        notes = CASE 
                            WHEN ? IS NOT NULL AND ? != '' 
                            THEN CONCAT(COALESCE(notes, ''), '\n[', NOW(), '] ', ?) 
                            ELSE notes 
                        END,
                        last_updated = CURRENT_TIMESTAMP 
                    WHERE id = ?";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                $data['current_quantity'],
                $data['update_notes'],
                $data['update_notes'],
                $data['update_notes'],
                $data['id']
            ]);

            if ($result && $stmt->rowCount() > 0) {
                $this->sendSuccess("Product updated successfully");
            } else {
                $this->sendError("Product not found or no changes made");
            }
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    /**
     * Delete a product
     */
    public function deleteProduct($id)
    {
        try {
            $sql = "DELETE FROM products WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$id]);

            if ($result && $stmt->rowCount() > 0) {
                $this->sendSuccess("Product deleted successfully");
            } else {
                $this->sendError("Product not found");
            }
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get products by category
     */
    public function getProductsByCategory($category)
    {
        try {
            $sql = "SELECT * FROM products WHERE product_category = ? ORDER BY created_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$category]);
            $products = $stmt->fetchAll();

            $this->sendSuccess("Products retrieved successfully", $products);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get expired products (assuming 1 year shelf life)
     */
    public function getExpiredProducts()
    {
        try {
            $sql = "SELECT * FROM products 
                    WHERE DATE_ADD(manufacture_date, INTERVAL 1 YEAR) < CURDATE()
                    ORDER BY manufacture_date ASC";
            $stmt = $this->pdo->query($sql);
            $products = $stmt->fetchAll();

            $this->sendSuccess("Expired products retrieved successfully", $products);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get products expiring soon (within 30 days)
     */
    public function getExpiringSoonProducts()
    {
        try {
            $sql = "SELECT * FROM products 
                    WHERE DATE_ADD(manufacture_date, INTERVAL 1 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                    ORDER BY manufacture_date ASC";
            $stmt = $this->pdo->query($sql);
            $products = $stmt->fetchAll();

            $this->sendSuccess("Products expiring soon retrieved successfully", $products);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get slow-moving products (in stock for 90+ days)
     */
    public function getSlowMovingProducts()
    {
        try {
            $sql = "SELECT * FROM products 
                    WHERE DATEDIFF(CURDATE(), offload_date) >= 90
                    ORDER BY offload_date ASC";
            $stmt = $this->pdo->query($sql);
            $products = $stmt->fetchAll();

            $this->sendSuccess("Slow moving products retrieved successfully", $products);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        try {
            $stats = [];

            // Total products
            $sql = "SELECT COUNT(*) as total FROM products";
            $stats['total_products'] = $this->pdo->query($sql)->fetch()['total'];

            // Total quantity
            $sql = "SELECT SUM(current_quantity) as total FROM products";
            $stats['total_quantity'] = $this->pdo->query($sql)->fetch()['total'] ?? 0;

            // Expired products
            $sql = "SELECT COUNT(*) as total FROM products WHERE DATE_ADD(manufacture_date, INTERVAL 1 YEAR) < CURDATE()";
            $stats['expired_products'] = $this->pdo->query($sql)->fetch()['total'];

            // Expiring soon
            $sql = "SELECT COUNT(*) as total FROM products WHERE DATE_ADD(manufacture_date, INTERVAL 1 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)";
            $stats['expiring_soon'] = $this->pdo->query($sql)->fetch()['total'];

            // Slow moving
            $sql = "SELECT COUNT(*) as total FROM products WHERE DATEDIFF(CURDATE(), offload_date) >= 90";
            $stats['slow_moving'] = $this->pdo->query($sql)->fetch()['total'];

            $this->sendSuccess("Dashboard statistics retrieved successfully", $stats);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    /**
     * Search products
     */
    public function searchProducts($searchTerm)
    {
        try {
            $sql = "SELECT * FROM products 
                    WHERE product_name LIKE ? 
                       OR product_category LIKE ? 
                       OR consignment_track LIKE ?
                    ORDER BY created_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $searchPattern = "%$searchTerm%";
            $stmt->execute([$searchPattern, $searchPattern, $searchPattern]);
            $products = $stmt->fetchAll();

            $this->sendSuccess("Search results retrieved successfully", $products);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    /**
     * Send success response
     */
    private function sendSuccess($message, $data = null)
    {
        $response = ['success' => true, 'message' => $message];
        if ($data !== null) {
            $response['data'] = $data;
        }
        echo json_encode($response);
        exit;
    }

    /**
     * Send error response
     */
    private function sendError($message)
    {
        echo json_encode(['success' => false, 'error' => $message]);
        exit;
    }

    /**
     * Validate required fields
     */
    private function validateRequired($data, $fields)
    {
        foreach ($fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $this->sendError("Missing required field: $field");
            }
        }
    }
}

// Initialize database connection
$db = new StockDatabase();

// Handle different HTTP methods and actions
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? $_POST['action'] ?? 'get_all';

try {
    switch ($method) {
        case 'GET':
            switch ($action) {
                case 'get_all':
                    $db->getAllProducts();
                    break;
                case 'get_by_category':
                    if (!isset($_GET['category'])) {
                        throw new Exception("Category parameter required");
                    }
                    $db->getProductsByCategory($_GET['category']);
                    break;
                case 'get_expired':
                    $db->getExpiredProducts();
                    break;
                case 'get_expiring_soon':
                    $db->getExpiringSoonProducts();
                    break;
                case 'get_slow_moving':
                    $db->getSlowMovingProducts();
                    break;
                case 'get_stats':
                    $db->getDashboardStats();
                    break;
                case 'search':
                    if (!isset($_GET['q'])) {
                        throw new Exception("Search query parameter required");
                    }
                    $db->searchProducts($_GET['q']);
                    break;
                default:
                    throw new Exception("Invalid action");
            }
            break;

        case 'POST':
            switch ($action) {
                case 'update':
                    $required = ['id', 'current_quantity'];
                    $data = $_POST;
                    $db->updateProduct($data);
                    break;
                case 'delete':
                    if (!isset($_POST['id'])) {
                        throw new Exception("Product ID required");
                    }
                    $db->deleteProduct($_POST['id']);
                    break;
                default:
                    // Default POST action is to add product
                    $required = ['product_category', 'product_name', 'offload_date', 'manufacture_date', 'consignment_track', 'initial_quantity'];
                    $data = $_POST;

                    // Validate required fields
                    foreach ($required as $field) {
                        if (!isset($data[$field]) || empty($data[$field])) {
                            throw new Exception("Missing required field: $field");
                        }
                    }

                    // Validate dates
                    if (!strtotime($data['offload_date']) || !strtotime($data['manufacture_date'])) {
                        throw new Exception("Invalid date format");
                    }

                    // Validate quantity
                    if (!is_numeric($data['initial_quantity']) || $data['initial_quantity'] < 1) {
                        throw new Exception("Initial quantity must be a positive number");
                    }

                    $db->addProduct($data);
                    break;
            }
            break;

        default:
            throw new Exception("Method not allowed");
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
