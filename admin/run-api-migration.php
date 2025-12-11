<?php
require_once '../auth/db.php';

try {
    $sql = file_get_contents('../migrations/create_api_tables.sql');
    $statements = explode(';', $sql);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement) && !str_starts_with($statement, '--')) {
            $pdo->exec($statement);
        }
    }
    
    echo "API tables migration completed successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>