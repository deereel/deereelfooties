<?php
$host = 'localhost';
$db   = 'drf_database';
$user = 'root';
$pass = ''; // or your actual MySQL root password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
  echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
  exit;
}

/**
 * Fetch data from database
 * 
 * @param string $table Table name
 * @param array $conditions Optional WHERE conditions as associative array
 * @param string $columns Columns to select, defaults to all
 * @param string $orderBy Optional ORDER BY clause
 * @param int $limit Optional LIMIT value
 * @return array Fetched data
 */
function fetchData($table, $conditions = [], $columns = '*', $orderBy = '', $limit = 0) {
  global $pdo;
  
  $sql = "SELECT $columns FROM $table";
  
  $params = [];
  if (!empty($conditions)) {
    $sql .= " WHERE ";
    $whereClauses = [];
    foreach ($conditions as $key => $value) {
      $whereClauses[] = "$key = :$key";
      $params[":$key"] = $value;
    }
    $sql .= implode(' AND ', $whereClauses);
  }
  
  if (!empty($orderBy)) {
    $sql .= " ORDER BY $orderBy";
  }
  
  if ($limit > 0) {
    $sql .= " LIMIT $limit";
  }
  
  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (\PDOException $e) {
    return ['error' => $e->getMessage()];
  }
}

/**
 * Check if an admin user has a specific permission
 *
 * @param int $userId Admin user ID
 * @param string $permissionName Permission name
 * @return bool True if user has permission, false otherwise
 */
function userHasPermission($userId, $permissionName) {
  global $pdo;

  // Check if user is super admin first
  if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'super_admin') {
    // Only grant permission if permissionName is not 'delete_product'
    if ($permissionName === 'delete_product' || $permissionName === 'delete_products') {
      return true;
    }
    // For other permissions, super admin has all permissions
    return true;
  }

  $sql = "SELECT COUNT(*) FROM admin_users au
          JOIN roles r ON au.role_id = r.id
          JOIN role_permissions rp ON r.id = rp.role_id
          JOIN permissions p ON rp.permission_id = p.id
          WHERE au.id = :userId AND p.name = :permissionName AND au.is_active = 1";

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':userId' => $userId, ':permissionName' => $permissionName]);
    $count = $stmt->fetchColumn();
    return $count > 0;
  } catch (\PDOException $e) {
    // If tables don't exist or query fails, allow access for logged-in admins
    return isset($_SESSION['admin_user_id']);
  }
}

/**
 * Get admin user role by user ID
 *
 * @param int $userId Admin user ID
 * @return array|null Role data or null if not found
 */
function getUserRole($userId) {
  global $pdo;

  $sql = "SELECT r.* FROM admin_users au
          JOIN roles r ON au.role_id = r.id
          WHERE au.id = :userId AND au.is_active = 1";

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':userId' => $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  } catch (\PDOException $e) {
    return null;
  }
}

/**
 * Get admin user by username for authentication
 *
 * @param string $username Username
 * @return array|null User data or null if not found
 */
function getAdminUserByUsername($username) {
  global $pdo;

  $sql = "SELECT au.*, r.name as role_name FROM admin_users au
          JOIN roles r ON au.role_id = r.id
          WHERE au.username = :username AND au.is_active = 1";

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  } catch (\PDOException $e) {
    return null;
  }
}

/**
 * Verify admin user password
 *
 * @param string $password Plain text password
 * @param string $hashedPassword Hashed password from database
 * @return bool True if password matches
 */
function verifyAdminPassword($password, $hashedPassword) {
  return password_verify($password, $hashedPassword);
}

/**
 * Insert data into database
 * 
 * @param string $table Table name
 * @param array $data Associative array of column => value pairs
 * @return array|int Last insert ID on success, error array on failure
 */
function insertData($table, $data) {
  global $pdo;
  
  $columns = implode(', ', array_keys($data));
  $placeholders = ':' . implode(', :', array_keys($data));
  
  $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
  
  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
    return $pdo->lastInsertId();
  } catch (\PDOException $e) {
    return ['error' => $e->getMessage()];
  }
}

