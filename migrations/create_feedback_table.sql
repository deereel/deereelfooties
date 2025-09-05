-- Create feedback table for customer feedback management
CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50),
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    category ENUM('general', 'product', 'service', 'website', 'suggestion', 'complaint', 'other') DEFAULT 'general',
    rating TINYINT CHECK (rating >= 1 AND rating <= 5),
    status ENUM('new', 'read', 'responded', 'closed') DEFAULT 'new',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    assigned_to INT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    responded_at TIMESTAMP NULL,
    response_notes TEXT,
    is_public BOOLEAN DEFAULT FALSE,
    source VARCHAR(100) DEFAULT 'website',
    FOREIGN KEY (assigned_to) REFERENCES admin_users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Create feedback_responses table for tracking responses
CREATE TABLE IF NOT EXISTS feedback_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    feedback_id INT NOT NULL,
    response_text TEXT NOT NULL,
    responded_by INT NOT NULL,
    responded_by_type ENUM('admin', 'system') DEFAULT 'admin',
    is_internal BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (feedback_id) REFERENCES feedback(id) ON DELETE CASCADE,
    FOREIGN KEY (responded_by) REFERENCES admin_users(id) ON DELETE CASCADE
);

-- Create feedback_categories table for custom categories
CREATE TABLE IF NOT EXISTS feedback_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    color VARCHAR(7) DEFAULT '#007bff',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default categories
INSERT INTO feedback_categories (name, description, color) VALUES
('General', 'General feedback and comments', '#007bff'),
('Product', 'Feedback about products', '#28a745'),
('Service', 'Feedback about customer service', '#ffc107'),
('Website', 'Feedback about website functionality', '#dc3545'),
('Suggestion', 'Suggestions for improvement', '#17a2b8'),
('Complaint', 'Customer complaints', '#fd7e14'),
('Other', 'Other types of feedback', '#6c757d');

-- Create indexes for better performance
CREATE INDEX idx_feedback_status ON feedback(status);
CREATE INDEX idx_feedback_category ON feedback(category);
CREATE INDEX idx_feedback_created_at ON feedback(created_at);
CREATE INDEX idx_feedback_customer_email ON feedback(customer_email);
CREATE INDEX idx_feedback_assigned_to ON feedback(assigned_to);
CREATE INDEX idx_feedback_responses_feedback_id ON feedback_responses(feedback_id);
