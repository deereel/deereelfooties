-- Returns and Refunds System Tables
-- This migration creates the necessary tables for managing return requests and refunds

-- Returns table
CREATE TABLE IF NOT EXISTS `returns` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `order_id` int(11) NOT NULL,
    `customer_id` int(11) NOT NULL,
    `reason` text NOT NULL,
    `notes` text,
    `status` enum('pending','approved','received','refunded','rejected') DEFAULT 'pending',
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `order_id` (`order_id`),
    KEY `customer_id` (`customer_id`),
    KEY `status` (`status`),
    KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Return items table (for tracking individual items in returns)
CREATE TABLE IF NOT EXISTS `return_items` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `return_id` int(11) NOT NULL,
    `product_id` int(11) NOT NULL,
    `quantity` int(11) NOT NULL DEFAULT 1,
    `reason` varchar(255) DEFAULT NULL,
    `condition` enum('new','used','damaged') DEFAULT 'used',
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `return_id` (`return_id`),
    KEY `product_id` (`product_id`),
    FOREIGN KEY (`return_id`) REFERENCES `returns` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Refunds table
CREATE TABLE IF NOT EXISTS `refunds` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `return_id` int(11) NOT NULL,
    `amount` decimal(10,2) NOT NULL,
    `method` enum('original_payment','store_credit','bank_transfer','check') DEFAULT 'original_payment',
    `processed_by` int(11) NOT NULL,
    `processed_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `reference_number` varchar(255) DEFAULT NULL,
    `notes` text,
    PRIMARY KEY (`id`),
    KEY `return_id` (`return_id`),
    KEY `processed_by` (`processed_by`),
    KEY `processed_at` (`processed_at`),
    FOREIGN KEY (`return_id`) REFERENCES `returns` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`processed_by`) REFERENCES `admin_users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Return reasons table (for categorization)
CREATE TABLE IF NOT EXISTS `return_reasons` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `reason` varchar(255) NOT NULL,
    `description` text,
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `reason` (`reason`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default return reasons
INSERT IGNORE INTO `return_reasons` (`reason`, `description`) VALUES
('wrong_item', 'Wrong item received'),
('defective', 'Item is defective or damaged'),
('not_as_described', 'Item not as described'),
('changed_mind', 'Changed mind about purchase'),
('size_issue', 'Wrong size or fit'),
('quality_issue', 'Quality not satisfactory'),
('late_delivery', 'Item delivered too late'),
('other', 'Other reason');

-- Add permissions for returns system
INSERT IGNORE INTO `permissions` (`name`, `description`, `module`) VALUES
('view_returns', 'View return requests', 'returns'),
('manage_returns', 'Create and manage return requests', 'returns'),
('process_refunds', 'Process refund requests', 'returns');

-- Assign permissions to super_admin role
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT r.id, p.id
FROM `roles` r, `permissions` p
WHERE r.name = 'super_admin'
AND p.name IN ('view_returns', 'manage_returns', 'process_refunds');

-- Add indexes for better performance
CREATE INDEX idx_returns_order_customer ON `returns` (`order_id`, `customer_id`);
CREATE INDEX idx_returns_status_created ON `returns` (`status`, `created_at`);
CREATE INDEX idx_return_items_return_product ON `return_items` (`return_id`, `product_id`);
CREATE INDEX idx_refunds_return_processed ON `refunds` (`return_id`, `processed_by`);