/**
 * Update data in database
 * 
 * @param string $table Table name
 * @param array $data Associative array of column => value pairs to update
 * @param array $conditions WHERE conditions as associative array
 * @return array|int Affected rows count on success, error array on failure
 */
function updateData($table, $data, $conditions) {
  global $pdo;
  
  $sql = "UPDATE $table SET ";
  
  $setClauses = [];
  $params = [];
  
  foreach ($data as $key => $value) {
    $setClauses[] = "$key = :set_$key";
    $params[":set_$key"] = $value;
  }
  
  $sql .= implode(', ', $setClauses);
  
  if (!empty($conditions)) {
    $sql .= " WHERE ";
    $whereClauses = [];
    foreach ($conditions as $key => $value) {
      $whereClauses[] = "$key = :where_$key";
      $params[":where_$key"] = $value;
    }
    $sql .= implode(' AND ', $whereClauses);
  }
  
  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
  } catch (\PDOException $e) {
    return ['error' => $e->getMessage()];
  }
}

/**
 * Delete data from database
 *
 * @param string $table Table name
 * @param array $conditions WHERE conditions as associative array
 * @return array|int Affected rows count on success, error array on failure
 */
function deleteData($table, $conditions) {
  global $pdo;

  $sql = "DELETE FROM $table";

  $params = [];
  if (!empty($conditions)) {
    $sql .= " WHERE ";
    $whereClauses = [];
    foreach ($conditions as $key => $value) {
      $whereClauses[] = "$key = :$key";
      $params[":$key"] = $value;
    }
    $sql .= implode(' AND ', $whereClauses);
  }

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
  } catch (\PDOException $e) {
    return ['error' => $e->getMessage()];
  }
}

/**
 * Log a login attempt
 *
 * @param string $username Username attempting login
 * @param string $status 'success', 'failed', or 'locked'
 * @param string $failureReason Reason for failure (optional)
 * @return bool True on success, false on failure
 */
function logLoginAttempt($username, $status, $failureReason = null) {
  global $pdo;

  $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';
  $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

  // Get device info from user agent
  $deviceInfo = getDeviceInfo($userAgent);

  $data = [
    'username' => $username,
    'ip_address' => $ipAddress,
    'user_agent' => $userAgent,
    'status' => $status,
    'failure_reason' => $failureReason,
    'device_info' => json_encode($deviceInfo)
  ];

  try {
    $result = insertData('login_attempts', $data);
    return !isset($result['error']);
  } catch (\PDOException $e) {
    // Silently fail if table doesn't exist yet
    return false;
  }
}

/**
 * Get device information from user agent
 *
 * @param string $userAgent User agent string
 * @return array Device information
 */
function getDeviceInfo($userAgent) {
  $deviceInfo = [
    'browser' => 'Unknown',
    'os' => 'Unknown',
    'device' => 'Unknown'
  ];

  // Browser detection
  if (strpos($userAgent, 'Chrome') !== false) {
    $deviceInfo['browser'] = 'Chrome';
  } elseif (strpos($userAgent, 'Firefox') !== false) {
    $deviceInfo['browser'] = 'Firefox';
  } elseif (strpos($userAgent, 'Safari') !== false) {
    $deviceInfo['browser'] = 'Safari';
  } elseif (strpos($userAgent, 'Edge') !== false) {
    $deviceInfo['browser'] = 'Edge';
  }

  // OS detection
  if (strpos($userAgent, 'Windows') !== false) {
    $deviceInfo['os'] = 'Windows';
  } elseif (strpos($userAgent, 'Mac') !== false) {
    $deviceInfo['os'] = 'macOS';
  } elseif (strpos($userAgent, 'Linux') !== false) {
    $deviceInfo['os'] = 'Linux';
  } elseif (strpos($userAgent, 'Android') !== false) {
    $deviceInfo['os'] = 'Android';
  } elseif (strpos($userAgent, 'iOS') !== false) {
    $deviceInfo['os'] = 'iOS';
  }

  // Device type detection
  if (strpos($userAgent, 'Mobile') !== false) {
    $deviceInfo['device'] = 'Mobile';
  } elseif (strpos($userAgent, 'Tablet') !== false) {
    $deviceInfo['device'] = 'Tablet';
  } else {
    $deviceInfo['device'] = 'Desktop';
  }

  return $deviceInfo;
}

