<?php

/**
 * UUL Ltd Customer Database Creation Script in PHP
 * Run this file with `php create_uul_db.php` to set up the schema
 */

$host = "localhost";
$user = "uul_user";       // change if needed
$pass = "uul@mysql123";           // change if needed
$db   = "uul_clients";

// Connect to MySQL server
$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
    die("❌ Connection failed: " . mysqli_connect_error());
}
echo "✅ Connected to MySQL.<br>";

// --- STEP 1: Create database
if (!mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $db")) {
    die("❌ Error creating DB: " . mysqli_error($conn));
}
echo "✅ Database `$db` created.<br>";

mysqli_select_db($conn, $db);

// --- STEP 2: Core schema (tables, sample data, views, indexes)
$coreSQL = <<<SQL
-- Create clients table
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(191) NOT NULL,
    phone VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(191) NULL,
    company VARCHAR(191) NULL,
category ENUM(
    'art_paper', 'art_board', 'chip_board', 'ncr', 'manilla',
    'sticker_paper', 'chemicals', 'plates', 'resellers', 'operators',
    'corporate_clients', 'freelancers', 'other'
) NOT NULL,
    address TEXT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_phone (phone),
    INDEX idx_name (name),
    INDEX idx_created_at (created_at)
);

-- bulk_messages
CREATE TABLE IF NOT EXISTS bulk_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message TEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    recipient_count INT NOT NULL DEFAULT 0,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_sent_at (sent_at)
);

-- message_recipients
CREATE TABLE IF NOT EXISTS message_recipients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bulk_message_id INT NOT NULL,
    client_id INT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    status ENUM('sent','delivered','failed','pending') DEFAULT 'pending',
    sent_at TIMESTAMP NULL,
    delivered_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bulk_message_id) REFERENCES bulk_messages(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_bulk_message_id (bulk_message_id),
    INDEX idx_client_id (client_id)
);

-- client_interactions
CREATE TABLE IF NOT EXISTS client_interactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    interaction_type ENUM('call','whatsapp','email','meeting','order','inquiry') NOT NULL,
    notes TEXT NULL,
    interaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by VARCHAR(100) NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    INDEX idx_client_id (client_id),
    INDEX idx_interaction_type (interaction_type),
    INDEX idx_interaction_date (interaction_date)
);

-- Audit table
CREATE TABLE IF NOT EXISTS client_audit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    action ENUM('INSERT','UPDATE','DELETE') NOT NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    changed_by VARCHAR(100) NULL,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Views
CREATE OR REPLACE VIEW client_summary AS
SELECT category,
       COUNT(*) as total_clients,
       COUNT(CASE WHEN email IS NOT NULL AND email != '' THEN 1 END) as clients_with_email,
       COUNT(CASE WHEN company IS NOT NULL AND company != '' THEN 1 END) as clients_with_company,
       MIN(created_at) as first_client_added,
       MAX(created_at) as last_client_added
FROM clients GROUP BY category;

CREATE OR REPLACE VIEW recent_interactions AS
SELECT c.name as client_name,
       c.phone,
       c.company,
       c.category,
       ci.interaction_type,
       ci.notes,
       ci.interaction_date
FROM client_interactions ci
JOIN clients c ON ci.client_id = c.id
ORDER BY ci.interaction_date DESC
LIMIT 50;

CREATE OR REPLACE VIEW bulk_message_stats AS
SELECT bm.id, bm.message, bm.category, bm.recipient_count, bm.sent_at,
       COUNT(mr.id) as actual_recipients,
       COUNT(CASE WHEN mr.status='delivered' THEN 1 END) as delivered_count,
       COUNT(CASE WHEN mr.status='failed' THEN 1 END) as failed_count,
       COUNT(CASE WHEN mr.status='pending' THEN 1 END) as pending_count
FROM bulk_messages bm
LEFT JOIN message_recipients mr ON bm.id = mr.bulk_message_id
GROUP BY bm.id
ORDER BY bm.sent_at DESC;

-- Indexes
CREATE INDEX idx_clients_search ON clients(name, phone, email, company);
CREATE INDEX idx_clients_category_created ON clients(category, created_at);
CREATE INDEX idx_bulk_messages_category_sent ON bulk_messages(category, sent_at);
SQL;

// Execute core SQL
if (!mysqli_multi_query($conn, $coreSQL)) {
    die("❌ Error creating schema: " . mysqli_error($conn));
}
do {
    mysqli_store_result($conn);
} while (mysqli_more_results($conn) && mysqli_next_result($conn));
echo "✅ Core schema + sample data + views created.<br>";

