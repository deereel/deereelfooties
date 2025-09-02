-- Migration: Create roles table
-- Date: 2024-01-15
-- Description: Creates the roles table for role-based access control

CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default roles
INSERT INTO roles (name, description) VALUES
('super_admin', 'Super Administrator with full access to all features'),
('admin', 'Administrator with access to most features'),
('manager', 'Manager with access to inventory and orders'),
('staff', 'Staff with limited access to specific features')
ON DUPLICATE KEY UPDATE description = VALUES(description);
