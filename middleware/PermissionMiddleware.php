<?php
session_start();

require_once __DIR__ . '/../auth/db.php';

class PermissionMiddleware {
    private $userId;
    private $requiredPermission;

    public function __construct($requiredPermission) {
        if (!isset($_SESSION['user_id'])) {
            $this->denyAccess();
        }
        $this->userId = $_SESSION['user_id'];
        $this->requiredPermission = $requiredPermission;
    }

    public function handle() {
        if (!$this->checkPermission()) {
            $this->denyAccess();
        }
    }

    private function checkPermission() {
        return userHasPermission($this->userId, $this->requiredPermission);
    }

    private function denyAccess() {
        header('HTTP/1.1 403 Forbidden');
        echo "Access denied. You do not have permission to view this page.";
        exit;
    }
}
?>
