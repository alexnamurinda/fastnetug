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
    $package = $_POST['package'] ?? '';
    $price = $_POST['price'] ?? '';

    // Validate required fields
    if (empty($phone) || empty($mac_address) || empty($package) || empty($price)) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required fields: phone, mac_address, package, price'
        ]);
        exit();
    }

    // Validate phone number format (Uganda format)
    if (!preg_match('/^(0[7][0-9]{8}|256[7][0-9]{8})$/', $phone)) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid phone number format. Use 07xxxxxxxx or 2567xxxxxxxx'
        ]);
        exit();
    }

    // Normalize phone number to international format
    $normalized_phone = $phone;
    if (substr($phone, 0, 1) === '0') {
        $normalized_phone = '256' . substr($phone, 1);
    }

    // Normalize and validate MAC address
    $mac_address = normalizeMAC($mac_address);
    if (!$mac_address) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid MAC address format'
        ]);
        exit();
    }

    // Validate price is numeric
    if (!is_numeric($price) || $price <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid price value'
        ]);
        exit();
    }

    // Check for existing pending request for this MAC address
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM voucher_requests 
        WHERE mac_address = ? AND status = 'pending'
    ");
    $stmt->execute([$mac_address]);
    $existing_requests = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    if ($existing_requests > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'You already have a pending payment request. Please wait for approval or contact support.'
        ]);
        exit();
    }

    // Generate unique request ID
    $request_id = 'REQ_' . strtoupper(uniqid());
    $created_at = date('Y-m-d H:i:s');
    $expires_at = date('Y-m-d H:i:s', strtotime('+24 hours')); // Request expires in 24 hours

    // Insert voucher request into database
    $stmt = $pdo->prepare("
        INSERT INTO voucher_requests 
        (request_id, phone, mac_address, package, price, status, created_at, expires_at) 
        VALUES (?, ?, ?, ?, ?, 'pending', ?, ?)
    ");

    $stmt->execute([
        $request_id,
        $normalized_phone,
        $mac_address,
        $package,
        $price,
        $created_at,
        $expires_at
    ]);

    // Log the request in system_logs
    $stmt = $pdo->prepare("
        INSERT INTO system_logs 
        (log_type, reference_id, user_identifier, action_description, ip_address, user_agent) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    $action_description = "New voucher request created - Package: $package, Price: UGX $price, MAC: $mac_address";
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? null;
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    
    $stmt->execute([
        'voucher_request',
        $request_id,
        $normalized_phone,
        $action_description,
        $ip_address,
        $user_agent
    ]);

    // Send SMS notification to admin
    $admin_phone = '+256744766410';
    $approval_url = 'https://www.fastnetug.com/pages/approve_payment.php?request_id=' . $request_id;

    $sms_message = "NEW PAYMENT REQUEST\n" .
        "ID: $request_id\n" .
        "Phone: $normalized_phone\n" .
        "MAC: $mac_address\n" .
        "Package: $package\n" .
        "Price: UGX $price\n" .
        "Approve: $approval_url";

    // Send SMS using your preferred SMS service
    $sms_sent = sendSMS($admin_phone, $sms_message);

    // Log the SMS attempt
    $sms_log_description = $sms_sent ? 
        "SMS notification sent to admin successfully" : 
        "Failed to send SMS notification to admin";
    
    $stmt = $pdo->prepare("
        INSERT INTO system_logs 
        (log_type, reference_id, user_identifier, action_description) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([
        'system_action',
        $request_id,
        'system',
        $sms_log_description
    ]);

    // Log successful request creation
    error_log("Voucher request created: $request_id for phone: $normalized_phone, MAC: $mac_address, Package: $package");

    echo json_encode([
        'success' => true,
        'message' => 'Payment request submitted successfully. You will receive a voucher code via SMS once payment is approved.',
        'request_id' => $request_id,
        'phone' => $normalized_phone,
        'package' => $package,
        'price' => $price,
        'expires_at' => $expires_at,
        'sms_sent' => $sms_sent
    ]);

} catch (PDOException $e) {
    error_log("Database error in voucher_request.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred. Please try again later.'
    ]);
} catch (Exception $e) {
    error_log("General error in voucher_request.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while processing your request. Please try again.'
    ]);
}

