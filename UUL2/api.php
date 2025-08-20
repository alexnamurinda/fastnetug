<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Database configuration
class Database
{
    private $host = 'localhost';
    private $db_name = 'uul_clients';
    private $username = 'root';
    private $password = 'Alex@mysql123';
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo json_encode(['success' => false, 'message' => 'Connection error: ' . $exception->getMessage()]);
            exit();
        }
        return $this->conn;
    }
}

// Client class
class Client
{
    private $conn;
    private $table_name = "clients";

    public $id;
    public $name;
    public $phone;
    public $email;
    public $company;
    public $category;
    public $address;
    public $notes;
    public $created_at;
    public $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all clients
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get clients by category
    public function getByCategory($category)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE category = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category);
        $stmt->execute();
        return $stmt;
    }

    // Search clients
    public function search($search_term)
    {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE name LIKE ? OR phone LIKE ? OR email LIKE ? OR company LIKE ? 
                  ORDER BY created_at DESC";
        $search_term = "%{$search_term}%";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $search_term);
        $stmt->bindParam(2, $search_term);
        $stmt->bindParam(3, $search_term);
        $stmt->bindParam(4, $search_term);
        $stmt->execute();
        return $stmt;
    }

    // Check if phone exists
    public function phoneExists($phone, $exclude_id = null)
    {
        $query = "SELECT id FROM " . $this->table_name . " WHERE phone = ?";
        if ($exclude_id) {
            $query .= " AND id != ?";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $phone);
        if ($exclude_id) {
            $stmt->bindParam(2, $exclude_id);
        }
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Create client
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . "
                  SET name=:name, phone=:phone, email=:email, company=:company, 
                      category=:category, address=:address, notes=:notes, created_at=NOW()";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->company = htmlspecialchars(strip_tags($this->company));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->notes = htmlspecialchars(strip_tags($this->notes));

        // Bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":company", $this->company);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":notes", $this->notes);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Update client
    public function update()
    {
        $query = "UPDATE " . $this->table_name . "
                  SET name=:name, phone=:phone, email=:email, company=:company,
                      category=:category, address=:address, notes=:notes, updated_at=NOW()
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->company = htmlspecialchars(strip_tags($this->company));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->notes = htmlspecialchars(strip_tags($this->notes));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":company", $this->company);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":notes", $this->notes);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // Delete client
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }

    // Get single client
    public function getOne()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->name = $row['name'];
            $this->phone = $row['phone'];
            $this->email = $row['email'];
            $this->company = $row['company'];
            $this->category = $row['category'];
            $this->address = $row['address'];
            $this->notes = $row['notes'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }
        return false;
    }

    // Get category statistics
    public function getCategoryStats()
    {
        // Get total count first
        $totalQuery = "SELECT COUNT(*) as total_count FROM " . $this->table_name;
        $totalStmt = $this->conn->prepare($totalQuery);
        $totalStmt->execute();
        $totalRow = $totalStmt->fetch(PDO::FETCH_ASSOC);

        // Get category counts
        $query = "SELECT category, COUNT(*) as count FROM " . $this->table_name . " GROUP BY category ORDER BY category";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Return both total and category data
        return [
            'total' => (int)$totalRow['total_count'],
            'categories' => $stmt
        ];
    }
}

// Message class for bulk messaging
class BulkMessage
{
    private $conn;
    private $table_name = "bulk_messages";

    public $id;
    public $message;
    public $category;
    public $recipient_count;
    public $sent_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Log bulk message
    public function logMessage()
    {
        $query = "INSERT INTO " . $this->table_name . "
                  SET message=:message, category=:category, recipient_count=:recipient_count, sent_at=NOW()";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->message = htmlspecialchars(strip_tags($this->message));
        $this->category = htmlspecialchars(strip_tags($this->category));

        // Bind values
        $stmt->bindParam(":message", $this->message);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":recipient_count", $this->recipient_count);

        return $stmt->execute();
    }
}

// Initialize database and client
$database = new Database();
$db = $database->getConnection();
$client = new Client($db);
$bulk_message = new BulkMessage($db);

