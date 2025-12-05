<?php

/**
 * Stock Management System - Enhanced Database Handler with Checklist System
 * This file handles all database operations including the new checklist functionality
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

    private function createTables()
    {
        // Original products table
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

        // Checklist categories
        $sql = "
CREATE TABLE IF NOT EXISTS checklist_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    display_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_category (category_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $this->pdo->exec($sql);

        // Checklist items (master list)
        $sql = "
CREATE TABLE IF NOT EXISTS checklist_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    item_text TEXT NOT NULL,
    display_order INT NOT NULL DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES checklist_categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $this->pdo->exec($sql);

        // Inspection visits
        $sql = "
CREATE TABLE IF NOT EXISTS inspection_visits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    location VARCHAR(100) NOT NULL,
    inspector_name VARCHAR(255) NOT NULL,
    visit_date DATE NOT NULL,
    visit_time TIME NOT NULL,
    overall_notes TEXT,
    status VARCHAR(50) DEFAULT 'in_progress',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    INDEX idx_location (location),
    INDEX idx_visit_date (visit_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $this->pdo->exec($sql);

        // Inspection responses
        $sql = "
CREATE TABLE IF NOT EXISTS inspection_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visit_id INT NOT NULL,
    item_id INT NOT NULL,
    status VARCHAR(20) NOT NULL,
    notes TEXT,
    photo_path VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (visit_id) REFERENCES inspection_visits(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES checklist_items(id) ON DELETE CASCADE,
    INDEX idx_visit (visit_id),
    INDEX idx_item (item_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $this->pdo->exec($sql);

        // Action items (follow-ups)
        $sql = "
CREATE TABLE IF NOT EXISTS action_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    response_id INT NOT NULL,
    action_description TEXT NOT NULL,
    assigned_to VARCHAR(255),
    priority VARCHAR(20) DEFAULT 'medium',
    due_date DATE,
    status VARCHAR(50) DEFAULT 'open',
    resolution_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    FOREIGN KEY (response_id) REFERENCES inspection_responses(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_priority (priority),
    INDEX idx_due_date (due_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $this->pdo->exec($sql);

        // Insert default checklist categories and items
        $this->insertDefaultChecklist();
    }

    private function insertDefaultChecklist()
    {
        // Check if categories already exist
        $check = $this->pdo->query("SELECT COUNT(*) as count FROM checklist_categories")->fetch();
        if ($check['count'] > 0) {
            return; // Already populated
        }

        $categories = [
            ['name' => 'Safety and Security', 'order' => 1],
            ['name' => 'Employee Safety', 'order' => 2],
            ['name' => 'Hygiene and Cleanliness', 'order' => 3],
            ['name' => 'Emergency Preparedness', 'order' => 4],
            ['name' => 'Administration', 'order' => 5],
            ['name' => 'General', 'order' => 6]
        ];

        $items = [
            ['category' => 'Safety and Security', 'text' => 'Incident Register: Is an incident register maintained to record all accidents, near-misses, and security incidents?', 'order' => 1],
            ['category' => 'Safety and Security', 'text' => 'Security Systems: Are security systems, including motion sensors and cameras, installed and functional?', 'order' => 2],
            ['category' => 'Safety and Security', 'text' => 'Fire Alarm Systems: Are fire alarm systems installed and regularly tested?', 'order' => 3],
            ['category' => 'Safety and Security', 'text' => 'Power Backups: Are power backups available in case of outages?', 'order' => 4],
            ['category' => 'Employee Safety', 'text' => 'PPE for Employees: Are employees in stores provided with personal protective equipment (PPE) such as boots, gloves, and face masks?', 'order' => 1],
            ['category' => 'Employee Safety', 'text' => 'Reflector Jackets for Casuals: Are casual laborers provided with reflector jackets for visibility and safety?', 'order' => 2],
            ['category' => 'Hygiene and Cleanliness', 'text' => 'Food Handlers License: Do food handlers possess valid licenses and certifications?', 'order' => 1],
            ['category' => 'Hygiene and Cleanliness', 'text' => 'Regular Cleaning: Are premises regularly cleaned and maintained to prevent pest infestations and ensure a safe working environment?', 'order' => 2],
            ['category' => 'Emergency Preparedness', 'text' => 'First Aid Kit: Is a first aid kit available, stocked, and easily accessible?', 'order' => 1],
            ['category' => 'Administration', 'text' => 'Visitors Register Book: Is a visitors register book maintained to record all visitors?', 'order' => 1],
            ['category' => 'Administration', 'text' => 'Assets Register: Is an assets register maintained to track inventory, equipment, and other assets?', 'order' => 2],
            ['category' => 'General', 'text' => 'Adequate Lighting Systems: Are lighting systems adequate and functional to ensure a safe working environment?', 'order' => 1]
        ];

        // Insert categories and map IDs
        $categoryIds = [];
        foreach ($categories as $cat) {
            $stmt = $this->pdo->prepare("INSERT INTO checklist_categories (category_name, display_order) VALUES (?, ?)");
            $stmt->execute([$cat['name'], $cat['order']]);
            $categoryIds[$cat['name']] = $this->pdo->lastInsertId();
        }

        // Insert items
        foreach ($items as $item) {
            $stmt = $this->pdo->prepare("INSERT INTO checklist_items (category_id, item_text, display_order) VALUES (?, ?, ?)");
            $stmt->execute([$categoryIds[$item['category']], $item['text'], $item['order']]);
        }
    }

    // ========== CHECKLIST METHODS ==========

    public function getChecklistTemplate()
    {
        try {
            $sql = "SELECT 
                        c.id as category_id,
                        c.category_name,
                        c.display_order as category_order,
                        i.id as item_id,
                        i.item_text,
                        i.display_order as item_order
                    FROM checklist_categories c
                    LEFT JOIN checklist_items i ON c.id = i.category_id
                    WHERE i.is_active = TRUE
                    ORDER BY c.display_order, i.display_order";

            $stmt = $this->pdo->query($sql);
            $results = $stmt->fetchAll();

            // Group by category
            $checklist = [];
            foreach ($results as $row) {
                $catId = $row['category_id'];
                if (!isset($checklist[$catId])) {
                    $checklist[$catId] = [
                        'category_id' => $row['category_id'],
                        'category_name' => $row['category_name'],
                        'items' => []
                    ];
                }
                if ($row['item_id']) {
                    $checklist[$catId]['items'][] = [
                        'item_id' => $row['item_id'],
                        'item_text' => $row['item_text']
                    ];
                }
            }

            $this->sendSuccess("Checklist template retrieved successfully", array_values($checklist));
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    public function createVisit($data)
    {
        try {
            $sql = "INSERT INTO inspection_visits (location, inspector_name, visit_date, visit_time, overall_notes, status) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['location'],
                $data['inspector_name'],
                $data['visit_date'],
                $data['visit_time'],
                $data['overall_notes'] ?? null,
                'in_progress'
            ]);

            $visitId = $this->pdo->lastInsertId();
            $this->sendSuccess("Visit created successfully", ['visit_id' => $visitId]);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    public function saveResponse($data)
    {
        try {
            $sql = "INSERT INTO inspection_responses (visit_id, item_id, status, notes, photo_path) 
                    VALUES (?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE status = VALUES(status), notes = VALUES(notes), photo_path = VALUES(photo_path)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['visit_id'],
                $data['item_id'],
                $data['status'],
                $data['notes'] ?? null,
                $data['photo_path'] ?? null
            ]);

            $this->sendSuccess("Response saved successfully");
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    public function completeVisit($visitId)
    {
        try {
            $sql = "UPDATE inspection_visits SET status = 'completed', completed_at = CURRENT_TIMESTAMP WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$visitId]);

            $this->sendSuccess("Visit completed successfully");
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    public function getVisitHistory($location = null, $limit = 50)
    {
        try {
            if ($location) {
                $sql = "SELECT * FROM inspection_visits WHERE location = ? ORDER BY visit_date DESC, visit_time DESC LIMIT ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$location, $limit]);
            } else {
                $sql = "SELECT * FROM inspection_visits ORDER BY visit_date DESC, visit_time DESC LIMIT ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$limit]);
            }

            $visits = $stmt->fetchAll();
            $this->sendSuccess("Visit history retrieved successfully", $visits);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    public function getVisitDetails($visitId)
    {
        try {
            // Get visit info
            $sql = "SELECT * FROM inspection_visits WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$visitId]);
            $visit = $stmt->fetch();

            if (!$visit) {
                $this->sendError("Visit not found");
                return;
            }

            // Get responses with items
            $sql = "SELECT 
                        r.id as response_id,
                        r.status,
                        r.notes,
                        r.photo_path,
                        i.item_text,
                        c.category_name
                    FROM inspection_responses r
                    JOIN checklist_items i ON r.item_id = i.id
                    JOIN checklist_categories c ON i.category_id = c.id
                    WHERE r.visit_id = ?
                    ORDER BY c.display_order, i.display_order";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$visitId]);
            $responses = $stmt->fetchAll();

            $visit['responses'] = $responses;
            $this->sendSuccess("Visit details retrieved successfully", $visit);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    public function getActionItems($status = null)
    {
        try {
            if ($status) {
                $sql = "SELECT a.*, r.notes as inspection_notes, v.location, v.visit_date 
                        FROM action_items a
                        JOIN inspection_responses r ON a.response_id = r.id
                        JOIN inspection_visits v ON r.visit_id = v.id
                        WHERE a.status = ?
                        ORDER BY a.priority DESC, a.due_date ASC";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$status]);
            } else {
                $sql = "SELECT a.*, r.notes as inspection_notes, v.location, v.visit_date 
                        FROM action_items a
                        JOIN inspection_responses r ON a.response_id = r.id
                        JOIN inspection_visits v ON r.visit_id = v.id
                        ORDER BY a.status, a.priority DESC, a.due_date ASC";
                $stmt = $this->pdo->query($sql);
            }

            $actions = $stmt->fetchAll();
            $this->sendSuccess("Action items retrieved successfully", $actions);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    public function createActionItem($data)
    {
        try {
            $sql = "INSERT INTO action_items (response_id, action_description, assigned_to, priority, due_date, status) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['response_id'],
                $data['action_description'],
                $data['assigned_to'] ?? null,
                $data['priority'] ?? 'medium',
                $data['due_date'] ?? null,
                'open'
            ]);

            $this->sendSuccess("Action item created successfully", ['action_id' => $this->pdo->lastInsertId()]);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    public function updateActionItem($data)
    {
        try {
            $sql = "UPDATE action_items SET status = ?, resolution_notes = ?, resolved_at = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $resolvedAt = $data['status'] === 'resolved' ? date('Y-m-d H:i:s') : null;
            $stmt->execute([
                $data['status'],
                $data['resolution_notes'] ?? null,
                $resolvedAt,
                $data['action_id']
            ]);

            $this->sendSuccess("Action item updated successfully");
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    public function getComplianceStats($location = null)
    {
        try {
            $stats = [];

            // Recent visits
            $sql = "SELECT COUNT(*) as total FROM inspection_visits WHERE visit_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
            if ($location) {
                $sql .= " AND location = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$location]);
            } else {
                $stmt = $this->pdo->query($sql);
            }
            $stats['recent_visits'] = $stmt->fetch()['total'];

            // Open action items
            $sql = "SELECT COUNT(*) as total FROM action_items WHERE status = 'open'";
            $stats['open_actions'] = $this->pdo->query($sql)->fetch()['total'];

            // Overdue actions
            $sql = "SELECT COUNT(*) as total FROM action_items WHERE status = 'open' AND due_date < CURDATE()";
            $stats['overdue_actions'] = $this->pdo->query($sql)->fetch()['total'];

            // Compliance rate (last 30 days)
            $sql = "SELECT 
                        COUNT(CASE WHEN r.status = 'compliant' THEN 1 END) as compliant,
                        COUNT(*) as total
                    FROM inspection_responses r
                    JOIN inspection_visits v ON r.visit_id = v.id
                    WHERE v.visit_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
            if ($location) {
                $sql .= " AND v.location = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$location]);
            } else {
                $stmt = $this->pdo->query($sql);
            }
            $result = $stmt->fetch();
            $stats['compliance_rate'] = $result['total'] > 0 ? round(($result['compliant'] / $result['total']) * 100, 1) : 0;

            $this->sendSuccess("Compliance statistics retrieved successfully", $stats);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    // ========== ORIGINAL PRODUCT METHODS ==========

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
            }
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

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
                $data['update_notes'] ?? '',
                $data['update_notes'] ?? '',
                $data['update_notes'] ?? '',
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

    public function getDashboardStats()
    {
        try {
            $stats = [];
            $sql = "SELECT COUNT(*) as total FROM products";
            $stats['total_products'] = $this->pdo->query($sql)->fetch()['total'];

            $sql = "SELECT SUM(current_quantity) as total FROM products";
            $stats['total_quantity'] = $this->pdo->query($sql)->fetch()['total'] ?? 0;

            $sql = "SELECT COUNT(*) as total FROM products WHERE DATE_ADD(manufacture_date, INTERVAL 1 YEAR) < CURDATE()";
            $stats['expired_products'] = $this->pdo->query($sql)->fetch()['total'];

            $sql = "SELECT COUNT(*) as total FROM products WHERE DATE_ADD(manufacture_date, INTERVAL 1 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)";
            $stats['expiring_soon'] = $this->pdo->query($sql)->fetch()['total'];

            $this->sendSuccess("Dashboard statistics retrieved successfully", $stats);
        } catch (PDOException $e) {
            $this->sendError("Database error: " . $e->getMessage());
        }
    }

    private function sendSuccess($message, $data = null)
    {
        $response = ['success' => true, 'message' => $message];
        if ($data !== null) {
            $response['data'] = $data;
        }
        echo json_encode($response);
        exit;
    }

    private function sendError($message)
    {
        echo json_encode(['success' => false, 'error' => $message]);
        exit;
    }
}

// Initialize database
$db = new StockDatabase();

// Handle requests
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? $_POST['action'] ?? 'get_all';

try {
    switch ($method) {
        case 'GET':
            switch ($action) {
                case 'get_checklist':
                    $db->getChecklistTemplate();
                    break;
                case 'get_visit_history':
                    $location = $_GET['location'] ?? null;
                    $db->getVisitHistory($location);
                    break;
                case 'get_visit_details':
                    if (!isset($_GET['visit_id'])) throw new Exception("Visit ID required");
                    $db->getVisitDetails($_GET['visit_id']);
                    break;
                case 'get_action_items':
                    $status = $_GET['status'] ?? null;
                    $db->getActionItems($status);
                    break;
                case 'get_compliance_stats':
                    $location = $_GET['location'] ?? null;
                    $db->getComplianceStats($location);
                    break;
                case 'get_all':
                    $db->getAllProducts();
                    break;
                case 'get_stats':
                    $db->getDashboardStats();
                    break;
                default:
                    throw new Exception("Invalid action");
            }
            break;

        case 'POST':
            switch ($action) {
                case 'create_visit':
                    $data = $_POST;
                    $db->createVisit($data);
                    break;
                case 'save_response':
                    $data = $_POST;
                    $db->saveResponse($data);
                    break;
                case 'complete_visit':
                    if (!isset($_POST['visit_id'])) throw new Exception("Visit ID required");
                    $db->completeVisit($_POST['visit_id']);
                    break;
                case 'create_action':
                    $data = $_POST;
                    $db->createActionItem($data);
                    break;
                case 'update_action':
                    $data = $_POST;
                    $db->updateActionItem($data);
                    break;
                case 'update':
                    $data = $_POST;
                    $db->updateProduct($data);
                    break;
                case 'delete':
                    if (!isset($_POST['id'])) throw new Exception("Product ID required");
                    $db->deleteProduct($_POST['id']);
                    break;
                default:
                    $data = $_POST;
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
