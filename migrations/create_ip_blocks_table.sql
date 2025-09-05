-- Migration: Create IP blocking tables
-- Date: 2024-01-15
-- Description: Creates tables for IP blocking and rate limiting

CREATE TABLE IF NOT EXISTS ip_blocks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL UNIQUE,
    block_type ENUM('manual', 'automatic', 'rate_limit') DEFAULT 'manual',
    reason TEXT,
    blocked_by INT,
    blocked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    INDEX idx_ip_address (ip_address),
    INDEX idx_block_type (block_type),
    INDEX idx_is_active (is_active),
    INDEX idx_expires_at (expires_at)
);

CREATE TABLE IF NOT EXISTS ip_whitelist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL UNIQUE,
    description TEXT,
    added_by INT,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    INDEX idx_ip_address (ip_address),
    INDEX idx_is_active (is_active)
);

CREATE TABLE IF NOT EXISTS rate_limits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    endpoint VARCHAR(255) NOT NULL,
    request_count INT DEFAULT 0,
    window_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    window_end TIMESTAMP,
    INDEX idx_ip_endpoint (ip_address, endpoint),
    INDEX idx_window_end (window_end)
);

-- Insert sample data for testing
INSERT INTO ip_whitelist (ip_address, description, added_by) VALUES
('127.0.0.1', 'Local development server', 1),
('192.168.1.0/24', 'Local network range', 1);

INSERT INTO ip_blocks (ip_address, block_type, reason, blocked_by, expires_at) VALUES
('10.0.0.1', 'manual', 'Suspicious activity detected', 1, DATE_ADD(NOW(), INTERVAL 24 HOUR)),
('192.168.1.100', 'automatic', 'Multiple failed login attempts', 1, DATE_ADD(NOW(), INTERVAL 1 HOUR));
