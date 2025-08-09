<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Database configuration
$servername = "localhost";
$username = "fastnetug_user1";  // Replace with your database username
$password = "smartwatt@mysql123";  // Replace with your database password
$dbname = "fastnet_db";  // Replace with your database name

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get POST data
    $phone = $_POST['phone'] ?? '';
    $mac_address = $_POST['mac_address'] ?? '';
    $package_name = $_POST['package_name'] ?? '';
    $package_price = $_POST['package_price'] ?? '';
    $admin_id = $_POST['admin_id'] ?? '';
    
    // Validate required fields
    if (empty($phone) || empty($mac_address) || empty($package_name) || empty($package_price)) {
        echo json_encode([
            'success' => false, 
            'message' => 'Missing required fields'
        ]);
        exit();
    }
    
    // Validate phone number format (Uganda format)
    if (!preg_match('/^(0[7][0-9]{8}|256[7][0-9]{8})$/', $phone)) {
        echo json_encode([
            'success' => false, 
            'message' => 'Invalid phone number format'
        ]);
        exit();
    }
    
    // Normalize phone number to international format
    $normalized_phone = $phone;
    if (substr($phone, 0, 1) === '0') {
        $normalized_phone = '256' . substr($phone, 1);
    }
    
    // Generate unique request ID
    $request_id = 'REQ_' . strtoupper(uniqid());
    $created_at = date('Y-m-d H:i:s');
    $expires_at = date('Y-m-d H:i:s', strtotime('+24 hours')); // Request expires in 24 hours
    
    // --- Removed the blocking check for pending requests by MAC ---
    
    // Insert payment request into database
    $stmt = $pdo->prepare("
        INSERT INTO payment_requests 
        (request_id, phone, mac_address, package_name, package_price, admin_id, status, created_at, expires_at) 
        VALUES (?, ?, ?, ?, ?, ?, 'pending', ?, ?)
    ");
    
    $stmt->execute([
        $request_id,
        $normalized_phone,
        $mac_address,
        $package_name,
        $package_price,
        $admin_id,
        $created_at,
        $expires_at
    ]);
    
    // Send SMS notification to admin
    $admin_phone = '+256744766410';
    $approval_url = 'https://www.fastnetug.com/pages/approve_payment.php?request_id=' . $request_id;
    
    $sms_message = "NEW PAYMENT REQUEST\n" .
                   "ID: $request_id\n" .
                   "Phone: $normalized_phone\n" .
                   "MAC: $mac_address\n" .
                   "Package: $package_name\n" .
                   "Price: UGX $package_price\n" .
                   "Approve: $approval_url";
    
    // Send SMS using your preferred SMS service
    $sms_sent = sendSMS($admin_phone, $sms_message);
    
    // Log the request
    error_log("Payment request created: $request_id for phone: $normalized_phone, MAC: $mac_address");
    
    echo json_encode([
        'success' => true,
        'message' => 'Payment request initiated successfully',
        'request_id' => $request_id,
        'sms_sent' => $sms_sent
    ]);
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Database error occurred'
    ]);
} catch (Exception $e) {
    error_log("General error: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'An error occurred while processing your request'
    ]);
}

// Send SMS function
function sendSMS($phone, $message) {
    // Option 1: Using Africa's Talking SMS API
    return sendSMS_AfricasTalking($phone, $message);
}

/**
 * Africa's Talking SMS Implementation
 */
function sendSMS_AfricasTalking($phone, $message) {
    $username = 'agritech_info';  // Replace with your Africa's Talking username
    $apikey = 'atsk_1eb8e8aa4cf9f3851dabd1bf4490983972432730c57f36cfcf51980d3047884b7d19c9c3';     // Replace with your Africa's Talking API key
    
    $data = array(
        'username' => $username,
        'to' => $phone,
        'message' => $message
    );
    
    $url = 'https://api.africastalking.com/version1/messaging';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Content-Type: application/x-www-form-urlencoded',
        'apiKey: ' . $apikey
    ));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 201) {
        $result = json_decode($response, true);
        return isset($result['SMSMessageData']) && isset($result['SMSMessageData']['Recipients']);
    }
    
    return false;
}
