<?php
session_start();

// Database configuration
$servername = "localhost";
$username = "fastnetug_user1";  // Replace with your database username
$password = "smartwatt@mysql123";  // Replace with your database password
$dbname = "fastnet_db";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$action = $_POST['action'] ?? '';
$request_id = $_POST['request_id'] ?? '';
$message = '';

// Handle approval/rejection actions
if (!empty($action) && !empty($request_id)) {
    if ($action === 'approve') {
        $message = approveRequest($pdo, $request_id);
    } elseif ($action === 'reject') {
        $reject_reason = $_POST['reject_reason'] ?? 'No reason provided';
        $message = rejectRequest($pdo, $request_id, $reject_reason);
    }
}

// Fetch all requests
$requests = fetchAllRequests($pdo);

// Fetch inventory data
$inventory = fetchInventory($pdo);

/**
 * Approve a payment request and assign voucher
 */
function approveRequest($pdo, $request_id) {
    try {
        // Get request details
        $stmt = $pdo->prepare("SELECT * FROM voucher_requests WHERE request_id = ? AND status = 'pending'");
        $stmt->execute([$request_id]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$request) {
            return "Request not found or already processed.";
        }
        
        // Determine voucher table based on package
        $voucher_table = getVoucherTable($request['package']);
        
        // Get an available voucher from the appropriate table
        $stmt = $pdo->prepare("
            SELECT voucher_code FROM $voucher_table 
            WHERE status = 'Available' AND price = ? 
            ORDER BY created_at ASC 
            LIMIT 1 FOR UPDATE
        ");
        $stmt->execute([$request['price']]);
        $voucher = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$voucher) {
            return "No available voucher codes for this package. Please generate more vouchers.";
        }
        
        $voucher_code = $voucher['voucher_code'];
        
        $pdo->beginTransaction();
        
        // Update voucher status to Used and assign to user
        $stmt = $pdo->prepare("
            UPDATE $voucher_table 
            SET status = 'Used', user_phone = ?, used_at = NOW() 
            WHERE voucher_code = ?
        ");
        $stmt->execute([$request['phone'], $voucher_code]);
        
        // Update request status to approved
        $stmt = $pdo->prepare("
            UPDATE voucher_requests 
            SET status = 'approved', voucher_code = ?, approved_at = NOW() 
            WHERE request_id = ?
        ");
        $stmt->execute([$voucher_code, $request_id]);
        
        // Log the action
        $stmt = $pdo->prepare("
            INSERT INTO system_logs 
            (log_type, reference_id, user_identifier, action_description, ip_address) 
            VALUES ('voucher_approval', ?, 'admin', ?, ?)
        ");
        $stmt->execute([
            $request_id,
            "Payment approved and voucher {$voucher_code} assigned to {$request['phone']}",
            $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
        
        $pdo->commit();
        
        // Send voucher to customer
        $sms_message = "FastNetUG: Payment approved! Your voucher code is: {$voucher_code}. Valid for {$request['package']}. Use this code to login at our hotspot.";
        sendSMS($request['phone'], $sms_message);
        
        return "✅ Payment approved! Voucher {$voucher_code} sent to {$request['phone']}.";
        
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollback();
        }
        error_log("Error approving request: " . $e->getMessage());
        return "❌ Error approving payment: " . $e->getMessage();
    }
}

/**
 * Reject a payment request
 */
function rejectRequest($pdo, $request_id, $reason) {
    try {
        // Get request details
        $stmt = $pdo->prepare("SELECT * FROM voucher_requests WHERE request_id = ? AND status = 'pending'");
        $stmt->execute([$request_id]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$request) {
            return "Request not found or already processed.";
        }
        
        // Update request status to rejected
        $stmt = $pdo->prepare("
            UPDATE voucher_requests 
            SET status = 'rejected', approved_at = NOW(), notes = ? 
            WHERE request_id = ?
        ");
        $stmt->execute([$reason, $request_id]);
        
        // Log the action
        $stmt = $pdo->prepare("
            INSERT INTO system_logs 
            (log_type, reference_id, user_identifier, action_description, ip_address) 
            VALUES ('voucher_approval', ?, 'admin', ?, ?)
        ");
        $stmt->execute([
            $request_id,
            "Payment rejected for {$request['phone']}. Reason: {$reason}",
            $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
        
        // Notify customer
        $sms_message = "FastNetUG: Your payment request {$request_id} has been rejected. Reason: {$reason}. Contact 0744766410 for assistance.";
        sendSMS($request['phone'], $sms_message);
        
        return "❌ Payment rejected. Customer has been notified.";
        
    } catch (Exception $e) {
        error_log("Error rejecting request: " . $e->getMessage());
        return "❌ Error rejecting payment: " . $e->getMessage();
    }
}

/**
 * Fetch all requests with pagination
 */
function fetchAllRequests($pdo, $limit = 50) {
    $stmt = $pdo->prepare("
        SELECT * FROM voucher_requests 
        ORDER BY 
            CASE WHEN status = 'pending' THEN 0 ELSE 1 END,
            created_at DESC 
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Fetch voucher inventory from all tables
 */
function fetchInventory($pdo) {
    $inventory = [];
    $tables = ['daily_vouchers', 'weekly_vouchers', 'monthly_vouchers'];
    
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("
            SELECT 
                status,
                COUNT(*) as count,
                SUM(CASE WHEN status = 'Available' THEN price ELSE 0 END) as available_value
            FROM $table 
            GROUP BY status
        ");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $inventory[$table] = [
            'Available' => 0,
            'Used' => 0,
            'Expired' => 0,
            'available_value' => 0
        ];
        
        foreach ($results as $row) {
            $inventory[$table][$row['status']] = $row['count'];
            if ($row['status'] === 'Available') {
                $inventory[$table]['available_value'] = $row['available_value'];
            }
        }
    }
    
    return $inventory;
}

/**
 * Helper function to determine voucher table based on package
 */
function getVoucherTable($package) {
    $package_lower = strtolower($package);
    
    if (strpos($package_lower, 'hours') !== false || strpos($package_lower, '24') !== false) {
        return 'daily_vouchers';
    } elseif (strpos($package_lower, 'week') !== false) {
        return 'weekly_vouchers';
    } elseif (strpos($package_lower, 'month') !== false) {
        return 'monthly_vouchers';
    }
    
    return 'daily_vouchers';
}

/**
 * SMS function
 */
function sendSMS($phone, $message) {
    return sendSMS_AfricasTalking($phone, $message);
}

function sendSMS_AfricasTalking($phone, $message) {
    $username = 'agritech_info';
    $apikey = 'atsk_1eb8e8aa4cf9f3851dabd1bf4490983972432730c57f36cfcf51980d3047884b7d19c9c3';

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

    return $httpCode == 201;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastNetUG - Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #2d3748;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .navbar-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .navbar h1 {
            color: #2d3748;
            font-size: 1.5rem;
        }

        .navbar-stats {
            display: flex;
            gap: 20px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 1.2rem;
            font-weight: bold;
            color: #4299e1;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #718096;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
            margin-bottom: 20px;
        }

        .main-content {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .inventory-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .inventory-card h3 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 1.1rem;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
        }

        .inventory-item {
            margin-bottom: 15px;
            padding: 15px;
            background: #f7fafc;
            border-radius: 10px;
            border-left: 4px solid #4299e1;
        }

        .inventory-title {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .inventory-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            font-size: 0.9rem;
        }