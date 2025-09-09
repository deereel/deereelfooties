-- Order Automation System Tables
-- This migration creates tables for the Order Automation system in Phase 3.1

-- Create order_automation_rules table
CREATE TABLE IF NOT EXISTS `order_automation_rules` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `rule_name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `trigger_event` ENUM('order_created', 'payment_received', 'order_shipped', 'order_delivered', 'custom') NOT NULL,
    `trigger_condition` JSON, -- Store complex conditions as JSON
    `actions` JSON NOT NULL, -- Store multiple actions as JSON array
    `is_active` BOOLEAN DEFAULT TRUE,
    `priority` INT(11) DEFAULT 0, -- Higher priority rules execute first
    `created_by` INT(11),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_trigger_event` (`trigger_event`),
    KEY `idx_is_active` (`is_active`),
    KEY `idx_priority` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create order_status_history table
CREATE TABLE IF NOT EXISTS `order_status_history` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `order_id` VARCHAR(50) NOT NULL,
    `old_status` VARCHAR(50),
    `new_status` VARCHAR(50) NOT NULL,
    `changed_by` INT(11), -- NULL for automated changes
    `change_reason` VARCHAR(255),
    `automation_rule_id` INT(11), -- NULL for manual changes
    `changed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_order_id` (`order_id`),
    KEY `idx_changed_at` (`changed_at`),
    KEY `idx_automation_rule_id` (`automation_rule_id`),
    FOREIGN KEY (`automation_rule_id`) REFERENCES `order_automation_rules`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create automation_logs table
CREATE TABLE IF NOT EXISTS `automation_logs` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `rule_id` INT(11),
    `order_id` VARCHAR(50),
    `action_type` VARCHAR(100) NOT NULL,
    `action_details` JSON,
    `execution_status` ENUM('success', 'failed', 'pending') DEFAULT 'pending',
    `error_message` TEXT,
    `executed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_rule_id` (`rule_id`),
    KEY `idx_order_id` (`order_id`),
    KEY `idx_execution_status` (`execution_status`),
    KEY `idx_executed_at` (`executed_at`),
    FOREIGN KEY (`rule_id`) REFERENCES `order_automation_rules`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample automation rules
INSERT INTO `order_automation_rules` (`rule_name`, `description`, `trigger_event`, `trigger_condition`, `actions`, `is_active`, `priority`) VALUES
('Auto-confirm paid orders', 'Automatically change order status to confirmed when payment is received', 'payment_received', NULL,
 '[{"type": "update_status", "status": "confirmed"}, {"type": "send_email", "template": "order_confirmed"}]', TRUE, 10),

('Auto-ship confirmed orders', 'Automatically change status to shipped after 1 hour of confirmation', 'order_created',
 '{"status": "confirmed", "time_elapsed": 3600}',
 '[{"type": "update_status", "status": "shipped"}, {"type": "send_email", "template": "order_shipped"}]', TRUE, 9),

('Auto-deliver shipped orders', 'Automatically mark as delivered after 3 days of shipping', 'order_created',
 '{"status": "shipped", "time_elapsed": 259200}',
 '[{"type": "update_status", "status": "delivered"}, {"type": "send_email", "template": "order_delivered"}]', TRUE, 8),

('Escalate pending orders', 'Escalate orders that remain pending for more than 24 hours', 'order_created',
 '{"status": "pending", "time_elapsed": 86400}',
 '[{"type": "update_status", "status": "escalated"}, {"type": "notify_admin", "message": "Order requires attention"}]', TRUE, 5);

-- Add permissions for order automation
INSERT INTO `permissions` (`permission_name`, `description`, `module`) VALUES
('manage_order_automation', 'Manage order automation rules', 'orders'),
('view_order_automation', 'View order automation rules', 'orders'),
('execute_order_automation', 'Execute order automation rules', 'orders');

-- Add to super_admin role
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id
FROM `roles` r, `permissions` p
WHERE r.role_name = 'super_admin'
AND p.permission_name IN ('manage_order_automation', 'view_order_automation', 'execute_order_automation');
