-- Migration: Create login_attempts table for tracking login activity
-- Created: $(date)

CREATE TABLE IF NOT EXISTS `login_attempts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `ip_address` varchar(45) NOT NULL,
    `user_agent` text,
    `attempt_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `status` enum('success','failed','locked') NOT NULL DEFAULT 'failed',
    `failure_reason` varchar(255) DEFAULT NULL,
    `location` varchar(255) DEFAULT NULL,
    `device_info` text,
    PRIMARY KEY (`id`),
    KEY `idx_username` (`username`),
    KEY `idx_ip_address` (`ip_address`),
    KEY `idx_attempt_time` (`attempt_time`),
    KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add indexes for performance
CREATE INDEX idx_login_attempts_username_time ON login_attempts(username, attempt_time);
CREATE INDEX idx_login_attempts_ip_time ON login_attempts(ip_address, attempt_time);

-- Insert sample data for testing (optional)
INSERT INTO login_attempts (username, ip_address, user_agent, status, failure_reason) VALUES
('admin', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'success', NULL),
('test_user', '192.168.1.100', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)', 'failed', 'Invalid password'),
('admin', '203.0.113.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)', 'failed', 'Account locked');

COMMIT;
