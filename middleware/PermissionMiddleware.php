<?php
require_once __DIR__ . '/../auth/db.php';

class PermissionMiddleware {
    private $userId;
    private $requiredPermission;
    private $isAdmin;

    public function __construct($requiredPermission) {
        // Check if admin user is logged in
        if (isset($_SESSION['admin_user_id'])) {
            $this->userId = $_SESSION['admin_user_id'];
            $this->isAdmin = true;
        }
        // Check if regular user is logged in (for backward compatibility)
        elseif (isset($_SESSION['user_id'])) {
            $this->userId = $_SESSION['user_id'];
            $this->isAdmin = false;
        }
        else {
            $this->denyAccess();
        }
        $this->requiredPermission = $requiredPermission;
    }

    public function handle() {
        try {
            if (!$this->checkPermission()) {
                $this->denyAccess();
            }
        } catch (Exception $e) {
            // If permission check fails due to missing tables or other issues,
            // allow access for logged-in admin users
            if ($this->isAdmin && isset($_SESSION['admin_user_id'])) {
                return true;
            }
            $this->denyAccess();
        }
    }

    private function checkPermission() {
        if ($this->isAdmin) {
            // Super admin bypass - check if user is super admin
            if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'super_admin') {
                return true; // Super admin has all permissions
            }

            // For other admin users, use permission check
            return userHasPermission($this->userId, $this->requiredPermission);
        } else {
            return userHasPermission($this->userId, $this->requiredPermission);
        }
    }

    private function denyAccess() {
        throw new Exception("Access denied. You do not have permission to view this page.");
    }
}
?>
