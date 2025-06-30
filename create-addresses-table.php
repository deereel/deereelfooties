<?php
// Create user_addresses table in drf_database
try {
    $pdo = new PDO("mysql:host=localhost;dbname=drf_database;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

echo "<h2>Creating User Addresses Table</h2>";

try {
    // Check if table exists and get its structure
    $stmt = $pdo->query("SHOW TABLES LIKE 'user_addresses'");
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        echo "<h3>Table exists - checking for required columns...</h3>";
        
        // Check for new columns and add them if missing
        $stmt = $pdo->query("DESCRIBE user_addresses");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $alterStatements = [];
        
        if (!in_array('address_name', $columns)) {
            $alterStatements[] = "ALTER TABLE user_addresses ADD COLUMN address_name VARCHAR(100) NOT NULL DEFAULT 'Home' AFTER user_id";
        }
        if (!in_array('full_name', $columns)) {
            $alterStatements[] = "ALTER TABLE user_addresses CHANGE COLUMN name full_name VARCHAR(255) NOT NULL";
        }
        if (!in_array('street_address', $columns)) {
            $alterStatements[] = "ALTER TABLE user_addresses CHANGE COLUMN address street_address TEXT NOT NULL";
        }
        if (!in_array('city', $columns)) {
            $alterStatements[] = "ALTER TABLE user_addresses ADD COLUMN city VARCHAR(100) NOT NULL DEFAULT '' AFTER street_address";
        }
        if (!in_array('country', $columns)) {
            $alterStatements[] = "ALTER TABLE user_addresses ADD COLUMN country VARCHAR(100) NOT NULL DEFAULT 'Nigeria' AFTER state";
        }
        
        if (!empty($alterStatements)) {
            echo "<h4>Updating table structure...</h4>";
            foreach ($alterStatements as $sql) {
                echo "<p>Executing: " . htmlspecialchars($sql) . "</p>";
                $pdo->exec($sql);
            }
            echo "<p style='color: green;'>✓ Table updated successfully!</p>";
        } else {
            echo "<p style='color: green;'>✓ Table structure is up to date!</p>";
        }
    } else {
        // Read the SQL file and create new table
        $sql = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/sql/create_addresses_table.sql');
        
        echo "<h3>Creating new table...</h3>";
        echo "<pre>" . htmlspecialchars($sql) . "</pre>";
        
        // Execute the SQL
        $pdo->exec($sql);
        echo "<p style='color: green;'>✓ New table created successfully!</p>";
    }
    
    echo "<div style='color: green;'><h3>✓ user_addresses table created successfully!</h3></div>";
    
    // Verify table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'user_addresses'");
    if ($stmt->rowCount() > 0) {
        echo "<p>✓ user_addresses table exists</p>";
        
        // Show table structure
        $stmt = $pdo->query("DESCRIBE user_addresses");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<h4>user_addresses table structure:</h4>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'>✗ user_addresses table not found</p>";
    }
    
} catch (PDOException $e) {
    echo "<div style='color: red;'>";
    echo "<h3>✗ Error creating table:</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

echo "<hr>";
echo "<h3>Database Connection Info:</h3>";
echo "<p>Host: localhost</p>";
echo "<p>Database: drf_database</p>";
echo "<p>Connection: " . ($pdo ? "✓ Connected" : "✗ Failed") . "</p>";
?>