// --- STEP 3: Stored Procedures
$procedures = [
    "CREATE PROCEDURE GetClientStatistics()
     BEGIN
         SELECT 'Total Clients' as metric, COUNT(*) as value FROM clients
         UNION ALL
         SELECT CONCAT(UPPER(SUBSTRING(category,1,1)),LOWER(SUBSTRING(category,2))) as metric, COUNT(*) as value FROM clients GROUP BY category
         UNION ALL
         SELECT 'Clients Added This Month' as metric, COUNT(*) as value FROM clients WHERE YEAR(created_at)=YEAR(CURDATE()) AND MONTH(created_at)=MONTH(CURDATE())
         UNION ALL
         SELECT 'Clients With Email' as metric, COUNT(*) as value FROM clients WHERE email IS NOT NULL AND email!='';
     END",

    "CREATE PROCEDURE SearchClients(IN search_term VARCHAR(255), IN category_filter VARCHAR(50), IN limit_count INT)
     BEGIN
         SET @sql = 'SELECT * FROM clients WHERE 1=1';
         IF search_term IS NOT NULL AND search_term!='' THEN
             SET @sql = CONCAT(@sql,' AND (name LIKE \"%',search_term,'%\" OR phone LIKE \"%',search_term,'%\" OR email LIKE \"%',search_term,'%\" OR company LIKE \"%',search_term,'%\")');
         END IF;
         IF category_filter IS NOT NULL AND category_filter!='' AND category_filter!='all' THEN
             SET @sql = CONCAT(@sql,' AND category=\"',category_filter,'\"');
         END IF;
         SET @sql = CONCAT(@sql,' ORDER BY created_at DESC');
         IF limit_count IS NOT NULL AND limit_count>0 THEN
             SET @sql = CONCAT(@sql,' LIMIT ',limit_count);
         END IF;
         PREPARE stmt FROM @sql;
         EXECUTE stmt;
         DEALLOCATE PREPARE stmt;
     END",

    "CREATE PROCEDURE LogClientInteraction(IN p_client_id INT, IN p_interaction_type VARCHAR(50), IN p_notes TEXT, IN p_created_by VARCHAR(100))
     BEGIN
         INSERT INTO client_interactions (client_id, interaction_type, notes, created_by)
         VALUES (p_client_id, p_interaction_type, p_notes, p_created_by);
         SELECT LAST_INSERT_ID() as interaction_id;
     END"
];

foreach ($procedures as $proc) {
    mysqli_query($conn, "DROP PROCEDURE IF EXISTS " . strtok($proc, "(")); // drop old version
    if (!mysqli_query($conn, $proc)) {
        echo "⚠️ Procedure error: " . mysqli_error($conn) . "<br>";
    }
}
echo "✅ Stored procedures created.<br>";

// --- STEP 4: Triggers
$triggers = [
    "CREATE TRIGGER client_update_audit AFTER UPDATE ON clients FOR EACH ROW
     BEGIN
         INSERT INTO client_audit (client_id, action, old_values, new_values, changed_at)
         VALUES (NEW.id,'UPDATE',
                 JSON_OBJECT('name',OLD.name,'phone',OLD.phone,'email',OLD.email,'company',OLD.company,'category',OLD.category,'address',OLD.address,'notes',OLD.notes),
                 JSON_OBJECT('name',NEW.name,'phone',NEW.phone,'email',NEW.email,'company',NEW.company,'category',NEW.category,'address',NEW.address,'notes',NEW.notes),
                 NOW());
     END",

    "CREATE TRIGGER client_delete_audit BEFORE DELETE ON clients FOR EACH ROW
     BEGIN
         INSERT INTO client_audit (client_id, action, old_values, changed_at)
         VALUES (OLD.id,'DELETE',
                 JSON_OBJECT('name',OLD.name,'phone',OLD.phone,'email',OLD.email,'company',OLD.company,'category',OLD.category,'address',OLD.address,'notes',OLD.notes),
                 NOW());
     END"
];

foreach ($triggers as $trig) {
    mysqli_query($conn, "DROP TRIGGER IF EXISTS " . strtok($trig, " ")); // drop old version
    if (!mysqli_query($conn, $trig)) {
        echo "⚠️ Trigger error: " . mysqli_error($conn) . "<br>";
    }
}
echo "✅ Triggers created.<br>";

// --- Final check
echo "🎉 UUL Ltd Customer Database fully created with all tables, views, procedures, and triggers.<br>";

mysqli_close($conn);
