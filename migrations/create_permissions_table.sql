-- Migration: Create permissions table
-- Date: 2024-01-15
-- Description: Creates the permissions table for granular access control

CREATE TABLE IF NOT EXISTS permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    module VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default permissions
INSERT INTO permissions (name, description, module) VALUES
-- Dashboard permissions
('view_dashboard', 'View admin dashboard', 'dashboard'),
('view_stats', 'View dashboard statistics', 'dashboard'),

-- User management permissions
('manage_users', 'Create, edit, delete admin users', 'users'),
('view_users', 'View list of admin users', 'users'),
('assign_roles', 'Assign roles to users', 'users'),

-- Role management permissions
('manage_roles', 'Create, edit, delete roles', 'roles'),
('view_roles', 'View list of roles', 'roles'),
('assign_permissions', 'Assign permissions to roles', 'roles'),

-- Product management permissions
('manage_products', 'Create, edit, delete products', 'products'),
('view_products', 'View product list', 'products'),
('manage_inventory', 'Update product inventory', 'inventory'),

-- Order management permissions
('manage_orders', 'Process and manage orders', 'orders'),
('view_orders', 'View order list', 'orders'),
('update_order_status', 'Update order status', 'orders'),

-- Customer management permissions
('view_customers', 'View customer list', 'customers'),
('manage_customers', 'Edit customer information', 'customers'),

-- Settings permissions
('manage_settings', 'Modify system settings', 'settings'),
('view_settings', 'View system settings', 'settings'),

-- Security permissions
('view_login_monitoring', 'View login monitoring dashboard', 'security'),
('manage_security', 'Manage security settings', 'security'),
('view_activity_logs', 'View activity logs and audit trail', 'security'),

-- System tools permissions
('manage_backups', 'Create and manage database backups', 'system'),
('view_system_health', 'View system health and monitoring', 'system'),
('view_error_logs', 'View and analyze error logs', 'system')
ON DUPLICATE KEY UPDATE description = VALUES(description), module = VALUES(module);
