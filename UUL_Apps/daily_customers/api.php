<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'uul_user');
define('DB_PASS', 'uul@mysql123');
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
switch ($action) {
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

function getDashboardStats($conn)
{
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $lastMonth = date('Y-m-d', strtotime('-30 days'));
    $lastWeek = date('Y-m-d', strtotime('-7 days'));

    // Total clients
    $totalClients = $conn->query("SELECT COUNT(*) as count FROM clients")->fetch_assoc()['count'];

    // Total clients last month
    $clientsLastMonth = $conn->query("SELECT COUNT(*) as count FROM clients WHERE created_at <= '$lastMonth'")->fetch_assoc()['count'];
    $clientsChange = $clientsLastMonth > 0 ? round((($totalClients - $clientsLastMonth) / $clientsLastMonth) * 100, 1) : 0;

    // Today's orders (count of records)
    $todayOrders = $conn->query("SELECT COUNT(*) as count FROM daily_sales WHERE sale_date = '$today'")->fetch_assoc()['count'];

    // Yesterday's orders
    $yesterdayOrders = $conn->query("SELECT COUNT(*) as count FROM daily_sales WHERE sale_date = '$yesterday'")->fetch_assoc()['count'];
    $ordersChange = $yesterdayOrders > 0 ? round((($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100, 1) : 0;

    // Total orders
    $totalOrders = $conn->query("SELECT COUNT(*) as count FROM daily_sales")->fetch_assoc()['count'];

    // New clients today
    $newClients = $conn->query("SELECT COUNT(*) as count FROM clients WHERE DATE(created_at) = '$today'")->fetch_assoc()['count'];

    // New clients this week
    $weeklyNewClients = $conn->query("SELECT COUNT(*) as count FROM clients WHERE created_at >= '$lastWeek'")->fetch_assoc()['count'];

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

function getClients($conn)
{
    $sql = "SELECT c.*, 
            (SELECT MAX(sale_date) FROM daily_sales WHERE client_id = c.id) as last_order_date,
            COALESCE((SELECT COUNT(*) FROM daily_sales WHERE client_id = c.id), 0) as total_orders
            FROM clients c 
            ORDER BY c.client_name ASC";

    $result = $conn->query($sql);
    $clients = [];

    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }

    echo json_encode(['success' => true, 'clients' => $clients]);
}

function getClient($conn)
{
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM clients WHERE id = $id");

    if ($row = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'client' => $row]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Client not found']);
    }
}

function addClient($conn)
{
    $clientType = $conn->real_escape_string(trim($_POST['clientType'] ?? 'Regular'));
    $name = $conn->real_escape_string(trim($_POST['name']));
    $contact = $conn->real_escape_string(trim($_POST['phone'] ?? ''));
    $address = $conn->real_escape_string(trim($_POST['address'] ?? ''));
    $salesPerson = $conn->real_escape_string(trim($_POST['salesPerson'] ?? ''));

    // Check if client exists
    $check = $conn->query("SELECT id FROM clients WHERE LOWER(client_name) = LOWER('$name')");
    if ($check->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Client already exists']);
        return;
    }

    $sql = "INSERT INTO clients (client_type, client_name, contact, address, sales_person) 
            VALUES ('$clientType', '$name', '$contact', '$address', '$salesPerson')";

    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Client added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding client']);
    }
}

function updateClient($conn)
{
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string(trim($_POST['name']));
    $contact = $conn->real_escape_string(trim($_POST['phone'] ?? ''));
    $salesPerson = $conn->real_escape_string(trim($_POST['salesPerson'] ?? ''));

    $sql = "UPDATE clients 
            SET client_name = '$name', 
                contact = '$contact', 
                sales_person = '$salesPerson' 
            WHERE id = $id";

    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Client updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating client']);
    }
}

function deleteClient($conn)
{
    $id = intval($_GET['id']);

    // Related sales records will be deleted automatically due to ON DELETE CASCADE

    if ($conn->query("DELETE FROM clients WHERE id = $id")) {
        echo json_encode(['success' => true, 'message' => 'Client deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting client']);
    }
}

// ===== SALES FUNCTIONS =====