/**
 * Check if account should be locked due to failed attempts
 *
 * @param string $username Username to check
 * @param int $maxAttempts Maximum failed attempts before lockout
 * @param int $lockoutMinutes Lockout duration in minutes
 * @return array Lock status information
 */
function checkAccountLockout($username, $maxAttempts = 5, $lockoutMinutes = 30) {
  global $pdo;

  try {
    // Count failed attempts in the last lockout period
    $stmt = $pdo->prepare("
      SELECT COUNT(*) as failed_count, MAX(attempt_time) as last_attempt
      FROM login_attempts
      WHERE username = ? AND status = 'failed'
      AND attempt_time > DATE_SUB(NOW(), INTERVAL ? MINUTE)
    ");
    $stmt->execute([$username, $lockoutMinutes]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $isLocked = $result['failed_count'] >= $maxAttempts;

    return [
      'is_locked' => $isLocked,
      'failed_attempts' => (int)$result['failed_count'],
      'last_attempt' => $result['last_attempt'],
      'lockout_minutes' => $lockoutMinutes,
      'remaining_time' => $isLocked ? calculateRemainingLockoutTime($result['last_attempt'], $lockoutMinutes) : 0
    ];
  } catch (\PDOException $e) {
    // If table doesn't exist, assume no lockout
    return [
      'is_locked' => false,
      'failed_attempts' => 0,
      'last_attempt' => null,
      'lockout_minutes' => $lockoutMinutes,
      'remaining_time' => 0
    ];
  }
}

/**
 * Calculate remaining lockout time
 *
 * @param string $lastAttempt Last attempt timestamp
 * @param int $lockoutMinutes Lockout duration
 * @return int Remaining minutes
 */
function calculateRemainingLockoutTime($lastAttempt, $lockoutMinutes) {
  $lastAttemptTime = strtotime($lastAttempt);
  $lockoutEndTime = $lastAttemptTime + ($lockoutMinutes * 60);
  $remainingSeconds = $lockoutEndTime - time();

  return max(0, ceil($remainingSeconds / 60));
}

/**
 * Log an activity
 *
 * @param int $userId User ID performing the action
 * @param string $username Username performing the action
 * @param string $action Action performed (e.g., 'create', 'update', 'delete', 'view')
 * @param string $module Module where action occurred (e.g., 'products', 'orders', 'users')
 * @param string $entityType Type of entity affected (optional)
 * @param int $entityId ID of entity affected (optional)
 * @param array $oldValues Previous values (optional)
 * @param array $newValues New values (optional)
 * @return bool True on success, false on failure
 */
function logActivity($userId, $username, $action, $module, $entityType = null, $entityId = null, $oldValues = null, $newValues = null) {
  global $pdo;

  $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';
  $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
  $sessionId = session_id();

  $data = [
    'user_id' => $userId,
    'username' => $username,
    'action' => $action,
    'module' => $module,
    'entity_type' => $entityType,
    'entity_id' => $entityId,
    'old_values' => $oldValues ? json_encode($oldValues) : null,
    'new_values' => $newValues ? json_encode($newValues) : null,
    'ip_address' => $ipAddress,
    'user_agent' => $userAgent,
    'session_id' => $sessionId
  ];

  try {
    $result = insertData('activity_logs', $data);
    return !isset($result['error']);
  } catch (\PDOException $e) {
    // Silently fail if table doesn't exist yet
    return false;
  }
}

/**
 * Get activity logs with filtering
 *
 * @param array $filters Optional filters (user_id, module, action, entity_type, date_from, date_to)
 * @param int $limit Number of records to return
 * @param int $offset Offset for pagination
 * @return array Activity logs
 */
function getActivityLogs($filters = [], $limit = 50, $offset = 0) {
  global $pdo;

  $sql = "SELECT * FROM activity_logs WHERE 1=1";
  $params = [];

  if (!empty($filters['user_id'])) {
    $sql .= " AND user_id = :user_id";
    $params[':user_id'] = $filters['user_id'];
  }

  if (!empty($filters['module'])) {
    $sql .= " AND module = :module";
    $params[':module'] = $filters['module'];
  }

  if (!empty($filters['action'])) {
    $sql .= " AND action = :action";
    $params[':action'] = $filters['action'];
  }

  if (!empty($filters['entity_type'])) {
    $sql .= " AND entity_type = :entity_type";
    $params[':entity_type'] = $filters['entity_type'];
  }

  if (!empty($filters['date_from'])) {
    $sql .= " AND created_at >= :date_from";
    $params[':date_from'] = $filters['date_from'];
  }

  if (!empty($filters['date_to'])) {
    $sql .= " AND created_at <= :date_to";
    $params[':date_to'] = $filters['date_to'];
  }

  $sql .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (\PDOException $e) {
    return [];
  }
}

/**
 * Get activity statistics
 *
 * @param string $period Period for statistics ('today', 'week', 'month')
 * @return array Statistics data
 */
function getActivityStats($period = 'today') {
  global $pdo;

  $dateCondition = match($period) {
    'today' => 'DATE(created_at) = CURDATE()',
    'week' => 'created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)',
    'month' => 'created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)',
    default => 'DATE(created_at) = CURDATE()'
  };

  $sql = "
    SELECT
      COUNT(*) as total_activities,
      COUNT(DISTINCT user_id) as unique_users,
      COUNT(CASE WHEN action LIKE '%create%' THEN 1 END) as create_actions,
      COUNT(CASE WHEN action LIKE '%update%' THEN 1 END) as update_actions,
      COUNT(CASE WHEN action LIKE '%delete%' THEN 1 END) as delete_actions,
      COUNT(CASE WHEN action LIKE '%view%' THEN 1 END) as view_actions
    FROM activity_logs
    WHERE $dateCondition
  ";

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  } catch (\PDOException $e) {
    return [
      'total_activities' => 0,
      'unique_users' => 0,
      'create_actions' => 0,
      'update_actions' => 0,
      'delete_actions' => 0,
      'view_actions' => 0
    ];
  }
}

/**
 * Get recent activities for a user
 *
 * @param int $userId User ID
 * @param int $limit Number of activities to return
 * @return array Recent activities
 */
function getUserRecentActivities($userId, $limit = 10) {
  global $pdo;

  $sql = "SELECT * FROM activity_logs WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit";

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userId, ':limit' => $limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (\PDOException $e) {
    return [];
  }
}

/**
 * Check if an IP address is blocked
 *
 * @param string $ipAddress IP address to check
 * @return array|false Block information or false if not blocked
 */
function isIpBlocked($ipAddress) {
  global $pdo;

  $sql = "SELECT * FROM ip_blocks
          WHERE ip_address = :ip_address
          AND is_active = 1
          AND (expires_at IS NULL OR expires_at > NOW())";

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ip_address' => $ipAddress]);
    $block = $stmt->fetch(PDO::FETCH_ASSOC);

    return $block ?: false;
  } catch (\PDOException $e) {
    return false;
  }
}