/**
 * Normalize MAC address to standard format
 */
function normalizeMAC($mac) {
    // Remove any whitespace
    $mac = trim($mac);
    
    // Handle MikroTik template variable
    if ($mac === '$(mac)' || empty($mac)) {
        // Generate a fallback MAC-like identifier
        $mac = 'XX:XX:XX:XX:XX:XX';
    }
    
    // Remove any non-hex characters except : and -
    $mac = preg_replace('/[^0-9A-Fa-f:-]/', '', $mac);
    
    // Handle different MAC formats
    if (strlen($mac) === 12) {
        // Format: 001122334455 -> 00:11:22:33:44:55
        $mac = implode(':', str_split($mac, 2));
    } elseif (strlen($mac) === 17) {
        // Already in correct format with separators
        $mac = str_replace('-', ':', $mac);
    } else {
        // Invalid length, return false
        return false;
    }
    
    // Final validation
    if (preg_match('/^([0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2}$/', $mac)) {
        return strtolower($mac);
    }
    
    return false;
}

/**
 * Send SMS function - Main dispatcher
 */
function sendSMS($phone, $message)
{
    // Using Africa's Talking SMS API
    return sendSMS_AfricasTalking($phone, $message);
}

/**
 * Africa's Talking SMS Implementation
 */
function sendSMS_AfricasTalking($phone, $message)
{
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
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Content-Type: application/x-www-form-urlencoded',
        'apiKey: ' . $apikey
    ));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    // Log SMS attempt for debugging
    error_log("SMS attempt to $phone: HTTP $httpCode, Response: $response, cURL Error: $curl_error");

    if ($httpCode == 201) {
        $result = json_decode($response, true);
        return isset($result['SMSMessageData']) && isset($result['SMSMessageData']['Recipients']);
    }

    return false;
}

/**
 * Helper function to determine voucher table based on package
 */
function getVoucherTable($package) {
    $package_lower = strtolower($package);
    
    if (strpos($package_lower, 'daily') !== false || strpos($package_lower, '24') !== false || strpos($package_lower, '1d') !== false) {
        return 'daily_vouchers';
    } elseif (strpos($package_lower, 'weekly') !== false || strpos($package_lower, '7') !== false || strpos($package_lower, '1w') !== false) {
        return 'weekly_vouchers';
    } elseif (strpos($package_lower, 'monthly') !== false || strpos($package_lower, '30') !== false || strpos($package_lower, '1m') !== false) {
        return 'monthly_vouchers';
    }
    
    // Default to daily if package type cannot be determined
    return 'daily_vouchers';
}

/**
 * Helper function to get profile code based on package
 */
function getProfileCode($package) {
    $package_lower = strtolower($package);
    
    if (strpos($package_lower, 'daily') !== false || strpos($package_lower, '24') !== false || strpos($package_lower, '1d') !== false) {
        return '1D';
    } elseif (strpos($package_lower, 'weekly') !== false || strpos($package_lower, '7') !== false || strpos($package_lower, '1w') !== false) {
        return '1W';
    } elseif (strpos($package_lower, 'monthly') !== false || strpos($package_lower, '30') !== false || strpos($package_lower, '1m') !== false) {
        return '1M';
    }
    
    // Default to daily profile
    return '1D';
}

/**
 * Function to clean expired requests (can be called via cron job)
 */
function cleanExpiredRequests($pdo) {
    try {
        $stmt = $pdo->prepare("
            UPDATE voucher_requests 
            SET status = 'expired' 
            WHERE status = 'pending' AND expires_at < NOW()
        ");
        $stmt->execute();
        
        $affected_rows = $stmt->rowCount();
        if ($affected_rows > 0) {
            // Log the cleanup action
            $stmt = $pdo->prepare("
                INSERT INTO system_logs 
                (log_type, reference_id, user_identifier, action_description) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                'system_action',
                null,
                'system_cleanup',
                "Expired $affected_rows pending voucher requests"
            ]);
        }
        
        return $affected_rows;
    } catch (Exception $e) {
        error_log("Error cleaning expired requests: " . $e->getMessage());
        return 0;
    }
}

// Uncomment the line below to run cleanup on each request (not recommended for production)
// cleanExpiredRequests($pdo);
?>