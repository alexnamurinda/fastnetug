<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

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
    $data = ['username' => $username, 'to' => $phone, 'message' => $message];
    $ch = curl_init('https://api.africastalking.com/version1/messaging');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/x-www-form-urlencoded',
        'apiKey: ' . $apikey
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    // Return true only if 201 Created returned
    return $httpCode === 201;
}

function getVoucherTable($package) {
    $package = strtolower($package);
    if (strpos($package, 'week') !== false) return 'weekly_vouchers';
    if (strpos($package, 'month') !== false) return 'monthly_vouchers';
    return 'daily_vouchers';
}

// ==== AJAX HANDLERS ====

if (isset($_GET['action'])) {
    $pdo = db();

    if ($_GET['action'] === 'get_requests') {
        $stmt = $pdo->prepare("SELECT request_id, phone, package, price, created_at, status, reject_reason FROM voucher_requests ORDER BY FIELD(status, 'pending', 'approved', 'rejected') ASC, created_at DESC LIMIT 100");
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
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
        }
        echo json_encode($inventory);
        exit;
    }

    // Approve request
    if ($_GET['action'] === 'approve' && !empty($_POST['id'])) {
        $request_id = $_POST['id'];
        try {
            $pdo->beginTransaction();

            // Lock the request row FOR UPDATE to avoid race
            $stmt = $pdo->prepare("SELECT * FROM voucher_requests WHERE request_id = ? AND status = 'pending' FOR UPDATE");
            $stmt->execute([$request_id]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$request) {
                $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => 'Request not found or already processed.']);
                exit;
            }

            $voucher_table = getVoucherTable($request['package']);

            // Lock voucher row for update
            $stmt = $pdo->prepare("SELECT * FROM $voucher_table WHERE status = 'Available' ORDER BY created_at ASC LIMIT 1 FOR UPDATE");
            $stmt->execute();
            $voucher = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$voucher) {
                $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => "No available vouchers for package '{$request['package']}'"]);
                exit;
            }

            // Update voucher to Used and assign phone
            $stmt = $pdo->prepare("UPDATE $voucher_table SET status = 'Used', user_phone = ?, used_at = NOW() WHERE id = ?");
            $stmt->execute([$request['phone'], $voucher['id']]);

            // Update request status to approved and save voucher code
            $stmt = $pdo->prepare("UPDATE voucher_requests SET status = 'approved', voucher_code = ?, approved_at = NOW() WHERE request_id = ?");
            $stmt->execute([$voucher['voucher_code'], $request_id]);

            // Log action
            $stmt = $pdo->prepare("INSERT INTO system_logs (log_type, reference_id, user_identifier, action_description, ip_address) VALUES ('voucher_approval', ?, 'admin', ?, ?)");
            $desc = "Approved payment, assigned voucher {$voucher['voucher_code']} to {$request['phone']}";
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $stmt->execute([$request_id, $desc, $ip]);

            $pdo->commit();

            // Send SMS to user
            $sms_message = "FastNetUG: Payment approved! Your voucher code is {$voucher['voucher_code']}. Valid for {$request['package']}. Use this to login.";
            sendSMS($request['phone'], $sms_message);

            echo json_encode(['success' => true, 'message' => 'Request approved and voucher sent.']);
            exit;

        } catch (Exception $ex) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Error approving request: ' . $ex->getMessage()]);
            exit;
        }
    }

    // Reject request (with reason)
    if ($_GET['action'] === 'reject' && !empty($_POST['id']) && isset($_POST['reason'])) {
        $request_id = $_POST['id'];
        $reason = trim($_POST['reason']);
        if ($reason === '') {
            echo json_encode(['success' => false, 'message' => 'Rejection reason required.']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("SELECT * FROM voucher_requests WHERE request_id = ? AND status = 'pending'");
            $stmt->execute([$request_id]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$request) {
                echo json_encode(['success' => false, 'message' => 'Request not found or already processed.']);
                exit;
            }

            // Update request status and save reject reason
            $stmt = $pdo->prepare("UPDATE voucher_requests SET status = 'rejected', reject_reason = ?, approved_at = NOW() WHERE request_id = ?");
            $stmt->execute([$reason, $request_id]);

            // Log action
            $stmt = $pdo->prepare("INSERT INTO system_logs (log_type, reference_id, user_identifier, action_description, ip_address) VALUES ('voucher_rejection', ?, 'admin', ?, ?)");
            $desc = "Rejected payment request for {$request['phone']}. Reason: $reason";
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $stmt->execute([$request_id, $desc, $ip]);

            // Notify user by SMS
            $sms_message = "FastNetUG: Your payment request (ID: $request_id) was rejected. Reason: $reason. Contact support for assistance.";
            sendSMS($request['phone'], $sms_message);

            echo json_encode(['success' => true, 'message' => 'Request rejected and user notified.']);
            exit;

        } catch (Exception $ex) {
            echo json_encode(['success' => false, 'message' => 'Error rejecting request: ' . $ex->getMessage()]);
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
    <meta charset="UTF-8" />
    <title>FastNetUG Admin - Approve Payments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: #f7fafc;
        }
        .voucher-status-pending { color: #0d6efd; font-weight: 600; }
        .voucher-status-approved { color: #198754; font-weight: 600; }
        .voucher-status-rejected { color: #dc3545; font-weight: 600; }
        .fade-in {
            animation: fadeIn 0.7s ease forwards;
            opacity: 0;
        }
        @keyframes fadeIn { to { opacity: 1; } }
    </style>
</head>
<body>
<div class="container my-4">
    <h1 class="mb-4">FastNetUG - Voucher Payment Requests</h1>

    <div class="row">
        <div class="col-lg-8">
            <h4>Pending and Processed Requests</h4>
            <div class="table-responsive shadow-sm rounded bg-white p-3">
                <table class="table table-hover" id="requests-table">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Phone</th>
                            <th>Package</th>
                            <th>Price (UGX)</th>
                            <th>Requested At</th>
                            <th>Status</th>
                            <th>Reject Reason</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-4">
            <h4>Voucher Inventory</h4>
            <div class="shadow-sm rounded bg-white p-3">
                <ul class="list-group" id="inventory-list"></ul>
            </div>
        </div>
    </div>
</div>

<!-- Reject Reason Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="reject-form" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rejectModalLabel">Reject Payment Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" id="reject-request-id" name="id" />
          <div class="mb-3">
            <label for="reject-reason" class="form-label">Reason for rejection</label>
            <textarea class="form-control" id="reject-reason" name="reason" rows="3" required></textarea>
          </div>
          <div class="alert alert-danger d-none" id="reject-error"></div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger">Reject Request</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'), {});

function loadRequests() {
    $.getJSON('?action=get_requests', function(data) {
        const tbody = $('#requests-table tbody');
        tbody.empty();
        data.forEach(req => {
            let statusClass = '';
            if (req.status === 'pending') statusClass = 'voucher-status-pending';
            else if (req.status === 'approved') statusClass = 'voucher-status-approved';
            else if (req.status === 'rejected') statusClass = 'voucher-status-rejected';

            let rejectReason = req.reject_reason ? req.reject_reason : '-';

            let actionsHtml = '';
            if (req.status === 'pending') {
                actionsHtml = `
                <button class="btn btn-success btn-sm approve-btn me-1" data-id="${req.request_id}">Approve</button>
                <button class="btn btn-danger btn-sm reject-btn" data-id="${req.request_id}">Reject</button>`;
            } else {
                actionsHtml = '<em>No actions</em>';
            }

            tbody.append(`
                <tr class="fade-in">
                    <td>${req.request_id}</td>
                    <td>${req.phone}</td>
                    <td>${req.package}</td>
                    <td>${Number(req.price).toLocaleString()}</td>
                    <td>${req.created_at}</td>
                    <td class="${statusClass}">${req.status.charAt(0).toUpperCase() + req.status.slice(1)}</td>
                    <td>${rejectReason}</td>
                    <td class="text-center">${actionsHtml}</td>
                </tr>
            `);
        });
    });
}

function loadInventory() {
    $.getJSON('?action=get_inventory', function(data) {
        const list = $('#inventory-list').empty();
        Object.entries(data).forEach(([table, counts]) => {
            const cleanName = table.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
            list.append(`
                <li class="list-group-item">
                    <strong>${cleanName}</strong><br/>
                    Available: ${counts.Available ?? 0} | Used: ${counts.Used ?? 0} | Expired: ${counts.Expired ?? 0}
                </li>
            `);
        });
    });
}

$(document).ready(function() {
    loadRequests();
    loadInventory();

    // Poll every 2 seconds
    setInterval(() => {
        loadRequests();
        loadInventory();
    }, 2000);

    // Approve
    $(document).on('click', '.approve-btn', function() {
        const id = $(this).data('id');
        if (!confirm("Approve this payment request?")) return;

        $.post('?action=approve', {id}, function(res) {
            alert(res.message || (res.success ? "Approved successfully." : "Failed."));
            loadRequests();
            loadInventory();
        }, 'json').fail(() => alert('Error approving request.'));
    });

    // Reject - open modal
    $(document).on('click', '.reject-btn', function() {
        const id = $(this).data('id');
        $('#reject-request-id').val(id);
        $('#reject-reason').val('');
        $('#reject-error').addClass('d-none').text('');
        rejectModal.show();
    });

    // Submit reject form
    $('#reject-form').submit(function(e) {
        e.preventDefault();
        const id = $('#reject-request-id').val();
        const reason = $('#reject-reason').val().trim();
        if (!reason) {
            $('#reject-error').removeClass('d-none').text('Please enter a rejection reason.');
            return;
        }
        $.post('?action=reject', {id, reason}, function(res) {
            if (res.success) {
                rejectModal.hide();
                loadRequests();
                alert('Request rejected and user notified.');
            } else {
                $('#reject-error').removeClass('d-none').text(res.message || 'Failed to reject.');
            }
        }, 'json').fail(() => {
            $('#reject-error').removeClass('d-none').text('Error processing rejection.');
        });
    });
});
</script>

</body>
</html>