/**
 * Check if an IP address is whitelisted
 *
 * @param string $ipAddress IP address to check
 * @return bool True if whitelisted
 */
function isIpWhitelisted($ipAddress) {
  global $pdo;

  $sql = "SELECT COUNT(*) FROM ip_whitelist WHERE ip_address = :ip_address AND is_active = 1";

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ip_address' => $ipAddress]);
    $count = $stmt->fetchColumn();

    return $count > 0;
  } catch (\PDOException $e) {
    return false;
  }
}

/**
 * Block an IP address
 *
 * @param string $ipAddress IP address to block
 * @param string $blockType Type of block (manual, automatic, rate_limit)
 * @param string $reason Reason for blocking
 * @param int $blockedBy User ID who blocked the IP
 * @param string $expiresAt Expiration timestamp (optional)
 * @return bool True on success
 */
function blockIpAddress($ipAddress, $blockType = 'manual', $reason = '', $blockedBy = null, $expiresAt = null) {
  global $pdo;

  // Check if already blocked
  if (isIpBlocked($ipAddress)) {
    return true; // Already blocked
  }

  $data = [
    'ip_address' => $ipAddress,
    'block_type' => $blockType,
    'reason' => $reason,
    'blocked_by' => $blockedBy,
    'expires_at' => $expiresAt
  ];

  try {
    $result = insertData('ip_blocks', $data);
    return !isset($result['error']);
  } catch (\PDOException $e) {
    return false;
  }
}

