<?php
// Start session and set basic configurations
session_start();
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Africa/Kampala');

// Database configuration
$servername = "localhost";
$username = "fastnetug_user1";
$password = "smartwatt@mysql123";
$dbname = "fastnet_db";

/**
 * Database connection function using PDO with singleton pattern
 * Returns a single PDO instance throughout the application lifecycle
 */
function db()
{
    static $pdo = null;
    global $servername, $username, $password, $dbname;
    if ($pdo === null) {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // CRITICAL FIX: Set MySQL timezone to match PHP timezone (East Africa Time UTC+3)
        $pdo->exec("SET time_zone = '+03:00'");
    }
    return $pdo;
}

/**
 * Send SMS notification to users via Africa's Talking API
 * @param string $phone - Phone number to send SMS to
 * @param string $message - SMS message content
 * @return bool - Returns true if SMS was sent successfully
 */
function sendSMS($phone, $message)
{
    $username = 'fastnetug';  // Replace with your Africa's Talking username
    $apikey = 'atsk_55f3cd22b22762efe6a8342bcbd478239a69a4aca7588f25694cdaac498101e0d027488d';     // Replace with your Africa's Talking API key

    $data = [
        'username' => $username,
        'to' => $phone,
        'message' => $message
    ];

    // Initialize cURL for API request
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

/**
 * Determine the correct voucher table based on package type
 * @param string $package - Package name/type
 * @return string - Returns the appropriate database table name
 */
function getVoucherTable($package)
{
    $package = strtolower($package);
    if (strpos($package, '5 hours') !== false || strpos($package, '5hours') !== false) return 'hourly_vouchers';
    if (strpos($package, 'week') !== false) return 'weekly_vouchers';
    if (strpos($package, 'month') !== false) return 'monthly_vouchers';
    return 'daily_vouchers';
}

/**
 * Format phone number to Uganda standard (+256)
 * @param string $phone - Raw phone number input
 * @return string - Formatted phone number with +256 prefix
 */
function formatPhoneNumber($phone)
{
    // Convert different phone number formats to +256 standard
    if (substr($phone, 0, 1) === '0') {
        return '+256' . substr($phone, 1);
    } elseif (substr($phone, 0, 3) === '256') {
        return '+' . $phone;
    } elseif (substr($phone, 0, 4) === '+256') {
        return $phone;
    }
    return '+256' . $phone;
}

/**
 * Convert package names to short profile codes
 * @param string $package - Full package name
 * @return string - Short profile code (1D, 1W, 1M)
 */
function getPackageProfile($package)
{
    $package = strtolower($package);
    if (strpos($package, '5 hours') !== false || strpos($package, '5hours') !== false) return '5H';
    if (strpos($package, 'week') !== false || strpos($package, '7') !== false) return '1W';
    if (strpos($package, 'month') !== false || strpos($package, '30') !== false) return '1M';
    return '1D'; // Default to daily
}

// ==== AJAX HANDLERS ====
if (isset($_GET['action'])) {
    $pdo = db();

    // Get all voucher requests with pagination and filtering
    if ($_GET['action'] === 'get_requests') {
        $stmt = $pdo->prepare("
    SELECT request_id, phone, package, price, created_at, status, notes, voucher_code, approved_at,
    (SELECT COUNT(*) FROM voucher_requests vr2 WHERE vr2.phone = voucher_requests.phone) as phone_count
    FROM voucher_requests 
    ORDER BY FIELD(status, 'pending', 'approved', 'refund', 'rejected') ASC, created_at DESC
");
        $stmt->execute();
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Format data for frontend display
        foreach ($requests as &$request) {
            $request['phone_formatted'] = formatPhoneNumber($request['phone']);
            $request['created_at_formatted'] = date('M j, Y g:i A', strtotime($request['created_at']));
            $request['approved_at_formatted'] = $request['approved_at'] ?
                date('M j, Y g:i A', strtotime($request['approved_at'])) : null;
            $request['package_profile'] = getPackageProfile($request['package']);
        }

        echo json_encode($requests);
        exit;
    }

    // Get voucher inventory status (Available, Used, Total only)
    if ($_GET['action'] === 'get_inventory') {
        $tables = ['hourly_vouchers', 'daily_vouchers', 'weekly_vouchers', 'monthly_vouchers'];
        $inventory = [];

        foreach ($tables as $table) {
            // Count vouchers by status (excluding expired from display)
            $stmt = $pdo->prepare("SELECT status, COUNT(*) AS count FROM $table GROUP BY status");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Initialize inventory counters
            $inventory[$table] = ['Available' => 0, 'Used' => 0];
            foreach ($results as $row) {
                if ($row['status'] === 'Available' || $row['status'] === 'Used') {
                    $inventory[$table][$row['status']] = (int)$row['count'];
                }
            }

            // Calculate total count (Available + Used only)
            $inventory[$table]['Total'] = $inventory[$table]['Available'] + $inventory[$table]['Used'];
        }

        echo json_encode($inventory);
        exit;
    }

    // Get dashboard statistics
    if ($_GET['action'] === 'get_stats') {
        $stats = [];

        try {
            // Count of pending requests
            $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM voucher_requests WHERE status = 'pending'");
            $stmt->execute();
            $stats['pending_requests'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];

            // Refund requests count for this month
            $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM voucher_requests WHERE status = 'refund' AND MONTH(approved_at) = MONTH(CURDATE()) AND YEAR(approved_at) = YEAR(CURDATE())");
            $stmt->execute();
            $stats['refund_requests_monthly'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];

            // Refund amount for this month
            $stmt = $pdo->prepare("SELECT SUM(price) AS amount FROM voucher_requests WHERE status = 'refund' AND MONTH(approved_at) = MONTH(CURDATE()) AND YEAR(approved_at) = YEAR(CURDATE())");
            $stmt->execute();
            $stats['refund_amount_monthly'] = (float)($stmt->fetch(PDO::FETCH_ASSOC)['amount'] ?? 0);

            // Revenue generated today - using DATE() to compare dates properly
            $stmt = $pdo->prepare("SELECT SUM(price) AS revenue FROM voucher_requests WHERE status = 'approved' AND DATE(approved_at) = CURDATE()");
            $stmt->execute();
            $stats['revenue_today'] = (float)($stmt->fetch(PDO::FETCH_ASSOC)['revenue'] ?? 0);

            // Monthly total revenue - using proper date functions
            $stmt = $pdo->prepare("SELECT SUM(price) AS revenue FROM voucher_requests WHERE status = 'approved' AND MONTH(approved_at) = MONTH(CURDATE()) AND YEAR(approved_at) = YEAR(CURDATE())");
            $stmt->execute();
            $stats['revenue_monthly'] = (float)($stmt->fetch(PDO::FETCH_ASSOC)['revenue'] ?? 0);
        } catch (Exception $e) {
            error_log("Stats query error: " . $e->getMessage());
            // Return safe defaults on error
            $stats = [
                'pending_requests' => 0,
                'requests_today' => 0,
                'revenue_today' => 0,
                'revenue_monthly' => 0
            ];
        }

        echo json_encode($stats);
        exit;
    }

    // Approve a payment request and assign voucher
    if ($_GET['action'] === 'approve' && !empty($_POST['id']) && !empty($_POST['approval_type'])) {
        $request_id = $_POST['id'];
        $approval_type = $_POST['approval_type']; // 'new' or 'refund'

        try {
            $pdo->beginTransaction();

            // Get and lock the pending request
            $stmt = $pdo->prepare("SELECT * FROM voucher_requests WHERE request_id = ? AND status = 'pending' FOR UPDATE");
            $stmt->execute([$request_id]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$request) {
                $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => 'Request not found']);
                exit;
            }

            $voucher_table = getVoucherTable($request['package']);

            // Get and lock an available voucher
            $stmt = $pdo->prepare("SELECT * FROM $voucher_table WHERE status = 'Available' ORDER BY created_at ASC LIMIT 1 FOR UPDATE");
            $stmt->execute();
            $voucher = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$voucher) {
                $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => "No available vouchers for package '{$request['package']}'. Try again later"]);
                exit;
            }

            // Format phone number for SMS delivery
            $formatted_phone = formatPhoneNumber($request['phone']);

            // Mark voucher as used and assign to customer
            $stmt = $pdo->prepare("UPDATE $voucher_table SET status = 'Used', user_phone = ?, used_at = NOW() WHERE id = ?");
            $stmt->execute([$formatted_phone, $voucher['id']]);

            // Determine the status based on approval type
            $status = ($approval_type === 'refund') ? 'refund' : 'approved';

            // Update request status
            $stmt = $pdo->prepare("UPDATE voucher_requests SET status = ?, voucher_code = ?, approved_at = NOW() WHERE request_id = ?");
            $stmt->execute([$status, $voucher['voucher_code'], $request_id]);

            // Log the approval action for audit trail
            $stmt = $pdo->prepare("
            INSERT INTO system_logs 
            (log_type, reference_id, user_identifier, action_description, ip_address) 
            VALUES ('voucher_approval', ?, 'admin', ?, ?)
        ");
            $action_type_text = ($approval_type === 'refund') ? 'Refunded' : 'Approved';
            $action_description = "{$action_type_text} payment request. Assigned voucher {$voucher['voucher_code']} to {$formatted_phone}";
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $stmt->execute([$request_id, $action_description, $ip_address]);

            $pdo->commit();

            // Send voucher code to customer via SMS
            $sms_message = "Voucher code: {$voucher['voucher_code']}. Valid for {$request['package']}";
            $sms_sent = sendSMS($formatted_phone, $sms_message);

            // Log SMS delivery attempt
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
                'message' => $action_type_text . ' successfully.',
                'voucher_code' => $voucher['voucher_code'],
                'sms_sent' => $sms_sent,
                'approval_type' => $approval_type
            ]);
            exit;
        } catch (Exception $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            error_log("Approval error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error processing request: ' . $e->getMessage()]);
            exit;
        }
    }
    // Reject a payment request with reason
    if ($_GET['action'] === 'reject' && !empty($_POST['id']) && !empty($_POST['reason'])) {
        $request_id = $_POST['id'];
        $reason = trim($_POST['reason']);

        try {
            // Verify request exists and is still pending
            $stmt = $pdo->prepare("SELECT * FROM voucher_requests WHERE request_id = ? AND status = 'pending'");
            $stmt->execute([$request_id]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$request) {
                echo json_encode(['success' => false, 'message' => 'Request not found']);
                exit;
            }

            // Update request status to rejected with reason
            $stmt = $pdo->prepare("UPDATE voucher_requests SET status = 'rejected', notes = ?, approved_at = NOW() WHERE request_id = ?");
            $stmt->execute([$reason, $request_id]);

            // Log the rejection action
            $stmt = $pdo->prepare("
                INSERT INTO system_logs 
                (log_type, reference_id, user_identifier, action_description, ip_address) 
                VALUES ('voucher_approval', ?, 'admin', ?, ?)
            ");
            $action_description = "Rejected payment request for {$request['phone']}. Reason: $reason";
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $stmt->execute([$request_id, $action_description, $ip_address]);

            // Send rejection notification to customer
            $formatted_phone = formatPhoneNumber($request['phone']);
            $sms_message = "Request rejected. Reason: $reason.";
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

    // Handle invalid action requests
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastNetUG Admin Dashboard</title>

    <!-- External CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS Files -->
    <link href="../css/mainstyles.css" rel="stylesheet">
    <link href="../css/responsive.css" rel="stylesheet">
    <link href="../css/approvepayment.css" rel="stylesheet">
</head>

<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-dark">
        <div class="container-fluid">
            <span class="navbar-brand">
                <img src="../images/FastNetUGbg.png" alt="FastNetUG Logo" class="logo-text">
            </span>
            <!-- Live clock display -->
            <span class="navbar-text">
                <i class="fas fa-clock me-1"></i>
                <span id="current-time"></span>
            </span>
        </div>
    </nav>

    <!-- Auto-refresh indicator -->
    <div id="refresh-indicator">

    </div>

    <div class="container-fluid my-4">
        <!-- Dashboard Statistics Cards Row -->
        <div class="row mb-4">
            <!-- Pending Requests Counter -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon pending">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1" id="pending-count">0 Pending</h5>
                            <p class="card-text text-muted mb-0">Requests</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Refund Requests Counter (resets monthly) -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon refund">
                            <i class="fas fa-undo"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1" id="refund-count">0 Refunds This Month</h5>
                            <p class="card-text text-muted mb-0" id="refund-amount">UGX 0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Revenue Counter (resets daily at midnight) -->
            <div class="col-lg-3 col-md-6 mb-3">
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

            <!-- Monthly Revenue Counter (resets at end of month) -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stats-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="stats-icon monthly">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1" id="revenue-monthly">UGX 0</h5>
                            <p class="card-text text-muted mb-0">Revenue This Month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Payment Requests Management Section -->
            <div class="col-lg-8">
                <div class="card main-card">
                    <div class="card-header-custom d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-list-alt me-2"></i>Requests Payment Form</span>
                        <!-- Download PDF button -->
                        <button class="downloadbtn" onclick="downloadTablePDF()">
                            <i class="fas fa-download me-1"></i>Download
                        </button>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive table-container">
                            <table class="table table-hover mb-0" id="requests-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Phone Number</th>
                                        <th>Profile</th>
                                        <!-- <th>Time</th> -->
                                        <th>Status</th>
                                        <th>Code</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-spinner fa-spin me-2"></i>Loading requests...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Voucher Inventory Section (3 categories: Available, Used, Total) -->
            <div class="col-lg-4">
                <div class="card main-card">
                    <div class="card-header-custom">
                        <span><i class="fas fa-boxes me-2"></i>Voucher Inventory</span>
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

    <!-- Modal for Rejecting Payment Requests -->
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

    <!-- Modal for Approving Payment Requests -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="approve-form" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">
                        <i class="fas fa-check-circle me-2"></i>Approve Payment Request
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="approve-request-id" name="id">

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-tag me-1"></i>Select Approval Type
                        </label>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success approval-type-btn" data-type="new">
                                <i class="fas fa-plus-circle me-2"></i>NEW - Regular Sale
                            </button>
                            <button type="button" class="btn btn-danger approval-type-btn" data-type="refund">
                                <i class="fas fa-undo me-2"></i>REFUND - No Revenue Count
                            </button>
                        </div>
                        <input type="hidden" id="approval-type" name="approval_type">
                        <div class="form-text mt-2">NEW: Counts as revenue | REFUND: No revenue impact</div>
                    </div>

                    <div class="alert alert-danger d-none" id="approve-error"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="approve-confirm-btn" disabled>
                        <i class="fas fa-check me-1"></i>Confirm Approval
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success/Error Toast Notifications -->
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

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jsPDF and autoTable for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

    <script>
        // Initialize Bootstrap components
        const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
        const approveModal = new bootstrap.Modal(document.getElementById('approveModal'));
        const actionToast = new bootstrap.Toast(document.getElementById('actionToast'));

        /**
         * Display toast notification to user
         * @param {string} message - Message to display
         * @param {boolean} isSuccess - Whether this is a success or error message
         */
        function showToast(message, isSuccess = true) {
            const toast = document.getElementById('actionToast');
            const icon = toast.querySelector('.toast-header i');
            const body = toast.querySelector('.toast-body');

            // Set appropriate icon based on message type
            if (isSuccess) {
                icon.className = 'fas fa-check-circle text-success me-2';
            } else {
                icon.className = 'fas fa-exclamation-circle text-danger me-2';
            }

            body.textContent = message;
            actionToast.show();
        }

        /**
         * Show auto-refresh indicator briefly
         */
        function showRefreshIndicator() {
            const indicator = document.getElementById('refresh-indicator');
            indicator.classList.add('show');
            setTimeout(() => indicator.classList.remove('show'), 1500);
        }

        /**
         * Update the live clock display in navigation
         */
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

        /**
         * Load and display dashboard statistics
         */
        function loadStats() {
            $.getJSON('?action=get_stats')
                .done(function(data) {
                    $('#pending-count').text(data.pending_requests + ' Pending');
                    $('#refund-count').text(data.refund_requests_monthly + ' Refunds');
                    $('#refund-amount').text('UGX ' + Number(data.refund_amount_monthly).toLocaleString());
                    $('#revenue-today').text('UGX ' + Number(data.revenue_today).toLocaleString());
                    $('#revenue-monthly').text('UGX ' + Number(data.revenue_monthly).toLocaleString());
                })
                .fail(function() {
                    console.error('Failed to load statistics');
                });
        }

        /**
         * Load and display payment requests in table format
         */
        function loadRequests() {
            $.getJSON('?action=get_requests')
                .done(function(data) {
                    const tbody = $('#requests-table tbody');
                    tbody.empty();

                    // Display message if no requests found
                    if (data.length === 0) {
                        tbody.append(`
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox me-2"></i>No payment requests found
                                </td>
                            </tr>
                        `);
                        return;
                    }

                    // Populate table with request data
                    data.forEach(function(request) {
                        let statusBadge = '';
                        let actionsHtml = '';

                        // Generate status badge and action buttons based on request status
                        switch (request.status) {
                            case 'pending':
                                statusBadge = '<span class="status-badge status-pending">Pending</span>';
                                actionsHtml = `
                                    <button class="btn btn-success btn-sm me-1 approve-btn" data-id="${request.request_id}" title="Approve Request">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm reject-btn" data-id="${request.request_id}" title="Reject Request">
                                        <i class="fas fa-times"></i>
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
                            case 'refund':
                                statusBadge = '<span class="status-badge status-refund">Refund</span>';
                                actionsHtml = '<small class="text-muted">Refunded</small>';
                                break;
                            default:
                                statusBadge = `<span class="status-badge">${request.status}</span>`;
                                actionsHtml = '<small class="text-muted">-</small>';
                        }

                        // Display voucher code if available
                        const voucherCode = request.voucher_code ?
                            `<code class="bg-light px-2 py-1 rounded">${request.voucher_code}</code>` :
                            '<small class="text-muted">-</small>';

                        // Add row to table
                        const regularClientClass = request.phone_count >= 3 ? 'regular-client' : '';
                        tbody.append(`
                            <tr class="${regularClientClass}">
                                <td><strong>${request.request_id}</strong></td>
                                <td>${request.phone_formatted}</td>
                                <td><span class="badge bg-secondary">${request.package_profile}</span></td>
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
                            <td colspan="6" class="text-center py-4 text-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>Failed to load requests
                            </td>
                        </tr>
                    `);
                });
        }

        /**
         * Load and display voucher inventory (Available, Used, Total only)
         */
        function loadInventory() {
            $.getJSON('?action=get_inventory')
                .done(function(data) {
                    const container = $('#inventory-container');
                    container.empty();

                    // Map table names to user-friendly titles
                    const tableNames = {
                        'hourly_vouchers': 'Short Pass (5 Hours)',
                        'daily_vouchers': 'Daily Vouchers (24 Hours)',
                        'weekly_vouchers': 'Weekly Vouchers (1 Week)',
                        'monthly_vouchers': 'Monthly Vouchers (1 Month)'
                    };

                    // Generate inventory display for each voucher type
                    Object.entries(data).forEach(function([table, counts]) {
                        const title = tableNames[table] || table;
                        const totalVouchers = counts.Total || 0;
                        const availablePercent = totalVouchers > 0 ? Math.round((counts.Available / totalVouchers) * 100) : 0;

                        // Set alert color based on availability percentage
                        let alertClass = '';
                        if (availablePercent < 20) alertClass = 'border-danger';
                        else if (availablePercent < 50) alertClass = 'border-warning';
                        else alertClass = 'border-success';

                        // Create inventory item (3 widgets: Available, Used, Total)
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

        /**
         * Refresh all dashboard data (requests, inventory, and stats)
         */
        function refreshAllData() {
            //showRefreshIndicator();
            loadRequests();
            loadInventory();
            loadStats();
        }

        /**
         * Handle approval of a payment request
         * @param {string} requestId - The request ID to approve
         */
        function handleApprove(requestId) {
            $('#approve-request-id').val(requestId);
            $('#approval-type').val('');
            $('#approve-error').addClass('d-none').text('');
            $('#approve-confirm-btn').prop('disabled', true);

            // Reset button selection
            $('.approval-type-btn').removeClass('btn-success btn-danger').addClass('btn-outline-secondary');

            approveModal.show();
        }


        /**
         * Handle rejection of a payment request
         * @param {string} requestId - The request ID to reject
         */
        function handleReject(requestId) {
            $('#reject-request-id').val(requestId);
            $('#reject-reason').val('');
            $('#reject-error').addClass('d-none').text('');
            rejectModal.show();
        }

        // Initialize page when DOM is ready
        $(document).ready(function() {
            // Load initial data
            refreshAllData();
            updateCurrentTime();

            // Update live clock every second
            setInterval(updateCurrentTime, 1000);

            // Auto-refresh all data every 5 seconds
            setInterval(function() {
                refreshAllData();
            }, 5000);

            // Event handlers for approve/reject buttons
            $(document).on('click', '.approve-btn', function() {
                const requestId = $(this).data('id');
                handleApprove(requestId);
            });

            $(document).on('click', '.reject-btn', function() {
                const requestId = $(this).data('id');
                handleReject(requestId);
            });

            // Handle reject form submission
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

                $.post('?action=reject', {
                        id: requestId,
                        reason: reason
                    })
                    .done(function(response) {
                        try {
                            // Parse response if it's a string
                            const data = typeof response === 'string' ? JSON.parse(response) : response;

                            if (data.success) {
                                rejectModal.hide();
                                showToast(`Request rejected`);
                                refreshAllData(); // Refresh all data after rejection
                            } else {
                                rejectModal.hide();
                                showToast(`Request rejected`);
                                refreshAllData();
                            }
                        } catch (e) {
                            $('#reject-error').removeClass('d-none').text('Invalid response format.');
                        }
                        submitBtn.html(originalHtml).prop('disabled', false);
                    });
            });

            // Clear modal form when hidden
            $('#rejectModal').on('hidden.bs.modal', function() {
                $('#reject-reason').val('');
                $('#reject-error').addClass('d-none').text('');
            });
        });


        // Handle approval type button selection
        $(document).on('click', '.approval-type-btn', function() {
            const type = $(this).data('type');
            $('#approval-type').val(type);

            // Update button styling
            $('.approval-type-btn').removeClass('btn-success btn-danger').addClass('btn-outline-secondary');

            if (type === 'new') {
                $(this).removeClass('btn-outline-secondary').addClass('btn-success');
            } else {
                $(this).removeClass('btn-outline-secondary').addClass('btn-danger');
            }

            // Enable confirm button
            $('#approve-confirm-btn').prop('disabled', false);
        });

        // Handle approve form submission
        $('#approve-form').on('submit', function(e) {
            e.preventDefault();

            const requestId = $('#approve-request-id').val();
            const approvalType = $('#approval-type').val();

            if (!approvalType) {
                $('#approve-error').removeClass('d-none').text('Please select an approval type.');
                return;
            }

            const submitBtn = $('#approve-confirm-btn');
            const originalHtml = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...').prop('disabled', true);

            $.post('?action=approve', {
                    id: requestId,
                    approval_type: approvalType
                })
                .done(function(response) {
                    try {
                        const data = typeof response === 'string' ? JSON.parse(response) : response;

                        if (data.success) {
                            approveModal.hide();
                            const message = approvalType === 'refund' ?
                                'Request refunded successfully' :
                                'Request approved successfully';
                            showToast(message);
                            refreshAllData();
                        } else {
                            $('#approve-error').removeClass('d-none').text(data.message || 'Failed to process request');
                        }
                    } catch (e) {
                        $('#approve-error').removeClass('d-none').text('Invalid response format.');
                    }
                    submitBtn.html(originalHtml).prop('disabled', false);
                })
                .fail(function() {
                    $('#approve-error').removeClass('d-none').text('Network error. Please try again.');
                    submitBtn.html(originalHtml).prop('disabled', false);
                });
        });

        // Clear approve modal form when hidden
        $('#approveModal').on('hidden.bs.modal', function() {
            $('#approval-type').val('');
            $('#approve-error').addClass('d-none').text('');
            $('.approval-type-btn').removeClass('btn-success btn-danger').addClass('btn-outline-secondary');
            $('#approve-confirm-btn').prop('disabled', true);
        });

        // Keyboard shortcuts
        $(document).keydown(function(e) {
            // Ctrl/Cmd + R to refresh all data
            if ((e.ctrlKey || e.metaKey) && e.keyCode === 82) {
                e.preventDefault();
                refreshAllData();
            }
        });

        /**
         * Download requests table as PDF
         */
        /**
         * Download requests table as PDF - Centered Layout
         */
        function downloadTablePDF() {
            try {
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF('l', 'mm', 'a4'); // Landscape orientation (297mm x 210mm)

                const pageWidth = doc.internal.pageSize.getWidth(); // 297mm
                const pageHeight = doc.internal.pageSize.getHeight(); // 210mm
                const centerX = pageWidth / 2;

                // Add company logo/header - CENTERED
                doc.setFontSize(20);
                doc.setTextColor(66, 153, 225);
                doc.setFont(undefined, 'bold');
                doc.text('FastNetUG', centerX, 15, {
                    align: 'center'
                });

                doc.setFontSize(16);
                doc.setTextColor(45, 55, 72);
                doc.text('Payment Requests Report', centerX, 24, {
                    align: 'center'
                });

                // Add generation date and time - CENTERED
                doc.setFontSize(9);
                doc.setTextColor(100);
                doc.setFont(undefined, 'normal');
                const now = new Date();
                const dateStr = now.toLocaleString('en-US', {
                    timeZone: 'Africa/Kampala',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                });
                doc.text('Generated: ' + dateStr, centerX, 32, {
                    align: 'center'
                });

                // Prepare table data from the HTML table
                const tableData = [];
                const rows = $('#requests-table tbody tr');

                // Check if there are any rows with actual data
                if (rows.length === 0 || rows.first().find('td').length === 1) {
                    showToast('No data available to download', false);
                    return;
                }

                // Extract data from each row
                rows.each(function() {
                    const row = $(this);
                    // Skip rows with loading/error messages (colspan rows)
                    if (row.find('td').length === 1) return;

                    const id = row.find('td:eq(0)').text().trim();
                    const phone = row.find('td:eq(1)').text().trim();
                    const profile = row.find('td:eq(2)').text().trim();
                    const status = row.find('td:eq(3)').text().trim();
                    const code = row.find('td:eq(4)').text().trim() || 'N/A';

                    // Only add rows with valid data
                    if (id && phone) {
                        tableData.push([id, phone, profile, status, code]);
                    }
                });

                // Check if we have data to export
                if (tableData.length === 0) {
                    showToast('No requests available to download', false);
                    return;
                }

                // Add summary statistics - CENTERED
                doc.setFontSize(9);
                doc.setTextColor(100);

                // Count requests by status
                const statusCounts = {};
                tableData.forEach(row => {
                    const status = row[3];
                    statusCounts[status] = (statusCounts[status] || 0) + 1;
                });

                // Build summary text
                let summaryText = `Total Requests: ${tableData.length}  |  `;
                const statusTexts = [];
                Object.entries(statusCounts).forEach(([status, count]) => {
                    statusTexts.push(`${status}: ${count}`);
                });
                summaryText += statusTexts.join('  |  ');

                doc.text(summaryText, centerX, 40, {
                    align: 'center'
                });

                // Calculate table width and center it
                const tableWidth = 240; // Total width of table
                const tableStartX = (pageWidth - tableWidth) / 2; // Center the table

                // Generate table in PDF - CENTERED
                doc.autoTable({
                    startY: 46,
                    head: [
                        ['Request ID', 'Phone Number', 'Package', 'Status', 'Voucher Code']
                    ],
                    body: tableData,
                    theme: 'grid',
                    styles: {
                        fontSize: 9,
                        cellPadding: 4,
                        overflow: 'linebreak',
                        halign: 'center',
                        valign: 'middle',
                        lineWidth: 0.1,
                        lineColor: [200, 200, 200]
                    },
                    headStyles: {
                        fillColor: [66, 153, 225],
                        textColor: [255, 255, 255],
                        fontStyle: 'bold',
                        halign: 'center',
                        fontSize: 10,
                        cellPadding: 5
                    },
                    columnStyles: {
                        0: {
                            cellWidth: 35,
                            halign: 'center'
                        }, // Request ID
                        1: {
                            cellWidth: 55,
                            halign: 'center'
                        }, // Phone Number
                        2: {
                            cellWidth: 30,
                            halign: 'center'
                        }, // Package
                        3: {
                            cellWidth: 35,
                            halign: 'center'
                        }, // Status
                        4: {
                            cellWidth: 55,
                            halign: 'center'
                        } // Voucher Code
                    },
                    alternateRowStyles: {
                        fillColor: [247, 250, 252]
                    },
                    margin: {
                        left: tableStartX,
                        right: tableStartX
                    },
                    tableWidth: tableWidth,
                    didParseCell: function(data) {
                        // Color code status cells
                        if (data.column.index === 3 && data.section === 'body') {
                            const status = data.cell.text[0].toLowerCase();
                            if (status === 'pending') {
                                data.cell.styles.textColor = [66, 153, 225];
                                data.cell.styles.fontStyle = 'bold';
                            } else if (status === 'approved') {
                                data.cell.styles.textColor = [72, 187, 120];
                                data.cell.styles.fontStyle = 'bold';
                            } else if (status === 'rejected') {
                                data.cell.styles.textColor = [229, 62, 62];
                                data.cell.styles.fontStyle = 'bold';
                            } else if (status === 'refund') {
                                data.cell.styles.textColor = [237, 137, 54];
                                data.cell.styles.fontStyle = 'bold';
                            }
                        }
                    }
                });

                // Add footer with page numbers and company info - CENTERED
                const pageCount = doc.internal.getNumberOfPages();
                for (let i = 1; i <= pageCount; i++) {
                    doc.setPage(i);

                    // Page number - CENTER
                    doc.setFontSize(8);
                    doc.setTextColor(150);
                    doc.text(
                        'Page ' + i + ' of ' + pageCount,
                        centerX,
                        pageHeight - 10, {
                            align: 'center'
                        }
                    );

                    // Company info - LEFT
                    doc.text(
                        'FastNetUG',
                        20,
                        pageHeight - 10
                    );

                    // Timestamp - RIGHT
                    doc.text(
                        dateStr.split(',')[0], // Just the date part
                        pageWidth - 20,
                        pageHeight - 10, {
                            align: 'right'
                        }
                    );
                }

                // Generate filename with timestamp
                const timestamp = now.toISOString().slice(0, 10).replace(/-/g, '') + '_' +
                    String(now.getHours()).padStart(2, '0') +
                    String(now.getMinutes()).padStart(2, '0') +
                    String(now.getSeconds()).padStart(2, '0');
                const filename = 'FastNetUG_Payment_Requests_' + timestamp + '.pdf';

                // Save the PDF
                doc.save(filename);

                showToast('PDF downloaded successfully: ' + tableData.length + ' requests', true);

            } catch (error) {
                console.error('PDF generation error:', error);
                showToast('Failed to generate PDF: ' + error.message, false);
            }
        }
    </script>
</body>

</html>