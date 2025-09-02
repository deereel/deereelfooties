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
        if (!$this->checkPermission()) {
            $this->denyAccess();
        }
    }

    private function checkPermission() {
        if ($this->isAdmin) {
            // Use userHasPermission which checks admin_users table
            return userHasPermission($this->userId, $this->requiredPermission);
        } else {
            return userHasPermission($this->userId, $this->requiredPermission);
        }
    }

    private function denyAccess() {
        header('HTTP/1.1 403 Forbidden');
        echo "Access denied. You do not have permission to view this page.";
        exit;
    }
}
?>
