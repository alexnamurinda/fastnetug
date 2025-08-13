<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
date_default_timezone_set('Africa/Kampala');

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

    // Get POST data - simplified to only require phone, package, and price
    $phone = $_POST['phone'] ?? '';
    $package = $_POST['package'] ?? '';
    $price = $_POST['price'] ?? '';

    // Validate required fields
    if (empty($phone) || empty($package) || empty($price)) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required fields: phone, package, price'
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

    // Validate price is numeric
    if (!is_numeric($price) || $price <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid price value'
        ]);
        exit();
    }

    // Check for existing pending request for this phone number (prevent spam)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM voucher_requests 
        WHERE phone = ? AND status = 'pending' AND created_at > DATE_SUB(NOW(), INTERVAL 10 MINUTE)
    ");
    $stmt->execute([$normalized_phone]);
    $existing_requests = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    if ($existing_requests > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'You already have a recent pending payment request.'
        ]);
        exit();
    }

    // Generate short unique request ID (8 characters)
    $request_id = generateShortRequestId($pdo);
    $created_at = date('Y-m-d H:i:s');

    // Insert voucher request into database
    $stmt = $pdo->prepare("
        INSERT INTO voucher_requests 
        (request_id, phone, package, price, status, created_at) 
        VALUES (?, ?, ?, ?, 'pending', ?)
    ");

    $stmt->execute([
        $request_id,
        $normalized_phone,
        $package,
        $price,
        $created_at
    ]);

    // Log the request in system_logs
    $stmt = $pdo->prepare("
        INSERT INTO system_logs 
        (log_type, reference_id, user_identifier, action_description, ip_address, user_agent) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $action_description = "New voucher request created - Package: $package, Price: UGX $price";
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

    // Send SMS notification to admin - simplified format
    $admin_phone = '+256744766410';
    $approval_url = 'https://www.fastnetug.com/pages/approvepayment.php';
    date_default_timezone_set('Africa/Kampala');
    $request_time = date('M j, g:i A', strtotime($created_at));


    $sms_message = "NEW PAYMENT REQUEST\n" .
        // "ID: $request_id\n" .
        "Phone: +$normalized_phone\n" .
        "Time: $request_time\n";
        // "Approve: $approval_url";

    // Send SMS using SMS service
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
    error_log("Voucher request created: $request_id for phone: $normalized_phone, Package: $package");

    echo json_encode([
        'success' => true,
        'message' => 'Payment request submitted successfully.',
        'request_id' => $request_id,
        'phone' => $normalized_phone,
        'package' => $package,
        'price' => $price,
        'sms_sent' => $sms_sent
    ]);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred. Please try again later.'
    ]);
} catch (Exception $e) {
    error_log("General error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while processing your request. Please try again.'
    ]);
}

/**
 * Generate short unique request ID
 */
function generateShortRequestId($pdo)
{
    $max_attempts = 10;
    $attempts = 0;

    do {
        // Generate 8-character ID: 2 letters + 6 digits
        $letters = chr(rand(65, 90)) . chr(rand(65, 90)); // AA-ZZ
        $numbers = str_pad(rand(0, 999999), 3, '0', STR_PAD_LEFT); // 000000-999999
        $request_id = $letters . $numbers;

        // Check if this ID already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM voucher_requests WHERE request_id = ?");
        $stmt->execute([$request_id]);
        $exists = $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;

        $attempts++;
    } while ($exists && $attempts < $max_attempts);

    if ($exists) {
        // Fallback to timestamp-based ID if we can't generate unique one
        $request_id = 'FN' . date('His') . rand(10, 99);
    }

    return $request_id;
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
    $username = 'fastnetug';  // Replace with your Africa's Talking username
    $apikey = 'atsk_55f3cd22b22762efe6a8342bcbd478239a69a4aca7588f25694cdaac498101e0d027488d';     // Replace with your Africa's Talking API key

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
function getVoucherTable($package)
{
    $package_lower = strtolower($package);

    if (strpos($package_lower, 'hours') !== false || strpos($package_lower, '24') !== false) {
        return 'daily_vouchers';
    } elseif (strpos($package_lower, 'week') !== false) {
        return 'weekly_vouchers';
    } elseif (strpos($package_lower, 'month') !== false) {
        return 'monthly_vouchers';
    }

    // Default to daily if package type cannot be determined
    return 'daily_vouchers';
}

/**
 * Helper function to get profile code based on package
 */
function getProfileCode($package)
{
    $package_lower = strtolower($package);

    if (strpos($package_lower, 'hours') !== false || strpos($package_lower, '24') !== false) {
        return '1D';
    } elseif (strpos($package_lower, 'week') !== false) {
        return '1W';
    } elseif (strpos($package_lower, 'month') !== false) {
        return '1M';
    }

    // Default to daily profile
    return '1D';
}

/**
 * Function to clean expired requests (can be called via cron job)
 */
function cleanExpiredRequests($pdo)
{
    try {
        $stmt = $pdo->prepare("
            UPDATE voucher_requests 
            SET status = 'expired' 
            WHERE status = 'pending' AND created_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)
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
                "Expired $affected_rows pending voucher requests older than 24 hours"
            ]);
        }

        return $affected_rows;
    } catch (Exception $e) {
        error_log("Error cleaning expired requests: " . $e->getMessage());
        return 0;
    }
}
