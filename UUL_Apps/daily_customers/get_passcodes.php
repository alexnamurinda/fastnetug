<?php
/**
 * Password Management API
 * For use by system developer only
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'uul_user');
define('DB_PASS', 'uul@mysql123');
define('DB_NAME', 'sales_dashboard');

header('Content-Type: application/json');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

$conn->set_charset('utf8mb4');

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'resetUserPasscode':
        resetUserPasscode($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

$conn->close();

function resetUserPasscode($conn)
{
    $userId = intval($_POST['userId'] ?? 0);
    $newPasscode = $_POST['newPasscode'] ?? '';

    if ($userId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
        return;
    }

    if (empty($newPasscode)) {
        echo json_encode(['success' => false, 'message' => 'Passcode is required']);
        return;
    }

    if (strlen($newPasscode) < 4) {
        echo json_encode(['success' => false, 'message' => 'Passcode must be at least 4 characters']);
        return;
    }

    // Hash the new passcode
    $hashedPasscode = hash('sha256', $newPasscode);

    // Update the passcode
    $stmt = $conn->prepare("UPDATE sales_persons SET passcode = ? WHERE id = ?");
    $stmt->bind_param("si", $hashedPasscode, $userId);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Passcode reset successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error resetting passcode: ' . $conn->error
        ]);
    }

    $stmt->close();
}
?>