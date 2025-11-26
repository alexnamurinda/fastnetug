<?php
session_start();
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
switch($action) {
    case 'login':
        login($conn);
        break;
    case 'logout':
        logout($conn);
        break;
    case 'checkSession':
        checkSession($conn);
        break;
    case 'addSalesPerson':
        addSalesPerson($conn);
        break;
    case 'getSalesPersons':
        getSalesPersons($conn);
        break;
    case 'addReport':
        addReport($conn);
        break;
    case 'getMyReports':
        getMyReports($conn);
        break;
    case 'getAllReports':
        getAllReports($conn);
        break;
    case 'approveReport':
        approveReport($conn);
        break;
    case 'rejectReport':
        rejectReport($conn);
        break;
    case 'getReportStats':
        getReportStats($conn);
        break;
    case 'getSalesPersonPerformance':
        getSalesPersonPerformance($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

$conn->close();

// ===== AUTHENTICATION FUNCTIONS =====

function login($conn) {
    $passcode = $_POST['passcode'] ?? '';
    
    if(empty($passcode)) {
        echo json_encode(['success' => false, 'message' => 'Passcode is required']);
        return;
    }
    
    $hashedPasscode = hash('sha256', $passcode);
    $stmt = $conn->prepare("SELECT id, name, role FROM sales_persons WHERE passcode = ?");
    $stmt->bind_param("s", $hashedPasscode);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($row = $result->fetch_assoc()) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['name'];
        $_SESSION['user_role'] = $row['role'];
        
        echo json_encode([
            'success' => true,
            'user' => [
                'id' => $row['id'],
                'name' => $row['name'],
                'role' => $row['role']
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid passcode']);
    }
}

function logout($conn) {
    session_destroy();
    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
}

function checkSession($conn) {
    if(isset($_SESSION['user_id'])) {
        echo json_encode([
            'success' => true,
            'loggedIn' => true,
            'user' => [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'role' => $_SESSION['user_role']
            ]
        ]);
    } else {
        echo json_encode(['success' => true, 'loggedIn' => false]);
    }
}

// ===== SALES PERSON MANAGEMENT =====

function addSalesPerson($conn) {
    $name = $conn->real_escape_string(trim($_POST['name']));
    $passcode = $_POST['passcode'] ?? '';
    $role = $_POST['role'] ?? 'salesperson';
    
    if(empty($name) || empty($passcode)) {
        echo json_encode(['success' => false, 'message' => 'Name and passcode are required']);
        return;
    }
    
    // Check if name already exists
    $check = $conn->query("SELECT id FROM sales_persons WHERE name = '$name'");
    if($check->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Sales person already exists']);
        return;
    }
    
    $hashedPasscode = hash('sha256', $passcode);
    $stmt = $conn->prepare("INSERT INTO sales_persons (name, passcode, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $hashedPasscode, $role);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Sales person added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding sales person']);
    }
}

function getSalesPersons($conn) {
    $sql = "SELECT id, name, role, created_at FROM sales_persons ORDER BY name ASC";
    $result = $conn->query($sql);
    $persons = [];
    
    while($row = $result->fetch_assoc()) {
        $persons[] = $row;
    }
    
    echo json_encode(['success' => true, 'persons' => $persons]);
}

// ===== REPORT FUNCTIONS =====

function addReport($conn) {
    if(!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        return;
    }
    
    $reportDate = $_POST['reportDate'] ?? date('Y-m-d');
    $clientId = intval($_POST['clientId']);
    $method = $_POST['method'] ?? 'C';
    $discussion = $conn->real_escape_string($_POST['discussion'] ?? '');
    $feedback = $conn->real_escape_string($_POST['feedback'] ?? '');
    $salesPersonId = $_SESSION['user_id'];
    
    if(empty($clientId)) {
        echo json_encode(['success' => false, 'message' => 'Client is required']);
        return;
    }
    
    $stmt = $conn->prepare("INSERT INTO daily_reports (report_date, client_id, sales_person_id, method, discussion, feedback) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siisss", $reportDate, $clientId, $salesPersonId, $method, $discussion, $feedback);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Report added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding report']);
    }
}

function getMyReports($conn) {
    if(!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        return;
    }
    
    $salesPersonId = $_SESSION['user_id'];
    $dateFrom = $_GET['dateFrom'] ?? date('Y-m-d', strtotime('-30 days'));
    $dateTo = $_GET['dateTo'] ?? date('Y-m-d');
    $search = $_GET['search'] ?? '';
    
    $sql = "SELECT dr.*, 
            c.client_name, c.client_type, c.contact, c.address,
            sp.name as sales_person_name,
            approver.name as approved_by_name
            FROM daily_reports dr
            LEFT JOIN clients c ON dr.client_id = c.id
            LEFT JOIN sales_persons sp ON dr.sales_person_id = sp.id
            LEFT JOIN sales_persons approver ON dr.approved_by = approver.id
            WHERE dr.sales_person_id = $salesPersonId
            AND dr.report_date BETWEEN '$dateFrom' AND '$dateTo'";
    
    if(!empty($search)) {
        $search = $conn->real_escape_string($search);
        $sql .= " AND (c.client_name LIKE '%$search%' OR dr.discussion LIKE '%$search%' OR dr.feedback LIKE '%$search%')";
    }
    
    $sql .= " ORDER BY dr.report_date DESC, dr.created_at DESC";
    
    $result = $conn->query($sql);
    $reports = [];
    
    while($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
    
    echo json_encode(['success' => true, 'reports' => $reports]);
}

function getAllReports($conn) {
    if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'supervisor') {
        echo json_encode(['success' => false, 'message' => 'Access denied']);
        return;
    }
    
    $salesPersonId = $_GET['salesPersonId'] ?? null;
    $dateFrom = $_GET['dateFrom'] ?? date('Y-m-d', strtotime('-30 days'));
    $dateTo = $_GET['dateTo'] ?? date('Y-m-d');
    $status = $_GET['status'] ?? '';
    
    $sql = "SELECT dr.*, 
            c.client_name, c.client_type, c.contact, c.address,
            sp.name as sales_person_name,
            approver.name as approved_by_name
            FROM daily_reports dr
            LEFT JOIN clients c ON dr.client_id = c.id
            LEFT JOIN sales_persons sp ON dr.sales_person_id = sp.id
            LEFT JOIN sales_persons approver ON dr.approved_by = approver.id
            WHERE dr.report_date BETWEEN '$dateFrom' AND '$dateTo'";
    
    if($salesPersonId) {
        $sql .= " AND dr.sales_person_id = " . intval($salesPersonId);
    }
    
    if(!empty($status)) {
        $status = $conn->real_escape_string($status);
        $sql .= " AND dr.approved = '$status'";
    }
    
    $sql .= " ORDER BY dr.report_date DESC, dr.created_at DESC";
    
    $result = $conn->query($sql);
    $reports = [];
    
    while($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
    
    echo json_encode(['success' => true, 'reports' => $reports]);
}

function approveReport($conn) {
    if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'supervisor') {
        echo json_encode(['success' => false, 'message' => 'Access denied']);
        return;
    }
    
    $reportId = intval($_POST['reportId']);
    $supervisorId = $_SESSION['user_id'];
    
    $sql = "UPDATE daily_reports 
            SET approved = 'approved', 
                approved_by = $supervisorId, 
                approved_at = NOW() 
            WHERE id = $reportId";
    
    if($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Report approved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error approving report']);
    }
}

function rejectReport($conn) {
    if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'supervisor') {
        echo json_encode(['success' => false, 'message' => 'Access denied']);
        return;
    }
    
    $reportId = intval($_POST['reportId']);
    $supervisorId = $_SESSION['user_id'];
    
    $sql = "UPDATE daily_reports 
            SET approved = 'rejected', 
                approved_by = $supervisorId, 
                approved_at = NOW() 
            WHERE id = $reportId";
    
    if($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Report rejected successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error rejecting report']);
    }
}

// ===== STATISTICS & PERFORMANCE =====

function getReportStats($conn) {
    if(!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        return;
    }
    
    $salesPersonId = $_SESSION['user_id'];
    $today = date('Y-m-d');
    $thisMonth = date('Y-m-01');
    
    // Today's reports
    $todayReports = $conn->query("SELECT COUNT(*) as count FROM daily_reports WHERE sales_person_id = $salesPersonId AND report_date = '$today'")->fetch_assoc()['count'];
    
    // This month's reports
    $monthReports = $conn->query("SELECT COUNT(*) as count FROM daily_reports WHERE sales_person_id = $salesPersonId AND report_date >= '$thisMonth'")->fetch_assoc()['count'];
    
    // Pending approvals
    $pending = $conn->query("SELECT COUNT(*) as count FROM daily_reports WHERE sales_person_id = $salesPersonId AND approved = 'pending'")->fetch_assoc()['count'];
    
    // Approved this month
    $approved = $conn->query("SELECT COUNT(*) as count FROM daily_reports WHERE sales_person_id = $salesPersonId AND approved = 'approved' AND report_date >= '$thisMonth'")->fetch_assoc()['count'];
    
    echo json_encode([
        'success' => true,
        'stats' => [
            'todayReports' => $todayReports,
            'monthReports' => $monthReports,
            'pending' => $pending,
            'approved' => $approved
        ]
    ]);
}

function getSalesPersonPerformance($conn) {
    if(!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        return;
    }
    
    $role = $_SESSION['user_role'];
    $days = intval($_GET['days'] ?? 30);
    $startDate = date('Y-m-d', strtotime("-$days days"));
    
    if($role === 'supervisor') {
        // Show all sales persons
        $sql = "SELECT sp.name, COUNT(dr.id) as report_count
                FROM sales_persons sp
                LEFT JOIN daily_reports dr ON sp.id = dr.sales_person_id AND dr.report_date >= '$startDate'
                WHERE sp.role = 'salesperson'
                GROUP BY sp.id, sp.name
                ORDER BY report_count DESC";
    } else {
        // Show only own performance
        $salesPersonId = $_SESSION['user_id'];
        $sql = "SELECT DATE(report_date) as date, COUNT(*) as report_count
                FROM daily_reports
                WHERE sales_person_id = $salesPersonId AND report_date >= '$startDate'
                GROUP BY DATE(report_date)
                ORDER BY report_date ASC";
    }
    
    $result = $conn->query($sql);
    $labels = [];
    $values = [];
    
    while($row = $result->fetch_assoc()) {
        if($role === 'supervisor') {
            $labels[] = $row['name'];
        } else {
            $labels[] = date('M d', strtotime($row['date']));
        }
        $values[] = intval($row['report_count']);
    }
    
    echo json_encode(['success' => true, 'labels' => $labels, 'values' => $values]);
}

?>