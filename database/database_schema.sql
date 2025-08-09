-- Create database (if it doesn't exist)
CREATE DATABASE IF NOT EXISTS fastnet_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE fastnet_db;

-- Create payment_requests table
CREATE TABLE IF NOT EXISTS payment_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id VARCHAR(50) UNIQUE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    mac_address VARCHAR(17) NOT NULL,
    package_name VARCHAR(100) NOT NULL,
    package_price DECIMAL(10,2) NOT NULL,
    admin_id VARCHAR(10),
    status ENUM('pending', 'approved', 'rejected', 'expired') DEFAULT 'pending',
    voucher_code VARCHAR(50) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    approved_at TIMESTAMP NULL,
    approved_by VARCHAR(50) NULL,
    notes TEXT NULL,
    INDEX idx_mac_address (mac_address),
    INDEX idx_phone (phone),
    INDEX idx_status (status),
    INDEX idx_request_id (request_id),
    INDEX idx_created_at (created_at)
);

-- Create admin_users table (for managing who can approve payments)
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'super_admin') DEFAULT 'admin',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Create voucher_codes table (for managing generated vouchers)
CREATE TABLE IF NOT EXISTS voucher_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voucher_code VARCHAR(50) UNIQUE NOT NULL,
    package_name VARCHAR(100) NOT NULL,
    package_price DECIMAL(10,2) NOT NULL,
    duration_hours INT NOT NULL,
    is_used BOOLEAN DEFAULT FALSE,
    used_by_mac VARCHAR(17) NULL,
    used_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    created_by_request_id VARCHAR(50) NULL,
    INDEX idx_voucher_code (voucher_code),
    INDEX idx_is_used (is_used),
    INDEX idx_created_at (created_at),
    FOREIGN KEY (created_by_request_id) REFERENCES payment_requests(request_id) ON DELETE SET NULL
);

-- Create system_logs table (for audit trail)
CREATE TABLE IF NOT EXISTS system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    log_type ENUM('payment_request', 'payment_approval', 'voucher_generation', 'voucher_usage', 'admin_action') NOT NULL,
    reference_id VARCHAR(50) NULL,
    user_identifier VARCHAR(100) NULL, -- Could be MAC address, phone, or admin username
    action_description TEXT NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_log_type (log_type),
    INDEX idx_reference_id (reference_id),
    INDEX idx_created_at (created_at)
);

-- Insert default admin user (change password after first login)
INSERT INTO admin_users (username, email, phone, password_hash, role) VALUES 
('admin', 'admin@fastnetug.com', '+256744766410', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin')
ON DUPLICATE KEY UPDATE username=username;

-- Note: The password hash above is for 'password123' - CHANGE THIS IMMEDIATELY after setup!
-- To generate a new password hash in PHP: password_hash('your_new_password', PASSWORD_DEFAULT)

-- Create indexes for better performance
ALTER TABLE payment_requests ADD INDEX idx_status_created (status, created_at);
ALTER TABLE payment_requests ADD INDEX idx_mac_status (mac_address, status);
ALTER TABLE voucher_codes ADD INDEX idx_used_created (is_used, created_at);