<?php
/**
 * Utility Script to View and Reset Sales Person Passcodes
 * WARNING: This file should be deleted after use for security reasons
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'uul_user');
define('DB_PASS', 'uul@mysql123');
define('DB_NAME', 'sales_dashboard');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$action = $_GET['action'] ?? 'list';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passcode Utility</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .warning {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            color: #856404;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }
        .btn-primary {
            background: #4CAF50;
            color: white;
        }
        .btn-danger {
            background: #f44336;
            color: white;
        }
        .btn-warning {
            background: #ff9800;
            color: white;
        }
        .form-group {
            margin: 15px 0;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Sales Person Passcode Utility</h1>
        
        <div class="warning">
            <strong>⚠️ SECURITY WARNING:</strong><br>
            This utility provides access to sensitive information. Please:
            <ul>
                <li>Delete this file after use</li>
                <li>Do not leave it accessible on a production server</li>
                <li>Keep new passcodes secure</li>
            </ul>
        </div>

        <?php
        if ($action === 'list'):
            // Display all sales persons
            $sql = "SELECT id, name, role, created_at FROM sales_persons ORDER BY name ASC";
            $result = $conn->query($sql);
        ?>
            <h2>All Sales Persons</h2>
            <p><strong>Note:</strong> Passcodes are hashed (SHA-256) and cannot be retrieved. You can only reset them.</p>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>
                            <span style="color: <?= $row['role'] === 'supervisor' ? '#ff5722' : '#2196F3' ?>">
                                <?= ucfirst($row['role']) ?>
                            </span>
                        </td>
                        <td><?= date('Y-m-d H:i', strtotime($row['created_at'])) ?></td>
                        <td>
                            <a href="?action=reset&id=<?= $row['id'] ?>" class="btn btn-warning">Reset Passcode</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <h3>Create Test Passcode Hash</h3>
            <form method="POST" action="?action=generate">
                <div class="form-group">
                    <label>Enter a passcode to see its hash:</label>
                    <input type="text" name="test_passcode" placeholder="e.g., 1234">
                </div>
                <button type="submit" class="btn btn-primary">Generate Hash</button>
            </form>

        <?php
        elseif ($action === 'reset'):
            $id = intval($_GET['id']);
            $stmt = $conn->prepare("SELECT id, name, role FROM sales_persons WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $person = $result->fetch_assoc();

            if (!$person):
        ?>
                <div class="error">Sales person not found!</div>
                <a href="?" class="btn btn-primary">Back to List</a>
        <?php
            else:
        ?>
                <h2>Reset Passcode for: <?= htmlspecialchars($person['name']) ?></h2>
                
                <form method="POST" action="?action=do_reset">
                    <input type="hidden" name="id" value="<?= $person['id'] ?>">
                    
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" value="<?= htmlspecialchars($person['name']) ?>" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label>Role:</label>
                        <input type="text" value="<?= ucfirst($person['role']) ?>" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label>New Passcode:</label>
                        <input type="password" name="new_passcode" required minlength="4" id="newPasscode">
                    </div>
                    
                    <div class="form-group">
                        <label>Confirm New Passcode:</label>
                        <input type="password" name="confirm_passcode" required minlength="4" id="confirmPasscode">
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="showPasswords" onclick="togglePasswords()">
                            Show passcodes
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Reset Passcode</button>
                    <a href="?" class="btn btn-danger">Cancel</a>
                </form>

                <script>
                function togglePasswords() {
                    const type = document.getElementById('showPasswords').checked ? 'text' : 'password';
                    document.getElementById('newPasscode').type = type;
                    document.getElementById('confirmPasscode').type = type;
                }
                </script>
        <?php
            endif;

        elseif ($action === 'do_reset'):
            $id = intval($_POST['id']);
            $newPasscode = $_POST['new_passcode'] ?? '';
            $confirmPasscode = $_POST['confirm_passcode'] ?? '';

            if (empty($newPasscode) || strlen($newPasscode) < 4) {
                echo '<div class="error">Passcode must be at least 4 characters long!</div>';
            } elseif ($newPasscode !== $confirmPasscode) {
                echo '<div class="error">Passcodes do not match!</div>';
            } else {
                $hashedPasscode = hash('sha256', $newPasscode);
                $stmt = $conn->prepare("UPDATE sales_persons SET passcode = ? WHERE id = ?");
                $stmt->bind_param("si", $hashedPasscode, $id);
                
                if ($stmt->execute()) {
                    $stmt2 = $conn->prepare("SELECT name FROM sales_persons WHERE id = ?");
                    $stmt2->bind_param("i", $id);
                    $stmt2->execute();
                    $result = $stmt2->get_result();
                    $person = $result->fetch_assoc();
                    
                    echo '<div class="success">';
                    echo '<strong>✓ Success!</strong><br>';
                    echo 'Passcode for <strong>' . htmlspecialchars($person['name']) . '</strong> has been reset.<br>';
                    echo 'New passcode: <strong>' . htmlspecialchars($newPasscode) . '</strong><br>';
                    echo '<small>(Please save this information securely)</small>';
                    echo '</div>';
                } else {
                    echo '<div class="error">Error resetting passcode: ' . $conn->error . '</div>';
                }
            }
            echo '<a href="?" class="btn btn-primary">Back to List</a>';

        elseif ($action === 'generate'):
            $testPasscode = $_POST['test_passcode'] ?? '';
            if (!empty($testPasscode)) {
                $hash = hash('sha256', $testPasscode);
                echo '<div class="success">';
                echo '<strong>Passcode:</strong> ' . htmlspecialchars($testPasscode) . '<br>';
                echo '<strong>SHA-256 Hash:</strong> <code>' . $hash . '</code>';
                echo '</div>';
            }
            echo '<a href="?" class="btn btn-primary">Back to List</a>';

        endif;
        ?>

    </div>
</body>
</html>
<?php
$conn->close();
?>