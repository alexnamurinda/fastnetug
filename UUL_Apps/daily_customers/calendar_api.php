<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'database.php';

$conn = getDbConnection();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'get_companies':
            getCompanies($conn);
            break;
        
        case 'add_calendar':
            if ($method === 'POST') {
                addCalendar($conn);
            }
            break;
        
        case 'get_calendars':
            getCalendars($conn);
            break;
        
        case 'get_stats':
            getStats($conn);
            break;
        
        case 'delete_calendar':
            if ($method === 'POST') {
                deleteCalendar($conn);
            }
            break;
        
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();

function getCompanies($conn)
{
    $sql = "SELECT id, client_name, contact FROM clients ORDER BY client_name ASC";
    $result = $conn->query($sql);
    
    $companies = [];
    while ($row = $result->fetch_assoc()) {
        $companies[] = $row;
    }
    
    echo json_encode(['success' => true, 'companies' => $companies]);
}

function addCalendar($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);
    
    $recipient_name = $conn->real_escape_string($data['recipient_name']);
    $contact = $conn->real_escape_string($data['contact']);
    $company_name = $conn->real_escape_string($data['company_name']);
    $issue_date = $conn->real_escape_string($data['issue_date']);
    $other_comment = $conn->real_escape_string($data['other_comment'] ?? '');
    $company_id = !empty($data['company_id']) ? intval($data['company_id']) : null;
    
    // If company is new, add to clients table
    if ($company_id === null && !empty($company_name)) {
        $checkSql = "SELECT id FROM clients WHERE client_name = '$company_name' LIMIT 1";
        $checkResult = $conn->query($checkSql);
        
        if ($checkResult->num_rows > 0) {
            $row = $checkResult->fetch_assoc();
            $company_id = $row['id'];
        } else {
            $insertClient = "INSERT INTO clients (client_type, client_name, contact) 
                           VALUES ('Christmas Calendar Client', '$company_name', '$contact')";
            if ($conn->query($insertClient)) {
                $company_id = $conn->insert_id;
            }
        }
    }
    
    $sql = "INSERT INTO christmas_calendars 
            (recipient_name, contact, company_id, company_name, issue_date, other_comment) 
            VALUES ('$recipient_name', '$contact', " . 
            ($company_id ? $company_id : "NULL") . ", '$company_name', '$issue_date', '$other_comment')";
    
    if ($conn->query($sql)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Calendar distribution recorded successfully',
            'id' => $conn->insert_id
        ]);
    } else {
        throw new Exception('Error adding calendar: ' . $conn->error);
    }
}

function getCalendars($conn)
{
    $sql = "SELECT 
                cc.id,
                cc.recipient_name,
                cc.contact,
                cc.company_name,
                cc.issue_date,
                cc.other_comment,
                cc.created_at,
                c.client_type
            FROM christmas_calendars cc
            LEFT JOIN clients c ON cc.company_id = c.id
            ORDER BY cc.issue_date DESC, cc.created_at DESC";
    
    $result = $conn->query($sql);
    
    $calendars = [];
    while ($row = $result->fetch_assoc()) {
        $calendars[] = $row;
    }
    
    echo json_encode(['success' => true, 'calendars' => $calendars]);
}

function getStats($conn)
{
    $totalSql = "SELECT COUNT(*) as total FROM christmas_calendars";
    $totalResult = $conn->query($totalSql);
    $total = $totalResult->fetch_assoc()['total'];
    
    $companiesSql = "SELECT COUNT(DISTINCT company_id) as total FROM christmas_calendars WHERE company_id IS NOT NULL";
    $companiesResult = $conn->query($companiesSql);
    $companies = $companiesResult->fetch_assoc()['total'];
    
    $todaySql = "SELECT COUNT(*) as total FROM christmas_calendars WHERE issue_date = CURDATE()";
    $todayResult = $conn->query($todaySql);
    $today = $todayResult->fetch_assoc()['total'];
    
    $thisMonthSql = "SELECT COUNT(*) as total FROM christmas_calendars 
                     WHERE MONTH(issue_date) = MONTH(CURDATE()) 
                     AND YEAR(issue_date) = YEAR(CURDATE())";
    $thisMonthResult = $conn->query($thisMonthSql);
    $thisMonth = $thisMonthResult->fetch_assoc()['total'];
    
    echo json_encode([
        'success' => true,
        'stats' => [
            'total' => $total,
            'companies' => $companies,
            'today' => $today,
            'this_month' => $thisMonth
        ]
    ]);
}

function deleteCalendar($conn)
{
    $data = json_decode(file_get_contents('php://input'), true);
    $id = intval($data['id']);
    
    $sql = "DELETE FROM christmas_calendars WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
    } else {
        throw new Exception('Error deleting record: ' . $conn->error);
    }
}