// Get the action from URL or POST data
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Handle different API endpoints
switch ($action) {
    case 'get_clients':
        // Get all clients
        $stmt = $client->getAll();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $clients_arr = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $client_item = array(
                    "id" => $id,
                    "name" => $name,
                    "phone" => $phone,
                    "email" => $email,
                    "company" => $company,
                    "category" => $category,
                    "address" => $address,
                    "notes" => $notes,
                    "created_at" => $created_at,
                    "updated_at" => $updated_at
                );
                array_push($clients_arr, $client_item);
            }

            echo json_encode(['success' => true, 'clients' => $clients_arr]);
        } else {
            echo json_encode(['success' => true, 'clients' => []]);
        }
        break;

    case 'get_category_clients':
        // Get clients by category
        $category = isset($_GET['category']) ? $_GET['category'] : '';

        if (empty($category)) {
            echo json_encode(['success' => false, 'message' => 'Category is required']);
            break;
        }

        $stmt = $client->getByCategory($category);
        $num = $stmt->rowCount();

        if ($num > 0) {
            $clients_arr = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $client_item = array(
                    "id" => $id,
                    "name" => $name,
                    "phone" => $phone,
                    "email" => $email,
                    "company" => $company,
                    "category" => $category,
                    "address" => $address,
                    "notes" => $notes,
                    "created_at" => $created_at,
                    "updated_at" => $updated_at
                );
                array_push($clients_arr, $client_item);
            }

            echo json_encode(['success' => true, 'clients' => $clients_arr]);
        } else {
            echo json_encode(['success' => true, 'clients' => []]);
        }
        break;

    case 'search_clients':
        // Search clients
        $search_term = isset($_GET['q']) ? $_GET['q'] : '';

        if (empty($search_term)) {
            echo json_encode(['success' => false, 'message' => 'Search term is required']);
            break;
        }

        $stmt = $client->search($search_term);
        $num = $stmt->rowCount();

        if ($num > 0) {
            $clients_arr = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $client_item = array(
                    "id" => $id,
                    "name" => $name,
                    "phone" => $phone,
                    "email" => $email,
                    "company" => $company,
                    "category" => $category,
                    "address" => $address,
                    "notes" => $notes,
                    "created_at" => $created_at,
                    "updated_at" => $updated_at
                );
                array_push($clients_arr, $client_item);
            }

            echo json_encode(['success' => true, 'clients' => $clients_arr]);
        } else {
            echo json_encode(['success' => true, 'clients' => []]);
        }
        break;

    case 'add_client':
        // Add new client
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if (empty($data['name']) || empty($data['phone']) || empty($data['category'])) {
            echo json_encode(['success' => false, 'message' => 'Name, phone, and category are required']);
            break;
        }

        // Check if phone already exists
        if ($client->phoneExists($data['phone'])) {
            echo json_encode(['success' => false, 'message' => 'Phone number already exists']);
            break;
        }

        // Set client properties
        $client->name = $data['name'];
        $client->phone = $data['phone'];
        $client->email = isset($data['email']) ? $data['email'] : '';
        $client->company = isset($data['company']) ? $data['company'] : '';
        $client->category = $data['category'];
        $client->address = isset($data['address']) ? $data['address'] : '';
        $client->notes = isset($data['notes']) ? $data['notes'] : '';

        // Create client
        if ($client->create()) {
            echo json_encode(['success' => true, 'message' => 'Client created successfully', 'id' => $client->id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Unable to create client']);
        }
        break;

    case 'update_client':
        // Update client
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if (empty($data['id']) || empty($data['name']) || empty($data['phone']) || empty($data['category'])) {
            echo json_encode(['success' => false, 'message' => 'ID, name, phone, and category are required']);
            break;
        }

        // Check if phone already exists (excluding current client)
        if ($client->phoneExists($data['phone'], $data['id'])) {
            echo json_encode(['success' => false, 'message' => 'Phone number already exists']);
            break;
        }

        // Set client properties
        $client->id = $data['id'];
        $client->name = $data['name'];
        $client->phone = $data['phone'];
        $client->email = isset($data['email']) ? $data['email'] : '';
        $client->company = isset($data['company']) ? $data['company'] : '';
        $client->category = $data['category'];
        $client->address = isset($data['address']) ? $data['address'] : '';
        $client->notes = isset($data['notes']) ? $data['notes'] : '';

        // Update client
        if ($client->update()) {
            echo json_encode(['success' => true, 'message' => 'Client updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Unable to update client']);
        }
        break;

    case 'delete_client':
        // Delete client
        $client_id = isset($_GET['id']) ? $_GET['id'] : '';

        if (empty($client_id)) {
            echo json_encode(['success' => false, 'message' => 'Client ID is required']);
            break;
        }

        $client->id = $client_id;

        // Check if client exists
        if (!$client->getOne()) {
            echo json_encode(['success' => false, 'message' => 'Client not found']);
            break;
        }

        // Delete client
        if ($client->delete()) {
            echo json_encode(['success' => true, 'message' => 'Client deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Unable to delete client']);
        }
        break;

    case 'get_client':
        // Get single client
        $client_id = isset($_GET['id']) ? $_GET['id'] : '';

        if (empty($client_id)) {
            echo json_encode(['success' => false, 'message' => 'Client ID is required']);
            break;
        }

        $client->id = $client_id;

        if ($client->getOne()) {
            $client_arr = array(
                "id" => $client->id,
                "name" => $client->name,
                "phone" => $client->phone,
                "email" => $client->email,
                "company" => $client->company,
                "category" => $client->category,
                "address" => $client->address,
                "notes" => $client->notes,
                "created_at" => $client->created_at,
                "updated_at" => $client->updated_at
            );

            echo json_encode(['success' => true, 'client' => $client_arr]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Client not found']);
        }
        break;
    case 'get_stats':
        // Get category statistics with total first
        $statsData = $client->getCategoryStats();
        $stats = array();

        // Add total clients first
        $stats['total'] = $statsData['total'];

        // Add category stats
        while ($row = $statsData['categories']->fetch(PDO::FETCH_ASSOC)) {
            $stats[$row['category']] = (int)$row['count'];
        }

        echo json_encode(['success' => true, 'stats' => $stats]);
        break;

    case 'send_bulk_message':
        // Send bulk message
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate required fields
        if (empty($data['message']) || empty($data['clients'])) {
            echo json_encode(['success' => false, 'message' => 'Message and clients are required']);
            break;
        }

        $message = $data['message'];
        $category = isset($data['category']) ? $data['category'] : 'all';
        $clients_data = $data['clients'];

        // Log bulk message
        $bulk_message->message = $message;
        $bulk_message->category = $category;
        $bulk_message->recipient_count = count($clients_data);

        if ($bulk_message->logMessage()) {
            // In a real application, you would integrate with SMS/WhatsApp APIs here
            // For demo purposes, we'll just return success

            // Simulate sending messages
            $sent_count = 0;
            $failed_count = 0;

            foreach ($clients_data as $client_data) {
                // Simulate random success/failure (90% success rate)
                if (rand(1, 10) <= 9) {
                    $sent_count++;
                } else {
                    $failed_count++;
                }
            }

            echo json_encode([
                'success' => true,
                'message' => 'Bulk message processed successfully',
                'sent_count' => $sent_count,
                'failed_count' => $failed_count,
                'total_count' => count($clients_data)
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to log bulk message']);
        }
        break;

    case 'check_phone':
        // Check if phone number exists
        $phone = isset($_GET['phone']) ? $_GET['phone'] : '';
        $exclude_id = isset($_GET['exclude_id']) ? $_GET['exclude_id'] : null;

        if (empty($phone)) {
            echo json_encode(['success' => false, 'message' => 'Phone number is required']);
            break;
        }

        $exists = $client->phoneExists($phone, $exclude_id);
        echo json_encode(['success' => true, 'exists' => $exists]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action or no action specified']);
        break;
}

// Error handling function
function handleError($message)
{
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

// Validation functions
function validateEmail($email)
{
    return empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone)
{
    // Basic phone validation (you can customize this based on your needs)
    return preg_match('/^[\+]?[0-9\s\-\(\)]{10,}$/', $phone);
}

function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags(trim($input)));
}
