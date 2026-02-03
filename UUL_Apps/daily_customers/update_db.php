<?php
require_once 'database.php';

$conn = getDbConnection();

echo "Updating database schema...\n<br>";

// Add missing columns to daily_reports table
$alterations = [
    "ALTER TABLE daily_reports ADD COLUMN IF NOT EXISTS supervisor_comment TEXT DEFAULT NULL",
    "ALTER TABLE daily_reports ADD COLUMN IF NOT EXISTS comment_notified TINYINT DEFAULT 0"
];

foreach ($alterations as $sql) {
    try {
        if ($conn->query($sql)) {
            echo "✓ Successfully executed: " . substr($sql, 0, 50) . "...\n<br>";
        } else {
            // Check if column already exists
            if (strpos($conn->error, 'Duplicate column') !== false) {
                echo "ℹ Column already exists: " . substr($sql, 0, 50) . "...\n<br>";
            } else {
                echo "✗ Error: " . $conn->error . "\n<br>";
            }
        }
    } catch (Exception $e) {
        echo "✗ Exception: " . $e->getMessage() . "\n<br>";
    }
}

// Verify columns exist
echo "\n<br><br>Verifying daily_reports table structure:\n<br>";
$result = $conn->query("DESCRIBE daily_reports");
echo "<table border='1' style='border-collapse: collapse; margin-top: 10px;'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['Field'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
    echo "</tr>";
}
echo "</table>";

$conn->close();
echo "\n<br><br>Database update complete!";
?>