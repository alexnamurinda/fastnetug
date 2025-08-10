<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Africa/Kampala');

// Database configuration
$servername = "localhost";
$username = "fastnetug_user1";
$password = "smartwatt@mysql123";
$dbname = "fastnet_db";

function db() {
    static $pdo = null;
    global $servername, $username, $password, $dbname;
    if ($pdo === null) {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
}

function sendSMS($phone, $message) {
    $username = 'agritech_info';
    $apikey = 'atsk_1eb8e8aa4cf9f3851dabd1bf4490983972432730c57f36cfcf51980d3047884b7d19c9c3';
    
    $data = [
        'username' => $username,
        'to' => $phone,
        'message' => $message
    ];
    
    $ch = curl_init('https://api.africastalking.com/version1/messaging');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/x-www-form-urlencoded',
        'apiKey: ' . $apikey
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode === 201;
}

function getVoucherTable($package) {
    $package = strtolower($package);
    if (strpos($package, 'week') !== false) return 'weekly_vouchers';
    if (strpos($package, 'month') !== false) return 'monthly_vouchers';
    return 'daily_vouchers';
}

function formatPhoneNumber($phone) {
    // Ensure phone number starts with +256
    if (substr($phone, 0, 1) === '0') {
        return '+256' . substr($phone, 1);
    } elseif (substr($phone, 0, 3) === '256') {
        return '+' . $phone;
    } elseif (substr($phone, 0, 4) === '+256') {
        return $phone;
    }
    return '+256' . $phone;
}

// ==== AJAX HANDLERS ====
if (isset($_GET['action'])) {
    $pdo = db();

    if ($_GET['action'] === 'get_requests') {
        $stmt = $pdo->prepare("
            SELECT request_id, phone, package, price, created_at, status, notes, voucher_code, approved_at 
            FROM voucher_requests 
            ORDER BY FIELD(status, 'pending', 'approved', 'rejected', 'expired') ASC, created_at DESC 
            LIMIT 100
        ");
        $stmt->execute();
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Format phone numbers and dates
        foreach ($requests as &$request) {
            $request['phone_formatted'] = formatPhoneNumber($request['phone']);
            $request['created_at_formatted'] = date('M j, Y g:i A', strtotime($request['created_at']));
            $request['approved_at_formatted'] = $request['approved_at'] ? 
                date('M j, Y g:i A', strtotime($request['approved_at'])) : null;
        }
        
        echo json_encode($requests);
        exit;
    }

    if ($_GET['action'] === 'get_inventory') {
        $tables = ['daily_vouchers', 'weekly_vouchers', 'monthly_vouchers'];
        $inventory = [];
        
        foreach ($tables as $table) {
            $stmt = $pdo->prepare("SELECT status, COUNT(*) AS count FROM $table GROUP BY status");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $inventory[$table] = ['Available' => 0, 'Used' => 0, 'Expired' => 0];
            foreach ($results as $row) {
                $inventory[$table][$row['status']] = (int)$row['count'];
            }
            
            // Get total count
            $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM $table");
            $stmt->execute();
            $inventory[$table]['Total'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        }
        
        echo json_encode($inventory);
        exit;
    }

    if ($_GET['action'] === 'get_stats') {
        $stats = [];
        
        // Total requests today
        $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM voucher_requests WHERE DATE(created_at) = CURDATE()");
        $stmt->execute();
        $stats['requests_today'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Pending requests
        $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM voucher_requests WHERE status = 'pending'");
        $stmt->execute();
        $stats['pending_requests'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Revenue today
        $stmt = $pdo->prepare("SELECT SUM(price) AS revenue FROM voucher_requests WHERE status = 'approved' AND DATE(approved_at) = CURDATE()");
        $stmt->execute();
        $stats['revenue_today'] = (float)($stmt->fetch(PDO::FETCH_ASSOC)['revenue'] ?? 0);
        
        echo json_encode($stats);
        exit;
    }

    // Approve request
    if ($_GET['action'] === 'approve' && !empty($_POST['id'])) {
        $request_id = $_POST['id'];
        
        try {
            $pdo->beginTransaction();

            // Get and lock the request
            $stmt = $pdo->prepare("SELECT * FROM voucher_requests WHERE request_id = ? AND status = 'pending' FOR UPDATE");
            $stmt->execute([$request_id]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$request) {
                $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => 'Request not found or already processed.']);
                exit;
            }

            $voucher_table = getVoucherTable($request['package']);

            // Get and lock an available voucher
            $stmt = $pdo->prepare("SELECT * FROM $voucher_table WHERE status = 'Available' ORDER BY created_at ASC LIMIT 1 FOR UPDATE");
            $stmt->execute();
            $voucher = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$voucher) {
                $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => "No available vouchers for package '{$request['package']}'. Please generate more vouchers."]);
                exit;
            }

            // Format phone number for SMS
            $formatted_phone = formatPhoneNumber($request['phone']);

            // Update voucher status to Used and assign phone
            $stmt = $pdo->prepare("UPDATE $voucher_table SET status = 'Used', user_phone = ?, used_at = NOW() WHERE id = ?");
            $stmt->execute([$formatted_phone, $voucher['id']]);

            // Update request status to approved
            $stmt = $pdo->prepare("UPDATE voucher_requests SET status = 'approved', voucher_code = ?, approved_at = NOW() WHERE request_id = ?");
            $stmt->execute([$voucher['voucher_code'], $request_id]);

            // Log the approval action
            $stmt = $pdo->prepare("
                INSERT INTO system_logs 
                (log_type, reference_id, user_identifier, action_description, ip_address) 
                VALUES ('voucher_approval', ?, 'admin', ?, ?)
            ");
            $action_description = "Approved payment request. Assigned voucher {$voucher['voucher_code']} to {$formatted_phone}";
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $stmt->execute([$request_id, $action_description, $ip_address]);

            $pdo->commit();

            // Send voucher code to user via SMS
            $sms_message = "FastNetUG: Your voucher code is {$voucher['voucher_code']}. Valid for {$request['package']}. Use it to login at the hotspot.";
            $sms_sent = sendSMS($formatted_phone, $sms_message);

            // Log SMS attempt
            $stmt = $pdo->prepare("
                INSERT INTO system_logs 
                (log_type, reference_id, user_identifier, action_description) 
                VALUES ('system_action', ?, ?, ?)
            ");
            $sms_log = $sms_sent ? 
                "Voucher SMS sent successfully to {$formatted_phone}" : 
                "Failed to send voucher SMS to {$formatted_phone}";
            $stmt->execute([$request_id, 'system', $sms_log]);

            echo json_encode([
                'success' => true,
                'message' => 'Request approved and voucher sent successfully.',
                'voucher_code' => $voucher['voucher_code'],
                'sms_sent' => $sms_sent
            ]);
            exit;

        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            error_log("Approval error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error approving request: ' . $e->getMessage()]);
            exit;
        }
    }

    // Reject request
    if ($_GET['action'] === 'reject' && !empty($_POST['id']) && !empty($_POST['reason'])) {
        $request_id = $_POST['id'];
        $reason = trim($_POST['reason']);
        
        try {
            $stmt = $pdo->prepare("SELECT * FROM voucher_requests WHERE request_id = ? AND status = 'pending'");
            $stmt->execute([$request_id]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$request) {
                echo json_encode(['success' => false, 'message' => 'Request not found or already processed.']);
                exit;
            }

            // Update request status to rejected
            $stmt = $pdo->prepare("UPDATE voucher_requests SET status = 'rejected', notes = ?, approved_at = NOW() WHERE request_id = ?");
            $stmt->execute([$reason, $request_id]);

            // Log the rejection
            $stmt = $pdo->prepare("
                INSERT INTO system_logs 
                (log_type, reference_id, user_identifier, action_description, ip_address) 
                VALUES ('voucher_approval', ?, 'admin', ?, ?)
            ");
            $action_description = "Rejected payment request for {$request['phone']}. Reason: $reason";
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $stmt->execute([$request_id, $action_description, $ip_address]);

            // Format phone and send rejection SMS
            $formatted_phone = formatPhoneNumber($request['phone']);
            $sms_message = "FastNetUG: Your payment request (ID: $request_id) was rejected. Reason: $reason. Contact support at 0744766410 for assistance.";
            $sms_sent = sendSMS($formatted_phone, $sms_message);

            echo json_encode([
                'success' => true,
                'message' => 'Request rejected and user notified.',
                'sms_sent' => $sms_sent
            ]);
            exit;

        } catch (Exception $e) {
            error_log("Rejection error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error rejecting request: ' . $e->getMessage()]);
            exit;
        }
    }

    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid action or missing parameters']);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastNetUG Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4299e1;
            --success-color: #48bb78;
            --warning-color: #ed8936;
            --danger-color: #e53e3e;
            --dark-color: #2d3748;
            --light-bg: #f7fafc;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            background: var(--light-bg);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), #3182ce) !important;
            box-shadow: var(--card-shadow);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .stats-card {
            background: white;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            border: none;
            transition: transform 0.2s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
        }

        .stats-card .card-body {
            padding: 1.5rem;
        }

        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 15px;
        }

        .stats-icon.pending {
            background: rgba(66, 153, 225, 0.1);
            color: var(--primary-color);
        }

        .stats-icon.today {
            background: rgba(72, 187, 120, 0.1);
            color: var(--success-color);
        }

        .stats-icon.revenue {
            background: rgba(237, 137, 54, 0.1);
            color: var(--warning-color);
        }

        .main-card {
            background: white;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            border: none;
            overflow: hidden;
        }

        .card-header-custom {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-bottom: 2px solid #dee2e6;
            padding: 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: rgba(66, 153, 225, 0.1);
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .status-approved {
            background: rgba(72, 187, 120, 0.1);
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }

        .status-rejected {
            background: rgba(229, 62, 62, 0.1);
            color: var(--danger-color);
            border: 1px solid var(--danger-color);
        }

        .status-expired {
            background: rgba(113, 128, 150, 0.1);
            color: #718096;
            border: 1px solid #718096;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-action:hover {
            transform: translateY(-1px);
        }

        .inventory-item {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            box-shadow: var(--card-shadow);
            border-left: 4px solid var(--primary-color);
        }

        .inventory-title {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
        }

        .inventory-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 0.75rem;
        }

        .inventory-stat {
            text-align: center;
            padding: 0.5rem;
            border-radius: 8px;
            background: #f8f9fa;
        }

        .inventory-stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .inventory-stat-label {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 500;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(66, 153, 225, 0.05);
        }

        .fade-in {
            animation: fadeIn 0.5s ease forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        .refresh-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--primary-color);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            box-shadow: var(--card-shadow);
            z-index: 1050;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .refresh-indicator.show {
            opacity: 1;
        }

        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            border-bottom: 2px solid #dee2e6;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }

        @media (max-width: 768px) {
            .stats-card .card-body {
                padding: 1rem;
            }
            
            .inventory-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .table-responsive {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-dark">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">
                <i class="fas fa-wifi me-2"></i>FastNetUG Admin Dashboard
            </span>
            <span class="navbar-text">
                <i class="fas fa-clock me-1"></i>
                <span id="current-time"></span>
            </span>
        </div>
    </nav>

    <!-- Refresh Indicator -->
    <div id="refresh-indicator" class="refresh-indicator">
        <i class="fas fa-sync-alt fa-spin me-1"></i>Refreshing...
    </div>

    <div class="container-fluid my-4">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card stats-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon pending">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1" id="pending-count">0</h5>
                            <p class="card-text text-muted mb-0">Pending Requests</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card stats-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon today">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1" id="today-count">0</h5>
                            <p class="card-text text-muted mb-0">Requests Today</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card stats-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon revenue">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1" id="revenue-today">UGX 0</h5>
                            <p class="card-text text-muted mb-0">Revenue Today</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Payment Requests Section -->
            <div class="col-lg-8">
                <div class="card main-card">
                    <div class="card-header-custom d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-list-alt me-2"></i>Payment Requests</span>
                        <button class="btn btn-outline-primary btn-sm" onclick="loadRequests()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="requests-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Request ID</th>
                                        <th>Phone Number</th>
                                        <th>Package</th>
                                        <!-- <th>Amount</th> -->
                                        <th>Requested At</th>
                                        <th>Voucher Code</th>
                                        <th>Status</th>
                                        <!-- <th class="text-center">Actions</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-spinner fa-spin me-2"></i>Loading requests...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Voucher Inventory Section -->
            <div class="col-lg-4">
                <div class="card main-card">
                    <div class="card-header-custom d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-boxes me-2"></i>Voucher Inventory</span>
                        <button class="btn btn-outline-success btn-sm" onclick="loadInventory()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="inventory-container">
                            <div class="text-center py-3">
                                <i class="fas fa-spinner fa-spin me-2"></i>Loading inventory...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Request Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="reject-form" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">
                        <i class="fas fa-times-circle me-2"></i>Reject Payment Request
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="reject-request-id" name="id">
                    
                    <div class="mb-3">
                        <label for="reject-reason" class="form-label">
                            <i class="fas fa-comment-alt me-1"></i>Reason for rejection
                        </label>
                        <textarea class="form-control" id="reject-reason" name="reason" rows="3" 
                                placeholder="Please provide a clear reason for rejecting this request..." required></textarea>
                        <div class="form-text">This reason will be sent to the customer via SMS.</div>
                    </div>
                    
                    <div class="alert alert-danger d-none" id="reject-error"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>Reject Request
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success/Error Toast -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="actionToast" class="toast" role="alert">
            <div class="toast-header">
                <i class="fas fa-check-circle text-success me-2"></i>
                <strong class="me-auto">FastNetUG</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
        const actionToast = new bootstrap.Toast(document.getElementById('actionToast'));

        function showToast(message, isSuccess = true) {
            const toast = document.getElementById('actionToast');
            const icon = toast.querySelector('.toast-header i');
            const body = toast.querySelector('.toast-body');
            
            if (isSuccess) {
                icon.className = 'fas fa-check-circle text-success me-2';
            } else {
                icon.className = 'fas fa-exclamation-circle text-danger me-2';
            }
            
            body.textContent = message;
            actionToast.show();
        }

        function showRefreshIndicator() {
            const indicator = document.getElementById('refresh-indicator');
            indicator.classList.add('show');
            setTimeout(() => indicator.classList.remove('show'), 1000);
        }

        function updateCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleString('en-US', {
                timeZone: 'Africa/Kampala',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('current-time').textContent = timeString;
        }

        function loadStats() {
            $.getJSON('?action=get_stats')
                .done(function(data) {
                    $('#pending-count').text(data.pending_requests);
                    $('#today-count').text(data.requests_today);
                    $('#revenue-today').text('UGX ' + Number(data.revenue_today).toLocaleString());
                })
                .fail(function() {
                    console.error('Failed to load statistics');
                });
        }

        function loadRequests() {
            showRefreshIndicator();
            
            $.getJSON('?action=get_requests')
                .done(function(data) {
                    const tbody = $('#requests-table tbody');
                    tbody.empty();
                    
                    if (data.length === 0) {
                        tbody.append(`
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox me-2"></i>No payment requests found
                                </td>
                            </tr>
                        `);
                        return;
                    }

                    data.forEach(function(request) {
                        let statusBadge = '';
                        let actionsHtml = '';
                        
                        switch(request.status) {
                            case 'pending':
                                statusBadge = '<span class="status-badge status-pending">Pending</span>';
                                actionsHtml = `
                                    <button class="btn btn-success btn-action me-1 approve-btn" data-id="${request.request_id}" title="Approve Request">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                    <button class="btn btn-danger btn-action reject-btn" data-id="${request.request_id}" title="Reject Request">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                `;
                                break;
                            case 'approved':
                                statusBadge = '<span class="status-badge status-approved">Approved</span>';
                                actionsHtml = '<small class="text-muted">Completed</small>';
                                break;
                            case 'rejected':
                                statusBadge = '<span class="status-badge status-rejected">Rejected</span>';
                                actionsHtml = '<small class="text-muted">Rejected</small>';
                                break;
                            case 'expired':
                                statusBadge = '<span class="status-badge status-expired">Expired</span>';
                                actionsHtml = '<small class="text-muted">Expired</small>';
                                break;
                            default:
                                statusBadge = `<span class="status-badge">${request.status}</span>`;
                                actionsHtml = '<small class="text-muted">-</small>';
                        }

                        const voucherCode = request.voucher_code ? 
                            `<code class="bg-light px-2 py-1 rounded">${request.voucher_code}</code>` : 
                            '<small class="text-muted">-</small>';

                        tbody.append(`
                            <tr class="fade-in">
                                <td><strong>${request.request_id}</strong></td>
                                <td>
                                    <i class="fas fa-phone me-1 text-muted"></i>
                                    ${request.phone_formatted}
                                </td>
                                <td>
                                    <i class="fas fa-box me-1 text-muted"></i>
                                    ${request.package}
                                </td>
                                <td>
                                    <strong>UGX ${Number(request.price).toLocaleString()}</strong>
                                </td>
                                <td>
                                    <small>${request.created_at_formatted}</small>
                                </td>
                                <td>${statusBadge}</td>
                                <td>${voucherCode}</td>
                                <td class="text-center">${actionsHtml}</td>
                            </tr>
                        `);
                    });
                })
                .fail(function() {
                    const tbody = $('#requests-table tbody');
                    tbody.html(`
                        <tr>
                            <td colspan="8" class="text-center py-4 text-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>Failed to load requests
                            </td>
                        </tr>
                    `);
                });
        }

        function loadInventory() {
            $.getJSON('?action=get_inventory')
                .done(function(data) {
                    const container = $('#inventory-container');
                    container.empty();

                    const tableNames = {
                        'daily_vouchers': 'Daily Vouchers (24 Hours)',
                        'weekly_vouchers': 'Weekly Vouchers (1 Week)', 
                        'monthly_vouchers': 'Monthly Vouchers (1 Month)'
                    };

                    Object.entries(data).forEach(function([table, counts]) {
                        const title = tableNames[table] || table;
                        const totalVouchers = counts.Total || 0;
                        const availablePercent = totalVouchers > 0 ? Math.round((counts.Available / totalVouchers) * 100) : 0;
                        
                        let alertClass = '';
                        if (availablePercent < 20) alertClass = 'border-danger';
                        else if (availablePercent < 50) alertClass = 'border-warning';
                        else alertClass = 'border-success';

                        container.append(`
                            <div class="inventory-item ${alertClass}">
                                <div class="inventory-title">${title}</div>
                                <div class="inventory-stats">
                                    <div class="inventory-stat">
                                        <div class="inventory-stat-value text-success">${counts.Available || 0}</div>
                                        <div class="inventory-stat-label">Available</div>
                                    </div>
                                    <div class="inventory-stat">
                                        <div class="inventory-stat-value text-primary">${counts.Used || 0}</div>
                                        <div class="inventory-stat-label">Used</div>
                                    </div>
                                    <div class="inventory-stat">
                                        <div class="inventory-stat-value text-warning">${counts.Expired || 0}</div>
                                        <div class="inventory-stat-label">Expired</div>
                                    </div>
                                    <div class="inventory-stat">
                                        <div class="inventory-stat-value text-secondary">${totalVouchers}</div>
                                        <div class="inventory-stat-label">Total</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: ${availablePercent}%"></div>
                                    </div>
                                    <small class="text-muted">${availablePercent}% available</small>
                                </div>
                            </div>
                        `);
                    });
                })
                .fail(function() {
                    $('#inventory-container').html(`
                        <div class="text-center py-3 text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>Failed to load inventory
                        </div>
                    `);
                });
        }

        function handleApprove(requestId) {
            if (!confirm(`Are you sure you want to approve request ${requestId}? This will send a voucher code to the customer.`)) {
                return;
            }

            const button = $(`.approve-btn[data-id="${requestId}"]`);
            const originalHtml = button.html();
            button.html('<i class="fas fa-spinner fa-spin"></i> Processing...').prop('disabled', true);

            $.post('?action=approve', {id: requestId})
                .done(function(response) {
                    if (response.success) {
                        showToast(`Request ${requestId} approved successfully! Voucher ${response.voucher_code} sent to customer.`);
                        loadRequests();
                        loadInventory();
                        loadStats();
                    } else {
                        showToast(response.message || 'Failed to approve request', false);
                        button.html(originalHtml).prop('disabled', false);
                    }
                })
                .fail(function() {
                    showToast('Network error. Please try again.', false);
                    button.html(originalHtml).prop('disabled', false);
                });
        }

        function handleReject(requestId) {
            $('#reject-request-id').val(requestId);
            $('#reject-reason').val('');
            $('#reject-error').addClass('d-none').text('');
            rejectModal.show();
        }

        // Initialize page
        $(document).ready(function() {
            // Load initial data
            loadRequests();
            loadInventory();
            loadStats();
            updateCurrentTime();

            // Update time every second
            setInterval(updateCurrentTime, 1000);

            // Auto-refresh every 30 seconds
            setInterval(function() {
                loadRequests();
                loadInventory();
                loadStats();
            }, 30000);

            // Event handlers
            $(document).on('click', '.approve-btn', function() {
                const requestId = $(this).data('id');
                handleApprove(requestId);
            });

            $(document).on('click', '.reject-btn', function() {
                const requestId = $(this).data('id');
                handleReject(requestId);
            });

            // Reject form submission
            $('#reject-form').on('submit', function(e) {
                e.preventDefault();
                
                const requestId = $('#reject-request-id').val();
                const reason = $('#reject-reason').val().trim();
                
                if (!reason) {
                    $('#reject-error').removeClass('d-none').text('Please enter a rejection reason.');
                    return;
                }

                const submitBtn = $(this).find('button[type="submit"]');
                const originalHtml = submitBtn.html();
                submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Rejecting...').prop('disabled', true);

                $.post('?action=reject', {id: requestId, reason: reason})
                    .done(function(response) {
                        if (response.success) {
                            rejectModal.hide();
                            showToast(`Request ${requestId} rejected and customer notified.`);
                            loadRequests();
                            loadStats();
                        } else {
                            $('#reject-error').removeClass('d-none').text(response.message || 'Failed to reject request.');
                        }
                        submitBtn.html(originalHtml).prop('disabled', false);
                    })
                    .fail(function() {
                        $('#reject-error').removeClass('d-none').text('Network error. Please try again.');
                        submitBtn.html(originalHtml).prop('disabled', false);
                    });
            });

            // Clear modal on hide
            $('#rejectModal').on('hidden.bs.modal', function() {
                $('#reject-reason').val('');
                $('#reject-error').addClass('d-none').text('');
            });
        });

        // Keyboard shortcuts
        $(document).keydown(function(e) {
            // Ctrl/Cmd + R to refresh
            if ((e.ctrlKey || e.metaKey) && e.keyCode === 82) {
                e.preventDefault();
                loadRequests();
                loadInventory();
                loadStats();
            }
        });
    </script>
</body>
</html>