/**
 * Unblock an IP address
 *
 * @param string $ipAddress IP address to unblock
 * @return bool True on success
 */
function unblockIpAddress($ipAddress) {
  global $pdo;

  $sql = "UPDATE ip_blocks SET is_active = 0 WHERE ip_address = :ip_address";

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ip_address' => $ipAddress]);
    return $stmt->rowCount() > 0;
  } catch (\PDOException $e) {
    return false;
  }
}

/**
 * Get blocked IPs with pagination
 *
 * @param array $filters Optional filters
 * @param int $limit Number of records to return
 * @param int $offset Offset for pagination
 * @return array Blocked IPs
 */
function getBlockedIps($filters = [], $limit = 50, $offset = 0) {
  global $pdo;

  $sql = "SELECT ib.*, au.username as blocked_by_username
          FROM ip_blocks ib
          LEFT JOIN admin_users au ON ib.blocked_by = au.id
          WHERE 1=1";

  $params = [];

  if (!empty($filters['ip_address'])) {
    $sql .= " AND ib.ip_address LIKE :ip_address";
    $params[':ip_address'] = '%' . $filters['ip_address'] . '%';
  }

  if (!empty($filters['block_type'])) {
    $sql .= " AND ib.block_type = :block_type";
    $params[':block_type'] = $filters['block_type'];
  }

  if (isset($filters['is_active'])) {
    $sql .= " AND ib.is_active = :is_active";
    $params[':is_active'] = $filters['is_active'];
  }

  $sql .= " ORDER BY ib.blocked_at DESC LIMIT :limit OFFSET :offset";
  $params[':limit'] = $limit;
  $params[':offset'] = $offset;

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (\PDOException $e) {
    return [];
  }
}

/**
 * Check rate limit for an IP and endpoint
 *
 * @param string $ipAddress IP address
 * @param string $endpoint Endpoint being accessed
 * @param int $maxRequests Maximum requests allowed
 * @param int $windowMinutes Time window in minutes
 * @return array Rate limit status
 */
