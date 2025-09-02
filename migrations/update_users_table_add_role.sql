-- Migration: Update users table to add role_id foreign key
-- Date: 2024-01-15
-- Description: Adds role_id column to users table and creates foreign key constraint

-- Add role_id column to users table
ALTER TABLE users ADD COLUMN role_id INT DEFAULT 2 AFTER user_id;

-- Add foreign key constraint
ALTER TABLE users ADD CONSTRAINT fk_users_role_id
FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL;

-- Update existing users to have admin role (assuming they are admins)
UPDATE users SET role_id = 1 WHERE role_id IS NULL;

-- Add index for better performance
CREATE INDEX idx_users_role_id ON users(role_id);
