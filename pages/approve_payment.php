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

$request_id = $_GET['request_id'] ?? '';
$action = $_POST['action'] ?? '';
$message = '';
$request_details = null;

// Fetch request details
if (!empty($request_id)) {
    $stmt = $pdo->prepare("
        SELECT * FROM payment_requests 
        WHERE request_id = ? AND status = 'pending' AND expires_at > NOW()
    ");
    $stmt->execute([$request_id]);
    $request_details = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle approval/rejection
if (!empty($action) && !empty($request_id) && $request_details) {
    if ($action === 'approve') {
        // Generate voucher code
        $voucher_code = generateVoucherCode();
        $duration_hours = getDurationHours($request_details['package_name']);
        $voucher_expires = date('Y-m-d H:i:s', strtotime("+{$duration_hours} hours"));

        try {
            $pdo->beginTransaction();

            // Update payment request
            $updateStmt = $pdo->prepare("
                UPDATE payment_requests 
                SET status = 'approved', voucher_code = ?, approved_at = NOW(), approved_by = 'admin' 
                WHERE request_id = ?
            ");
            $updateStmt->execute([$voucher_code, $request_id]);

            // Insert voucher code
            $voucherStmt = $pdo->prepare("
                INSERT INTO voucher_codes 
                (voucher_code, package_name, package_price, duration_hours, expires_at, created_by_request_id) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $voucherStmt->execute([
                $voucher_code,
                $request_details['package_name'],
                $request_details['package_price'],
                $duration_hours,
                $voucher_expires,
                $request_id
            ]);

            // Log the action
            $logStmt = $pdo->prepare("
                INSERT INTO system_logs 
                (log_type, reference_id, user_identifier, action_description, ip_address) 
                VALUES ('payment_approval', ?, 'admin', ?, ?)
            ");
            $logStmt->execute([
                $request_id,
                "Payment approved and voucher {$voucher_code} generated for {$request_details['phone']}",
                $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);

            $pdo->commit();

            // Send voucher to customer
            $sms_message = "FastNetUG: Your payment has been approved! Your voucher code is: {$voucher_code}. Valid for {$request_details['package_name']}. Use this code to login.";
            sendSMS($request_details['phone'], $sms_message);

            $message = "Payment approved successfully! Voucher code {$voucher_code} has been sent to {$request_details['phone']}.";
            $request_details['status'] = 'approved';
            $request_details['voucher_code'] = $voucher_code;
        } catch (Exception $e) {
            $pdo->rollback();
            $message = "Error approving payment: " . $e->getMessage();
        }
    } elseif ($action === 'reject') {
        $reject_reason = $_POST['reject_reason'] ?? 'No reason provided';

        try {
            $updateStmt = $pdo->prepare("
                UPDATE payment_requests 
                SET status = 'rejected', approved_at = NOW(), approved_by = 'admin', notes = ? 
                WHERE request_id = ?
            ");
            $updateStmt->execute([$reject_reason, $request_id]);

            // Log the action
            $logStmt = $pdo->prepare("
                INSERT INTO system_logs 
                (log_type, reference_id, user_identifier, action_description, ip_address) 
                VALUES ('payment_approval', ?, 'admin', ?, ?)
            ");
            $logStmt->execute([
                $request_id,
                "Payment rejected for {$request_details['phone']}. Reason: {$reject_reason}",
                $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);

            // Notify customer
            $sms_message = "FastNetUG: Your payment request has been rejected. Reason: {$reject_reason}. Please contact us on 0744766410 for assistance.";
            sendSMS($request_details['phone'], $sms_message);

            $message = "Payment rejected successfully. Customer has been notified.";
            $request_details['status'] = 'rejected';
        } catch (Exception $e) {
            $message = "Error rejecting payment: " . $e->getMessage();
        }
    }
}

// Generate unique voucher code
if (!function_exists('generateVoucherCode')) {
    function generateVoucherCode()
    {
        return 'FN' . strtoupper(substr(uniqid(), -8));
    }
}

// Get duration hours based on package name
if (!function_exists('getDurationHours')) {
    function getDurationHours($package_name)
    {
        $hours_map = [
            '24 HOURS' => 24,
            '1 WEEK' => 168,  // 7 * 24
            '1 MONTH' => 720  // 30 * 24
        ];

        return $hours_map[$package_name] ?? 24;
    }
}

// SMS function (same as in voucher_request.php)
function sendSMS($phone, $message)
{
    return sendSMS_AfricasTalking($phone, $message);
}

function sendSMS_AfricasTalking($phone, $message)
{
    $username = 'agritech_info';  // Replace with your Africa's Talking username
    $apikey = 'atsk_1eb8e8aa4cf9f3851dabd1bf4490983972432730c57f36cfcf51980d3047884b7d19c9c3';   // Replace with your Africa's Talking API key

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
    <title>FastNetUG - Payment Approval</title>
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
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4299e1, #3182ce);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .content {
            padding: 30px;
        }

        .request-details {
            background: #f7fafc;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #4299e1;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .label {
            font-weight: 600;
            color: #4a5568;
        }

        .value {
            color: #2d3748;
        }

        .actions {
            display: grid;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-approve {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
        }

        .btn-approve:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(72, 187, 120, 0.4);
        }

        .btn-reject {
            background: linear-gradient(135deg, #f56565, #e53e3e);
            color: white;
        }

        .btn-reject:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(245, 101, 101, 0.4);
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #c6f6d5, #9ae6b4);
            color: #22543d;
            border-left: 4px solid #38a169;
        }

        .alert-error {
            background: linear-gradient(135deg, #fed7d7, #feb2b2);
            color: #742a2a;
            border-left: 4px solid #e53e3e;
        }

        .alert-info {
            background: linear-gradient(135deg, #bee3f8, #90cdf4);
            color: #2a4365;
            border-left: 4px solid #4299e1;
        }

        .reject-form {
            display: none;
            background: #f7fafc;
            padding: 20px;
            border-radius: 10px;
            margin-top: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #4a5568;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
        }

        .form-control:focus {
            outline: none;
            border-color: #4299e1;
        }

        .status-approved {
            color: #38a169;
            font-weight: 600;
        }

        .status-rejected {
            color: #e53e3e;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>FastNetUG Payment Approval</h1>
            <p>Review and approve payment requests</p>
        </div>

        <div class="content">
            <?php if (!empty($message)): ?>
                <div class="alert <?php echo strpos($message, 'Error') !== false ? 'alert-error' : 'alert-success'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if (!$request_details): ?>
                <div class="alert alert-error">
                    <strong>Request not found or expired!</strong><br>
                    The payment request you're looking for either doesn't exist, has already been processed, or has expired.
                </div>
            <?php else: ?>
                <div class="request-details">
                    <h3>Payment Request Details</h3>
                    <div class="detail-row">
                        <span class="label">Request ID:</span>
                        <span class="value"><?php echo htmlspecialchars($request_details['request_id']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Customer Phone:</span>
                        <span class="value"><?php echo htmlspecialchars($request_details['phone']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Device MAC Address:</span>
                        <span class="value"><?php echo htmlspecialchars($request_details['mac_address']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Package:</span>
                        <span class="value"><?php echo htmlspecialchars($request_details['package_name']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Amount:</span>
                        <span class="value">UGX <?php echo number_format($request_details['package_price']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Request Time:</span>
                        <span class="value"><?php echo date('M j, Y g:i A', strtotime($request_details['created_at'])); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Status:</span>
                        <span class="value <?php echo $request_details['status'] == 'approved' ? 'status-approved' : ($request_details['status'] == 'rejected' ? 'status-rejected' : ''); ?>">
                            <?php echo ucfirst($request_details['status']); ?>
                            <?php if ($request_details['status'] == 'approved' && !empty($request_details['voucher_code'])): ?>
                                - Code: <?php echo htmlspecialchars($request_details['voucher_code']); ?>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <?php if ($request_details['status'] == 'pending'): ?>
                    <div class="actions">
                        <form method="POST" style="display: contents;">
                            <button type="submit" name="action" value="approve" class="btn btn-approve">
                                ✅ Approve Payment & Generate Voucher
                            </button>
                        </form>

                        <button type="button" class="btn btn-reject" onclick="toggleRejectForm()">
                            ❌ Reject Payment
                        </button>

                        <div class="reject-form" id="reject-form">
                            <form method="POST">
                                <div class="form-group">
                                    <label for="reject_reason">Rejection Reason:</label>
                                    <textarea
                                        name="reject_reason"
                                        id="reject_reason"
                                        class="form-control"
                                        rows="3"
                                        placeholder="Enter reason for rejection..."
                                        required></textarea>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                    <button type="submit" name="action" value="reject" class="btn btn-reject">
                                        Confirm Rejection
                                    </button>
                                    <button type="button" class="btn" onclick="toggleRejectForm()" style="background: #a0aec0; color: white;">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php elseif ($request_details['status'] == 'approved'): ?>
                    <div class="alert alert-success">
                        <strong>✅ Payment Approved!</strong><br>
                        Voucher code <strong><?php echo htmlspecialchars($request_details['voucher_code']); ?></strong>
                        has been sent to the customer.
                    </div>
                <?php elseif ($request_details['status'] == 'rejected'): ?>
                    <div class="alert alert-error">
                        <strong>❌ Payment Rejected</strong><br>
                        This request has been rejected and the customer has been notified.
                        <?php if (!empty($request_details['notes'])): ?>
                            <br><strong>Reason:</strong> <?php echo htmlspecialchars($request_details['notes']); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Recent Requests Section -->
            <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid #e2e8f0;">
                <h3>Recent Payment Requests</h3>
                <div style="margin-top: 20px;">
                    <?php
                    // Fetch recent requests
                    $recentStmt = $pdo->prepare("
                        SELECT request_id, phone, package_name, package_price, status, created_at 
                        FROM payment_requests 
                        ORDER BY created_at DESC 
                        LIMIT 10
                    ");
                    $recentStmt->execute();
                    $recentRequests = $recentStmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <?php if (empty($recentRequests)): ?>
                        <div class="alert alert-info">
                            No payment requests found.
                        </div>
                    <?php else: ?>
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background: #f7fafc;">
                                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0;">Request ID</th>
                                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0;">Phone</th>
                                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0;">Package</th>
                                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0;">Amount</th>
                                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0;">Status</th>
                                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0;">Date</th>
                                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentRequests as $req): ?>
                                        <tr>
                                            <td style="padding: 10px; border-bottom: 1px solid #e2e8f0; font-family: monospace; font-size: 0.9rem;">
                                                <?php echo htmlspecialchars($req['request_id']); ?>
                                            </td>
                                            <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">
                                                <?php echo htmlspecialchars($req['phone']); ?>
                                            </td>
                                            <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">
                                                <?php echo htmlspecialchars($req['package_name']); ?>
                                            </td>
                                            <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">
                                                UGX <?php echo number_format($req['package_price']); ?>
                                            </td>
                                            <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">
                                                <span class="<?php echo $req['status'] == 'approved' ? 'status-approved' : ($req['status'] == 'rejected' ? 'status-rejected' : ''); ?>">
                                                    <?php echo ucfirst($req['status']); ?>
                                                </span>
                                            </td>
                                            <td style="padding: 10px; border-bottom: 1px solid #e2e8f0; font-size: 0.9rem;">
                                                <?php echo date('M j, g:i A', strtotime($req['created_at'])); ?>
                                            </td>
                                            <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">
                                                <?php if ($req['status'] == 'pending'): ?>
                                                    <a href="?request_id=<?php echo urlencode($req['request_id']); ?>"
                                                        style="color: #4299e1; text-decoration: none; font-weight: 600;">
                                                        Review
                                                    </a>
                                                <?php else: ?>
                                                    <span style="color: #a0aec0;">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleRejectForm() {
            const form = document.getElementById('reject-form');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
                document.getElementById('reject_reason').focus();
            } else {
                form.style.display = 'none';
            }
        }

        // Auto-refresh page every 30 seconds if viewing pending requests
        <?php if (empty($request_id)): ?>
            setTimeout(function() {
                window.location.reload();
            }, 30000);
        <?php endif; ?>
    </script>
</body>

</html>

<?php
// Helper function to generate voucher codes
if (!function_exists('generateVoucherCode')) {
    function generateVoucherCode()
    {
        // Generate a random 8-character alphanumeric code
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = 'FN';
        for ($i = 0; $i < 6; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }
}

// Helper function to get duration hours
if (!function_exists('getDurationHours')) {
    function getDurationHours($package_name)
    {
        $hours_map = [
            '24 HOURS' => 24,
            '1 WEEK' => 168,   // 7 * 24
            '1 MONTH' => 720   // 30 * 24
        ];

        return $hours_map[$package_name] ?? 24;
    }
}
?>