function checkRateLimit($ipAddress, $endpoint, $maxRequests = 100, $windowMinutes = 15) {
  global $pdo;

  // Clean up old rate limit entries
  $cleanupSql = "DELETE FROM rate_limits WHERE window_end < NOW()";
  $pdo->exec($cleanupSql);

  // Check current rate limit
  $sql = "SELECT * FROM rate_limits
          WHERE ip_address = :ip_address
          AND endpoint = :endpoint
          AND window_end > NOW()";

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':ip_address' => $ipAddress,
      ':endpoint' => $endpoint
    ]);

    $rateLimit = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rateLimit) {
      // Update existing record
      $newCount = $rateLimit['request_count'] + 1;
      $updateSql = "UPDATE rate_limits SET request_count = :count WHERE id = :id";
      $pdo->prepare($updateSql)->execute([':count' => $newCount, ':id' => $rateLimit['id']]);

      return [
        'allowed' => $newCount <= $maxRequests,
        'current_count' => $newCount,
        'max_requests' => $maxRequests,
        'remaining' => max(0, $maxRequests - $newCount),
        'reset_time' => $rateLimit['window_end']
      ];
    } else {
      // Create new rate limit record
      $windowEnd = date('Y-m-d H:i:s', strtotime("+{$windowMinutes} minutes"));
      $insertSql = "INSERT INTO rate_limits (ip_address, endpoint, request_count, window_end)
                    VALUES (:ip_address, :endpoint, 1, :window_end)";
      $pdo->prepare($insertSql)->execute([
        ':ip_address' => $ipAddress,
        ':endpoint' => $endpoint,
        ':window_end' => $windowEnd
      ]);

      return [
        'allowed' => true,
        'current_count' => 1,
        'max_requests' => $maxRequests,
        'remaining' => $maxRequests - 1,
        'reset_time' => $windowEnd
      ];
    }
  } catch (\PDOException $e) {
    // If rate limiting fails, allow the request
    return [
      'allowed' => true,
      'current_count' => 0,
      'max_requests' => $maxRequests,
      'remaining' => $maxRequests,
      'reset_time' => null
    ];
  }
}

/**
 * Get IP blocking statistics
 *
 * @return array Statistics
 */
function getIpBlockStats() {
  global $pdo;

  try {
    $stats = [];

    // Total blocked IPs
    $stmt = $pdo->query("SELECT COUNT(*) FROM ip_blocks WHERE is_active = 1");
    $stats['total_blocked'] = $stmt->fetchColumn();

    // Active blocks by type
    $stmt = $pdo->query("SELECT block_type, COUNT(*) as count FROM ip_blocks WHERE is_active = 1 GROUP BY block_type");
    $stats['blocks_by_type'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // Recent blocks (last 24 hours)
    $stmt = $pdo->query("SELECT COUNT(*) FROM ip_blocks WHERE blocked_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)");
    $stats['recent_blocks'] = $stmt->fetchColumn();

    // Whitelisted IPs
    $stmt = $pdo->query("SELECT COUNT(*) FROM ip_whitelist WHERE is_active = 1");
    $stats['whitelisted'] = $stmt->fetchColumn();

    return $stats;
  } catch (\PDOException $e) {
    return [
      'total_blocked' => 0,
      'blocks_by_type' => [],
      'recent_blocks' => 0,
      'whitelisted' => 0
    ];
  }
}

/**
 * Automatically block suspicious IPs based on login attempts
 *
 * @param int $maxFailedAttempts Maximum failed attempts before blocking
 * @param int $blockDurationMinutes Block duration in minutes
 * @return int Number of IPs blocked
 */
function autoBlockSuspiciousIps($maxFailedAttempts = 5, $blockDurationMinutes = 60) {
  global $pdo;

  try {
    $sql = "SELECT ip_address, COUNT(*) as failed_count
            FROM login_attempts
            WHERE status = 'failed'
            AND attempt_time >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
            GROUP BY ip_address
            HAVING failed_count >= :max_attempts";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':max_attempts' => $maxFailedAttempts]);
    $suspiciousIps = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $blockedCount = 0;
    foreach ($suspiciousIps as $ip) {
      if (!isIpBlocked($ip['ip_address']) && !isIpWhitelisted($ip['ip_address'])) {
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$blockDurationMinutes} minutes"));
        if (blockIpAddress($ip['ip_address'], 'automatic', "Too many failed login attempts ({$ip['failed_count']})", null, $expiresAt)) {
          $blockedCount++;
        }
      }
    }

    return $blockedCount;
  } catch (\PDOException $e) {
    return 0;
  }
}
?>
