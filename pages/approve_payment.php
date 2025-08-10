<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

// ==== CONFIGURATION ====
$servername = "localhost";
$username = "fastnetug_user1";
$password = "smartwatt@mysql123";
$dbname = "fastnet_db";

// ==== HELPER: DB CONNECTION ====
function db() {
    global $servername, $username, $password, $dbname;
    static $pdo;
    if (!$pdo) {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
}

// ==== HELPER: SEND SMS ====
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
    curl_close($ch);
    return $response;
}

// ==== AJAX HANDLERS ====
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'get_requests') {
        $stmt = db()->query("SELECT * FROM voucher_requests WHERE status = 'pending' ORDER BY created_at DESC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    if ($_GET['action'] === 'get_inventory') {
        $tables = ['daily_vouchers', 'weekly_vouchers', 'monthly_vouchers'];
        $inventory = [];
        foreach ($tables as $table) {
            $stmt = db()->query("SELECT COUNT(*) as count FROM $table WHERE used = 0");
            $inventory[$table] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        }
        echo json_encode($inventory);
        exit;
    }

    if ($_GET['action'] === 'approve' && isset($_POST['id'])) {
        $pdo = db();
        $stmt = $pdo->prepare("SELECT * FROM voucher_requests WHERE request_id = ?");
        $stmt->execute([$_POST['id']]);
        $req = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$req) {
            echo json_encode(['success' => false, 'message' => 'Request not found']);
            exit;
        }

        // Pick voucher table
        $package = strtolower($req['package']);
        if (strpos($package, 'week') !== false) $table = 'weekly_vouchers';
        elseif (strpos($package, 'month') !== false) $table = 'monthly_vouchers';
        else $table = 'daily_vouchers';

        // Get voucher
        $stmt = $pdo->query("SELECT * FROM $table WHERE used = 0 LIMIT 1");
        $voucher = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$voucher) {
            echo json_encode(['success' => false, 'message' => 'No voucher available']);
            exit;
        }

        // Send SMS to user
        $msg = "Your FastNet voucher code: {$voucher['code']}\nValid for package: {$req['package']}";
        sendSMS($req['phone'], $msg);

        // Update voucher as used
        $stmt = $pdo->prepare("UPDATE $table SET used = 1, user_phone = ? WHERE id = ?");
        $stmt->execute([$req['phone'], $voucher['id']]);

        // Update request status
        $stmt = $pdo->prepare("UPDATE voucher_requests SET status = 'approved' WHERE request_id = ?");
        $stmt->execute([$req['request_id']]);

        echo json_encode(['success' => true]);
        exit;
    }

    if ($_GET['action'] === 'reject' && isset($_POST['id'])) {
        $stmt = db()->prepare("UPDATE voucher_requests SET status = 'rejected' WHERE request_id = ?");
        $stmt->execute([$_POST['id']]);
        echo json_encode(['success' => true]);
        exit;
    }
}

// ==== HTML VIEW ====
?>
<!DOCTYPE html>
<html>
<head>
    <title>Approve Payments - FastNetUG</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .fade-in { animation: fadeIn 1s ease-in; }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
    </style>
</head>
<body class="bg-light p-3">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <h3>Pending Payment Requests</h3>
            <table class="table table-striped" id="requests-table">
                <thead>
                    <tr>
                        <th>ID</th><th>Phone</th><th>Package</th><th>Price</th><th>Time</th><th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="col-md-3">
            <h3>Voucher Inventory</h3>
            <ul class="list-group" id="inventory-list"></ul>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function loadRequests() {
    $.getJSON('?action=get_requests', function(data) {
        let tbody = $('#requests-table tbody');
        let existing = {};
        tbody.find('tr').each(function() {
            existing[$(this).data('id')] = true;
        });
        tbody.empty();
        data.forEach(row => {
            let newRow = $('<tr>').attr('data-id', row.request_id);
            if (!existing[row.request_id]) newRow.addClass('fade-in');
            newRow.append(`<td>${row.request_id}</td>`);
            newRow.append(`<td>${row.phone}</td>`);
            newRow.append(`<td>${row.package}</td>`);
            newRow.append(`<td>${row.price}</td>`);
            newRow.append(`<td>${row.created_at}</td>`);
            newRow.append(`<td>
                <button class="btn btn-success btn-sm approve" data-id="${row.request_id}">Approve</button>
                <button class="btn btn-danger btn-sm reject" data-id="${row.request_id}">Reject</button>
            </td>`);
            tbody.append(newRow);
        });
    });
}

function loadInventory() {
    $.getJSON('?action=get_inventory', function(data) {
        let list = $('#inventory-list').empty();
        for (let table in data) {
            list.append(`<li class="list-group-item">${table.replace('_',' ')}: ${data[table]}</li>`);
        }
    });
}

$(document).on('click', '.approve', function() {
    let id = $(this).data('id');
    $.post('?action=approve', {id:id}, function(res) {
        loadRequests(); loadInventory();
    }, 'json');
});

$(document).on('click', '.reject', function() {
    let id = $(this).data('id');
    $.post('?action=reject', {id:id}, function(res) {
        loadRequests();
    }, 'json');
});

setInterval(loadRequests, 2000);
setInterval(loadInventory, 2000);
loadRequests();
loadInventory();
</script>
</body>
</html>
