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
    case 'getClientActivity':
        getClientActivity($conn);
        break;
    case 'getCategories':
        getCategories($conn);
        break;
    case 'getSubCategories':
        getSubCategories($conn);
        break;
    case 'getClientCategories':
        getClientCategories($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getCategories($conn)
{
    $sql = "SELECT 
                c.*,
                (SELECT COUNT(*) FROM client_categorization WHERE parent_id = c.id) as has_children,
                (SELECT COUNT(DISTINCT ccm.client_id) 
                 FROM client_category_mapping ccm 
                 WHERE ccm.category_id = c.id 
                 OR ccm.category_id IN (SELECT id FROM client_categorization WHERE parent_id = c.id)
                ) as client_count
            FROM client_categorization c
            WHERE c.parent_id IS NULL
            ORDER BY c.display_order, c.category_name ASC";

    $result = $conn->query($sql);
    $categories = [];

    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    echo json_encode(['success' => true, 'categories' => $categories]);
}

function getSubCategories($conn)
{
    $parentId = intval($_GET['parentId']);

    $sql = "SELECT 
                c.*,
                (SELECT COUNT(DISTINCT client_id) FROM client_category_mapping WHERE category_id = c.id) as client_count
            FROM client_categorization c
            WHERE c.parent_id = $parentId
            ORDER BY c.display_order, c.category_name ASC";

    $result = $conn->query($sql);
    $subCategories = [];

    while ($row = $result->fetch_assoc()) {
        $subCategories[] = $row;
    }

    echo json_encode(['success' => true, 'subCategories' => $subCategories]);
}

$conn->close();

// ===== DASHBOARD FUNCTIONS =====

function getDashboardStats($conn)
{
    $today = date('Y-m-d');
    $firstDayOfMonth = date('Y-m-01');

    // Total clients
    $totalClients = $conn->query("SELECT COUNT(*) as count FROM clients")->fetch_assoc()['count'];

    // Today's orders (count of daily_sales records today)
    $todayOrders = $conn->query("SELECT COUNT(*) as count FROM daily_sales WHERE sale_date = '$today'")->fetch_assoc()['count'];

    // Total orders (all time)
    $totalOrders = $conn->query("SELECT COUNT(*) as count FROM daily_sales")->fetch_assoc()['count'];

    // Monthly reports (daily_reports this month)
    $monthlyReports = $conn->query("SELECT COUNT(*) as count FROM daily_reports WHERE report_date >= '$firstDayOfMonth'")->fetch_assoc()['count'];

    echo json_encode([
        'success' => true,
        'stats' => [
            'totalClients' => $totalClients,
            'todayOrders' => $todayOrders,
            'totalOrders' => $totalOrders,
            'monthlyReports' => $monthlyReports
        ]
    ]);
}

// ===== CLIENT FUNCTIONS =====

function getClients($conn)
{
    $categoryId = isset($_GET['categoryId']) ? intval($_GET['categoryId']) : null;

    $sql = "SELECT DISTINCT c.*, 
            (SELECT MAX(sale_date) FROM daily_sales WHERE client_id = c.id) as last_order_date,
            COALESCE((SELECT COUNT(*) FROM daily_sales WHERE client_id = c.id), 0) as total_orders,
            (SELECT GROUP_CONCAT(cat.category_name SEPARATOR ', ') 
             FROM client_category_mapping ccm 
             JOIN client_categorization cat ON ccm.category_id = cat.id 
             WHERE ccm.client_id = c.id) as categories
            FROM clients c";

    if ($categoryId !== null) {
        $sql .= " INNER JOIN client_category_mapping ccm ON c.id = ccm.client_id 
                  WHERE ccm.category_id = $categoryId";
    }

    $sql .= " ORDER BY c.client_name ASC";

    $result = $conn->query($sql);
    $clients = [];

    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }

    echo json_encode(['success' => true, 'clients' => $clients]);
}

function getClientCategories($conn)
{
    $clientId = intval($_GET['clientId']);

    $sql = "SELECT category_id FROM client_category_mapping WHERE client_id = $clientId";
    $result = $conn->query($sql);
    $categories = [];

    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category_id'];
    }

    echo json_encode(['success' => true, 'categories' => $categories]);
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

    // Build update query based on provided fields
    $updates = [];

    // Only update name and category if provided (supervisor only)
    if (isset($_POST['name'])) {
        $name = $conn->real_escape_string(trim($_POST['name']));
        $updates[] = "client_name = '$name'";
    }

    // Handle multiple categories
    if (isset($_POST['categories'])) {
        $categories = json_decode($_POST['categories'], true);

        // Delete existing mappings
        $conn->query("DELETE FROM client_category_mapping WHERE client_id = $id");

        // Insert new mappings
        if (!empty($categories)) {
            $values = [];
            foreach ($categories as $catId) {
                $catId = intval($catId);
                $values[] = "($id, $catId)";
            }
            if (!empty($values)) {
                $conn->query("INSERT INTO client_category_mapping (client_id, category_id) VALUES " . implode(',', $values));
            }
        }
    }

    // These can always be updated
    $contact = $conn->real_escape_string(trim($_POST['phone'] ?? ''));
    $address = $conn->real_escape_string(trim($_POST['address'] ?? ''));
    $salesPerson = $conn->real_escape_string(trim($_POST['salesPerson'] ?? ''));

    $updates[] = "contact = '$contact'";
    $updates[] = "address = '$address'";
    $updates[] = "sales_person = '$salesPerson'";

    if (!empty($updates)) {
        $sql = "UPDATE clients SET " . implode(', ', $updates) . " WHERE id = $id";
        $conn->query($sql);
    }

    echo json_encode(['success' => true, 'message' => 'Client updated successfully']);
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

function getClientActivity($conn)
{
    $clientId = intval($_GET['clientId']);

    $sql = "SELECT 
                ds.sale_date as date,
                'order' as type,
                'Order Placed' as title,
                CONCAT('Order details: ', COALESCE(ds.discussion, 'No details provided')) as description
            FROM daily_sales ds
            WHERE ds.client_id = $clientId
            
            UNION ALL
            
            SELECT 
                dr.report_date as date,
                'report' as type,
                CONCAT('Contact: ', IF(dr.method = 'M', 'Meeting', 'Phone Call')) as title,
                COALESCE(dr.discussion, 'No details provided') as description
            FROM daily_reports dr
            WHERE dr.client_id = $clientId
            
            ORDER BY date DESC
            LIMIT 10";

    $result = $conn->query($sql);
    $activities = [];

    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }

    echo json_encode(['success' => true, 'activities' => $activities]);
}