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
 * Check if a user has a specific permission
 * 
 * @param int $userId User ID
 * @param string $permissionName Permission name
 * @return bool True if user has permission, false otherwise
 */
function userHasPermission($userId, $permissionName) {
  global $pdo;
  
  $sql = "SELECT COUNT(*) FROM users u
          JOIN roles r ON u.role_id = r.id
          JOIN role_permissions rp ON r.id = rp.role_id
          JOIN permissions p ON rp.permission_id = p.id
          WHERE u.user_id = :userId AND p.name = :permissionName";
  
  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':userId' => $userId, ':permissionName' => $permissionName]);
    $count = $stmt->fetchColumn();
    return $count > 0;
  } catch (\PDOException $e) {
    return false;
  }
}

/**
 * Get user role by user ID
 * 
 * @param int $userId User ID
 * @return array|null Role data or null if not found
 */
function getUserRole($userId) {
  global $pdo;
  
  $sql = "SELECT r.* FROM users u
          JOIN roles r ON u.role_id = r.id
          WHERE u.user_id = :userId";
  
  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':userId' => $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  } catch (\PDOException $e) {
    return null;
  }
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
?>
