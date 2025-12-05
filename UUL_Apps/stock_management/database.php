<?php

/**
 * Complete Stock Management & Inspection System - Database Handler
 * This file handles all database operations for both stock management and inspection checklist
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

class UnifiedDatabase
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
     * Create all necessary tables
     */
    private function createTables()
    {
        // Products table for stock management
        $sql_products = "
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

        // Inspection locations table
        $sql_locations = "
        CREATE TABLE IF NOT EXISTS inspection_locations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            location_name VARCHAR(100) NOT NULL,
            location_type ENUM('warehouse', 'shop') NOT NULL,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        // Checklist categories table
        $sql_categories = "
        CREATE TABLE IF NOT EXISTS checklist_categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category_name VARCHAR(100) NOT NULL,
            display_order INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        // Checklist items table
        $sql_items = "
        CREATE TABLE IF NOT EXISTS checklist_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category_id INT NOT NULL,
            item_description TEXT NOT NULL,
            display_order INT NOT NULL,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES checklist_categories(id) ON DELETE CASCADE,
            INDEX idx_category (category_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        // Inspections table
        $sql_inspections = "
        CREATE TABLE IF NOT EXISTS inspections (
            id INT AUTO_INCREMENT PRIMARY KEY,
            location_id INT NOT NULL,
            inspector_name VARCHAR(100) NOT NULL,
            inspection_date DATE NOT NULL,
            overall_status ENUM('excellent', 'good', 'needs_improvement', 'critical') DEFAULT 'good',
            general_notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (location_id) REFERENCES inspection_locations(id) ON DELETE CASCADE,
            INDEX idx_location (location_id),
            INDEX idx_date (inspection_date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        // Inspection results table
        $sql_results = "
        CREATE TABLE IF NOT EXISTS inspection_results (
            id INT AUTO_INCREMENT PRIMARY KEY,
            inspection_id INT NOT NULL,
            item_id INT NOT NULL,
            status ENUM('compliant', 'non_compliant', 'na') NOT NULL,
            notes TEXT,
            photo_url VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (inspection_id) REFERENCES inspections(id) ON DELETE CASCADE,
            FOREIGN KEY (item_id) REFERENCES checklist_items(id) ON DELETE CASCADE,
            INDEX idx_inspection (inspection_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        // Follow-up actions table
        $sql_followup = "
        CREATE TABLE IF NOT EXISTS followup_actions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            inspection_result_id INT NOT NULL,
            action_required TEXT NOT NULL,
            assigned_to VARCHAR(100),
            priority ENUM('low', 'medium', 'high', 'critical') NOT NULL,
            due_date DATE NOT NULL,
            status ENUM('pending', 'in_progress', 'completed', 'overdue') DEFAULT 'pending',
            completion_date DATE,
            completion_notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (inspection_result_id) REFERENCES inspection_results(id) ON DELETE CASCADE,
            INDEX idx_status (status),
            INDEX idx_due_date (due_date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        // Execute all table creation queries
        $this->pdo->exec($sql_products);
        $this->pdo->exec($sql_locations);
        $this->pdo->exec($sql_categories);
        $this->pdo->exec($sql_items);
        $this->pdo->exec($sql_inspections);
        $this->pdo->exec($sql_results);
        $this->pdo->exec($sql_followup);

        // Insert default inspection data if tables are empty
        $this->insertDefaultInspectionData();
    }

    /**
     * Insert default inspection checklist items
     */
    private function insertDefaultInspectionData()
    {
        // Check if data already exists
        $count = $this->pdo->query("SELECT COUNT(*) as cnt FROM checklist_categories")->fetch()['cnt'];
        if ($count > 0) return;

        // Insert categories
        $categories = [
            ['Safety and Security', 1],
            ['Employee Safety', 2],
            ['Hygiene and Cleanliness', 3],
            ['Emergency Preparedness', 4],
            ['Administration', 5],
            ['General', 6]
        ];

        $stmt = $this->pdo->prepare("INSERT INTO checklist_categories (category_name, display_order) VALUES (?, ?)");
        foreach ($categories as $cat) {
            $stmt->execute($cat);
        }

        // Insert checklist items
        $items = [
            [1, 'Is an incident register maintained to record all accidents, near-misses, and security incidents?', 1],
            [1, 'Are security systems, including motion sensors and cameras, installed and functional?', 2],
            [1, 'Are fire alarm systems installed and regularly tested?', 3],
            [1, 'Are power backups available in case of outages?', 4],
            [2, 'Are employees in stores provided with personal protective equipment (PPE) such as boots, gloves, and face masks?', 1],
            [2, 'Are casual laborers provided with reflector jackets for visibility and safety?', 2],
            [3, 'Do food handlers possess valid licenses and certifications?', 1],
            [3, 'Are premises regularly cleaned and maintained to prevent pest infestations and ensure a safe working environment?', 2],
            [4, 'Is a first aid kit available, stocked, and easily accessible?', 1],
            [5, 'Is a visitors register book maintained to record all visitors?', 1],
            [5, 'Is an assets register maintained to track inventory, equipment, and other assets?', 2],
            [6, 'Are lighting systems adequate and functional to ensure a safe working environment?', 1]
        ];

        $stmt = $this->pdo->prepare("INSERT INTO checklist_items (category_id, item_description, display_order) VALUES (?, ?, ?)");
        foreach ($items as $item) {
            $stmt->execute($item);
        }

        // Insert default locations
        $locations = [
            ['Main Warehouse', 'warehouse'],
            ['Shop 08', 'shop']
        ];

        $stmt = $this->pdo->prepare("INSERT INTO inspection_locations (location_name, location_type) VALUES (?, ?)");
        foreach ($locations as $loc) {
            $stmt->execute($loc);
        }
    }

    // ==========================================
    // STOCK MANAGEMENT METHODS
    // ==========================================

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
     * Get stock dashboard statistics
     */
    public function getStockStats()
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

            $this->sendSuccess("Stock statistics retrieved successfully", $stats);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    // ==========================================
    // INSPECTION SYSTEM METHODS
    // ==========================================

    /**
     * Get all active locations
     */
    public function getLocations()
    {
        try {
            $sql = "SELECT * FROM inspection_locations WHERE is_active = 1 ORDER BY location_name";
            $stmt = $this->pdo->query($sql);
            $locations = $stmt->fetchAll();
            $this->sendSuccess("Locations retrieved successfully", $locations);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get checklist with categories and items
     */
    public function getChecklist()
    {
        try {
            $sql = "SELECT c.id, c.category_name, c.display_order,
                           i.id as item_id, i.item_description, i.display_order as item_order
                    FROM checklist_categories c
                    LEFT JOIN checklist_items i ON c.id = i.category_id AND i.is_active = 1
                    ORDER BY c.display_order, i.display_order";

            $stmt = $this->pdo->query($sql);
            $results = $stmt->fetchAll();

            // Group by category
            $checklist = [];
            foreach ($results as $row) {
                if (!isset($checklist[$row['id']])) {
                    $checklist[$row['id']] = [
                        'id' => $row['id'],
                        'category_name' => $row['category_name'],
                        'display_order' => $row['display_order'],
                        'items' => []
                    ];
                }
                if ($row['item_id']) {
                    $checklist[$row['id']]['items'][] = [
                        'id' => $row['item_id'],
                        'description' => $row['item_description'],
                        'display_order' => $row['item_order']
                    ];
                }
            }

            $this->sendSuccess("Checklist retrieved successfully", array_values($checklist));
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    /**
     * Create new inspection
     */
    public function createInspection($data)
    {
        try {
            $this->pdo->beginTransaction();

            // Insert inspection record
            $sql = "INSERT INTO inspections (location_id, inspector_name, inspection_date, overall_status, general_notes) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['location_id'],
                $data['inspector_name'],
                $data['inspection_date'],
                $data['overall_status'] ?? 'good',
                $data['general_notes'] ?? null
            ]);

            $inspectionId = $this->pdo->lastInsertId();

            // Insert inspection results
            if (isset($data['results']) && is_array($data['results'])) {
                $sql = "INSERT INTO inspection_results (inspection_id, item_id, status, notes) VALUES (?, ?, ?, ?)";
                $stmt = $this->pdo->prepare($sql);

                foreach ($data['results'] as $result) {
                    $stmt->execute([
                        $inspectionId,
                        $result['item_id'],
                        $result['status'],
                        $result['notes'] ?? null
                    ]);

                    $resultId = $this->pdo->lastInsertId();

                    // Create follow-up action if non-compliant
                    if ($result['status'] === 'non_compliant' && !empty($result['notes'])) {
                        $followupSql = "INSERT INTO followup_actions 
                                       (inspection_result_id, action_required, assigned_to, priority, due_date, status) 
                                       VALUES (?, ?, ?, ?, ?, 'pending')";
                        $followupStmt = $this->pdo->prepare($followupSql);
                        $followupStmt->execute([
                            $resultId,
                            $result['notes'],
                            $result['assigned_to'] ?? null,
                            $result['priority'] ?? 'medium',
                            $result['due_date'] ?? date('Y-m-d', strtotime('+7 days'))
                        ]);
                    }
                }
            }

            $this->pdo->commit();
            $this->sendSuccess("Inspection created successfully", ['inspection_id' => $inspectionId]);
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get inspection history for a location
     */
    public function getInspectionHistory($locationId)
    {
        try {
            $sql = "SELECT i.*, 
                           COUNT(ir.id) as total_items,
                           SUM(CASE WHEN ir.status = 'compliant' THEN 1 ELSE 0 END) as compliant_items,
                           SUM(CASE WHEN ir.status = 'non_compliant' THEN 1 ELSE 0 END) as non_compliant_items
                    FROM inspections i
                    LEFT JOIN inspection_results ir ON i.id = ir.inspection_id
                    WHERE i.location_id = ?
                    GROUP BY i.id
                    ORDER BY i.inspection_date DESC, i.created_at DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$locationId]);
            $inspections = $stmt->fetchAll();

            $this->sendSuccess("Inspection history retrieved successfully", $inspections);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get detailed inspection results
     */
    public function getInspectionDetails($inspectionId)
    {
        try {
            // Get inspection info
            $sql = "SELECT i.*, l.location_name, l.location_type
                    FROM inspections i
                    JOIN inspection_locations l ON i.location_id = l.id
                    WHERE i.id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$inspectionId]);
            $inspection = $stmt->fetch();

            if (!$inspection) {
                $this->sendError("Inspection not found");
            }

            // Get inspection results with follow-up actions
            $sql = "SELECT ir.*, ci.item_description, cc.category_name,
                           fa.id as action_id, fa.action_required, fa.assigned_to, 
                           fa.priority, fa.due_date, fa.status as action_status,
                           fa.completion_date, fa.completion_notes
                    FROM inspection_results ir
                    JOIN checklist_items ci ON ir.item_id = ci.id
                    JOIN checklist_categories cc ON ci.category_id = cc.id
                    LEFT JOIN followup_actions fa ON ir.id = fa.inspection_result_id
                    WHERE ir.inspection_id = ?
                    ORDER BY cc.display_order, ci.display_order";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$inspectionId]);
            $results = $stmt->fetchAll();

            $inspection['results'] = $results;

            $this->sendSuccess("Inspection details retrieved successfully", $inspection);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get all pending follow-up actions
     */
    public function getPendingActions($locationId = null)
    {
        try {
            $sql = "SELECT fa.*, ir.item_id, ci.item_description, 
                           i.inspection_date, i.inspector_name,
                           l.location_name, l.location_type
                    FROM followup_actions fa
                    JOIN inspection_results ir ON fa.inspection_result_id = ir.id
                    JOIN checklist_items ci ON ir.item_id = ci.id
                    JOIN inspections i ON ir.inspection_id = i.id
                    JOIN inspection_locations l ON i.location_id = l.id
                    WHERE fa.status IN ('pending', 'in_progress')";

            if ($locationId) {
                $sql .= " AND i.location_id = ?";
            }

            $sql .= " ORDER BY 
                     CASE fa.priority 
                         WHEN 'critical' THEN 1 
                         WHEN 'high' THEN 2 
                         WHEN 'medium' THEN 3 
                         ELSE 4 
                     END,
                     fa.due_date ASC";

            $stmt = $this->pdo->prepare($sql);
            if ($locationId) {
                $stmt->execute([$locationId]);
            } else {
                $stmt->execute();
            }
            $actions = $stmt->fetchAll();

            $this->sendSuccess("Pending actions retrieved successfully", $actions);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    /**
     * Update follow-up action status
     */
    public function updateFollowupAction($data)
    {
        try {
            $sql = "UPDATE followup_actions 
                    SET status = ?, 
                        completion_date = ?,
                        completion_notes = ?
                    WHERE id = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['status'],
                $data['status'] === 'completed' ? date('Y-m-d') : null,
                $data['completion_notes'] ?? null,
                $data['action_id']
            ]);

            $this->sendSuccess("Follow-up action updated successfully");
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get inspection dashboard statistics
     */
    public function getInspectionStats()
    {
        try {
            $stats = [];

            // Total inspections this month
            $sql = "SELECT COUNT(*) as total FROM inspections 
                    WHERE MONTH(inspection_date) = MONTH(CURDATE()) 
                    AND YEAR(inspection_date) = YEAR(CURDATE())";
            $stats['inspections_this_month'] = $this->pdo->query($sql)->fetch()['total'];

            // Pending actions
            $sql = "SELECT COUNT(*) as total FROM followup_actions WHERE status IN ('pending', 'in_progress')";
            $stats['pending_actions'] = $this->pdo->query($sql)->fetch()['total'];

            // Overdue actions
            $sql = "SELECT COUNT(*) as total FROM followup_actions 
                    WHERE status IN ('pending', 'in_progress') AND due_date < CURDATE()";
            $stats['overdue_actions'] = $this->pdo->query($sql)->fetch()['total'];

            // Average compliance rate
            $sql = "SELECT 
                        COUNT(CASE WHEN status = 'compliant' THEN 1 END) * 100.0 / NULLIF(COUNT(*), 0) as rate
                    FROM inspection_results ir
                    JOIN inspections i ON ir.inspection_id = i.id
                    WHERE MONTH(i.inspection_date) = MONTH(CURDATE()) 
                    AND YEAR(i.inspection_date) = YEAR(CURDATE())";
            $result = $this->pdo->query($sql)->fetch();
            $stats['compliance_rate'] = round($result['rate'] ?? 0, 1);

            $this->sendSuccess("Inspection statistics retrieved successfully", $stats);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    // ==========================================
    // UTILITY METHODS
    // ==========================================

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
}

// Initialize database connection
$db = new UnifiedDatabase();

// Determine which module to use based on URL parameter
$module = $_GET['module'] ?? 'stock';

// Handle different HTTP methods and actions
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? $_POST['action'] ?? 'get_all';

try {
    if ($module === 'inspection') {
        // INSPECTION MODULE ROUTES
        switch ($method) {
            case 'GET':
                switch ($action) {
                    case 'get_locations':
                        $db->getLocations();
                        break;
                    case 'get_checklist':
                        $db->getChecklist();
                        break;
                    case 'get_history':
                        if (!isset($_GET['location_id'])) {
                            throw new Exception("Location ID required");
                        }
                        $db->getInspectionHistory($_GET['location_id']);
                        break;
                    case 'get_details':
                        if (!isset($_GET['inspection_id'])) {
                            throw new Exception("Inspection ID required");
                        }
                        $db->getInspectionDetails($_GET['inspection_id']);
                        break;
                    case 'get_pending_actions':
                        $locationId = $_GET['location_id'] ?? null;
                        $db->getPendingActions($locationId);
                        break;
                    case 'get_stats':
                        $db->getInspectionStats();
                        break;
                    default:
                        throw new Exception("Invalid action for inspection module");
                }
                break;

            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;

                switch ($action) {
                    case 'create_inspection':
                        $db->createInspection($data);
                        break;
                    case 'update_action':
                        $db->updateFollowupAction($data);
                        break;
                    default:
                        throw new Exception("Invalid action for inspection module");
                }
                break;

            default:
                throw new Exception("Method not allowed");
        }
    } else {
        // STOCK MANAGEMENT MODULE ROUTES (default)
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
                    case 'get_stats':
                        $db->getStockStats();
                        break;
                    case 'search':
                        if (!isset($_GET['q'])) {
                            throw new Exception("Search query parameter required");
                        }
                        $db->searchProducts($_GET['q']);
                        break;
                    default:
                        throw new Exception("Invalid action for stock module");
                }
                break;

            case 'POST':
                switch ($action) {
                    case 'update':
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
                        $data = $_POST;
                        $required = ['product_category', 'product_name', 'offload_date', 'manufacture_date', 'consignment_track', 'initial_quantity'];

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
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
