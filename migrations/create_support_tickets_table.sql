-- Create support tickets table
CREATE TABLE IF NOT EXISTS support_tickets (
    ticket_id INT PRIMARY KEY AUTO_INCREMENT,
    ticket_number VARCHAR(20) UNIQUE NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20),
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category ENUM('general', 'order', 'product', 'shipping', 'payment', 'returns', 'technical', 'other') DEFAULT 'general',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    status ENUM('open', 'in_progress', 'waiting_customer', 'resolved', 'closed') DEFAULT 'open',
    assigned_to INT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    resolution_notes TEXT,
    attachments JSON,
    tags JSON,
    metadata JSON,
    INDEX idx_status (status),
    INDEX idx_category (category),
    INDEX idx_priority (priority),
    INDEX idx_assigned_to (assigned_to),
    INDEX idx_created_by (created_by),
    INDEX idx_created_at (created_at),
    INDEX idx_customer_email (customer_email),
    FOREIGN KEY (assigned_to) REFERENCES admin_users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Create ticket replies table
CREATE TABLE IF NOT EXISTS ticket_replies (
    reply_id INT PRIMARY KEY AUTO_INCREMENT,
    ticket_id INT NOT NULL,
    reply_text TEXT NOT NULL,
    replied_by INT NOT NULL,
    replied_by_type ENUM('admin', 'customer') DEFAULT 'admin',
    is_internal BOOLEAN DEFAULT FALSE,
    attachments JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ticket_id (ticket_id),
    INDEX idx_replied_by (replied_by),
    INDEX idx_created_at (created_at),
    FOREIGN KEY (ticket_id) REFERENCES support_tickets(ticket_id) ON DELETE CASCADE,
    FOREIGN KEY (replied_by) REFERENCES admin_users(id) ON DELETE CASCADE
);

-- Create ticket categories table for custom categories
CREATE TABLE IF NOT EXISTS ticket_categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    color VARCHAR(7) DEFAULT '#007bff',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_category_name (name)
);

-- Insert default categories
INSERT INTO ticket_categories (name, description, color) VALUES
('General Inquiry', 'General customer questions and inquiries', '#007bff'),
('Order Issues', 'Problems with orders, tracking, or delivery', '#28a745'),
('Product Questions', 'Questions about products, specifications, or usage', '#17a2b8'),
('Shipping & Delivery', 'Shipping questions, delays, or delivery issues', '#ffc107'),
('Payment Problems', 'Payment processing, refunds, or billing issues', '#dc3545'),
('Returns & Exchanges', 'Return requests, exchange processes, or refund status', '#6f42c1'),
('Technical Support', 'Website issues, login problems, or technical difficulties', '#fd7e14'),
('Other', 'Other types of customer support requests', '#6c757d');

-- Create ticket templates table
CREATE TABLE IF NOT EXISTS ticket_templates (
    template_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    category VARCHAR(50),
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    is_active BOOLEAN DEFAULT TRUE,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_created_by (created_by),
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Insert default templates
INSERT INTO ticket_templates (name, subject, message, category, priority) VALUES
('Order Delay Response', 'Update on Your Order Delay', 'Dear {customer_name},\n\nWe apologize for the delay in processing your order #{order_id}. Our team is working diligently to resolve this issue.\n\nCurrent Status: {order_status}\nExpected Resolution: {resolution_date}\n\nWe appreciate your patience and understanding.\n\nBest regards,\nDRF Support Team', 'order', 'medium'),
('Refund Processing', 'Refund Processed for Your Order', 'Dear {customer_name},\n\nYour refund for order #{order_id} has been processed successfully.\n\nRefund Amount: ₦{refund_amount}\nProcessing Time: 3-5 business days\n\nThe refund will appear in your original payment method.\n\nThank you for your patience.\n\nBest regards,\nDRF Support Team', 'payment', 'medium'),
('Return Request Confirmation', 'Return Request Confirmation', 'Dear {customer_name},\n\nYour return request for order #{order_id} has been received and is being processed.\n\nReturn Items: {return_items}\nReturn Reason: {return_reason}\nRefund Method: {refund_method}\n\nProcessing Time: 5-7 business days\n\nWe will send you a return shipping label shortly.\n\nBest regards,\nDRF Support Team', 'returns', 'medium');

-- Create ticket statistics view
CREATE OR REPLACE VIEW ticket_statistics AS
SELECT
    COUNT(*) as total_tickets,
    SUM(CASE WHEN status = 'open' THEN 1 ELSE 0 END) as open_tickets,
    SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_tickets,
    SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved_tickets,
    SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed_tickets,
    SUM(CASE WHEN priority = 'urgent' THEN 1 ELSE 0 END) as urgent_tickets,
    SUM(CASE WHEN priority = 'high' THEN 1 ELSE 0 END) as high_priority_tickets,
    AVG(CASE WHEN resolved_at IS NOT NULL THEN TIMESTAMPDIFF(HOUR, created_at, resolved_at) ELSE NULL END) as avg_resolution_hours,
    COUNT(DISTINCT DATE(created_at)) as active_days
FROM support_tickets
WHERE created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY);