function uploadSales($conn)
{
    $data = json_decode($_POST['data'], true);

    if (!$data || !is_array($data)) {
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

        foreach ($data as $row) {
            $customerName = $conn->real_escape_string(trim($row['customer_name'] ?? $row['CUSTOMER_NAME'] ?? $row['Customer Name'] ?? ''));
            $method = $conn->real_escape_string(trim($row['method'] ?? $row['METHOD'] ?? $row['Method'] ?? 'C'));
            $discussion = $conn->real_escape_string(trim($row['discussion'] ?? $row['DISCUSSION'] ?? $row['Discussion'] ?? ''));
            $feedback = $conn->real_escape_string(trim($row['feedback'] ?? $row['FEEDBACK'] ?? $row['Feedback'] ?? ''));
            $salesValue = floatval($row['sales_value'] ?? $row['SALES_VALUE'] ?? $row['Sales Value'] ?? 0);

            if (empty($customerName)) continue;

            // Check if client exists
            $checkClient = $conn->query("SELECT id FROM clients WHERE LOWER(client_name) = LOWER('$customerName')");

            $isNewClient = false;
            $clientId = null;

            if ($checkClient->num_rows == 0) {
                // Add new client
                $conn->query("INSERT INTO clients (client_type, client_name, contact, address, sales_person) 
                             VALUES ('Regular', '$customerName', '', '', '')");
                $clientId = $conn->insert_id;
                $newClientsCount++;
                $isNewClient = true;
            } else {
                $clientId = $checkClient->fetch_assoc()['id'];
            }

            // Add daily sale
            $conn->query("INSERT INTO daily_sales (sale_date, client_id, method, discussion, feedback, sales_value) 
                         VALUES ('$today', $clientId, '$method', '$discussion', '$feedback', $salesValue)");

            $totalOrders++;
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
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error uploading data: ' . $e->getMessage()]);
    }
}

function getSalesHistory($conn)
{
    $dateFrom = $_GET['dateFrom'] ?? date('Y-m-d', strtotime('-30 days'));
    $dateTo = $_GET['dateTo'] ?? date('Y-m-d');

    $sql = "SELECT ds.*, c.client_name as customer_name,
            (SELECT COUNT(*) FROM daily_sales ds2 WHERE ds2.client_id = ds.client_id AND ds2.id < ds.id) as is_new_client
            FROM daily_sales ds
            LEFT JOIN clients c ON ds.client_id = c.id
            WHERE ds.sale_date BETWEEN '$dateFrom' AND '$dateTo'
            ORDER BY ds.sale_date DESC, c.client_name ASC";

    $result = $conn->query($sql);
    $sales = [];

    while ($row = $result->fetch_assoc()) {
        // is_new_client = 1 if this is the first sale for this client, 0 otherwise
        $row['is_new_client'] = ($row['is_new_client'] == 0) ? 1 : 0;
        $row['order_count'] = 1; // For compatibility with frontend
        $sales[] = $row;
    }

    echo json_encode(['success' => true, 'sales' => $sales]);
}

function getRecentSales($conn)
{
    $limit = intval($_GET['limit'] ?? 10);

    $sql = "SELECT ds.sale_date, c.client_name as customer_name, COUNT(*) as order_count
            FROM daily_sales ds
            LEFT JOIN clients c ON ds.client_id = c.id
            GROUP BY ds.sale_date, c.client_name
            ORDER BY ds.sale_date DESC, order_count DESC
            LIMIT $limit";

    $result = $conn->query($sql);
    $sales = [];

    while ($row = $result->fetch_assoc()) {
        $sales[] = $row;
    }

    echo json_encode(['success' => true, 'sales' => $sales]);
}

// ===== CHART FUNCTIONS =====

function getSalesChart($conn)
{
    $days = intval($_GET['days'] ?? 30);
    $startDate = date('Y-m-d', strtotime("-$days days"));

    $sql = "SELECT sale_date, COUNT(*) as total_orders
            FROM daily_sales 
            WHERE sale_date >= '$startDate'
            GROUP BY sale_date
            ORDER BY sale_date ASC";

    $result = $conn->query($sql);
    $labels = [];
    $values = [];

    while ($row = $result->fetch_assoc()) {
        $labels[] = date('M d', strtotime($row['sale_date']));
        $values[] = intval($row['total_orders']);
    }

    echo json_encode(['success' => true, 'labels' => $labels, 'values' => $values]);
}

function getTopClients($conn)
{
    $limit = intval($_GET['limit'] ?? 10);

    $sql = "SELECT c.client_name as customer_name, COUNT(*) as total_orders
            FROM daily_sales ds
            LEFT JOIN clients c ON ds.client_id = c.id
            GROUP BY c.client_name
            ORDER BY total_orders DESC
            LIMIT $limit";

    $result = $conn->query($sql);
    $labels = [];
    $values = [];

    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['customer_name'];
        $values[] = intval($row['total_orders']);
    }

    echo json_encode(['success' => true, 'labels' => $labels, 'values' => $values]);
}

function getSalesByPerson($conn)
{
    $sql = "SELECT 
                COALESCE(c.sales_person, 'Unassigned') as sales_person,
                COUNT(*) as total_orders
            FROM daily_sales ds
            LEFT JOIN clients c ON ds.client_id = c.id
            GROUP BY COALESCE(c.sales_person, 'Unassigned')
            ORDER BY total_orders DESC
            LIMIT 10";

    $result = $conn->query($sql);
    $labels = [];
    $values = [];

    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['sales_person'];
        $values[] = intval($row['total_orders']);
    }

    echo json_encode(['success' => true, 'labels' => $labels, 'values' => $values]);
}

// ===== UPLOAD HISTORY =====

function getUploadHistory($conn)
{
    $sql = "SELECT * FROM upload_history ORDER BY upload_date DESC LIMIT 20";
    $result = $conn->query($sql);
    $uploads = [];

    while ($row = $result->fetch_assoc()) {
        $uploads[] = $row;
    }

    echo json_encode(['success' => true, 'uploads' => $uploads]);
}
