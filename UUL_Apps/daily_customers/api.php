<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sales_dashboard');

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

$conn->set_charset('utf8mb4');

// Get action from request
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Route to appropriate handler
switch($action) {
    case 'getDashboardStats':
        getDashboardStats($conn);
        break;
    case 'getClients':
        getClients($conn);
        break;
    case 'getClient':
        getClient($conn);
        break;
    case 'addClient':
        addClient($conn);
        break;
    case 'updateClient':
        updateClient($conn);
        break;
    case 'deleteClient':
        deleteClient($conn);
        break;
    case 'uploadSales':
        uploadSales($conn);
        break;
    case 'getSalesHistory':
        getSalesHistory($conn);
        break;
    case 'getRecentSales':
        getRecentSales($conn);
        break;
    case 'getSalesChart':
        getSalesChart($conn);
        break;
    case 'getTopClients':
        getTopClients($conn);
        break;
    case 'getSalesByPerson':
        getSalesByPerson($conn);
        break;
    case 'getUploadHistory':
        getUploadHistory($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

$conn->close();

// ===== DASHBOARD FUNCTIONS =====

function getDashboardStats($conn) {
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $lastMonth = date('Y-m-d', strtotime('-30 days'));
    $lastWeek = date('Y-m-d', strtotime('-7 days'));
    
    // Total clients
    $totalClients = $conn->query("SELECT COUNT(*) as count FROM clients")->fetch_assoc()['count'];
    
    // Total clients last month
    $clientsLastMonth = $conn->query("SELECT COUNT(*) as count FROM clients WHERE first_order_date <= '$lastMonth'")->fetch_assoc()['count'];
    $clientsChange = $clientsLastMonth > 0 ? round((($totalClients - $clientsLastMonth) / $clientsLastMonth) * 100, 1) : 0;
    
    // Today's orders
    $todayOrders = $conn->query("SELECT COALESCE(SUM(order_count), 0) as count FROM daily_sales WHERE sale_date = '$today'")->fetch_assoc()['count'];
    
    // Yesterday's orders
    $yesterdayOrders = $conn->query("SELECT COALESCE(SUM(order_count), 0) as count FROM daily_sales WHERE sale_date = '$yesterday'")->fetch_assoc()['count'];
    $ordersChange = $yesterdayOrders > 0 ? round((($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100, 1) : 0;
    
    // Total orders
    $totalOrders = $conn->query("SELECT COALESCE(SUM(order_count), 0) as count FROM daily_sales")->fetch_assoc()['count'];
    
    // New clients today
    $newClients = $conn->query("SELECT COUNT(*) as count FROM clients WHERE first_order_date = '$today'")->fetch_assoc()['count'];
    
    // New clients this week
    $weeklyNewClients = $conn->query("SELECT COUNT(*) as count FROM clients WHERE first_order_date >= '$lastWeek'")->fetch_assoc()['count'];
    
    echo json_encode([
        'success' => true,
        'stats' => [
            'totalClients' => $totalClients,
            'todayOrders' => $todayOrders,
            'totalOrders' => $totalOrders,
            'newClients' => $newClients,
            'clientsChange' => $clientsChange,
            'ordersChange' => $ordersChange,
            'weeklyNewClients' => $weeklyNewClients
        ]
    ]);
}

// ===== CLIENT FUNCTIONS =====

function getClients($conn) {
    $sql = "SELECT c.*, 
            (SELECT MAX(sale_date) FROM daily_sales WHERE customer_name = c.customer_name) as last_order_date,
            COALESCE((SELECT SUM(order_count) FROM daily_sales WHERE customer_name = c.customer_name), 0) as total_orders
            FROM clients c 
            ORDER BY c.customer_name ASC";
    
    $result = $conn->query($sql);
    $clients = [];
    
    while($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
    
    echo json_encode(['success' => true, 'clients' => $clients]);
}

function getClient($conn) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM clients WHERE id = $id");
    
    if($row = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'client' => $row]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Client not found']);
    }
}

function addClient($conn) {
    $name = $conn->real_escape_string(trim($_POST['name']));
    $phone = $conn->real_escape_string(trim($_POST['phone'] ?? ''));
    $salesPerson = $conn->real_escape_string(trim($_POST['salesPerson'] ?? ''));
    $today = date('Y-m-d');
    
    // Check if client exists
    $check = $conn->query("SELECT id FROM clients WHERE LOWER(customer_name) = LOWER('$name')");
    if($check->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Client already exists']);
        return;
    }
    
    $sql = "INSERT INTO clients (customer_name, phone_number, sales_person, first_order_date) 
            VALUES ('$name', '$phone', '$salesPerson', '$today')";
    
    if($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Client added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding client']);
    }
}

function updateClient($conn) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string(trim($_POST['name']));
    $phone = $conn->real_escape_string(trim($_POST['phone'] ?? ''));
    $salesPerson = $conn->real_escape_string(trim($_POST['salesPerson'] ?? ''));
    
    $sql = "UPDATE clients 
            SET customer_name = '$name', 
                phone_number = '$phone', 
                sales_person = '$salesPerson' 
            WHERE id = $id";
    
    if($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Client updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating client']);
    }
}

function deleteClient($conn) {
    $id = intval($_GET['id']);
    
    // Also delete related sales records
    $conn->query("DELETE FROM daily_sales WHERE customer_id = $id");
    
    if($conn->query("DELETE FROM clients WHERE id = $id")) {
        echo json_encode(['success' => true, 'message' => 'Client deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting client']);
    }
}

// ===== SALES FUNCTIONS =====

function uploadSales($conn) {
    $data = json_decode($_POST['data'], true);
    
    if(!$data || !is_array($data)) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        return;
    }
    
    $today = date('Y-m-d');
    $newClientsCount = 0;
    $totalOrders = 0;
    
    $conn->begin_transaction();
    
    try {
        // Record upload
        $conn->query("INSERT INTO upload_history (upload_date, new_clients, total_orders) VALUES ('$today', 0, 0)");
        $uploadId = $conn->insert_id;
        
        foreach($data as $row) {
            $customerName = $conn->real_escape_string(trim($row['customer_name'] ?? $row['CUSTOMER_NAME'] ?? $row['Customer Name'] ?? ''));
            $count = intval($row['count'] ?? $row['COUNT'] ?? $row['Count'] ?? 0);
            
            if(empty($customerName) || $count <= 0) continue;
            
            // Check if client exists
            $checkClient = $conn->query("SELECT id FROM clients WHERE LOWER(customer_name) = LOWER('$customerName')");
            
            $isNewClient = false;
            $clientId = null;
            
            if($checkClient->num_rows == 0) {
                // Add new client
                $conn->query("INSERT INTO clients (customer_name, phone_number, sales_person, first_order_date) 
                             VALUES ('$customerName', '', '', '$today')");
                $clientId = $conn->insert_id;
                $newClientsCount++;
                $isNewClient = true;
            } else {
                $clientId = $checkClient->fetch_assoc()['id'];
            }
            
            // Add daily sale
            $isNewInt = $isNewClient ? 1 : 0;
            $conn->query("INSERT INTO daily_sales (sale_date, customer_name, customer_id, order_count, is_new_client) 
                         VALUES ('$today', '$customerName', $clientId, $count, $isNewInt)");
            
            $totalOrders += $count;
        }
        
        // Update upload history
        $conn->query("UPDATE upload_history SET new_clients = $newClientsCount, total_orders = $totalOrders WHERE id = $uploadId");
        
        $conn->commit();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Data uploaded successfully',
            'newClients' => $newClientsCount,
            'totalOrders' => $totalOrders
        ]);
        
    } catch(Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error uploading data: ' . $e->getMessage()]);
    }
}

function getSalesHistory($conn) {
    $dateFrom = $_GET['dateFrom'] ?? date('Y-m-d', strtotime('-30 days'));
    $dateTo = $_GET['dateTo'] ?? date('Y-m-d');
    
    $sql = "SELECT * FROM daily_sales 
            WHERE sale_date BETWEEN '$dateFrom' AND '$dateTo'
            ORDER BY sale_date DESC, customer_name ASC";
    
    $result = $conn->query($sql);
    $sales = [];
    
    while($row = $result->fetch_assoc()) {
        $sales[] = $row;
    }
    
    echo json_encode(['success' => true, 'sales' => $sales]);
}

function getRecentSales($conn) {
    $limit = intval($_GET['limit'] ?? 10);
    
    $sql = "SELECT sale_date, customer_name, SUM(order_count) as order_count
            FROM daily_sales 
            GROUP BY sale_date, customer_name
            ORDER BY sale_date DESC, order_count DESC
            LIMIT $limit";
    
    $result = $conn->query($sql);
    $sales = [];
    
    while($row = $result->fetch_assoc()) {
        $sales[] = $row;
    }
    
    echo json_encode(['success' => true, 'sales' => $sales]);
}

// ===== CHART FUNCTIONS =====

function getSalesChart($conn) {
    $days = intval($_GET['days'] ?? 30);
    $startDate = date('Y-m-d', strtotime("-$days days"));
    
    $sql = "SELECT sale_date, SUM(order_count) as total_orders
            FROM daily_sales 
            WHERE sale_date >= '$startDate'
            GROUP BY sale_date
            ORDER BY sale_date ASC";
    
    $result = $conn->query($sql);
    $labels = [];
    $values = [];
    
    while($row = $result->fetch_assoc()) {
        $labels[] = date('M d', strtotime($row['sale_date']));
        $values[] = intval($row['total_orders']);
    }
    
    echo json_encode(['success' => true, 'labels' => $labels, 'values' => $values]);
}

function getTopClients($conn) {
    $limit = intval($_GET['limit'] ?? 10);
    
    $sql = "SELECT customer_name, SUM(order_count) as total_orders
            FROM daily_sales 
            GROUP BY customer_name
            ORDER BY total_orders DESC
            LIMIT $limit";
    
    $result = $conn->query($sql);
    $labels = [];
    $values = [];
    
    while($row = $result->fetch_assoc()) {
        $labels[] = $row['customer_name'];
        $values[] = intval($row['total_orders']);
    }
    
    echo json_encode(['success' => true, 'labels' => $labels, 'values' => $values]);
}

function getSalesByPerson($conn) {
    $sql = "SELECT 
                COALESCE(c.sales_person, 'Unassigned') as sales_person,
                SUM(ds.order_count) as total_orders
            FROM daily_sales ds
            LEFT JOIN clients c ON ds.customer_id = c.id
            GROUP BY COALESCE(c.sales_person, 'Unassigned')
            ORDER BY total_orders DESC
            LIMIT 10";
    
    $result = $conn->query($sql);
    $labels = [];
    $values = [];
    
    while($row = $result->fetch_assoc()) {
        $labels[] = $row['sales_person'];
        $values[] = intval($row['total_orders']);
    }
    
    echo json_encode(['success' => true, 'labels' => $labels, 'values' => $values]);
}

// ===== UPLOAD HISTORY =====

function getUploadHistory($conn) {
    $sql = "SELECT * FROM upload_history ORDER BY upload_date DESC LIMIT 20";
    $result = $conn->query($sql);
    $uploads = [];
    
    while($row = $result->fetch_assoc()) {
        $uploads[] = $row;
    }
    
    echo json_encode(['success' => true, 'uploads' => $uploads]);
}

?>